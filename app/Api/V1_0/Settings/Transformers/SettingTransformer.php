<?php
namespace ERP\Api\V1_0\Settings\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingTransformer
{
	/**
     * @param Request Object
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//trim-data and make an array
		$data = array();
		$data['barcode_width'] = trim($request->input('barcodeWidth'));
		$data['barcode_height'] = trim($request->input('barcodeHeight'));
		return $data;
	}
	
    /**
     * @param Request Object
     * @return array
     */
   public function trimUpdateData()
	{
		$tTemplateArray = array();
		$templateValue;
		$keyValue = func_get_arg(0);
		$convertedValue="";
		$templateEnumArray = array();
		$templateTypeFlag=0;
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
		$templateValue = func_get_arg(1);
		for($data=0;$data<count($templateValue);$data++)
		{
			$tTemplateArray[$data]= array($convertedValue=> trim($templateValue));
			$templateEnumArray = array_keys($tTemplateArray[$data])[0];
		}
		$enumTemplateTypeArray = array();
		$templateTypeEnum = new TemplateTypeEnum();
		$enumTemplateTypeArray = $templateTypeEnum->enumArrays();
		if(strcmp($templateEnumArray,'template_type')==0)
		{
			foreach ($enumTemplateTypeArray as $key => $value)
			{
				if(strcmp($tTemplateArray[0]['template_type'],$value)==0)
				{
					$templateTypeFlag=1;
					break;
				}
				else
				{
					$templateTypeFlag=2;
				}
			}
		}
		
		if($templateTypeFlag==2)
		{
			return "1";
		}
		else
		{
			return $tTemplateArray;
		}
	}
}