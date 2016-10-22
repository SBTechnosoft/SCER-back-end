<?php
namespace ERP\Core\Accounting\Ledgers\Services;

use ERP\Core\Accounting\Ledgers\Persistables\LedgerPersistable;
use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Accounting\Ledgers\Entities\EncodeData;
use ERP\Core\Accounting\Ledgers\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerService extends AbstractService
{
    /**
     * @var ledgerService
	 * $var ledgerModel
     */
    private $ledgerService;
    private $ledgerModel;
	
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
     * @param LedgerPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$ledgerArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$ledgerArray = func_get_arg(0);
		for($data=0;$data<count($ledgerArray);$data++)
		{
			$funcName[$data] = $ledgerArray[$data][0]->getName();
			$getData[$data] = $ledgerArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $ledgerArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$ledgerModel = new LedgerModel();
		$status = $ledgerModel->insertData($getData,$keyName);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllLedgerData()
	{
		$ledgerModel = new LedgerModel();
		$status = $ledgerModel->getAllData();
		if($status=="204: No Content")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			// print_r($encodeAllData);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data  as per given id and call the model for database selection opertation
     * @param $ledgerId
     * @return status
     */
	public function getLedgerData($ledgerId)
	{
		$ledgerModel = new LedgerModel();
		$status = $ledgerModel->getData($ledgerId);
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
	public function getAllData($ledgerGrpId)
	{
		$ledgerModel = new LedgerModel();
		$status = $ledgerModel->getAllLedgerData($ledgerGrpId);
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
     * get the data from persistable object and call the model for database update opertation
     * @param LedgerPersistable $persistable
     * @param updateOptions $options [optional]
	 * parameter is in array form.
     * @return status
     */
    public function update()
    {
		$ledgerArray = array();
		$getData = array();
		$funcName = array();
		$ledgerArray = func_get_arg(0);
		for($data=0;$data<count($ledgerArray);$data++)
		{
			$funcName[$data] = $ledgerArray[$data][0]->getName();
			$getData[$data] = $ledgerArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $ledgerArray[$data][0]->getkey();
		}
		$ledgerId = $ledgerArray[0][0]->getLedgerId();
		// data pass to the model object for update
		$ledgerModel = new LedgerModel();
		$status = $ledgerModel->updateData($getData,$keyName,$ledgerId);
		return $status;	
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
	
    /**
     * @param delete
     * @param LedgerPersistable $persistable
     */
    public function delete(LedgerPersistable $persistable)
    {      
		$ledgerId = $persistable->getLedgerId();
        $ledgerModel = new LedgerModel();
		$status = $ledgerModel->deleteData($ledgerId);
		return $status;
    }   
}