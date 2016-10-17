<?php
namespace ERP\Core\Invoices\Services;

use ERP\Core\Invoices\Persistables\InvoicePersistable;
use ERP\Core\Invoices\Entities\Invoice;
use ERP\Model\Invoices\InvoiceModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Invoices\Entities\EncodeData;
use ERP\Core\Invoices\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceService extends AbstractService
{
    /**
     * @var invoiceService
	 * $var invoiceModel
     */
    private $invoiceService;
    private $invoiceModel;
	
    /**
     * @param InvoiceService $invoiceService
     */
    public function initialize(InvoiceService $invoiceService)
    {		
		echo "init";
    }
	
    /**
     * @param InvoicePersistable $persistable
     */
    public function create(InvoicePersistable $persistable)
    {
		return "create method of InvoiceService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param InvoicePersistable $persistable
     * @return status
     */
	public function insert()
	{
		$invoiceArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$invoiceArray = func_get_arg(0);
		for($data=0;$data<count($invoiceArray);$data++)
		{
			$funcName[$data] = $invoiceArray[$data][0]->getName();
			$getData[$data] = $invoiceArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $invoiceArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$invoiceModel = new InvoiceModel();
		$status = $invoiceModel->insertData($getData,$keyName);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllInvoiceData()
	{
		$invoiceModel = new InvoiceModel();
		$status = $invoiceModel->getAllData();
		if($status=="204: No Content")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data  as per given id and call the model for database selection opertation
     * @param $invoiceId
     * @return status
     */
	public function getInvoiceData($invoiceId)
	{
		$invoiceModel = new InvoiceModel();
		$status = $invoiceModel->getData($invoiceId);
		if($status=="404:Id Not Found")
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
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllData($companyId)
	{
		$invoiceModel = new InvoiceModel();
		$status = $invoiceModel->getAllInvoiceData($companyId);
		if($status=="204: No Content")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
	
	 /**
     * get and invoke method is of Container Interface method
     * @param int $id,$name
     */
    public function get($id,$name)
    {
		echo "get";		
    }   
	public function invoke(callable $method)
	{
		echo "invoke";
	}
}