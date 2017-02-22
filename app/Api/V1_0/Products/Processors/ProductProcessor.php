<?php
namespace ERP\Api\V1_0\Products\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Products\Persistables\ProductPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Products\Validations\ProductValidate;
use ERP\Api\V1_0\Products\Transformers\ProductTransformer;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Journals\Validations\BuisnessLogic;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Companies\CompanyModel;
use ERP\Model\ProductGroups\ProductGroupModel;
use ERP\Model\ProductCategories\ProductCategoryModel;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductProcessor extends BaseProcessor
{
	/**
     * @var productPersistable
	 * @var request
     */
	private $productPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return product Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$productValue = array();
		$tKeyValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		
		// get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		if(count($_POST)==0)
		{
			return $msgArray['204'];
		}
		else
		{
			$productValidate = new ProductValidate();
			
			// trim an input 
			$productTransformer = new ProductTransformer();
			$tRequest = $productTransformer->trimInsertData($this->request);
			if($tRequest==1)
			{
				return $msgArray['content'];
			}
			else
			{
				//make a product_code and validate it with other codes
				//get company_name 
				$companyModel = new CompanyModel();
				$companyResult = $companyModel->getData($tRequest['company_id']);
				$decodedCompanyData = json_decode($companyResult);
				
				//get product group name
				$productGroupData = new ProductGroupModel();
				$groupData = $productGroupData->getData($tRequest['product_group_id']);
				$decodedGroupData = json_decode($groupData);
				
				//get product category name
				$productCategoryData = new ProductCategoryModel();
				$categoryData = $productCategoryData->getData($tRequest['product_category_id']);
				$decodedCategoryData = json_decode($categoryData);
				
				$color = preg_replace('/[^A-Za-z0-9\-]/', '', $tRequest['color']);
				$size = preg_replace('/[^A-Za-z0-9\-]/', '', $tRequest['size']);
				$product_name = preg_replace('/[^A-Za-z0-9]/', '', $tRequest['product_name']);
				$company_name = preg_replace('/[^A-Za-z0-9]/', '', $decodedCompanyData[0]->company_name);
				$group_name = preg_replace('/[^A-Za-z0-9]/', '', $decodedGroupData[0]->product_group_name);
				$category_name = preg_replace('/[^A-Za-z0-9]/', '', $decodedCategoryData[0]->product_category_name);
				$convertedCompanyName = substr($company_name,0,3);
				$convertedCategoryName = substr($category_name,0,3);
				$convertedGroupName = substr($group_name,0,2);
				$convertedProductName = substr($product_name,0,8);
				$convertedColor = substr($color,0,2);
				$convertedSize = substr($size,0,4);
				$tRequest['product_code'] = $convertedCompanyName."_".
											$convertedCategoryName."_".
											$convertedGroupName."_".
											$convertedProductName."_".
											$convertedColor."_".
											$convertedSize."_";
				// validation
				$validationResult = $productValidate->productCodeValidate($tRequest['company_id'],$tRequest['product_code']);
			}	
			if(strcmp($validationResult,$msgArray['200'])==0)
			{
				// validation
				$status = $productValidate->validate($tRequest);
				if($status=="Success")
				{
					foreach ($tRequest as $key => $value)
					{
						if(!is_numeric($value))
						{
							if (strpos($value, '\'') !== FALSE)
							{
								$productValue[$data]= str_replace("'","\'",$value);
								$keyName[$data] = $key;
							}
							else
							{
								$productValue[$data] = $value;
								$keyName[$data] = $key;
							}
						}
						else
						{
							$productValue[$data]= $value;
							$keyName[$data] = $key;
						}
						$data++;
					}
					
					// set data to the persistable object
					for($data=0;$data<count($productValue);$data++)
					{
						// set the data in persistable object
						$productPersistable = new ProductPersistable();	
						$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
						// make function name dynamically
						$setFuncName = 'set'.$str;
						$getFuncName[$data] = 'get'.$str;
						$productPersistable->$setFuncName($productValue[$data]);
						$productPersistable->setName($getFuncName[$data]);
						$productPersistable->setKey($keyName[$data]);
						$productArray[$data] = array($productPersistable);
					}
					return $productArray;
				}
				else
				{
					return $status;
				}
			}
			else
			{
				return $validationResult;
			}
		}
	}
	
	/**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return product Persistable object
     */	
    public function createPersistableInOutWard(Request $request,$inOutWard)
	{	
		$this->request = $request;	
		$data=0;
		$productValidate = new ProductValidate();
		
		// trim an input 
		$productTransformer = new ProductTransformer();
		$tRequest = $productTransformer->trimInsertInOutwardData($this->request,$inOutWard);
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if($tRequest==1)
		{
			return $exceptionArray['content'];
		}	
		else
		{
			if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$tRequest['transactionDate']))
			{
				return "transaction-date is not valid";
			}
			// validation
			$status = $productValidate->validateInOutward($tRequest);
			if($status=="Success")
			{
				$productPersistable=array();
				for($data=0;$data<count($tRequest[0]);$data++)
				{
					$productPersistable[$data] = new ProductPersistable();
					$productPersistable[$data]->setTransactionDate($tRequest['transactionDate']);
					$productPersistable[$data]->setCompanyId($tRequest['companyId']);
					$productPersistable[$data]->setTransactionType($tRequest['transactionType']);
					$productPersistable[$data]->setInvoiceNumber($tRequest['invoiceNumber']);
					$productPersistable[$data]->setBillNumber($tRequest['billNumber']);
					$productPersistable[$data]->setTax($tRequest['tax']);
					
					$productPersistable[$data]->setProductId($tRequest[0][$data]['productId']);
					$productPersistable[$data]->setDiscount($tRequest[0][$data]['discount']);
					$productPersistable[$data]->setDiscountValue($tRequest[0][$data]['discountValue']);
					$productPersistable[$data]->setDiscountType($tRequest[0][$data]['discountType']);
					$productPersistable[$data]->setPrice($tRequest[0][$data]['price']);
					$productPersistable[$data]->setQty($tRequest[0][$data]['qty']);
				}
				return $productPersistable;
			}
			else
			{
				return $status;
			}
		}
	}
	
	/**
     * update product
     * $param Request object [Request $request] and product id
     * @return product Persistable object
     */	
	public function createPersistableChange(Request $request,$productId)
	{
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		// update
		if($requestMethod == $constantArray['postMethod'])
		{
			$productValue = array();
			$productPersistable;
			$productArray = array();
			$productValidate = new ProductValidate();
			$status;
			// if data is not available in update request
			if(count($_POST)==0)
			{
				$status = $exceptionArray['204'];
				return $status;
			}
			// data is avalilable for update
			else
			{
				for($data=0;$data<count($_POST);$data++)
				{
					// data get from body
					$productPersistable = new ProductPersistable();
					$value[$data] = $_POST[array_keys($_POST)[$data]];
					$key[$data] = array_keys($_POST)[$data];
					
					// trim an input 
					$productTransformer = new ProductTransformer();
					$tRequest = $productTransformer->trimUpdateData($key[$data],$value[$data]);
					if($tRequest==1)
					{
						return $exceptionArray['content'];
					}
					else
					{
						// get key value from trim array
						$tKeyValue[$data] = array_keys($tRequest[0])[0];
						$tValue[$data] = $tRequest[0][array_keys($tRequest[0])[0]];
					
						if(strcmp($tKeyValue[$data],"product_name")==0)
						{
							$validationResult = $productValidate->productNameValidateUpdate($tRequest[0],$productId);
							if(!is_array($validationResult))
							{
								return $validationResult;
							}
						}
					}
					// validation
					$status = $productValidate->validateUpdateData($tKeyValue[$data],$tValue[$data],$tRequest[0]);
					
					// enter data is valid(one data validate status return)
					if($status=="Success")
					{
						// check data is string or not
						if(!is_numeric($tValue[$data]))
						{
							if (strpos($tValue[$data], '\'') !== FALSE)
							{
								$productValue[$data] = str_replace("'","\'",$tValue[$data]);
							}
							else
							{
								$productValue[$data] = $tValue[$data];
							}
						}
						else
						{
							$productValue[$data] = $tValue[$data];
						}
						
						// flag=0...then data is valid(consider one data at a time)
						if($flag==0)
						{
							$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue[$data])));
							// make function name dynamically
							$setFuncName = 'set'.$str;
							$getFuncName[$data] = 'get'.$str;
							$productPersistable->$setFuncName($productValue[$data]);
							
							$productPersistable->setName($getFuncName[$data]);
							
							$productPersistable->setKey($tKeyValue[$data]);
							$productPersistable->setProductId($productId);
							
							$productArray[$data] = array($productPersistable);
							
						}
					}
					// enter data is not valid
					else
					{
						// if flag==1 then enter data is not valid ..so error return(consider one data at a time)
						$flag=1;
						if(!empty($status[0]))
						{
							$errorStatus[$errorCount]=$status[0];
							$errorCount++;
						}
					}
					if($data==(count($_POST)-1))
					{
						if($flag==1)
						{
							return json_encode($errorStatus);
						}
						else
						{
							
							return $productArray;
						}
					}
					
				}
			}
		}
		
		// delete
		else if($requestMethod == $constantArray['deleteMethod'])
		{
			$productPersistable = new productPersistable();		
			$productPersistable->setproductId($productId);			
			return $productPersistable;
		}
	}	
	
	/**
     * process product-transaction data(sale/purchase)
     * $param product-array and transaction-type
     * @return product-transaction Persistable object/exception message/error message
     */	
	public function createPersistableChangeInOutWard($productArray,$inOutWard,$jfId)
	{
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$trimFlag=0;
		$trimArrayFalg=0;
		$productPersistable;
		$productValidate = new ProductValidate();
		$status;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		// update
		if(strcmp($requestMethod,$constantArray['postMethod'])==0)
		{
			// if data is not available in update request
			if(count($_POST)==0)
			{
				$status = $exceptionArray['204'];
				return $status;
			}
			// data is avalilable for update
			else
			{
				$productPersistable = array();
				$productMultipleArray = array();
				$productSingleArray = array();
				
				// trim an input 
				$productTransformer = new ProductTransformer();
				$tRequest = $productTransformer->trimUpdateProductData($productArray,$inOutWard);
				
				if($tRequest==1)
				{
					return $exceptionArray['content'];
				}
				else
				{
					if(strcmp($inOutWard,$constantArray['journalInward'])==0)
					{
						$headerType=$constantArray['purchase'];
					}
					else
					{
						$headerType=$constantArray['sales'];
					}
					$journalData = array();
					if(array_key_exists("tax",$tRequest) || array_key_exists("0",$tRequest))
					{
						if(array_key_exists("0",$tRequest))
						{
							$validationResult = $productValidate->validateTransactionArrayUpdateData($tRequest);
							if(strcmp($validationResult,"Success")!=0)
							{
								return $validationResult;
							}
						}
						// check accounting Rules
						$buisnessLogic = new BuisnessLogic();
						$buisnessResult = $buisnessLogic->validateUpdateProductBuisnessLogic($headerType,$journalData,$tRequest,$jfId);
						if(!is_array($buisnessResult))
						{
							return $buisnessResult;
						}
					}
				}
				// get data from trim array
				if(is_array($tRequest))
				{
					// data is exists in request or not checking by flag
					if(array_key_exists($constantArray['flag'],$tRequest))
					{
						$trimFlag=1;
					}
					// data
					if($trimFlag==1)
					{
						// check array is exists 
						if(array_key_exists(0,$tRequest))
						{
							$trimArrayFalg=1;
						}	
						// array with data
						if($trimArrayFalg==1)
						{
							// validate single data
							for($multipleArray=0;$multipleArray<count($tRequest[0]);$multipleArray++)
							{
								$productPersistable[$multipleArray] = new ProductPersistable();
								$productPersistable[$multipleArray]->setDiscount($tRequest[0][$multipleArray]['discount']);
								$productPersistable[$multipleArray]->setDiscountValue($tRequest[0][$multipleArray]['discount_value']);
								$productPersistable[$multipleArray]->setDiscountType($tRequest[0][$multipleArray]['discount_type']);
								$productPersistable[$multipleArray]->setProductId($tRequest[0][$multipleArray]['product_id']);
								$productPersistable[$multipleArray]->setPrice($tRequest[0][$multipleArray]['price']);
								$productPersistable[$multipleArray]->setQty($tRequest[0][$multipleArray]['qty']);
								$productMultipleArray[$multipleArray] = array($productPersistable[$multipleArray]);
							}
						
							for($trimResponse=0;$trimResponse<count($tRequest)-2;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								
								//validate transaction-date
								if(array_key_exists('transaction_date',$trimRequest[0]))
								{
									if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$trimRequest[0]['transaction_date']))
									{
										return "transaction-date is not valid";
									}
								}
								$status = $productValidate->validateTransactionUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$productPersistable[$trimResponse] = new ProductPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$productPersistable[$trimResponse]->$setFuncName($tValue);
									$productPersistable[$trimResponse]->setName($getFuncName);
									$productPersistable[$trimResponse]->setKey($tKeyValue);
									$productSingleArray[$trimResponse] = array($productPersistable[$trimResponse]);
								}
							}
							array_push($productSingleArray,$productMultipleArray);
							return $productSingleArray;
						}
						// only data exists
						else
						{
							for($trimResponse=0;$trimResponse<count($tRequest)-1;$trimResponse++)
							{
								$tKeyValue = array_keys($tRequest)[$trimResponse];
								$tValue =$tRequest[array_keys($tRequest)[$trimResponse]];
								$trimRequest[0] = array($tKeyValue=>$tValue);
								
								//validate transaction-date
								if(array_key_exists('transaction_date',$trimRequest[0]))
								{
									if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$trimRequest[0]['transaction_date']))
									{
										return "transaction-date is not valid";
									}
								}
								$status = $productValidate->validateTransactionUpdateData($tKeyValue,$tValue,$trimRequest[0]);
								
								if(strcmp($status,"Success")!=0)
								{
									return $status;
								}
								else
								{
									$productPersistable[$trimResponse] = new ProductPersistable();
									$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue)));
									$setFuncName = 'set'.$str;
									$getFuncName = 'get'.$str;
									$productPersistable[$trimResponse]->$setFuncName($tValue);
									$productPersistable[$trimResponse]->setName($getFuncName);
									$productPersistable[$trimResponse]->setKey($tKeyValue);
									$productSingleArray[$trimResponse] = array($productPersistable[$trimResponse]);
								}
							}
							return $productSingleArray;
						}
					}
					// only array exists
					else
					{
						for($multipleArray=0;$multipleArray<count($tRequest);$multipleArray++)
						{
							$productPersistable[$multipleArray] = new ProductPersistable();
							$productPersistable[$multipleArray]->setDiscount($tRequest[$multipleArray]['discount']);
							$productPersistable[$multipleArray]->setDiscountValue($tRequest[$multipleArray]['discount_value']);
							$productPersistable[$multipleArray]->setDiscountType($tRequest[$multipleArray]['discount_type']);
							$productPersistable[$multipleArray]->setProductId($tRequest[$multipleArray]['product_id']);
							$productPersistable[$multipleArray]->setPrice($tRequest[$multipleArray]['price']);
							$productPersistable[$multipleArray]->setQty($tRequest[$multipleArray]['qty']);
							$productMultipleArray[$multipleArray] = array($productPersistable[$multipleArray]);
						}
						$productMultipleArray['flag']="1";
						return $productMultipleArray;
					}
				}
				else
				{
					return $tRequest;
				}
			}
		}
	}	
	
	/**
     * process header data
     * $param request header
     * @return persistable object of header data
     */	
	public function createJfIdPersistableData($requestHeader)
	{
		$trimJfId = trim($requestHeader['jfid'][0]);
		$productPersistable = new ProductPersistable();
		$productPersistable->setJfId($trimJfId);
		return $productPersistable;
	}
	
	/**
     * process header data
     * $param request header
     * @return persistable object of header data
     */	
	public function createprocessDatePersistableData($requestHeader)
	{
		$fromDate = $requestHeader['fromdate'][0];
		$toDate = $requestHeader['todate'][0];
		
		//date conversion
		// from date conversion
		$splitedFromDate = explode("-",$fromDate);
		$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
		// to date conversion
		$splitedToDate = explode("-",$toDate);
		$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
	
		//validate date
		if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$transformFromDate))
		{
			return "from-date is not valid";
		}
		if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$transformToDate))
		{
			return "to-date is not valid";
		}
		$productPersistable = new ProductPersistable();
		$productPersistable->setFromDate($transformFromDate);
		$productPersistable->setToDate($transformToDate);
		return $productPersistable;
	}
}