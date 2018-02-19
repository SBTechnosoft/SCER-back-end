<?php
namespace ERP\Api\V1_0\Settings\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Settings\Entities\ChequeNoEnum;
use ERP\Core\Settings\Entities\ReminderEnum;
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
    	//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		//trim-data and make an array
		$data = array();
		if(array_key_exists('barcodeWidth',$request->input()))
		{
			$data['barcode_width'] = trim($request->input('barcodeWidth'));
			$data['barcode_height'] = trim($request->input('barcodeHeight'));
		}
		else if(array_key_exists('servicedateNoOfDays',$request->input()))
		{
			$data['servicedate_no_of_days'] = trim($request->input('servicedateNoOfDays'));
		}
		else if(array_key_exists('paymentdateNoOfDays',$request->input()))
		{
			$data['paymentdate_no_of_days'] = trim($request->input('paymentdateNoOfDays'));
			$data['paymentdate_status'] = trim($request->input('paymentdateStatus'));
		}
		else if(array_key_exists('productColorStatus',$request->input()))
		{
			$data['product_color_status'] = trim($request->input('productColorStatus'));
			$data['product_size_status'] = trim($request->input('productSizeStatus'));
			$data['product_best_before_status'] = trim($request->input('productBestBeforeStatus'));
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
				return $exceptionArray['content'];
			}
		}
		else if(array_key_exists('birthreminderType',$request->input()))
		{

			$reminderTypeFlag=0;
			$reminderTimeFlag=0;
			$notifyByFlag=0;
			$reminderEnum = new ReminderEnum();
			$reminderTypeData = $reminderEnum->reminderTypeEnumArrays();
			$reminderTimeData = $reminderEnum->reminderTimeEnumArrays();
			$notifyByData = $reminderEnum->notifyByEnumArrays();
			if(strcmp($reminderTypeData['beforeReminderType'],trim($request->input('birthreminderType')))==0 ||
			   strcmp($reminderTypeData['afterReminderType'],trim($request->input('birthreminderType')))==0)
			{
				$reminderTypeFlag=1;
				$data['birthreminder_type'] = trim($request->input('birthreminderType'));
			}
			if(strcmp($notifyByData['notifyBySms'],trim($request->input('birthreminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByEmail'],trim($request->input('birthreminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByBoth'],trim($request->input('birthreminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByNone'],trim($request->input('birthreminderNotifyBy')))==0)
			{
				$notifyByFlag=1;
				$data['birthreminder_notify_by'] = trim($request->input('birthreminderNotifyBy'));
			}
			if(strcmp($reminderTimeData['1Hour'],trim($request->input('birthreminderTime')))==0 ||
			   strcmp($reminderTimeData['2Hour'],trim($request->input('birthreminderTime')))==0 ||
			   strcmp($reminderTimeData['4Hour'],trim($request->input('birthreminderTime')))==0 ||
			   strcmp($reminderTimeData['6Hour'],trim($request->input('birthreminderTime')))==0 ||
			   strcmp($reminderTimeData['12Hour'],trim($request->input('birthreminderTime')))==0 ||
			   strcmp($reminderTimeData['24Hour'],trim($request->input('birthreminderTime')))==0)
			{
				$reminderTimeFlag=1;
				$data['birthreminder_time'] = trim($request->input('birthreminderTime'));
			}
			$data['birthreminder_status'] = trim($request->input('birthreminderStatus'));
			if($reminderTypeFlag==0 || $notifyByFlag==0 || $reminderTimeFlag==0)
			{
				return $exceptionArray['content'];
			}
		}
		else if(array_key_exists('annireminderType',$request->input()))
		{
			$reminderTypeFlag=0;
			$reminderTimeFlag=0;
			$notifyByFlag=0;
			$reminderEnum = new ReminderEnum();
			$reminderTypeData = $reminderEnum->reminderTypeEnumArrays();
			$reminderTimeData = $reminderEnum->reminderTimeEnumArrays();
			$notifyByData = $reminderEnum->notifyByEnumArrays();
			if(strcmp($reminderTypeData['beforeReminderType'],trim($request->input('annireminderType')))==0 ||
			   strcmp($reminderTypeData['afterReminderType'],trim($request->input('annireminderType')))==0)
			{
				$reminderTypeFlag=1;
				$data['annireminder_type'] = trim($request->input('annireminderType'));
			}
			if(strcmp($notifyByData['notifyBySms'],trim($request->input('annireminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByEmail'],trim($request->input('annireminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByBoth'],trim($request->input('annireminderNotifyBy')))==0 ||
			   strcmp($notifyByData['notifyByNone'],trim($request->input('annireminderNotifyBy')))==0)
			{
				$notifyByFlag=1;
				$data['annireminder_notify_by'] = trim($request->input('annireminderNotifyBy'));
			}
			if(strcmp($reminderTimeData['1Hour'],trim($request->input('annireminderTime')))==0 ||
			   strcmp($reminderTimeData['2Hour'],trim($request->input('annireminderTime')))==0 ||
			   strcmp($reminderTimeData['4Hour'],trim($request->input('annireminderTime')))==0 ||
			   strcmp($reminderTimeData['6Hour'],trim($request->input('annireminderTime')))==0 ||
			   strcmp($reminderTimeData['12Hour'],trim($request->input('annireminderTime')))==0 ||
			   strcmp($reminderTimeData['24Hour'],trim($request->input('annireminderTime')))==0)
			{
				$reminderTimeFlag=1;
				$data['annireminder_time'] = trim($request->input('annireminderTime'));
			}
			$data['annireminder_status'] = trim($request->input('annireminderStatus'));
			if($reminderTypeFlag==0 || $notifyByFlag==0 || $reminderTimeFlag==0)
			{
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