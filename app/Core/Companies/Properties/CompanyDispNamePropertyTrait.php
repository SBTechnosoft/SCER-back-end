<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait CompanyDispNamePropertyTrait
{
	/**
     * @var companyDispName
     */
    private $companyDispName;
	/**
	 * @param int $companyDispName
	 */
	public function setCompanyDispName($companyDispName)
	{
		$this->companyDispName = $companyDispName;
	}
	/**
	 * @return companyDispName
	 */
	public function getCompanyDispName()
	{
		return $this->companyDispName;
	}
}