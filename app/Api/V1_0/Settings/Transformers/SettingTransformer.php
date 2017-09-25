<?php
namespace ERP\Api\V1_0\Settings\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Settings\Entities\ChequeNoEnum;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
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
		if(array_key_exists('barcodeWidth',$request->input()))
		{
			$data['barcode_width'] = trim($request->input('barcodeWidth'));
			$data['barcode_height'] = trim($request->input('barcodeHeight'));
		}
		else if(array_key_exists('chequeno',$request->input()))
		{
			$chequeNoEnum = new ChequeNoEnum();
			$chequeNoData = $chequeNoEnum->enumArrays();
			if(strcmp($chequeNoData['chequeNoEnable'],trim($request->input('chequeno')))==0 ||
			   strcmp($chequeNoData['chequeNoDisable'],trim($request->input('chequeno')))==0)
			{
				$data['chequeno_status'] = trim($request->input('chequeno'));
			}
			else
			{
				//get exception message
				$exception = new ExceptionMessage();
				$exceptionArray = $exception->messageArrays();
				return $exceptionArray['content'];
			}
		}
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
		$valueData= func_get_arg(1);
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
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($convertedValue,$constantArray['chequeNoSetting'])==0)
		{
			$chequeNoEnum = new ChequeNoEnum();
			$chequeNoData = $chequeNoEnum->enumArrays();
			if(strcmp($chequeNoData['chequeNoEnable'],$valueData)==0 || strcmp($chequeNoData['chequeNoDisable'],$valueData)==0)
			{
				$settingValue = func_get_arg(1);
				for($data=0;$data<count($settingValue);$data++)
				{
					$tSettingArray[$data]= array('chequeno_status'=> trim($settingValue));
					$settingEnumArray = array_keys($tSettingArray[$data])[0];
				}
				return $tSettingArray;
			}
			else
			{
				//get exception message
				$exception = new ExceptionMessage();
				$exceptionArray = $exception->messageArrays();
				return $exceptionArray['content'];
			}
		}
		else
		{
			$settingValue = func_get_arg(1);
			for($data=0;$data<count($settingValue);$data++)
			{
				$tSettingArray[$data]= array($convertedValue=> trim($settingValue));
				$settingEnumArray = array_keys($tSettingArray[$data])[0];
			}
			return $tSettingArray;
		}
		
	}
}