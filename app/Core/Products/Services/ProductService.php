<?php
namespace ERP\Core\Products\Services;

use ERP\Core\Products\Persistables\ProductPersistable;
use ERP\Core\Products\Entities\Product;
use ERP\Model\Products\ProductModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Products\Entities\EncodeData;
use ERP\Core\Products\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductService extends AbstractService
{
    /**
     * @var productService
	 * $var productModel
     */
    private $productService;
    private $productModel;
	
    /**
     * @param ProductService $productService
     */
    public function initialize(ProductService $productService)
    {		
		echo "init";
    }
	
    /**
     * @param ProductPersistable $persistable
     */
    public function create(ProductPersistable $persistable)
    {
		return "create method of ProductService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param ProductPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$productArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$productArray = func_get_arg(0);
		for($data=0;$data<count($productArray);$data++)
		{
			$funcName[$data] = $productArray[$data][0]->getName();
			$getData[$data] = $productArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$productModel = new ProductModel();
		$status = $productModel->insertData($getData,$keyName);
		return $status;
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllProductData()
	{
		$productModel = new ProductModel();
		$status = $productModel->getAllData();
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
     * @param $productId
     * @return status
     */
	public function getProductData($productId)
	{
		echo "product";
		$productModel = new ProductModel();
		$status = $productModel->getData($productId);
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
		$productModel = new ProductModel();
		$status = $productModel->getAllProductData($companyId);
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
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getCBProductData($branchId,$companyId)
	{
		if($branchId=="null")
		{
			//getCompanyProductData(getCProductData)
			$productModel = new ProductModel();
			$status = $productModel->getCProductData($companyId);
			if($status=="204: No Content")
			{
				print_r("bId");
				// return $status;
			}
			else
			{
				$encoded = new EncodeAllData();
				$encodeAllData = $encoded->getEncodedAllData($status);
				return $encodeAllData;
			}
		}
		else if($companyId=="null")
		{
			//getBranchProductData(getBProductData)
			$productModel = new ProductModel();
			$status = $productModel->getBProductData($branchId);
			if($status=="204: No Content")
			{
				print_r("cId");
				// return $status;
			}
			else
			{
				
				$encoded = new EncodeAllData();
				$encodeAllData = $encoded->getEncodedAllData($status);
				return $encodeAllData;
			}
			
		}
		else
		{
			//getBranchCompanyProductData(getBCProductData)
			$productModel = new ProductModel();
			$status = $productModel->getBCProductData($companyId,$branchId);
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
	}
    /**
     * get the data from persistable object and call the model for database update opertation
     * @param ProductPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update()
    {
		$productArray = array();
		$getData = array();
		$funcName = array();
		$productArray = func_get_arg(0);
		for($data=0;$data<count($productArray);$data++)
		{
			$funcName[$data] = $productArray[$data][0]->getName();
			$getData[$data] = $productArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productArray[$data][0]->getkey();
		}
		$productId = $productArray[0][0]->getProductId();
		//data pass to the model object for update
		$productModel = new ProductModel();
		$status = $productModel->updateData($getData,$keyName,$productId);
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
    public function delete(ProductPersistable $persistable)
    {      
		$productId = $persistable->getProductId();
        $productModel = new ProductModel();
		$status = $productModel->deleteData($productId);
		return $status;
    }   
}