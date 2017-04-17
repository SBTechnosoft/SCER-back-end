<?php
namespace ERP\Api\V1_0\ProductGroups\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Entities\EnumClasses\IsDisplayEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupTransformer 
{
    /**
     * @param Request object
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$isDisplayFlag=0;
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
			$data['product_group_name'] = $tProductGroupName;
			$data['product_group_description'] = $tProductGroupDesc;
			$data['product_group_parent_id'] = $tProductGroupParentId;
			$data['is_display'] = $tIsDisplay;
			return $data;
		}
	}
	
	/**
     * @param Request object
     * @return array
     */
    public function trimInsertBatchData(Request $request)
    {
		$data = array();
		$requestInputData = $request->input();
		for($arrayData=0;$arrayData<count($requestInputData);$arrayData++)
		{
			$isDisplayFlag=0;
			//data get from body
			$productGroupName = $requestInputData[$arrayData]['productGroupName']; 
			$productGroupDesc = $requestInputData[$arrayData]['productGroupDescription']; 
			$productGroupParentId = $requestInputData[$arrayData]['productGroupParentId']; 
			$isDisplay = $requestInputData[$arrayData]['isDisplay']; 			
			
			//trim an input
			$tProductGroupName = trim($productGroupName);
			$tProductGroupDesc = trim($productGroupDesc);
			$tProductGroupParentId = trim($productGroupParentId);
			$tIsDisplay = trim($isDisplay);
			
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
				$data[$arrayData] = array();
				$data[$arrayData]['product_group_name'] = $tProductGroupName;
				$data[$arrayData]['product_group_description'] = $tProductGroupDesc;
				$data[$arrayData]['product_group_parent_id'] = $tProductGroupParentId;
				$data[$arrayData]['is_display'] = $tIsDisplay;
				
			}
		}
		return $data;
	}
	public function trimUpdateData()
	{
		$tProductGroupArray = array();
		$productGroupValue;
		$keyValue = func_get_arg(0);
		$convertedValue="";
		$productGrpEnumArray = array();
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
		$productGroupValue = func_get_arg(1);
		for($data=0;$data<count($productGroupValue);$data++)
		{
			$tproductGroupArray[$data]= array($convertedValue=> trim($productGroupValue));
			$productGrpEnumArray = array_keys($tproductGroupArray[$data])[0];
		}
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if(strcmp($productGrpEnumArray,'is_display')==0)
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($tproductGroupArray[0]['is_display'],$value)==0)
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
			return $tproductGroupArray;
		}
	}
}