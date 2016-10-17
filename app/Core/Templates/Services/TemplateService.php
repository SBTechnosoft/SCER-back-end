<?php
namespace ERP\Core\Templates\Services;

use ERP\Core\Templates\Persistables\TemplatePersistable;
use ERP\Core\Templates\Entities\Branch;
use ERP\Model\Templates\TemplateModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Templates\Entities\EncodeData;
use ERP\Core\Templates\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateService extends AbstractService
{
    /**
     * @var templateService
	 * $var templateModel
     */
    private $templateService;
    private $templateModel;
	
    /**
     * @param TemplateService $templateService
     */
    public function initialize(TemplateService $templateService)
    {		
		echo "init";
    }
	
    /**
     * @param TemplatePersistable $persistable
     */
    public function create(TemplatePersistable $persistable)
    {
		return "create method of TemplateService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BranchPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$branchArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$branchArray = func_get_arg(0);
		for($data=0;$data<count($branchArray);$data++)
		{
			$funcName[$data] = $branchArray[$data][0]->getName();
			$getData[$data] = $branchArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $branchArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$branchModel = new BranchModel();
		$status = $branchModel->insertData($getData,$keyName);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllTemplateData()
	{
		$templateModel = new TemplateModel();
		$status = $templateModel->getAllData();
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
     * @param $templateId
     * @return status
     */
	public function getTemplateData($templateId)
	{
		$templateModel = new TemplateModel();
		$status = $templateModel->getData($templateId);
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
	 * parameter is in array form.
     * @return status
     */
    public function update()
    {
		$templateArray = array();
		$getData = array();
		$funcName = array();
		$templateArray = func_get_arg(0);
		for($data=0;$data<count($templateArray);$data++)
		{
			$funcName[$data] = $templateArray[$data][0]->getName();
			$getData[$data] = $templateArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $templateArray[$data][0]->getkey();
		}
		$templateId = $templateArray[0][0]->getTemplateId();
		// data pass to the model object for update
		$templateModel = new TemplateModel();
		$status = $templateModel->updateData($getData,$keyName,$templateId);
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