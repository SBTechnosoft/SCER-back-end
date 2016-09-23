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
     * @param array
     * @return status
     */
	public function insert()
	{
		$productGroupArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$productGroupArray = func_get_arg(0);
		for($data=0;$data<count($productGroupArray);$data++)
		{
			$funcName[$data] = $productGroupArray[$data][0]->getName();
			$getData[$data] = $productGroupArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productGroupArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$productGrpModel = new ProductGroupModel();
		$status = $productGrpModel->insertData($getData,$keyName);
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
     * @param array
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update()
    {
		$productGrpArray = array();
		$getData = array();
		$funcName = array();
		$productGrpArray = func_get_arg(0);
		for($data=0;$data<count($productGrpArray);$data++)
		{
			$funcName[$data] = $productGrpArray[$data][0]->getName();
			$getData[$data] = $productGrpArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $productGrpArray[$data][0]->getkey();
		}
		$productGrpId = $productGrpArray[0][0]->getProductGroupId();
		// data pass to the model object for update
		$productGrpModel = new ProductGroupModel();
		$status = $productGrpModel->updateData($getData,$keyName,$productGrpId);
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