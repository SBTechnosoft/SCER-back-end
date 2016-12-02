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
			else if(is_array($tRequest))
			{
				//validation
				$journalValidate = new JournalValidate();
				$status = $journalValidate->validate($tRequest);
				// echo "else";
				// print_r($status);
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
	
	public function createPersistableChange(Request $request,$journalId)
	{
		$this->request = $request;
		$jouunalValue = array();
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
				$tRequest = $journalTransformer->trimUpdateData($this->request);
				
				//get data from trim array
				if(is_array($tRequest))
				{
					//array is exists in array or not by flag
					for($trimResponse=0;$trimResponse<count($tRequest);$trimResponse++)
					{
						if(strcmp(array_keys($tRequest)[$trimResponse],"flag")==0)
						{
							$trimFlag=1;
							break;
						}
					}
					if($trimFlag==1)
					{
						for($trimResponse=0;$trimResponse<count($tRequest);$trimResponse++)
						{
							if(strcmp(array_keys($tRequest)[$trimResponse],0)==0)
							{
								$trimArrayFalg=1;
								break;
							}
						}
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
									$journalPersistable[$trimResponse]->setJournalId($journalId);
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
									$journalPersistable[$trimResponse]->setJournalId($journalId);
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