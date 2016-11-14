<?php
namespace ERP\Api\V1_0\States\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateTransformer
{
   /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$stateName = $request->input('stateName'); 
		$stateAbb = $request->input('stateAbb'); 
		$isDisplay = $request->input('isDisplay'); 
		//trim an input
		$tStateName = trim($stateName);
		$tStateAbb = trim($stateAbb);
		$tIsDisplay = trim($isDisplay);
		//make an array
		$data = array();
		$data['state_name'] = $tStateName;
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
		$tStateArray = array();
		$stateValue;
		$convertedValue="";
		$keyValue = func_get_arg(0);
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
		$stateValue = func_get_arg(1);
		for($data=0;$data<count($stateValue);$data++)
		{
			$tStateArray[$data]= array($convertedValue=> trim($stateValue));
		}
		return $tStateArray;
	}
}