<?php
namespace ERP\Core\Entities;

use ERP\Core\Companies\Services\CompanyService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyName extends CompanyService 
{
	public function getCompanyName($companyId)
	{
		//get the city_name from database
		$encodeCompanyDataClass = new CompanyName();
		$companyStatus = $encodeCompanyDataClass->getCompanyData($companyId);
		$companyDecodedJson = json_decode($companyStatus,true);
		$companyName= $companyDecodedJson['company_name'];
		return $companyName;
	}
    
}