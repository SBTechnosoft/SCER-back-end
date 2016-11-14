<?php
namespace ERP\Api\V1_0\ProductCategories\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryTransformer
{
    /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$productCatName = $request->input('product_cat_name'); 
		$productCatDesc = $request->input('product_cat_desc'); 
		$isDisplay = $request->input('is_display'); 
		$productParentCatId = $request->input('product_parent_cat_id');  
		//trim an input
		$tProductCatName = trim($productCatName);
		$tProductCatDesc = trim($productCatDesc);
		$tIsDisplay = trim($isDisplay);
		$tProductParentCatId= trim($productParentCatId);
		//make an array
		$data = array();
		$data['product_cat_name'] = $tProductCatName;
		$data['product_cat_desc'] = $tProductCatDesc;
		$data['is_display'] = $tIsDisplay;
		$data['product_parent_cat_id'] = $tProductParentCatId;
		return $data;
	}
	
	/**
     * @param key and value
     * @return array
     */
	public function trimUpdateData()
	{
		$tProductCatArray = array();
		$productCatValue;
		$keyValue = func_get_arg(0);
		$productCatValue = func_get_arg(1);
		for($data=0;$data<count($productCatValue);$data++)
		{
			$tProductCatArray[$data]= array($keyValue=> trim($productCatValue));
		}
		return $tProductCatArray;
	}
}