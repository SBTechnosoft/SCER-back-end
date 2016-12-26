<?php
namespace ERP\Core\Accounting\Journals\Validations;

use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Entities\Constants\ConstantClass;
use ERP\Api\V1_0\Products\Controllers\ProductController;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Model\Accounting\Journals\JournalModel;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class BuisnessLogic extends LedgerModel
{
	/**
	 * validate trim-request data for insert
     * @param trim-array
     * @return array/exception message
     */
	public function validateBuisnessLogic($trimRequest)
	{
		$ledgerId = array();
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($journalArray=0;$journalArray<count($trimRequest[0]);$journalArray++)
		{
			$amount[$journalArray][0] = $trimRequest[0][$journalArray]['amount'];
			$amountType[$journalArray][1] = $trimRequest[0][$journalArray]['amountType'];
			$ledgerId[$journalArray][2] = $trimRequest[0][$journalArray]['ledgerId'];
			
			//check ledger exists
			$journalObject = new BuisnessLogic();
			$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
			if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
			{
				return $exceptionArray['404'];
			}
			else
			{
				//check credit-debit amount
				if(strcmp($amountType[$journalArray][1],"credit")==0)
				{
					$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
				}
				else
				{
					$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
				}
			}
		}
		if($creditAmountArray==$debitAmountArray)
		{
			return $trimRequest;
		}
		else
		{
			return $exceptionArray['equal'];
		}
	}
	
	/**
	 * validate trim-request data for update
     * @param trim-array
     * @return array/exception message
     */
	public function validateUpdateBuisnessLogic($trimRequest)
	{
		$ledgerId = array();
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//array exist
		if(array_key_exists('0',$trimRequest))
		{
			if(array_key_exists('flag',$trimRequest))
			{
				for($journalArray=0;$journalArray<count($trimRequest[0]);$journalArray++)
				{
					$amount[$journalArray][0] = $trimRequest[0][$journalArray]['amount'];
					$amountType[$journalArray][1] = $trimRequest[0][$journalArray]['amount_type'];
					$ledgerId[$journalArray][2] = $trimRequest[0][$journalArray]['ledger_id'];
					
					//check ledger exists
					$journalObject = new BuisnessLogic();
					$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
					
					if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
					{
						return $exceptionArray['404'];
					}
					else
					{
						//check credit-debit amount
						if(strcmp($amountType[$journalArray][1],"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
						}
					}
				}
				if($creditAmountArray==$debitAmountArray)
				{
					return $trimRequest;
				}	
				else
				{
					return $exceptionArray['equal'];
				}
			}
			else
			{
				for($journalArray=0;$journalArray<count($trimRequest);$journalArray++)
				{
					$amount[$journalArray][0] = $trimRequest[$journalArray]['amount'];
					$amountType[$journalArray][1] = $trimRequest[$journalArray]['amount_type'];
					$ledgerId[$journalArray][2] = $trimRequest[$journalArray]['ledger_id'];
					
					//check ledger exists
					$journalObject = new BuisnessLogic();
					$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
					if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
					{
						return $exceptionArray['404'];
					}
					else
					{
						//check credit-debit amount
						if(strcmp($amountType[$journalArray][1],"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
						}
					}
				}
				if($creditAmountArray==$debitAmountArray)
				{
					return $trimRequest;
				}
				else
				{
					return $exceptionArray['equal'];
				}
			}
		}
		else
		{
			return 0;
		}
	}
	
	/**
	 * validate trim-request data for update
     * @param trim-array of product and journal and header data
	 * check journal and product data and if tax and discount is available then check that value
     * @return array/exception message
     */
	public function validateUpdateJournalBuisnessLogic($headerData,$trimJournalData,$productData,$jfId)
	{
		$ledgerIdArray = array();
		$discountArray = array();
		$taxFlag=0;
		$discountTotal=0;
		$discountFlag=0;
		$journalDiscountFlag=0;
		$journalTaxFlag=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// get productArray and validate it with journal array
		$productController = new ProductController(new Container());
		$method=$constantArray['getMethod'];
		$path=$constantArray['productUrl'];
		$productId = array();
		$productRequest = Request::create($path,$method,$productId);
		$productRequest->headers->set('jfid',$jfId);
		$processedData = $productController->getData($productRequest);
		$jsonDecodedProductData = json_decode($processedData);
		
		$ledgerService = new LedgerService();
		//tax and array both exist
		if(array_key_exists("tax",$productData) && array_key_exists("0",$productData))
		{
			//calculate total discount amount
			for($arrayData=0;$arrayData<count($productData[0]);$arrayData++)
			{
				if(strcmp($productData[0][$arrayData]['discount_type'],"flat")==0)
				{
					$discountArray[$arrayData] = $productData[0][$arrayData]['discount']; 
				}
				else
				{
					$discountArray[$arrayData] = ($productData[0][$arrayData]['discount']/100)*$productData[0][$arrayData]['price'];
				}
				$discountTotal = $discountTotal+$discountArray[$arrayData];
			}
			
			//check tax and discount is available in journal data
			for($journalArrayData=0;$journalArrayData<count($trimJournalData[0]);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $trimJournalData[0][$journalArrayData]['ledger_id'];
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData['type'][0])==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						//tax  ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//discount ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
						{
							$discountFlag=1;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//tax  ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						//discount ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
						{
							$discountFlag=1;
						}
					}
				}
			}
			if($taxFlag==0 || $discountFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		//only tax exist
		else if(array_key_exists("tax",$productData))
		{
			//check tax 
			for($journalArrayData=0;$journalArrayData<count($trimJournalData[0]);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $trimJournalData[0][$journalArrayData]['ledger_id'];
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData['type'][0])==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						//tax ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						$journalDiscountFlag=1;
						if(is_array($jsonDecodedProductData))
						{
							for($productArrayData=0;$productArrayData<count($jsonDecodedProductData);$productArrayData++)
							{
								if(strcmp($jsonDecodedProductData[$productArrayData]->discountType,"flat")==0)
								{
									$discountArray[$productArrayData] = $jsonDecodedProductData[$productArrayData]->discount;
								}
								else
								{
									$discountArray[$productArrayData] = ($jsonDecodedProductData[$productArrayData]->discount/100)*$jsonDecodedProductData['productArrayData']->price;
								}
								$discountTotal = $discountTotal+$discountArray[$productArrayData];
							}
							if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
							{
								$discountFlag=1;
							}
						}
						else
						{
							return $processedData;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//tax ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						$journalDiscountFlag=1;
						if(is_array($jsonDecodedProductData))
						{
							for($productArrayData=0;$productArrayData<count($jsonDecodedProductData);$productArrayData++)
							{
								if(strcmp($jsonDecodedProductData[$productArrayData]->discountType,"flat")==0)
								{
									$discountArray[$productArrayData] = $jsonDecodedProductData[$productArrayData]->discount;
								}
								else
								{
									$discountArray[$productArrayData] = ($jsonDecodedProductData[$productArrayData]->discount/100)*$jsonDecodedProductData['productArrayData']->price;
								}
								$discountTotal = $discountTotal+$discountArray[$productArrayData];
							}
							if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
							{
								$discountFlag=1;
							}
						}
						else
						{
							return $processedData;
						}
					}
				}
			}
			if($taxFlag==0 || $journalDiscountFlag==1 && $discountFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		//only array exist
		else
		{
			//calculate total discount amount
			for($arrayData=0;$arrayData<count($productData[0]);$arrayData++)
			{
				if(strcmp($productData[0][$arrayData]['discount_type'],"flat")==0)
				{
					$discountArray[$arrayData] = $productData[0][$arrayData]['discount']; 
				}
				else
				{
					$discountArray[$arrayData] = ($productData[0][$arrayData]['discount']/100)*$productData[0][$arrayData]['price'];
				}
				$discountTotal = $discountTotal+$discountArray[$arrayData];
			}
			
			//check tax and discount is available in journal data
			for($journalArrayData=0;$journalArrayData<count($trimJournalData[0]);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $trimJournalData[0][$journalArrayData]['ledger_id'];
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData['type'][0])==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//discount ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
						{
							$discountFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						// discount ledger exist
						$journalTaxFlag=1;
						if(is_array($jsonDecodedProductData))
						{
							$taxAmount=0;
							if($trimJournalData[0][$journalArrayData]['amount']==$jsonDecodedProductData[0]->tax)
							{
								$taxFlag=1;
							}
						}
						else
						{
							return $processedData;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						// discount ledger exist
						if($trimJournalData[0][$journalArrayData]['amount']==$discountTotal)
						{
							$discountFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						// discount ledger exist
						$journalTaxFlag=1;
						if(is_array($jsonDecodedProductData))
						{
							if($trimJournalData[0][$journalArrayData]['amount']==$jsonDecodedProductData[0]->tax)
							{
								$taxFlag=1;
							}
						}
						else
						{
							return $processedData;
						}
					}
				}
			}
			if($discountFlag==0 || $journalTaxFlag==1 && $taxFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		return $trimJournalData;
	}
	
	/**
	 * validate product data for update
     * @param trim-array of product and journal and header data
	 * check journal and product data and if tax and discount is available then check that value
     * @return array/exception message
     */
	public function validateUpdateProductBuisnessLogic($headerData,$trimJournalData,$productData,$jfId)
	{
		$journalDiscountFlag=0;
		$taxFlag=0;
		$discountFlag=0;
		$ledgerIdArray = array();
		$discountArray = array();
		$ledgerService = new LedgerService();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// get journalArray for validate it with product array
		$journalModel = new JournalModel();
		$journalArrayData = $journalModel->getJfIdArrayData($jfId);
		
		// get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// get productArray and validate it with journal array
		$productController = new ProductController(new Container());
		$method=$constantArray['getMethod'];
		$path=$constantArray['productUrl'];
		$productId = array();
		$productRequest = Request::create($path,$method,$productId);
		$productRequest->headers->set('jfid',$jfId);
		$processedData = $productController->getData($productRequest);
		$jsonDecodedProductData = json_decode($processedData);
		
		if(strcmp($journalArrayData,$exceptionArray['404'])==0)
		{
			return $journalArrayData;
		}
		$decodedJournalData = json_decode($journalArrayData);
		//tax and array both exist
		if(array_key_exists("tax",$productData) && array_key_exists("0",$productData))
		{
			for($journalArrayData=0;$journalArrayData<count($decodedJournalData);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $decodedJournalData[$journalArrayData]->ledger_id;
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData)==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						//tax ledger exist
						if($decodedJournalData[$journalArrayData]->amount==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						$discountTotal = 0;
						//discount ledger exist
						for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
						{
							if(strcmp($productData[0][$productArrayData]['discount_type'],"flat")==0)
							{
								$discountArray[$productArrayData]=$productData[0][$productArrayData]['discount'];
							}
							else
							{
								$discountArray[$productArrayData]=($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
							}
							$discountTotal = $discountTotal+$discountArray[$productArrayData];
						}
						if($discountTotal == $decodedJournalData[$journalArrayData]->amount)
						{
							$discountFlag=1;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//tax ledger exist
						if($decodedJournalData[$journalArrayData]->amount==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						$discountTotal = 0;
						//discount ledger exist
						for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
						{
							if(strcmp($productData[0][$productArrayData]['discount_type'],"flat")==0)
							{
								$discountArray[$productArrayData]=$productData[0][$productArrayData]['discount'];
							}
							else
							{
								$discountArray[$productArrayData]=($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
							}
							$discountTotal = $discountTotal+$discountArray[$productArrayData];
						}
						if($discountTotal == $decodedJournalData[$journalArrayData]->amount)
						{
							$discountFlag=1;
						}
					}
				}
			}
			if($discountFlag==0 || $taxFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		//only tax exist
		else if(array_key_exists("tax",$productData))
		{
			for($journalArrayData=0;$journalArrayData<count($decodedJournalData);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $decodedJournalData[$journalArrayData]->ledger_id;
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData)==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						// tax ledger exist
						if($decodedJournalData[$journalArrayData]->amount==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						// tax ledger exist
						if($decodedJournalData[$journalArrayData]->amount==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
				}
			} 
			if($taxFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		//only array exist
		else
		{
			for($journalArrayData=0;$journalArrayData<count($decodedJournalData);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $decodedJournalData[$journalArrayData]->ledger_id;
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				if(strcmp("sales",$headerData)==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						$discountTotal=0;
						//discount ledger exist
						for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
						{
							if(strcmp($productData[0][$productArrayData]['discount_type'],"flat")==0)
							{
								$discountArray[$productArrayData]=$productData[0][$productArrayData]['discount'];
							}
							else
							{
								$discountArray[$productArrayData]=($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
							}
							$discountTotal = $discountTotal+$discountArray[$productArrayData];
						}
						if($discountTotal == $decodedJournalData[$journalArrayData]->amount)
						{
							$discountFlag=1;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						$discountTotal=0;
						//discount ledger exist
						for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
						{
							if(strcmp($productData[0][$productArrayData]['discount_type'],"flat")==0)
							{
								$discountArray[$productArrayData]=$productData[0][$productArrayData]['discount'];
							}
							else
							{
								$discountArray[$productArrayData]=($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
							}
							$discountTotal = $discountTotal+$discountArray[$productArrayData];
						}
						if($discountTotal == $decodedJournalData[$journalArrayData]->amount)
						{
							$discountFlag=1;
						}
					}
				}
			}
			if($discountFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		return $trimJournalData;
	}
	
	/**
	 * validate jouranl data for update
     * @param trim-array of journal and header data
	 * check journal and product data and if tax and discount is available then check that value
     * @return array/exception message
     */
	public function validateJournalBuisnessLogic($headerData,$journalData,$jfId)
	{
		$discountFlag=0;
		$journalTaxFlag=0;
		$journalDiscountFlag=0;
		$taxFlag=0;
		$discountArray = array();
		$ledgerService = new LedgerService();
		$ledgerIdArray = array();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// get productArray and validate it with journal array
		$productController = new ProductController(new Container());
		$method=$constantArray['getMethod'];
		$path=$constantArray['productUrl'];
		$productId = array();
		$productRequest = Request::create($path,$method,$productId);
		$productRequest->headers->set('jfid',$jfId);
		$processedData = $productController->getData($productRequest);
		$jsonDecodedProductData = json_decode($processedData);
		
		for($journalArrayData=0;$journalArrayData<count($journalData[0]);$journalArrayData++)
		{
			$ledgerIdArray[$journalArrayData] = $journalData[0][$journalArrayData]['ledger_id'];
			$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
			
			if(strcmp("sales",$headerData['type'][0])==0)
			{
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
				{
					$journalTaxFlag=1;
					//tax  ledger exist
					if($journalData[0][$journalArrayData]['amount']==$jsonDecodedProductData[0]->tax)
					{
						$taxFlag=1;
					}
				}
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
				{
					//discount ledger exist
					$journalDiscountFlag=1;
					$discount=0;
					
					for($productArrayData=0;$productArrayData<count($jsonDecodedProductData);$productArrayData++)
					{
						if(strcmp($jsonDecodedProductData[$productArrayData]->discountType,"flat")==0)
						{
							$discountArray[$productArrayData] = $jsonDecodedProductData[$productArrayData]->discount;
						}
						else
						{
							$discountArray[$productArrayData] = ($jsonDecodedProductData[$productArrayData]->discount/100)*$jsonDecodedProductData[$productArrayData]->price;
						}
						$discount = $discount+$discountArray[$productArrayData];
					}
					if($discount==$journalData[0][$journalArrayData]['amount'])
					{
						$discountFlag=1;
					}
				}
			}
			else
			{
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
				{
					$journalTaxFlag=1;
					//tax  ledger exist
					if($journalData[0][$journalArrayData]['amount']==$jsonDecodedProductData[0]->tax)
					{
						$taxFlag=1;
					}
				}
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
				{
					$discount=0;
					//discount ledger exist
					$journalDiscountFlag=1;
					for($productArrayData=0;$productArrayData<count($jsonDecodedProductData);$productArrayData++)
					{
						if(strcmp($jsonDecodedProductData[$productArrayData]->discountType,"flat")==0)
						{
							$discountArray[$productArrayData] = $jsonDecodedProductData[$productArrayData]->discount;
						}
						else
						{
							$discountArray[$productArrayData] = ($jsonDecodedProductData[$productArrayData]->discount/100)*$jsonDecodedProductData[$productArrayData]->price;
						}
						$discount = $discount+$discountArray[$productArrayData];
					}
					if($discount==$journalData[0][$journalArrayData]['amount'])
					{
						$discountFlag=1;
					}
				}
			}
		}
		if($journalTaxFlag==1 && $taxFlag==0 || $journalDiscountFlag==1 && $discountFlag==0)
		{
			return $exceptionArray['content'];
		}
		return $journalData;
	}
	
	/**
	 * validate jouranl-product data for update
     * @param trim-array of journal and product header data
	 * check journal and product data and if tax and discount is available then check that value
     * @return array/exception message
     */
	public function validateInsertBuisnessLogic($productData,$journalData,$journalType)
	{
		$taxFlag=0;
		$journalTaxFlag=0;
		$journalDiscountFlag=0;
		$discountFlag=0;
		$discountArray = array();
		$ledgerService = new LedgerService();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($journalArrayData=0;$journalArrayData<count($journalData[0]);$journalArrayData++)
		{
			$ledgerIdArray[$journalArrayData] = $journalData[0][$journalArrayData]['ledgerId'];
			$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
			// print_r(json_decode($ledgerResult));
			if(strcmp("sales",$journalType)==0)
			{
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
				{
					$journalTaxFlag=1;
					//tax ledger exist
					if($journalData[0][$journalArrayData]['amount']==$productData['tax'])
					{
						$taxFlag=1;
					}
				}
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
				{
					//discount ledger exist
					$discount=0;
					$journalDiscountFlag=1;
					//calculate total discount in product
					for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
					{
						if(strcmp($productData[0][$productArrayData]['discountType'],"flat")==0)
						{
							$discountArray[$productArrayData] = $productData[0][$productArrayData]['discount'];
						}
						else
						{
							$discountArray[$productArrayData] = ($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
						}
						$discount = $discount+$discountArray[$productArrayData];
					}
					if($discount==$journalData[0][$journalArrayData]['amount'])
					{
						$discountFlag=1;
					}
				}
			}
			else
			{
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
				{
					$journalTaxFlag=1;
					//tax ledger exist
					if($journalData[0][$journalArrayData]['amount']==$productData['tax'])
					{
						$taxFlag=1;
					}
				}
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
				{
					//discount ledger exist
					$discount=0;
					$journalDiscountFlag=1;
					//calculate total discount in product
					for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
					{
						if(strcmp($productData[0][$productArrayData]['discountType'],"flat")==0)
						{
							$discountArray[$productArrayData] = $productData[0][$productArrayData]['discount'];
						}
						else
						{
							$discountArray[$productArrayData] = ($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
						}
						$discount = $discount+$discountArray[$productArrayData];
					}
					if($discount==$journalData[0][$journalArrayData]['amount'])
					{
						$discountFlag=1;
					}
				}
			}
		}
		if($journalTaxFlag==1 && $taxFlag==0 || $journalDiscountFlag==1 && $discountFlag==0)
		{
			return $exceptionArray['content'];
		}
		
		//reverse checking(product to journal checking)
		if($productData['tax']==0)
		{
			$discount=0;
			for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
			{
				if($productData[0][$productArrayData]['discountType']=="flat")
				{
					$discountArray[$productArrayData] = $productData[0][$productArrayData]['discount'];
				}
				else
				{
					$discountArray[$productArrayData] = ($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
				}
				$discount = $discount+$discountArray[$productArrayData];
			}
			if($discount!=0)
			{
				for($journalInnerArrayData=0;$journalInnerArrayData<count($journalData[0]);$journalInnerArrayData++)
				{
					$ledgerIdArray[$journalInnerArrayData] = $journalData[0][$journalInnerArrayData]['ledgerId'];
					$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalInnerArrayData]);
					if(strcmp("sales",$journalType)==0)
					{
						if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
						{
							$jouranlDiscountFlag=1;
							if($discount==$journalData[0][$journalInnerArrayData]['amount'])
							{
								$discountFlag=1;
							}
						}
					}
					else
					{
						if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
						{
							$jouranlDiscountFlag=1;
							if($discount==$journalData[0][$journalInnerArrayData]['amount'])
							{
								$discountFlag=1;
							}
						}
					}
				}
				if($jouranlDiscountFlag==0 || $discountFlag==0)
				{
					return $exceptionArray['content'];
				}
			}
		}
		else
		{
			//tax ledger should be exist in journal
			for($journalArrayData=0;$journalArrayData<count($journalData[0]);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $journalData[0][$journalArrayData]['ledgerId'];
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				
				if(strcmp("sales",$journalType)==0)
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
					{
						//tax exist
						$journalTaxFlag=1;
						if($journalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
				}
				else
				{
					if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
					{
						//tax exist
						$journalTaxFlag=1;
						if($journalData[0][$journalArrayData]['amount']==$productData['tax'])
						{
							$taxFlag=1;
						}
					}
					
				}
			}
			if($taxFlag==0 || $journalTaxFlag==0)
			{
				return $exceptionArray['content'];
			}
			
			$discount=0;
			for($productArrayData=0;$productArrayData<count($productData[0]);$productArrayData++)
			{
				if($productData[0][$productArrayData]['discountType']=="flat")
				{
					$discountArray[$productArrayData] = $productData[0][$productArrayData]['discount'];
				}
				else
				{
					$discountArray[$productArrayData] = ($productData[0][$productArrayData]['discount']/100)*$productData[0][$productArrayData]['price'];
				}
				$discount = $discount+$discountArray[$productArrayData];
			}
			if($discount!=0)
			{
				for($journalInnerArrayData=0;$journalInnerArrayData<count($journalData[0]);$journalInnerArrayData++)
				{
					$ledgerIdArray[$journalInnerArrayData] = $journalData[0][$journalInnerArrayData]['ledgerId'];
					$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalInnerArrayData]);
					if(strcmp("sales",$journalType)==0)
					{
						if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
						{
							$jouranlDiscountFlag=1;
							if($discount==$journalData[0][$journalInnerArrayData]['amount'])
							{
								$discountFlag=1;
							}
						}
					}
					else
					{
						if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
						{
							$jouranlDiscountFlag=1;
							if($discount==$journalData[0][$journalInnerArrayData]['amount'])
							{
								$discountFlag=1;
							}
						}
					}
				}
				if($jouranlDiscountFlag==0 || $discountFlag==0)
				{
					return $exceptionArray['content'];
				}
			}
		}
		return $journalData;
	}
}