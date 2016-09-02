<?php
namespace ERP\Core\Companies\Services;

// use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Companies\Entities\Company;
// use ERP\Core\Companies\Validation\companyValidator;
use ERP\Model\Companies\CompanyModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyService extends AbstractService
{
    /**
     * @var companyService
	 * $var companyModel
     */
    private $companyService;
    private $companyModel;
	
    /**
     * @param CompanyService $companyService
     */
    public function initialize(CompanyService $companyService)
    {		
		echo "init";
    }
	
    /**
     * @param CompanyPersistable $persistable
     */
    public function create(CompanyPersistable $persistable)
    {
		return "create method of CompanyService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param BranchPersistable $persistable
     * @return status
     */
	// public function insert(CompanyPersistable $persistable)
	// {
		// $name = $persistable->getName();
		// $age = $persistable->getAge();
		// $branchModel = new BranchModel();
		// $status = $branchModel->insertData($name,$age);
		// return $status;
	// }
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllCompanyData()
	{
		$companyModel = new CompanyModel();
		$status = $companyModel->getAllData();
		return $status;
	}
	
	/**
     * get all the data from the table and call the model for database selection opertation
     * @return status
     */
	public function getCompanyData($companyId)
	{
		$companyModel = new CompanyModel();
		$status = $companyModel->getData($companyId);
		$decodedJson = json_decode($status,true);
		echo "hello";
		
		new CompanyTransformer()->transform(new Company());
		// print_r($decodedJson[0]['created_at']);
		// print_r($decodedJson[0]['']);
		// print_r($decodedJson[0]['created_at']);
		// return $status;
	}
	
    /**
     * get the data from persistable object and call the model for database update opertation
     * @param BranchPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    // public function update(BranchPersistable $persistable, UpdateOptions $options = null)
    // {
	    // $name = $persistable->getName();		
		// $id = $persistable->getId();
		// $age = $persistable->getAge();
		// $branchModel = new BranchModel();
		// $status = $branchModel->updateData($name,$age,$id);
		// return $status;		
    // }

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
    // public function delete(BranchPersistable $persistable)
    // {      
		// $id = $persistable->getId();
        // $branchModel = new BranchModel();
		// $status = $branchModel->deleteData($id);
		// return $status;
    // }   
}