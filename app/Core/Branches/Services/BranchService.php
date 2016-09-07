<?php
namespace ERP\Core\Branches\Services;

use ERP\Core\Branches\Persistables\BranchPersistable;
use ERP\Core\Branches\Entities\Branch;
use ERP\Model\Branches\BranchModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Branches\Entities\EncodeData;
use ERP\Core\Branches\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchService extends AbstractService
{
    /**
     * @var branchService
	 * $var branchModel
     */
    private $branchService;
    private $branchModel;
	
    /**
     * @param BranchService $branchService
     */
    public function initialize(BranchService $branchService)
    {		
		echo "init";
    }
	
    /**
     * @param BranchPersistable $persistable
     */
    public function create(BranchPersistable $persistable)
    {
		return "create method of BranchService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BranchPersistable $persistable
     * @return status
     */
	public function insert(BranchPersistable $persistable)
	{
		$branchName = $persistable->getName();
		$address1 = $persistable->getAddress1();
		$address2 = $persistable->getAddress2();
		$pincode = $persistable->getPincode();
		$isDisplay = $persistable->getIsDisplay();
		$isDefault = $persistable->getIsDefault();
		$stateAbb = $persistable->getStateAbb();
		$cityId = $persistable->getId();
		$companyId = $persistable->getCompanyId();
		$branchModel = new BranchModel();
		
		//data pass to the model object for insertion
		$status = $branchModel->insertData($branchName,$address1,$address2,$pincode,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllBranchData()
	{
		$branchModel = new BranchModel();
		$status = $branchModel->getAllData();
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
     * @param $branchId
     * @return status
     */
	public function getBranchData($branchId)
	{
		$branchModel = new BranchModel();
		$status = $branchModel->getData($branchId);
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
		$branchModel = new BranchModel();
		$status = $branchModel->getAllBranchData($companyId);
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
     * @param BranchPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update(BranchPersistable $persistable, UpdateOptions $options = null)
    {
		$branchName = $persistable->getName();
		$address1 = $persistable->getAddress1();
		$address2 = $persistable->getAddress2();
		$pincode = $persistable->getPincode();
		$isDisplay = $persistable->getIsDisplay();
		$isDefault = $persistable->getIsDefault();
		$stateAbb = $persistable->getStateAbb();
		$cityId = $persistable->getId();
		$companyId = $persistable->getCompanyId();
		$branchId = $persistable->getBranchId();
		$branchModel = new BranchModel();
	    
		//data pass to the model object for update
		$status = $branchModel->updateData($branchName,$address1,$address2,$pincode,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId,$branchId);
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
     * @param BranchPersistable $persistable
     */
    public function delete(BranchPersistable $persistable)
    {      
		$branchId = $persistable->getBranchId();
        $branchModel = new BranchModel();
		$status = $branchModel->deleteData($branchId);
		return $status;
    }   
}