<?php
namespace ERP\Core\Companies\Entities;

use ERP\Core\Cities\Services\CityService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityName extends CityService 
{
	public function getCityName($cityId)
	{
		//get the city_name from database
		$encodeCityDataClass = new CityName();
		$cityStatus = $encodeCityDataClass->getCityData($cityId);
		$cityDecodedJson = json_decode($cityStatus,true);
		$cityName= $cityDecodedJson['city_name'];
		return $cityName;
	}
    
}