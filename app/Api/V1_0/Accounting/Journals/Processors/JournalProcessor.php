<?php
namespace ERP\Api\V1_0\Accounting\Journals\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\Journals\Validations\JournalValidate;
use ERP\Api\V1_0\Accounting\Journals\Transformers\JournalTransformer;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Journals\Validations\BuisnessLogic;
use ERP\Api\V1_0\Products\Transformers\ProductTransformer;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalProcessor extends BaseProcessor
{
	/**
     * @var journalPersistable
	 * @var request
     */
	private $journalPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Journal Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$data=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		if(count($_POST)==0)
		{
			return $msgArray['204'];
		}
		else
		{
			//trim an input 
			$journalTransformer = new JournalTransformer();
			$tRequest = $journalTransformer->trimInsertData($this->request);
			if($tRequest==1)
			{
				return $msgArray['content'];
			}
			else
			{
				//check accounting Rules
				$buisnessLogic = new BuisnessLogic();
				$busnessResult = $buisnessLogic->validateBuisnessLogic($tRequest);
			}
			if(is_array($busnessResult))
			{
				//simple validation
				$journalValidate = new JournalValidate();
				$status = $journalValidate->validate($tRequest);
				
				if($status=="Success")
				{
					$journalPersistable=array();
					for($data=0;$data<count($tRequest[0]);$data++)
					{
						$journalPersistable[$data] = new JournalPersistable();
						$journalPersistable[$data]->setJfId($tRequest['jfId']);
						$journalPersistable[$data]->setEntryDate($tRequest['entryDate']);
						$journalPersistable[$data]->setCompanyId($tRequest['companyId']);
						
						$journalPersistable[$data]->setAmount($tRequest[0][$data]['amount']);
						$journalPersistable[$data]->setAmountType($tRequest[0][$data]['amountType']);
						$journalPersistable[$data]->setLedgerId($tRequest[0][$data]['ledgerId']);
					}
					return $journalPersistable;
				}
				else
				{
					return $status;
				}
			}
			else
			{
				return $tRequest;
			}
		}
	}
	//trim data & set header data (fromdate and todate data)
	public function createPersistableData(Request $request)
	{
		$this->request = $request;	
		
		//trim an input 
		$journalTransformer = new JournalTransformer();
		$tRequest = $journalTransformer->trimDateData($this->request);
		
		$journalPersistable = new JournalPersistable();
		$journalPersistable->setFromdate($tRequest['fromDate']);
		$journalPersistable->setTodate($tRequest['toDate']);
		
		return $journalPersistable;
	}
	
	public function createPersistableChange($journalArray,$jfId)
	{
		echo "processor";
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$trimFlag=0;
		$trimArrayFalg=0;
		$journalPersistable;
		$jounrnalArray = array();
		$journalValidate = new JournalValidate();
		$status;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		// update
		if($requestMethod == 'POST')
		{
			//if data is not available in update request
			if(count($_POST)==0)
			{
				$status = $exceptionArray['204'];
				return $status;
			}
			//data is avalilable for update
			else
			{
				$journalPersistable = array();
				$journalMultipleArray = array();
				//data get from body
				
				$journalSingleArray = array();
				//trim an input 
				$journalTransformer = new JournalTransformer();
				$tRequest = $journalTransformer->trimUpdateData($journalArray);
				
				if($tRequest==1)
				{
					return $exceptionArray['content'];
				}
				else
				{
					//check accounting Rules
					$buisnessLogic = new BuisnessLogic();
					$busnessResult = $buisnessLogic->validateUpdateBuisnessLogic($tRequest);
				}
				
				//get data from trim array
				if(is_array($tRequest))
				{
					//data is exists in request or not checking by flag
					if(array_key_exists("flag",$tRequest))
					{
						$trimFlag=1;
					}
					//data
					if($trimFlag==1)
					{
						//check array is exists 
						if(array_key_exists(0,$tRequest))
						{
							$trimArrayFalg=1;
						}	
						//array with data
						if($trimArrayFalg==1)
						{
							//validate only single data not an array (pending multiple array data)
							for($trimResponse=0;$trimResponse<count($tRequest)-2;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								$status = $journalValidate->validateUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$journalPersistable[$trimResponse] = new JournalPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$journalPersistable[$trimResponse]->$setFuncName($tValue);
									$journalPersistable[$trimResponse]->setName($getFuncName);
									$journalPersistable[$trimResponse]->setKey($tKeyValue);
									$journalPersistable[$trimResponse]->setJfId($jfId);
									$journalSingleArray[$trimResponse] = array($journalPersistable[$trimResponse]);
									
								}
							}
							for($multipleArray=0;$multipleArray<count($tRequest[0]);$multipleArray++)
							{
								$journalPersistable[$multipleArray] = new JournalPersistable();
								$journalPersistable[$multipleArray]->setAmount($tRequest[0][$multipleArray]['amount']);
								$journalPersistable[$multipleArray]->setAmountType($tRequest[0][$multipleArray]['amount_type']);
								$journalPersistable[$multipleArray]->setLedgerId($tRequest[0][$multipleArray]['ledger_id']);
								$journalMultipleArray[$multipleArray] = array($journalPersistable[$multipleArray]);
							}
							array_push($journalSingleArray,$journalMultipleArray);
							return $journalSingleArray;
						}
						else
						{
							for($trimResponse=0;$trimResponse<count($tRequest)-1;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								$status = $journalValidate->validateUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$journalPersistable[$trimResponse] = new JournalPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$journalPersistable[$trimResponse]->$setFuncName($tValue);
									$journalPersistable[$trimResponse]->setName($getFuncName);
									$journalPersistable[$trimResponse]->setKey($tKeyValue);
									$journalPersistable[$trimResponse]->setJfId($jfId);
									$journalSingleArray[$trimResponse] = array($journalPersistable[$trimResponse]);
								}
							}
							return $journalSingleArray;
						}
					}
					else
					{
						//validation of multiple array is pending
						for($multipleArray=0;$multipleArray<count($tRequest);$multipleArray++)
						{
							$journalPersistable[$multipleArray] = new JournalPersistable();
							$journalPersistable[$multipleArray]->setAmount($tRequest[$multipleArray]['amount']);
							$journalPersistable[$multipleArray]->setAmountType($tRequest[$multipleArray]['amount_type']);
							$journalPersistable[$multipleArray]->setLedgerId($tRequest[$multipleArray]['ledger_id']);
							$journalMultipleArray[$multipleArray] = array($journalPersistable[$multipleArray]);
						}
						$journalMultipleArray['flag']="1";
						return $journalMultipleArray;
					}
				}
				else
				{
					return $tRequest;
				}
			}
		}
	}	
	
	public function createPersistableChangeData($headerData,$productArray,$journalArray,$jfId)
	{
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$trimFlag=0;
		$trimArrayFalg=0;
		$journalPersistable;
		$jounrnalArray = array();
		$journalValidate = new JournalValidate();
		$status;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		// update
		if($requestMethod == 'POST')
		{
			//if data is not available in update request
			if(count($_POST)==0)
			{
				$status = $exceptionArray['204'];
				return $status;
			}
			//data is avalilable for update
			else
			{
				$journalPersistable = array();
				$journalMultipleArray = array();
				//data get from body
				
				$journalSingleArray = array();
				
				//trim an input 
				$journalTransformer = new JournalTransformer();
				$tRequest = $journalTransformer->trimUpdateData($journalArray);
				
				$inOutWard="Inward";
				//trim an input 
				$productTransformer = new ProductTransformer();
				$trimProductData = $productTransformer->trimUpdateProductData($productArray,$inOutWard);
				
				if($tRequest==1)
				{
					return $exceptionArray['content'];
				}
				else
				{
					//check accounting Rules
					$buisnessLogic = new BuisnessLogic();
					$buisnessResult = $buisnessLogic->validateUpdateBuisnessLogic($tRequest);
					
					//journal array and product array exist/tax exist
					if(is_array($buisnessResult))
					{
						//data is valid and validate journal-product array data
						$buisnessProductResult = $buisnessLogic->validateUpdateProductBuisnessLogic($headerData,$tRequest,$trimProductData,$jfId);
					}
					else
					{
						//get error message / journal-array not exist(return 0)
						if($buisnessResult==0)
						{
							//get journal-array from database and validate it with given productArray
						}
						else
						{
							return $buisnessResult;
						}
					}
					exit;
				}
				
				//get data from trim array
				if(is_array($tRequest))
				{
					//data is exists in request or not checking by flag
					if(array_key_exists("flag",$tRequest))
					{
						$trimFlag=1;
					}
					//data
					if($trimFlag==1)
					{
						//check array is exists 
						if(array_key_exists(0,$tRequest))
						{
							$trimArrayFalg=1;
						}	
						//array with data
						if($trimArrayFalg==1)
						{
							//validate only single data not an array (pending multiple array data)
							for($trimResponse=0;$trimResponse<count($tRequest)-2;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								$status = $journalValidate->validateUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$journalPersistable[$trimResponse] = new JournalPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$journalPersistable[$trimResponse]->$setFuncName($tValue);
									$journalPersistable[$trimResponse]->setName($getFuncName);
									$journalPersistable[$trimResponse]->setKey($tKeyValue);
									$journalPersistable[$trimResponse]->setJfId($jfId);
									$journalSingleArray[$trimResponse] = array($journalPersistable[$trimResponse]);
									
								}
							}
							for($multipleArray=0;$multipleArray<count($tRequest[0]);$multipleArray++)
							{
								$journalPersistable[$multipleArray] = new JournalPersistable();
								$journalPersistable[$multipleArray]->setAmount($tRequest[0][$multipleArray]['amount']);
								$journalPersistable[$multipleArray]->setAmountType($tRequest[0][$multipleArray]['amount_type']);
								$journalPersistable[$multipleArray]->setLedgerId($tRequest[0][$multipleArray]['ledger_id']);
								$journalMultipleArray[$multipleArray] = array($journalPersistable[$multipleArray]);
							}
							array_push($journalSingleArray,$journalMultipleArray);
							return $journalSingleArray;
						}
						else
						{
							for($trimResponse=0;$trimResponse<count($tRequest)-1;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								$status = $journalValidate->validateUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$journalPersistable[$trimResponse] = new JournalPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$journalPersistable[$trimResponse]->$setFuncName($tValue);
									$journalPersistable[$trimResponse]->setName($getFuncName);
									$journalPersistable[$trimResponse]->setKey($tKeyValue);
									$journalPersistable[$trimResponse]->setJfId($jfId);
									$journalSingleArray[$trimResponse] = array($journalPersistable[$trimResponse]);
								}
							}
							return $journalSingleArray;
						}
					}
					else
					{
						//validation of multiple array is pending
						for($multipleArray=0;$multipleArray<count($tRequest);$multipleArray++)
						{
							$journalPersistable[$multipleArray] = new JournalPersistable();
							$journalPersistable[$multipleArray]->setAmount($tRequest[$multipleArray]['amount']);
							$journalPersistable[$multipleArray]->setAmountType($tRequest[$multipleArray]['amount_type']);
							$journalPersistable[$multipleArray]->setLedgerId($tRequest[$multipleArray]['ledger_id']);
							$journalMultipleArray[$multipleArray] = array($journalPersistable[$multipleArray]);
						}
						$journalMultipleArray['flag']="1";
						return $journalMultipleArray;
					}
				}
				else
				{
					return $tRequest;
				}
			}
		}
	}	
}