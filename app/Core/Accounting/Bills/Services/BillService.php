<?php
namespace ERP\Core\Accounting\Bills\Services;

use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use ERP\Core\Accounting\Bills\Entities\Bill;
use ERP\Model\Accounting\Bills\BillModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\User\Entities\User;
use ERP\Core\Accounting\Bills\Entities\EncodeData;
use ERP\Core\Accounting\Bills\Entities\EncodeAllData;
use ERP\Exceptions\ExceptionMessage;
use Illuminate\Container\Container;
use ERP\Http\Requests;
use Illuminate\Http\Request;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillService
{
    /**
     * @var billService
	 * $var billModel
     */
    private $billService;
    private $billModel;
	
    /**
     * @param LedgerService $ledgerService
     */
    public function initialize(LedgerService $ledgerService)
    {		
		echo "init";
    }
	
    /**
     * @param LedgerPersistable $persistable
     */
    public function create(LedgerPersistable $persistable)
    {
		return "create method of LedgerService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BillPersistable $persistable
     * @return status/error message
     */
	public function insert()
	{
		$billArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$billArray = func_get_arg(0);
		
		//only data insertion
		if(is_object($billArray))
		{
			$productArray = $billArray->getProductArray();
			$paymentMode = $billArray->getPaymentMode();
			$invoiceNumber = $billArray->getInvoiceNumber();
			$jobCardNumber = $billArray->getJobCardNumber();
			$bankName = $billArray->getBankName();
			$checkNumber = $billArray->getCheckNumber();
			$total = $billArray->getTotal();
			$extraCharge = $billArray->getExtraCharge();
			$tax = $billArray->getTax();
			$grandTotal = $billArray->getGrandTotal();
			$advance = $billArray->getAdvance();
			$balance = $billArray->getBalance();
			$remark = $billArray->getRemark();
			$entryDate = $billArray->getEntryDate();
			$companyId = $billArray->getCompanyId();
			$ClientId = $billArray->getClientId();
			$salesType = $billArray->getSalesType();
			$jfId= $billArray->getJfId();
			//data pass to the model object for insert
			$billModel = new BillModel();
			$status = $billModel->insertData($productArray,$paymentMode,$invoiceNumber,$jobCardNumber,$bankName,$checkNumber,$total,$extraCharge,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$jfId);
			
			//get exception message
			$exception = new ExceptionMessage();
			$exceptionArray = $exception->messageArrays();
			if(strcmp($status,$exceptionArray['500'])==0)
			{
				return $status;
			}
			else
			{
				
				$encoded = new EncodeData();
				$encodeData = $encoded->getEncodedData($status);
				return $encodeData;
			}
		}
		//data with image insertion
		else
		{
			$documentArray = array();
			$productArray = $billArray[count($billArray)-1]->getProductArray();
			$paymentMode = $billArray[count($billArray)-1]->getPaymentMode();
			$invoiceNumber = $billArray[count($billArray)-1]->getInvoiceNumber();
			$jobCardNumber = $billArray[count($billArray)-1]->getJobCardNumber();
			$bankName = $billArray[count($billArray)-1]->getBankName();
			$checkNumber = $billArray[count($billArray)-1]->getCheckNumber();
			$total = $billArray[count($billArray)-1]->getTotal();
			$extraCharge = $billArray[count($billArray)-1]->getExtraCharge();
			$tax = $billArray[count($billArray)-1]->getTax();
			$grandTotal = $billArray[count($billArray)-1]->getGrandTotal();
			$advance = $billArray[count($billArray)-1]->getAdvance();
			$balance = $billArray[count($billArray)-1]->getBalance();
			$remark = $billArray[count($billArray)-1]->getRemark();
			$entryDate = $billArray[count($billArray)-1]->getEntryDate();
			$companyId = $billArray[count($billArray)-1]->getCompanyId();
			$ClientId = $billArray[count($billArray)-1]->getClientId();
			$salesType = $billArray[count($billArray)-1]->getSalesType();
			$jfId = $billArray[count($billArray)-1]->getJfId();
			for($doc=0;$doc<(count($billArray)-1);$doc++)
			{
				array_push($documentArray,$billArray[$doc]);	
			}
			
			//data pass to the model object for insert
			$billModel = new BillModel();
			$status = $billModel->insertAllData($productArray,$paymentMode,$invoiceNumber,$jobCardNumber,$bankName,$checkNumber,$total,$extraCharge,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$documentArray,$jfId);
			
			//get exception message
			$exception = new ExceptionMessage();
			$exceptionArray = $exception->messageArrays();
			if(strcmp($status,$exceptionArray['500'])==0)
			{
				return $status;
			}
			else
			{
				$encoded = new EncodeData();
				$encodeData = $encoded->getEncodedData($status);
				return $encodeData;
			}
		}
	}
	
	 /**
     * get the data from persistable object and call the model for database get opertation
     * @param BillPersistable $persistable
     * @return status/error message
     */
	public function getData()
	{
		$persistableData = func_get_arg(0);
		$companyId = func_get_arg(1);
		
		$salesType = $persistableData->getSalesType();
		$fromDate = $persistableData->getFromDate();
		$toDate = $persistableData->getToDate();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
			
		//data pass to the model object for getData
		$billModel = new BillModel();
		$billResult = $billModel->getSpecifiedData($companyId,$salesType,$fromDate,$toDate);
		
		if(strcmp($billResult,$exceptionArray['404'])==0)
		{
			return $billResult;
		}
		else
		{
			$encodeAllData = new EncodeAllData();
			$encodingResult = $encodeAllData->getEncodedAllData($billResult);
			return $encodingResult;
		}
	}
	
   	/**
     * call the model for database get opertation
     * @param headerData
     * @return sale-data/error message
     */
	public function getPreviousNextData($headerData)
	{
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
			
		// data pass to the model object for getData
		$billModel = new BillModel();
		$billResult = $billModel->getPreviousNextData($headerData);
		
		if(strcmp($billResult,$exceptionArray['204'])==0)
		{
			return $billResult;
		}
		else
		{
			$encodeAllData = new EncodeAllData();
			$encodingResult = $encodeAllData->getEncodedAllData($billResult);
			return $encodingResult;
		}
	}
	
	 /**
     * update bill payment data
     * @param BillPersistable $persistable
     * @return status/error message
     */
	public function updatePaymentData()
	{
		$persistableData = func_get_arg(0);
		$billArray = $persistableData->getBillArray();
		$decodedBillData = json_decode($billArray);
		
		//data pass to the model object for getData
		$billModel = new BillModel();
		$billResult = $billModel->updatePaymentData($decodedBillData);
		return $billResult;
	}
	
	/**
     * update bill data
     * @param BillPersistable $persistable
     * @return status/error message
     */
	public function updateData()
	{
		$persistableData = func_get_arg(0);
		$saleId = func_get_arg(1);
		$headerData = func_get_arg(2);
		$flag=0;
		$inventoryFlag=0;
		$dataFlag=0;
		$noDataFlag=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$imageArrayData = array();
		if(empty($persistableData))
		{
			$noDataFlag=1;
		}
		else if(is_array($persistableData[0]))
		{
			$imageFlag=1;
			
			//store image in an array
			for($imageArray=0;$imageArray<count($persistableData)-1;$imageArray++)
			{
				$imageArrayData[$imageArray] = $persistableData[$imageArray];
			}
			$arrayLength = count($persistableData);
			$innerArrayLength = count($persistableData[$arrayLength-1]);
			
			
			if($innerArrayLength!=0)
			{
				if(!is_object($persistableData[$arrayLength-1][$innerArrayLength-1]))
				{
					// inventory is available
					$flag=1;
				}
				if($flag==1)
				{
					$saleId = $persistableData[$arrayLength-1][0]->getSaleId();
					for($arrayData=0;$arrayData<count($persistableData[$arrayLength-1])-1;$arrayData++)
					{
						if($persistableData[$arrayLength-1][$arrayData]->getProductArray())
						{
							$inventoryFlag=1;
							$singleData['product_array'] = $persistableData[$arrayLength-1][$arrayData]->getProductArray();
						}
						else
						{
							$dataFlag=1;
							$funcName = $persistableData[$arrayLength-1][$arrayData]->getName();
							$value = $persistableData[$arrayLength-1][$arrayData]->$funcName();
							$key = $persistableData[$arrayLength-1][$arrayData]->getKey();
							$singleData[$key] = $value;
						}
					}
				}
				else
				{
					$saleId = $persistableData[$arrayLength-1][0]->getSaleId();
					for($arrayData=0;$arrayData<count($persistableData[$arrayLength-1]);$arrayData++)
					{
						$dataFlag=1;
						$funcName = $persistableData[$arrayLength-1][$arrayData]->getName();
						$value = $persistableData[$arrayLength-1][$arrayData]->$funcName();
						$key = $persistableData[$arrayLength-1][$arrayData]->getKey();
						$singleData[$key] = $value;
					}
				}
				$billModel = new BillModel();
				$billUpdateResult = $billModel->updateBillData($singleData,$saleId,$imageArrayData);
				if(strcmp($billUpdateResult,$exceptionArray['200'])==0)
				{
					$saleIdArray = array();
					$saleIdArray['saleId'] = $saleId;
					$documentController = new DocumentController(new Container());
					
					$method=$constantArray['postMethod'];
					$path=$constantArray['documentGenerateUrl'];
					$documentRequest = Request::create($path,$method,$saleIdArray);
					if(array_key_exists('operation',$headerData))
					{
						$documentRequest->headers->set('operation',$headerData['operation'][0]);
					}
					else
					{
						$documentRequest->headers->set('key',$request->header());
					}
					$processedData = $documentController->getData($documentRequest);
					return $processedData;
				}
			}
			else
			{
				//only image is available
				$billModel = new BillModel();
				$billUpdateResult = $billModel->updateImageData($saleId,$imageArrayData);
				if(strcmp($billUpdateResult,$exceptionArray['200'])==0)
				{
					$saleIdArray = array();
					$saleIdArray['saleId'] = $saleId;
					$documentController = new DocumentController(new Container());
					
					$method=$constantArray['postMethod'];
					$path=$constantArray['documentGenerateUrl'];
					$documentRequest = Request::create($path,$method,$saleIdArray);
					if(array_key_exists('operation',$headerData))
					{
						$documentRequest->headers->set('operation',$headerData['operation'][0]);
					}
					else
					{
						$documentRequest->headers->set('key',$request->header());
					}
					$processedData = $documentController->getData($documentRequest);
					return $processedData;
				}
			}
		}
		else
		{
			if(!is_object($persistableData[count($persistableData)-1]))
			{
				//inventory is available
				$flag=1;
			}
			$singleData = array();
			if($flag==1)
			{
				$saleId = $persistableData[0]->getSaleId();
				for($arrayData=0;$arrayData<count($persistableData)-1;$arrayData++)
				{
					if($persistableData[$arrayData]->getProductArray())
					{
						$inventoryFlag=1;
						$singleData['product_array'] = $persistableData[$arrayData]->getProductArray();
					}
					else
					{
						$dataFlag=1;
						$funcName = $persistableData[$arrayData]->getName();
						$value = $persistableData[$arrayData]->$funcName();
						$key = $persistableData[$arrayData]->getKey();
						$singleData[$key] = $value;
					}
				}
			}
			else
			{
				$saleId = $persistableData[0]->getSaleId();
				for($arrayData=0;$arrayData<count($persistableData);$arrayData++)
				{
					$dataFlag=1;
					$funcName = $persistableData[$arrayData]->getName();
					$value = $persistableData[$arrayData]->$funcName();
					$key = $persistableData[$arrayData]->getKey();
					$singleData[$key] = $value;
				}
			}
			$billModel = new BillModel();
			$billUpdateResult = $billModel->updateBillData($singleData,$saleId,$imageArrayData);
			if(strcmp($billUpdateResult,$exceptionArray['200'])==0)
			{
				$saleIdArray = array();
				$saleIdArray['saleId'] = $saleId;
				$documentController = new DocumentController(new Container());
				
				$method=$constantArray['postMethod'];
				$path=$constantArray['documentGenerateUrl'];
				$documentRequest = Request::create($path,$method,$saleIdArray);
				if(array_key_exists('operation',$headerData))
				{
					$documentRequest->headers->set('operation',$headerData['operation'][0]);
				}
				else
				{
					$documentRequest->headers->set('key',$request->header());
				}
				$processedData = $documentController->getData($documentRequest);
				return $processedData;
			}
		}
		if($noDataFlag==1)
		{
			$saleIdArray = array();
			$saleIdArray['saleId'] = $saleId;
			$documentController = new DocumentController(new Container());
			
			$method=$constantArray['postMethod'];
			$path=$constantArray['documentGenerateUrl'];
			$documentRequest = Request::create($path,$method,$saleIdArray);
			if(array_key_exists('operation',$headerData))
			{
				$documentRequest->headers->set('operation',$headerData['operation'][0]);
			}
			else
			{
				$documentRequest->headers->set('key',$request->header());
			}
			$processedData = $documentController->getData($documentRequest);
			return $processedData;
		}
	}
}