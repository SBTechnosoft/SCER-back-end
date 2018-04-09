<?php
namespace ERP\Core\Products\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait CommissionTrait
{
	/**
     * @var commission
     */
    private $commission;
	/**
	 * @param float $commission
	 */
	public function setCommission($commission)
	{
		$this->commission = $commission;
	}
	/**
	 * @return commission
	 */
	public function getCommission()
	{
		return $this->commission;
	}
}