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
		$tSettingArray = array();
		$settingValue;
		$keyValue = func_get_arg(0);
		$convertedValue="";
		$settingEnumArray = array();
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
		$settingValue = func_get_arg(1);
		for($data=0;$data<count($settingValue);$data++)
		{
			$tSettingArray[$data]= array($convertedValue=> trim($settingValue));
			$settingEnumArray = array_keys($tSettingArray[$data])[0];
		}
		return $tSettingArray;
	}
}