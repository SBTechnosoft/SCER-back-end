<?php
namespace ERP\Api\V1_0\Cities\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityTransformer
{
   /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$cityName = $request->input('city_name'); 
		$stateAbb = $request->input('state_abb'); 
		$isDisplay = $request->input('is_display'); 
		//trim an input
		$tCityName = trim($cityName);
		$tStateAbb = trim($stateAbb);
		$tIsDisplay = trim($isDisplay);
		//make an array
		$data = array();
		$data['city_name'] = $tCityName;
		$data['state_abb'] = $tStateAbb;
		$data['is_display'] = $tIsDisplay;
		return $data;
	}
	
	/**
     * @param key and value
     * @return array
     */
	public function trimUpdateData()
	{
		$tCityArray = array();
		$cityValue;
		$keyValue = func_get_arg(0);
		$cityValue = func_get_arg(1);
		for($data=0;$data<count($cityValue);$data++)
		{
			$tCityArray[$data]= array($keyValue=> trim($cityValue));
		}
		return $tCityArray;
	}
}