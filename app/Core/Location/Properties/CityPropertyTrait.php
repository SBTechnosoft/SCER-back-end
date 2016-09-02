<?php
namespace ERP\Core\Location\Properties;
 
use ERP\Core\Location\Entities\City;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
trait CityPropertyTrait
{
    /**
     * @var string
     */
    private $city;

	/**
	 * @param string $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}
}