<?php
namespace ERP\Core\Accounting\Bills\Services;

use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use ERP\Core\Accounting\Bills\Entities\Bill;
use ERP\Model\Accounting\Bills\BillModel;
use ERP\Core\Shared\Options\UpdateOptions;
// use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Accounting\Bills\Entities\EncodeData;
// use ERP\Core\Accounting\Bills\Entities\EncodeAllData;
use ERP\Exceptions\ExceptionMessage;
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
     * @return status
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
			$bankName = $billArray->getBankName();
			$checkNumber = $billArray->getCheckNumber();
			$total = $billArray->getTotal();
			$tax = $billArray->getTax();
			$grandTotal = $billArray->getGrandTotal();
			$advance = $billArray->getAdvance();
			$balance = $billArray->getBalance();
			$remark = $billArray->getRemark();
			$entryDate = $billArray->getEntryDate();
			$companyId = $billArray->getCompanyId();
			$ClientId = $billArray->getClientId();
			$salesType = $billArray->getSalesType();
			
			//data pass to the model object for insert
			$billModel = new BillModel();
			$status = $billModel->insertData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType);
			return $status;
		}
		//data with image insertion
		else
		{
			$documentArray = array();
			$productArray = $billArray[count($billArray)-1]->getProductArray();
			$paymentMode = $billArray[count($billArray)-1]->getPaymentMode();
			$invoiceNumber = $billArray[count($billArray)-1]->getInvoiceNumber();
			$bankName = $billArray[count($billArray)-1]->getBankName();
			$checkNumber = $billArray[count($billArray)-1]->getCheckNumber();
			$total = $billArray[count($billArray)-1]->getTotal();
			$tax = $billArray[count($billArray)-1]->getTax();
			$grandTotal = $billArray[count($billArray)-1]->getGrandTotal();
			$advance = $billArray[count($billArray)-1]->getAdvance();
			$balance = $billArray[count($billArray)-1]->getBalance();
			$remark = $billArray[count($billArray)-1]->getRemark();
			$entryDate = $billArray[count($billArray)-1]->getEntryDate();
			$companyId = $billArray[count($billArray)-1]->getCompanyId();
			$ClientId = $billArray[count($billArray)-1]->getClientId();
			$salesType = $billArray[count($billArray)-1]->getSalesType();
			for($doc=0;$doc<(count($billArray)-1);$doc++)
			{
				array_push($documentArray,$billArray[$doc]);	
			}
			//data pass to the model object for insert
			$billModel = new BillModel();
			$status = $billModel->insertAllData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$documentArray);
			
			//get exception message
			$exception = new ExceptionMessage();
			$fileSizeArray = $exception->messageArrays();
			if(strcmp($status,$fileSizeArray['500'])==0)
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
}