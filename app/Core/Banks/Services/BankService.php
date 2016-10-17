<?php
namespace ERP\Core\Banks\Services;

use ERP\Core\Banks\Persistables\BankPersistable;
use ERP\Core\Banks\Entities\Bank;
use ERP\Model\Banks\BankModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BankService extends AbstractService
{
    /**
     * @var bankService
	 * $var bankModel
     */
    private $bankService;
    private $bankModel;
	
    /**
     * @param BankService $bankService
     */
    public function initialize(BankService $bankService)
    {		
		echo "init";
    }
	
    /**
     * @param BranchPersistable $persistable
     */
    public function create(BankPersistable $persistable)
    {
		return "create method of BankService";
		
    }
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllBankData()
	{
		$bankModel = new BankModel();
		$status = $bankModel->getAllData();
		return $status;
	}
	
	/**
     * get all the data  as per given id and call the model for database selection opertation
     * @param $bankId
     * @return status
     */
	public function getBankData($bankId)
	{
		$bankModel = new BankModel();
		$status = $bankModel->getData($bankId);
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