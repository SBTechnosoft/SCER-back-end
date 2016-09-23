<?php
namespace ERP\Api\V1_0\Branches\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//data get from body
		$branchName = $request->input('branch_name'); 
		$address1 = $request->input('address1'); 
		$address2 = $request->input('address2'); 
		$pincode = $request->input('pincode'); 
		$isDisplay = $request->input('is_display'); 			
		$isDefault = $request->input('is_default'); 			
		$stateAbb = $request->input('state_abb'); 			
		$cityId = $request->input('city_id'); 			
		$companyId = $request->input('company_id');  
		
		//trim an input
		$tBranchName = trim($branchName);
		$tAddress1 = trim($address1);
		$tAddress2 = trim($address2);
		$tPincode = trim($pincode);
		$tIsDisplay = trim($isDisplay);
		$tIsDefault = trim($isDefault);
		$tStateAbb = trim($stateAbb);
		$tCityId = trim($cityId);
		$tCompanyId = trim($companyId);
		
		//make an array
		$data = array();
		$data['branch_name'] = $tBranchName;
		$data['address1'] = $tAddress1;
		$data['address2'] = $tAddress2;
		$data['pincode'] = $tPincode;
		$data['is_display'] = $tIsDisplay;
		$data['is_default'] = $tIsDefault;
		$data['state_abb'] = $tStateAbb;
		$data['city_id'] = $tCityId;
		$data['company_id'] = $tCompanyId;
		return $data;
	}
	public function trimUpdateData()
	{
		$tCompanyArray = array();
		$companyValue;
		$keyValue = func_get_arg(0);
		$companyValue = func_get_arg(1);
		for($data=0;$data<count($companyValue);$data++)
		{
			$tCompanyArray[$data]= array($keyValue=> trim($companyValue));
			
		}
		return $tCompanyArray;
	}
}