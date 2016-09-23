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
     * get the data from persistable object and call the model for database insertion opertation
     * @param array
     * @return status
     */
	public function insert()
	{
		$productCatArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$productCatArray = func_get_arg(0);
		for($data=0;$data<count($productCatArray);$data++)
		{
			$funcName[$data] = $productCatArray[$data][0]->getName();
			$getData[$data] = $productCatArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productCatArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$productCatModel = new ProductCategoryModel();
		$status = $productCatModel->insertData($getData,$keyName);
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
    public function update()
    {
		$productCatArray = array();
		$getData = array();
		$funcName = array();
		$productCatArray = func_get_arg(0);
		for($data=0;$data<count($productCatArray);$data++)
		{
			$funcName[$data] = $productCatArray[$data][0]->getName();
			$getData[$data] = $productCatArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productCatArray[$data][0]->getkey();
		}
		$productCatId = $productCatArray[0][0]->getProductCatId();
		//data pass to the model object for update
		$productCategoryModel = new ProductCategoryModel();
		$status = $productCategoryModel->updateData($getData,$keyName,$productCatId);
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