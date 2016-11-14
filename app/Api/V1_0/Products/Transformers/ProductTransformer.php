<?php
namespace ERP\Api\V1_0\Products\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductTransformer
{
    /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$productName = $request->input('product_name'); 
		$measurementUnit = $request->input('measurement_unit'); 
		$isDisplay = $request->input('is_display'); 			
		$companyId = $request->input('company_id'); 			
		$productCatId = $request->input('product_cat_id'); 			
		$productGrpId = $request->input('product_group_id'); 			
		$branchId = $request->input('branch_id'); 	 
		//trim an input
		$tProductName = trim($productName);
		$tMeasUnit = trim($measurementUnit);
		$tIsDisplay = trim($isDisplay);
		$tCompanyId = trim($companyId);
		$tProductCatId = trim($productCatId);
		$tProductGrpId = trim($productGrpId);
		$tBranchId = trim($branchId);
		//make an array
		$data = array();
		$data['product_name'] = $tProductName;
		$data['measurement_unit'] = $tMeasUnit;
		$data['is_display'] = $tIsDisplay;
		$data['company_id'] = $tCompanyId;
		$data['product_cat_id'] = $tProductCatId;
		$data['product_group_id'] = $tProductGrpId;
		$data['branch_id'] = $tBranchId;
		return $data;
	}
	
	/**
     * @param key and value
     * @return array
     */
	public function trimUpdateData()
	{
		$tProductArray = array();
		$productValue;
		$keyValue = func_get_arg(0);
		$productValue = func_get_arg(1);
		for($data=0;$data<count($productValue);$data++)
		{
			$tProductArray[$data]= array($keyValue=> trim($productValue));
		}
		return $tProductArray;
	}
}