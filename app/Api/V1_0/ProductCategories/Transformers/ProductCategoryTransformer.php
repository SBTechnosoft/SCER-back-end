<?php
namespace ERP\Api\V1_0\ProductCategories\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Entities\EnumClasses\IsDisplayEnum;
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
		$isDisplayFlag=0;
		//data get from body
		$productCatName = $request->input('productCategoryName'); 
		$productCatDesc = $request->input('productCategoryDescription'); 
		$isDisplay = $request->input('isDisplay'); 
		$productParentCatId = $request->input('productParentCategoryId');  
		//trim an input
		$tProductCatName = trim($productCatName);
		$tProductCatDesc = trim($productCatDesc);
		$tIsDisplay = trim($isDisplay);
		$tProductParentCatId= trim($productParentCatId);
		
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if($tIsDisplay=="")
		{
			$tIsDisplay = $enumIsDispArray['display'];
		}
		else
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($value,$tIsDisplay)==0)
				{
					$isDisplayFlag=1;
					break;
				}
				else
				{
					$isDisplayFlag=2;
				}
			}
		}
		if($isDisplayFlag==2)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['product_category_name'] = $tProductCatName;
			$data['product_category_description'] = $tProductCatDesc;
			$data['is_display'] = $tIsDisplay;
			$data['product_parent_category_id'] = $tProductParentCatId;
			return $data;
		}
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
		$convertedValue="";
		$productCategoryEnumArray = array();
		$isDisplayFlag=0;
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
		$productCatValue = func_get_arg(1);
		for($data=0;$data<count($productCatValue);$data++)
		{
			$tProductCatArray[$data]= array($convertedValue=> trim($productCatValue));
			$productCategoryEnumArray = array_keys($tProductCatArray[$data])[0];
		}
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if(strcmp($productCategoryEnumArray,'is_display')==0)
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($tProductCatArray[0]['is_display'],$value)==0)
				{
					$isDisplayFlag=1;
					break;
				}
				else
				{
					$isDisplayFlag=2;
				}
			}
		}
		
		if($isDisplayFlag==2)
		{
			return "1";
		}
		else
		{
			return $tProductCatArray;
		}
	}
}