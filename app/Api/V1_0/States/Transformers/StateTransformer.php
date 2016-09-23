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
		$stateName = $request->input('state_name'); 
		$stateAbb = $request->input('state_abb'); 
		$isDisplay = $request->input('is_display'); 
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
		$keyValue = func_get_arg(0);
		$stateValue = func_get_arg(1);
		for($data=0;$data<count($stateValue);$data++)
		{
			$tStateArray[$data]= array($keyValue=> trim($stateValue));
		}
		return $tStateArray;
	}
}