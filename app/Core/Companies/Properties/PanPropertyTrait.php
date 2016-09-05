<?php
namespace ERP\Core\Companies\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait PanPropertyTrait
{
	/**
     * @var pan
     */
    private $pan;
	/**
	 * @param int $pan
	 */
	public function setPanNo($pan)
	{
		$this->pan = $pan;
	}
	/**
	 * @return pan
	 */
	public function getPanNo()
	{
		return $this->pan;
	}
}