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
		$productGroupName = $request->input('productGroupName'); 
		$productGroupDesc = $request->input('productGroupDescription'); 
		$productGroupParentId = $request->input('productGroupParentId'); 
		$isDisplay = $request->input('isDisplay'); 			
		
		//trim an input
		$tProductGroupName = trim($productGroupName);
		$tProductGroupDesc = trim($productGroupDesc);
		$tProductGroupParentId = trim($productGroupParentId);
		$tIsDisplay = trim($isDisplay);
		
		//make an array
		$data = array();
		$data['product_group_name'] = $tProductGroupName;
		$data['product_group_description'] = $tProductGroupDesc;
		$data['product_group_parent_id'] = $tProductGroupParentId;
		$data['is_display'] = $tIsDisplay;
		return $data;
	}
	public function trimUpdateData()
	{
		$tProductGroupArray = array();
		$productGroupValue;
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
		$productGroupValue = func_get_arg(1);
		for($data=0;$data<count($productGroupValue);$data++)
		{
			$tproductGroupArray[$data]= array($convertedValue=> trim($productGroupValue));
			
		}
		return $tproductGroupArray;
	}
}