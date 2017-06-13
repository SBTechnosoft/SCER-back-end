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
// use ERP\Api\V1_0\Documents\Controllers\DocumentController;
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
			$status = $quotationModel->insertData($productArray,$quotationNumber,$total,$extraCharge,$tax,$grandTotal,$remark,$entryDate,$companyId,$ClientId,$jfId);
			
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
	
	public function getSearchingData($headerData)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
			
		//data pass to the model object for getData
		$quotationModel = new QuotationModel();
		$quotationResult = $quotationModel->getSpecifiedData($headerData);
		if(strcmp($quotationResult,$exceptionArray['404'])==0)
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
}