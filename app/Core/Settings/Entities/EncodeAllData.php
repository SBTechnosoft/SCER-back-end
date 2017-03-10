<?php
namespace ERP\Core\Settings\Entities;

use ERP\Core\Settings\Entities\Setting;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends Setting
{
	public function getEncodedAllData($status)
	{
		$convertedUpdatedDate =  array();
		$convertedCreatedDate =  array();
		
		$decodedJson = json_decode($status,true);
		$setting = new EncodeAllData();
		$data = array();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			
			// date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$setting->setCreated_at($convertedCreatedDate[$decodedData]);
			$getCreatedDate[$decodedData] = $setting->getCreated_at();
			if(strcmp($updatedAt[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getUpdatedDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				$setting->setUpdated_at($convertedUpdatedDate[$decodedData]);
				$getUpdatedDate[$decodedData] = $setting->getUpdated_at();
			}
			$settingData[$decodedData] = $decodedJson[$decodedData]['setting_data'];
			
			$decodedSettingData = json_decode($settingData[$decodedData]);
			if(strcmp($decodedJson[$decodedData]['setting_type'],'barcode')==0)
			{
				$data[$decodedData]= array(
					'settingId' => $decodedJson[$decodedData]['setting_id'],
					'settingType' => $decodedJson[$decodedData]['setting_type'],
					'barcodeWidth' => $decodedSettingData->barcode_width,
					'barcodeHeight' => $decodedSettingData->barcode_height,
					'createdAt' => $getCreatedDate[$decodedData],
					'updatedAt' => $getUpdatedDate[$decodedData],
				);
			}
		}
		return json_encode($data);
	}
}