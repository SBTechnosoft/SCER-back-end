<?php
namespace ERP\Core\Accounting\LedgerGrps\Services;

use ERP\Core\Accounting\LedgerGrps\Persistables\LedgerGrpPersistable;
use ERP\Core\Accounting\LedgerGrps\Entities\LedgerGrp;
use ERP\Model\Accounting\LedgerGrps\LedgerGrpModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerGrpService extends AbstractService
{
    /**
     * @var ledgerGrpService
	 * $var ledgerGrpModel
     */
    private $ledgerGrpService;
    private $ledgerGrpModel;
	
    /**
     * @param LedgerGrpService $ledgerGrpService
     */
    public function initialize(LedgerGrpService $ledgerGrpService)
    {		
		echo "init";
    }
	
    /**
     * @param BranchPersistable $persistable
     */
    public function create(LedgerGrpPersistable $persistable)
    {
		return "create method of LedgerGrpService";
		
    }
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllLedgerGrpData()
	{
		$ledgerGrpModel = new LedgerGrpModel();
		$status = $ledgerGrpModel->getAllData();
		return $status;
	}
	
	/**
     * get all the data  as per given id and call the model for database selection opertation
     * @param $ledgerGrpId
     * @return status
     */
	public function getLedgerGrpData($ledgerGrpId)
	{
		$ledgerGrpModel = new LedgerGrpModel();
		$status = $ledgerGrpModel->getData($ledgerGrpId);
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
}