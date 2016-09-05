<?php
namespace ERP\Core\ProductGroups\Services;

use ERP\Core\ProductGroups\Persistables\ProductGroupPersistable;
use ERP\Core\ProductGroups\Entities\ProductGroup;
use ERP\Model\ProductGroups\ProductGroupModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\ProductGroups\Entities\EncodeData;
use ERP\Core\ProductGroups\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupService extends AbstractService
{
    /**
     * @var productGroupService
	 * $var productGroupModel
     */
    private $productGroupService;
    private $productCategoryModel;
	
    /**
     * @param ProductGroupService $productGroupService
     */
    public function initialize(ProductGroupService $productGroupService)
    {		
		echo "init";
    }
	
    /**
     * @param ProductCategoryPersistable $persistable
     */
    public function create(ProductGroupPersistable $persistable)
    {
		return "create method of ProductGroupService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BranchPersistable $persistable
     * @return status
     */
	public function insert(ProductGroupPersistable $persistable)
	{
		$productParentGrpId = $persistable->getProductParentGrpId();
		$productGrpDesc = $persistable->getProductGrpDesc();
		$isDisplay = $persistable->getIsDisplay();
		$productGrpName = $persistable->getName();
		$productGrpModel = new ProductGroupModel();
		$status = $productGrpModel->insertData($productParentGrpId,$productGrpDesc,$isDisplay,$productGrpName);
		return $status;
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllProductGrpData()
	{
		$productGroupModel = new ProductGroupModel();
		$status = $productGroupModel->getAllData();
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
     * get all the data from the table and call the model for database selection opertation
     * @param $productCategoryId
     * @return status
     */
	public function getProductGrpData($productGroupId)
	{
		$productGroupModel = new ProductGroupModel();
		$status = $productGroupModel->getData($productGroupId);
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
     * get the data from persistable object and call the model for database update opertation
     * @param ProductCategoryPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update(ProductGroupPersistable $persistable, UpdateOptions $options = null)
    {
		$productParentGrpId = $persistable->getProductParentGrpId();
		$productGrpDesc = $persistable->getProductGrpDesc();
		$isDisplay = $persistable->getIsDisplay();
		$productGrpName = $persistable->getName();
		$productGrpId = $persistable->getId();
		$productGrpModel = new ProductGroupModel();
		$status = $productGrpModel->updateData($productParentGrpId,$productGrpDesc,$isDisplay,$productGrpName,$productGrpId);
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
     * @param int $id
     */
    public function delete(ProductGroupPersistable $persistable)
    {      
		$productGrpId = $persistable->getId();
        $productGrpModel = new ProductGroupModel();
		$status = $productGrpModel->deleteData($productGrpId);
		return $status;
    }   
}