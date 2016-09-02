<?php
namespace ERP\Core\Companies\Services;

// use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Companies\Entities\Company;
// use ERP\Core\Companies\Validation\companyValidator;
use ERP\Model\Companies\CompanyModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use Carbon;
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
			$company = new Company();
			for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
			{
				$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
				$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
				$companyName[$decodedData] = $decodedJson[$decodedData]['company_name'];
				$companyDisplayName[$decodedData] = $decodedJson[$decodedData]['company_display_name'];
				$address1[$decodedData] = $decodedJson[$decodedData]['address1'];
				$address2[$decodedData] = $decodedJson[$decodedData]['address2'];
				$pincode[$decodedData] = $decodedJson[$decodedData]['pincode'];
				$pan[$decodedData] = $decodedJson[$decodedData]['pan'];
				$tin[$decodedData] = $decodedJson[$decodedData]['tin'];
				$vat_no[$decodedData] = $decodedJson[$decodedData]['vat_no'];
				$serviceTaxNo[$decodedData] = $decodedJson[$decodedData]['service_tax_no'];
				$basicCurrencySymbol[$decodedData] = $decodedJson[$decodedData]['basic_currency_symbol'];
				$formalName[$decodedData] = $decodedJson[$decodedData]['formal_name'];
				$noOfDecimalPoints[$decodedData] = $decodedJson[$decodedData]['no_of_decimal_points'];
				$currencySymbol[$decodedData] = $decodedJson[$decodedData]['currency_symbol'];
				$documentName[$decodedData] = $decodedJson[$decodedData]['document_name'];
				$documentUrl[$decodedData] = $decodedJson[$decodedData]['document_url'];
				$documentSize[$decodedData] = $decodedJson[$decodedData]['document_size'];
				$documentFormat[$decodedData] = $decodedJson[$decodedData]['document_format'];
				$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
				$isDefault[$decodedData] = $decodedJson[$decodedData]['is_default'];
				$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
				$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
				
				$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
				
				$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
			}
			$company->setCreated_at($convertedCreatedDate);
			$getCreatedDate = $company->getCreated_at();
			
			$company->setCreated_at($convertedUpdatedDate);
			$getUpdatedDate = $company->getUpdated_at();
			$data = array();
			for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
			{
				$data['company_name'] = $companyName[$jsonData];
				$data['company_display_name'] = $companyDisplayName[$jsonData];
				$data['address1'] = $address1[$jsonData];
				$data['address2'] = $address2[$jsonData];
				$data['pincode'] = $pincode[$jsonData];
				$data['pan'] = $pan[$jsonData];
				$data['tin'] = $tin[$jsonData];
				$data['vat_no'] = $vat_no[$jsonData];
				$data['service_tax_no'] = $serviceTaxNo[$jsonData];
				$data['basic_currency_symbol'] = $basicCurrencySymbol[$jsonData];
				$data['formal_name'] = $formalName[$jsonData];
				$data['no_of_decimal_points'] = $noOfDecimalPoints[$jsonData];
				$data['currency_symbol'] = $currencySymbol[$jsonData];
				$data['document_name'] = $documentName[$jsonData];
				$data['document_url'] = $documentUrl[$jsonData];
				$data['document_size'] = $documentSize[$jsonData];
				$data['document_format'] = $documentFormat[$jsonData];
				$data['is_display'] = $isDisplay[$jsonData];
				$data['is_default'] = $isDefault[$jsonData];
				$data['state_abb'] = $stateAbb[$jsonData];
				$data['city_id'] = $cityId[$jsonData];
				$data['created_at'] = $getCreatedDate[$jsonData];
				$data['updated_at'] = $getUpdatedDate[$jsonData];
				
				$encodeData[$jsonData] = json_encode($data);	
			}
			header("Content-type:application/json");
			print_r($encodeData);
			// return $encodeData;
		}
	}
	
	/**
     * get all the data from the table and call the model for database selection opertation
     * @return status
     */
	public function getCompanyData($companyId)
	{
		$companyModel = new CompanyModel();
		$status = $companyModel->getData($companyId);
		if($status=="404:Id Not Found")
		{
			return $status;
		}
		else
		{
			$decoded = new DecodeData();
			$encodeData = $decoded->getDecodedData($status);
			return $encodeData;
		}
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