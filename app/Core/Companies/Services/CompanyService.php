<?php
namespace ERP\Core\Companies\Services;

use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Companies\Entities\Company;
use ERP\Model\Companies\CompanyModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Companies\Entities\EncodeData;
use ERP\Core\Companies\Entities\EncodeAllData;
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
     * @param CompanyPersistable $persistable
     * @return status
     */
	public function insert(CompanyPersistable $persistable)
	{
		$companyName = $persistable->getName();
		$companyDispName = $persistable->getCompanyDispName();
		$address1 = $persistable->getAddress1();
		$address2 = $persistable->getAddress2();
		$pincode = $persistable->getPincode();
		$panNo = $persistable->getPanNo();
		$tinNo = $persistable->getTinNo();
		$vatNo = $persistable->getVatNo();
		$serviceTaxNO = $persistable->getServiceTaxNo();
		$basicCurrencySymbol = $persistable->getBasicCurrencySymbol();
		$formalName = $persistable->getFormalName();
		$noOfDecimalPoints = $persistable->getNoOfDecimalPoints();
		$currencySymbol = $persistable->getCurrencySymbol();
		$documentName = $persistable->getDocumentName();
		$documentUrl = $persistable->getDocumentUrl();
		$documentSize = $persistable->getDocumentSize();
		$documentFormat = $persistable->getDocumentFormat();
		$isDisplay = $persistable->getIsDisplay();
		$isDefault = $persistable->getIsDefault();
		$stateAbb = $persistable->getStateAbb();
		$cityId = $persistable->getId();
		$companyModel = new CompanyModel();
		
		//data pass to the model object for insertion
		$status = $companyModel->insertData($companyName,$companyDispName,$address1,$address2,$pincode,$panNo,$tinNo,$vatNo,$serviceTaxNO,$basicCurrencySymbol,$formalName,$noOfDecimalPoints,$currencySymbol,$documentName,$documentUrl,$documentSize,$documentFormat,$isDisplay,$isDefault,$stateAbb,$cityId);
		return $status;
	}
	
	/**
     * get all the data call the model for database selection opertation
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
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @param company_id
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
			$encoded = new EncodeData();
			$encodeData = $encoded->getEncodedData($status);
			return $encodeData;
		}
	}
	
    /**
     * get the data from persistable object and call the model for database update opertation
     * @param CompanyPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update(CompanyPersistable $persistable, UpdateOptions $options = null)
    {
		$companyName = $persistable->getName();
		$companyDispName = $persistable->getCompanyDispName();
		$address1 = $persistable->getAddress1();
		$address2 = $persistable->getAddress2();
		$pincode = $persistable->getPincode();
		$panNo = $persistable->getPanNo();
		$tinNo = $persistable->getTinNo();
		$vatNo = $persistable->getVatNo();
		$serviceTaxNO = $persistable->getServiceTaxNo();
		$basicCurrencySymbol = $persistable->getBasicCurrencySymbol();
		$formalName = $persistable->getFormalName();
		$noOfDecimalPoints = $persistable->getNoOfDecimalPoints();
		$currencySymbol = $persistable->getCurrencySymbol();
		$documentName = $persistable->getDocumentName();
		$documentUrl = $persistable->getDocumentUrl();
		$documentSize = $persistable->getDocumentSize();
		$documentFormat = $persistable->getDocumentFormat();
		$isDisplay = $persistable->getIsDisplay();
		$isDefault = $persistable->getIsDefault();
		$stateAbb = $persistable->getStateAbb();
		$cityId = $persistable->getId();
		$companyId = $persistable->getCompanyId();
		$companyModel = new CompanyModel();
	    
		//data pass to the model object for update
		$status = $companyModel->updateData($companyName,$companyDispName,$address1,$address2,$pincode,$panNo,$tinNo,$vatNo,$serviceTaxNO,$basicCurrencySymbol,$formalName,$noOfDecimalPoints,$currencySymbol,$documentName,$documentUrl,$documentSize,$documentFormat,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId);
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
     * delete
     * @param CompanyPersistable $persistable
     */
    public function delete(CompanyPersistable $persistable)
    {      
		$companyId = $persistable->getId();
        $companyModel = new CompanyModel();
		$status = $companyModel->deleteData($companyId);
		return $status;
    }   
}