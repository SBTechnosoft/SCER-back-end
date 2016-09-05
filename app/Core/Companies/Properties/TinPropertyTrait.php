<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait TinPropertyTrait
{
	/**
     * @var tin
     */
    private $tin;
	/**
	 * @param int $tin
	 */
	public function setTinNo($tin)
	{
		$this->tin = $tin;
	}
	/**
	 * @return tin
	 */
	public function getTinNo()
	{
		return $this->tin;
	}
}