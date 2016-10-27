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
		$productName = $request->input('productName'); 
		$measurementUnit = $request->input('measurementUnit'); 
		$isDisplay = $request->input('isDisplay'); 			
		$companyId = $request->input('companyId'); 			
		$productCatId = $request->input('productCategoryId'); 			
		$productGrpId = $request->input('productGroupId'); 			
		$branchId = $request->input('branchId'); 	 
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
		$data['product_category_id'] = $tProductCatId;
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
		$productValue = func_get_arg(1);
		for($data=0;$data<count($productValue);$data++)
		{
			$tProductArray[$data]= array($convertedValue=> trim($productValue));
		}
		return $tProductArray;
	}
}