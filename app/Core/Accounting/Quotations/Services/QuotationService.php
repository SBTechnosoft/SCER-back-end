<?php
namespace ERP\Core\Accounting\Quotations\Services;

// use ERP\Core\Accounting\Quotations\Persistables\BillPersistable;
// use ERP\Core\Accounting\Quotations\Entities\Quotation;
use ERP\Model\Accounting\Quotations\QuotationModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\User\Entities\User;
use ERP\Core\Accounting\Quotations\Entities\EncodeData;
use ERP\Core\Accounting\Quotations\Entities\EncodeAllData;
use ERP\Exceptions\ExceptionMessage;
use Illuminate\Container\Container;
use ERP\Http\Requests;
use Illuminate\Http\Request;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationService
{
    /**
     * @var quotationService
	 * $var quotationModel
     */
    private $quotationService;
    private $quotationModel;
	
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
		$quotationArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$quotationArray = func_get_arg(0);
		
		//only data insertion
		if(is_object($quotationArray))
		{
			$productArray = $quotationArray->getProductArray();
			$quotationNumber = $quotationArray->getQuotationNumber();
			$total = $quotationArray->getTotal();
			$totalDiscounttype = $quotationArray->getTotalDiscounttype();
			$totalDiscount = $quotationArray->getTotalDiscount();
			$extraCharge = $quotationArray->getExtraCharge();
			$tax = $quotationArray->getTax();
			$grandTotal = $quotationArray->getGrandTotal();
			$remark = $quotationArray->getRemark();
			$entryDate = $quotationArray->getEntryDate();
			$companyId = $quotationArray->getCompanyId();
			$ClientId = $quotationArray->getClientId();
			$jfId= $quotationArray->getJfId();
			
			//data pass to the model object for insert
			$quotationModel = new QuotationModel();
			$status = $quotationModel->insertData($productArray,$quotationNumber,$total,$extraCharge,$tax,$grandTotal,$remark,$entryDate,$companyId,$ClientId,$jfId,$totalDiscounttype,$totalDiscount);
			
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
     * get quotation data as per given data in header
     * @param header-data
     * @return array-data/error message
     */
	public function getSearchingData($headerData)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
			
		//data pass to the model object for getData
		$quotationModel = new QuotationModel();
		$quotationResult = $quotationModel->getSpecifiedData($headerData);
		if(strcmp($quotationResult,$exceptionArray['204'])==0)
		{
			return $quotationResult;
		}
		else
		{
			$encodeAllData = new EncodeAllData();
			$encodingResult = $encodeAllData->getEncodedAllData($quotationResult);
			return $encodingResult;
		}
	}
	
	/**
     * update quotation data
     * @param QuotationPersistable $persistable
     * @return status/error message
     */
	public function updateData()
	{
		$persistableData = func_get_arg(0);
		$quotationBillId = func_get_arg(1);
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
		$quotationModel = new QuotationModel();
				
		if(empty($persistableData))
		{
			$noDataFlag=1;
		}
		else if(is_array($persistableData))
		{
			if(count($persistableData)==0)
			{
				//almost only client data is available
				$noDataFlag=1;
			}
			$arrayLength = count($persistableData);
			$innerArrayLength = count($persistableData[$arrayLength-1]);
			
			if($innerArrayLength!=0)
			{
				if(!is_object($persistableData[$arrayLength-1]))
				{
					// inventory is available
					$flag=1;
				}
				if($flag==1)
				{
					$quotationId = $quotationBillId;
					$quotationCount = count($persistableData)-1;
					for($arrayData=0;$arrayData<$quotationCount;$arrayData++)
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
					$quotationId = $quotationBillId;
					$quotationCount = count($persistableData);
					for($arrayData=0;$arrayData<$quotationCount;$arrayData++)
					{
						$dataFlag=1;
						$funcName = $persistableData[$arrayData]->getName();
						$value = $persistableData[$arrayData]->$funcName();
						$key = $persistableData[$arrayData]->getKey();
						$singleData[$key] = $value;
					}
				}
				$quotationUpdateResult = $quotationModel->updateQuotationData($singleData,$quotationId);
				if(strcmp($quotationUpdateResult,$exceptionArray['204'])==0 || strcmp($quotationUpdateResult,$exceptionArray['500'])==0)
				{
					return $quotationUpdateResult;
				}
			}
		}
		else
		{
			// if(!is_object($persistableData[count($persistableData)-1]))
			// {
				//inventory is available
				// $flag=1;
			// }
			// $singleData = array();
			// if($flag==1)
			// {
				// $quotationId = $persistableData[0]->getQuotationId();
				// for($arrayData=0;$arrayData<count($persistableData)-1;$arrayData++)
				// {
					// if($persistableData[$arrayData]->getProductArray())
					// {
						// $inventoryFlag=1;
						// $singleData['product_array'] = $persistableData[$arrayData]->getProductArray();
					// }
					// else
					// {
						// $dataFlag=1;
						// $funcName = $persistableData[$arrayData]->getName();
						// $value = $persistableData[$arrayData]->$funcName();
						// $key = $persistableData[$arrayData]->getKey();
						// $singleData[$key] = $value;
					// }
				// }
			// }
			// else
			// {
				// $quotationId = $persistableData[0]->getQuotationId();
				// for($arrayData=0;$arrayData<count($persistableData);$arrayData++)
				// {
					// $dataFlag=1;
					// $funcName = $persistableData[$arrayData]->getName();
					// $value = $persistableData[$arrayData]->$funcName();
					// $key = $persistableData[$arrayData]->getKey();
					// $singleData[$key] = $value;
				// }
			// }
			// $quotationModel = new QuotationModel();
			// $quotationUpdateResult = $quotationModel->updateQuotationData($singleData,$quotationId);
			// if(strcmp($quotationUpdateResult,$exceptionArray['204'])!=0 || strcmp($quotationUpdateResult,$exceptionArray['500'])!=0)
			// {
				// $encoded = new EncodeData();
				// $encodeData = $encoded->getEncodedData($quotationUpdateResult);
				// $decodedQuotationData = json_decode($encodeData);
				
				// $quotationBillIdArray = array();
				// $quotationBillIdArray['quotationBillId'] = $quotationId;
				// $quotationBillIdArray['companyId'] = $decodedQuotationData->company->companyId;
				// $quotationBillIdArray['quotationData'] = $decodedQuotationData;
				// $documentController = new DocumentController(new Container());
				// $method=$constantArray['postMethod'];
				// $path=$constantArray['documentGenerateQuotationUrl'];
				// $documentRequest = Request::create($path,$method,$quotationBillIdArray);
				// $processedData = $documentController->getQuotationData($documentRequest);
				// return $processedData;
			// }
		}
		if($noDataFlag==1)
		{
			//get quotation data
			$quotationUpdateResult = $quotationModel->getquotationIdData($quotationBillId);
			if(strcmp($quotationUpdateResult,$exceptionArray['204'])==0)
			{
				return $exceptionArray['204'];
			}
		}
		//pdf generation for update quotation data
		$documentController = new DocumentController(new Container());
		$encoded = new EncodeData();
		$encodeData = $encoded->getEncodedData($quotationUpdateResult);
		$decodedQuotationData = json_decode($encodeData);
		
		$quotationBillIdArray = array();
		$quotationBillIdArray['quotationBillId'] = $quotationBillId;
		$quotationBillIdArray['companyId'] = $decodedQuotationData->company->companyId;
		$quotationBillIdArray['quotationData'] = $decodedQuotationData;
		$method=$constantArray['postMethod'];
		$path=$constantArray['documentGenerateQuotationUrl'];
		$documentRequest = Request::create($path,$method,$quotationBillIdArray);
		if(array_key_exists('operation',$headerData))
		{
			$documentRequest->headers->set('operation',$headerData['operation'][0]);
		}
		else
		{
			$documentRequest->headers->set('key',$headerData);
		}
		$processedData = $documentController->getQuotationData($documentRequest);
		return $processedData;
		
	}
}