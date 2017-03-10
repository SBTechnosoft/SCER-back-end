<?php
namespace ERP\Model\Settings;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingModel extends Model
{
	protected $table = 'setting_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$getSettingData = array();
		$getSettingKey = array();
		$getSettingData = func_get_arg(0);
		$getSettingKey = func_get_arg(1);
		$barcodeFlag=0;
		$barcodeArray = array();
		
		for($data=0;$data<count($getSettingData);$data++)
		{
			$explodedSetting = explode('_',$getSettingKey[$data]);
			if(strcmp('barcode',$explodedSetting[0])==0)
			{
				$barcodeFlag=1;
				$barcodeArray[$getSettingKey[$data]] = $getSettingData[$data];
			}
		}
		$constantArray = $constantDatabase->constantVariable();
		if($barcodeFlag==1)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into setting_mst(setting_type,setting_data) 
			values('".$constantArray['barcodeSetting']."','".json_encode($barcodeArray)."')");
			DB::commit();
		}
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * update data 
	 * @param  setting-data,key of setting-data
	 * returns the status
	*/
	public function updateData($settingData,$key)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$barcodeArray = array();
		date_default_timezone_set("Asia/Calcutta");
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		// print_r($settingData);
		for($data=0;$data<count($settingData);$data++)
		{
			$explodedSetting = explode('_',$key[$data]);
			if(strcmp('barcode',$explodedSetting[0])==0)
			{
				$barcodeFlag=1;
				$barcodeArray[$key[$data]] = $settingData[$data];
			}
		}
		// print_r($barcodeArray);
		$constantArray = $constantDatabase->constantVariable();
		if($barcodeFlag==1)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("update
			setting_mst 
			set setting_data = '".json_encode($barcodeArray)."',
			updated_at = '".$mytime."'
			where setting_type='barcode' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
		}
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * get-all data 
	 * returns error-message/data
	 */
	public function getAllData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		date_default_timezone_set("Asia/Calcutta");
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select
		setting_id,
		setting_type,
		setting_data,
		created_at,
		updated_at
		from setting_mst
		where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)!=0)
		{
			return json_encode($raw);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
}
