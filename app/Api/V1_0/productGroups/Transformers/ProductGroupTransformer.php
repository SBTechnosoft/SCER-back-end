<?php
namespace ERP\Api\V1_0\ProductGroups\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupTransformer 
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//data get from body
		$productGroupName = $request->input('product_group_name'); 
		$productGroupDesc = $request->input('product_group_desc'); 
		$productGroupParentId = $request->input('product_group_parent_id'); 
		$isDisplay = $request->input('is_display'); 			
		
		//trim an input
		$tProductGroupName = trim($productGroupName);
		$tProductGroupDesc = trim($productGroupDesc);
		$tProductGroupParentId = trim($productGroupParentId);
		$tIsDisplay = trim($isDisplay);
		
		//make an array
		$data = array();
		$data['product_group_name'] = $tProductGroupName;
		$data['product_group_desc'] = $tProductGroupDesc;
		$data['product_group_parent_id'] = $tProductGroupParentId;
		$data['is_display'] = $tIsDisplay;
		return $data;
	}
	public function trimUpdateData()
	{
		$tProductGroupArray = array();
		$productGroupValue;
		$keyValue = func_get_arg(0);
		$productGroupValue = func_get_arg(1);
		for($data=0;$data<count($productGroupValue);$data++)
		{
			$tproductGroupArray[$data]= array($keyValue=> trim($productGroupValue));
			
		}
		return $tproductGroupArray;
	}
}