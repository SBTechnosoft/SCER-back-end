<?php
namespace ERP\Core\Sample\Services;

use ERP\Core\Sample\Persistables\BranchPersistable;
use ERP\Core\Sample\Entities\Branch;
use ERP\Core\Sample\Validation\BranchValidator;
use ERP\Model\Sample\BranchModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchService extends AbstractService
{
    /**
     * @var branchService
	 * $var branchModel
	 * $var branch
     */
    private $branchService;
    private $branchModel;
    private $branch;
	
    /**
     * @param BranchService $branchService
     */
    public function initialize(BranchService $branchService)
    {		
		echo "init";
        // return $this->branchService = $branchService;
    }
	
    /**
     * @param BranchPersistable $persistable
     * @return Branch
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
		$name = $persistable->getName();
		$age = $persistable->getAge();
		$imageName = $persistable->getImageName();
		$branchModel = new BranchModel();
		$status = $branchModel->insertData($name,$age,$imageName);
		return $status;
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @param $id
     * @return status
     */
	public function getBranchData($id)
	{
		$branchModel = new BranchModel();
		$status = $branchModel->getData($id);
		if($status=="404:Id Not Found")
		{
			return $status;
		}
		else
		{
			$decodedJson = json_decode($status,true);
			
			$createdAt = $decodedJson[0]['created_at'];
			$updatedAt = $decodedJson[0]['updated_at'];
			$name = $decodedJson[0]['name'];
			$age = $decodedJson[0]['age'];
			$imageName = $decodedJson[0]['image_name'];
			
			$branch = new Branch();
			$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
			$branch->setCreated_at($convertedCreatedDate);
			$getCreatedDate = $branch->getCreated_at();
			
			$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
			$branch->setCreated_at($convertedUpdatedDate);
			$getUpdatedDate = $branch->getUpdated_at();
			
			$data = array();
			$data['name']=$name;
			$data['age']=$age;
			$data['image_name']=$imageName;
			$data['created_at']=$getCreatedDate;
			$data['updated_at']=$getUpdatedDate;
			$encodeData = json_encode($data);
			return $encodeData;
		}
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
			$convertedCreatedDate =  array();
			$convertedUpdatedDate =  array();
			$encodeData =  array();
			
			$decodedJson = json_decode($status,true);
			$branch = new Branch();
			for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
			{
				$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
				$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
				$name[$decodedData] = $decodedJson[$decodedData]['name'];
				$age[$decodedData] = $decodedJson[$decodedData]['age'];
				$imageName[$decodedData] = $decodedJson[$decodedData]['image_name'];
				$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
				
				$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
			}
			$branch->setCreated_at($convertedCreatedDate);
			$getCreatedDate = $branch->getCreated_at();
			
			$branch->setCreated_at($convertedUpdatedDate);
			$getUpdatedDate = $branch->getUpdated_at();
			
			$data = array();
			for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
			{
				$data['name'] = $name[$jsonData];
				$data['age'] = $age[$jsonData];
				$data['image_name'] = $imageName[$jsonData];
				$data['created_at'] = $getCreatedDate[$jsonData];
				$data['updated_at'] = $getUpdatedDate[$jsonData];
				$encodeData[$jsonData] = json_encode($data);	
			}
			
			print_r($encodeData);
			return $encodeData;
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
	    $name = $persistable->getName();		
		$id = $persistable->getId();
		$age = $persistable->getAge();
		$imageName = $persistable->getImageName();
		$branchModel = new BranchModel();
		$status = $branchModel->updateData($name,$age,$id,$imageName);
		return $status;		
    }
	
	/**
     * @param int $id
     */
    public function delete(BranchPersistable $persistable)
    {      
		$id = $persistable->getId();
        $branchModel = new BranchModel();
		$status = $branchModel->deleteData($id);
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