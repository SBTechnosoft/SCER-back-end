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
		$cityName = $request->input('cityName'); 
		$stateAbb = $request->input('stateAbb'); 
		$isDisplay = $request->input('isDisplay'); 
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
		$convertedValue="";
		for($asciiChar=0;$asciiChar<strlen($keyValue);$asciiChar++)
		{
			if(ord($keyValue[$asciiChar])<=90 && ord($keyValue[$asciiChar])>=65) 
			{
				$convertedValue1 = "_".chr(ord($keyValue[$asciiChar])+32);
				$convertedValue=$convertedValue.$convertedValue1;
			}
			else
			{
				$convertedValue=$convertedValue.$keyValue[$asciiChar];
			}
		}
		$cityValue = func_get_arg(1);
		for($data=0;$data<count($cityValue);$data++)
		{
			$tCityArray[$data]= array($convertedValue=> trim($cityValue));
		}
		return $tCityArray;
	}
}