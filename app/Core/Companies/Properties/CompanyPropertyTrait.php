<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait CompanyPropertyTrait
{
	
	// $companyPersistable = new CompanyPersistable();		
			// $companyPersistable->setCompanyName($companyName);		 
			// $companyPersistable->setCompanyDispName($companyDispName);		 
			// $companyPersistable->setAddress1($address1);		 
			// $companyPersistable->setAddress2($address2);		 
			// $companyPersistable->setPincode($pincode);		 
			// $companyPersistable->setPanNo($pan);		 
			// $companyPersistable->setTinNo($tin);		 
			// $companyPersistable->setVatNo($vatNo);		 
			// $companyPersistable->setServiceTaxNo($serviceTaxNo);		 
			// $companyPersistable->setBasicCurrencySymbol($basicCurrencySymbol);		 
			// $companyPersistable->setFormalName($formalName);		 
			// $companyPersistable->setNoOfDecimalPoints($noOfDecimalPoints);		 
			// $companyPersistable->setCurrencySymbol($currencySymbol);		 
			// $companyPersistable->setDocumentName($documentName);		 
			// $companyPersistable->setDocumentUrl($documentUrl);		 
			// $companyPersistable->setDocumentSize($documentSize);		 
			// $companyPersistable->setDocumentFormat($documentFormat);		 
			// $companyPersistable->setIsDisplay($isDisplay);		 
			// $companyPersistable->setIsDefault($isDefault);		 
			// $companyPersistable->setStateAbb($stateAbb);		 
			// $companyPersistable->setCityid($cityId);	
    /**
     * @var age
     */
    private $age;
	/**
	 * @param int $age
	 */
	public function setAge($age)
	{
		$this->age = $age;
	}
	/**
	 * @return age
	 */
	public function getAge()
	{
		return $this->age;
	}
}