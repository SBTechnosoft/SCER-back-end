<?php
namespace ERP\Core\Products\Properties;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait MeasureUnitPropertyTrait
{
	/**
     * @var measureUnit
     */
    private $measureUnit;
	/**
	 * @param int $measureUnit
	 */
	public function setMeasureUnit($measureUnit)
	{
		$this->measureUnit = $measureUnit;
	}
	/**
	 * @return measureUnit
	 */
	public function getMeasureUnit()
	{
		return $this->measureUnit;
	}
}