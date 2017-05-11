<?php
namespace ERP\Core\Crm\JobForm\Services;

use ERP\Core\Crm\JobForm\Persistables\JobFormPersistable;
// use ERP\Core\Crm\JobForm\Entities\Branch;
use ERP\Model\Crm\JobForm\JobFormModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
// use ERP\Core\Crm\JobForm\Entities\EncodeData;
// use ERP\Core\Crm\JobForm\Entities\EncodeAllData;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JobFormService extends AbstractService
{
    /**
     * @var jobFormService
	 * $var jobFormModel
     */
    private $jobFormService;
    private $jobFormModel;
	
    /**
     * @param JobFormService $jobFormService
     */
    public function initialize(JobFormService $jobFormService)
    {		
		echo "init";
    }
	
    /**
     * @param JobFormPersistable $persistable
     */
    public function create(JobFormPersistable $persistable)
    {
		return "create method of JobFormService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param JobFormPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$jobFormArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$jobFormArray = func_get_arg(0);
		for($data=0;$data<count($jobFormArray);$data++)
		{
			$funcName[$data] = $jobFormArray[$data][0]->getName();
			$getData[$data] = $jobFormArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $jobFormArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$jobFormModel = new JobFormModel();
		$status = $jobFormModel->insertData($getData,$keyName);
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($status,$exceptionArray['204'])==0)
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($status,$fileSizeArray['404'])==0)
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($status,$fileSizeArray['204'])==0)
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
		$branchArray = array();
		$getData = array();
		$funcName = array();
		$branchArray = func_get_arg(0);
		for($data=0;$data<count($branchArray);$data++)
		{
			$funcName[$data] = $branchArray[$data][0]->getName();
			$getData[$data] = $branchArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $branchArray[$data][0]->getkey();
		}
		$branchId = $branchArray[0][0]->getBranchId();
		// data pass to the model object for update
		$branchModel = new BranchModel();
		$status = $branchModel->updateData($getData,$keyName,$branchId);
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