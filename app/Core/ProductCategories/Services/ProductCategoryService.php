<?php
namespace ERP\Core\ProductCategories\Services;

use ERP\Core\ProductCategories\Persistables\ProductCategoryPersistable;
use ERP\Core\ProductCategories\Entities\ProductCategory;
use ERP\Model\ProductCategories\ProductCategoryModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\ProductCategories\Entities\EncodeData;
use ERP\Core\ProductCategories\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryService extends AbstractService
{
    /**
     * @var productCategoryService
	 * $var productCategoryModel
     */
    private $productCategoryService;
    private $productCategoryModel;
	
    /**
     * @param ProductCategoryService $productCategoryService
     */
    public function initialize(ProductCategoryService $productCategoryService)
    {		
		echo "init";
    }
	
    /**
     * @param ProductCategoryPersistable $persistable
     */
    public function create(ProductCategoryPersistable $persistable)
    {
		return "create method of ProductCategoryService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BranchPersistable $persistable
     * @return status
     */
	public function insert(ProductCategoryPersistable $persistable)
	{
		$productParentCatId = $persistable->getProductParentCatId();
		$productCatDesc = $persistable->getProductCatDesc();
		$isDisplay = $persistable->getIsDisplay();
		$productCatName = $persistable->getName();
		$productCatModel = new ProductCategoryModel();
		$status = $productCatModel->insertData($productParentCatId,$productCatDesc,$isDisplay,$productCatName);
		return $status;
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllProductCatData()
	{
		$productCategoryModel = new ProductCategoryModel();
		$status = $productCategoryModel->getAllData();
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
	public function getProductCatData($productCategoryId)
	{
		$productCategoryModel = new ProductCategoryModel();
		$status = $productCategoryModel->getData($productCategoryId);
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
    public function update(ProductCategoryPersistable $persistable, UpdateOptions $options = null)
    {
	   $productParentCatId = $persistable->getProductParentCatId();
		$productCatDesc = $persistable->getProductCatDesc();
		$isDisplay = $persistable->getIsDisplay();
		$productCatName = $persistable->getName();
		$productCatId = $persistable->getId();
		$productCategoryModel = new ProductCategoryModel();
		$status = $productCategoryModel->updateData($productParentCatId,$productCatDesc,$isDisplay,$productCatName,$productCatId);
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
    public function delete(ProductCategoryPersistable $persistable)
    {      
		$productCatId = $persistable->getId();
        $productCategoryModel = new ProductCategoryModel();
		$status = $productCategoryModel->deleteData($productCatId);
		return $status;
    }   
}