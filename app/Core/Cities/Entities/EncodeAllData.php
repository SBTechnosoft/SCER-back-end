<?php
namespace ERP\Core\Cities\Entities;

use ERP\Core\Cities\Entities\City;
use ERP\Core\States\Services\StateService;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends StateService
{
	//date conversion and merge with json data and returns json array
    public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$city = new City();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$cityName[$decodedData] = $decodedJson[$decodedData]['city_name'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];	
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];	
			
			//get the state details from database
			$encodeDataClass = new EncodeAllData();
			$stateStatus[$decodedData] = $encodeDataClass->getStateData($stateAbb[$decodedData]);
			$stateDecodedJson[$decodedData] = json_decode($stateStatus[$decodedData],true);
			$stateName[$decodedData]= $stateDecodedJson[$decodedData]['state_name'];
			$stateIsDisplay[$decodedData]= $stateDecodedJson[$decodedData]['is_display'];
			$stateCreatedAt[$decodedData]= $stateDecodedJson[$decodedData]['created_at'];
			$stateUpdatedAt[$decodedData]= $stateDecodedJson[$decodedData]['updated_at'];
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
		}
		$city->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $city->getCreated_at();
		$city->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $city->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'city_name' => $cityName[$jsonData],
				'is_display' => $isDisplay[$jsonData],
				'city_id' => $cityId[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' =>$getUpdatedDate[$jsonData],
				
				'state_abb' =>$stateAbb[$jsonData],
				'state_name' => $stateName[$jsonData],
				'sIs_display' => $stateIsDisplay[$jsonData],
				'sCreated_at' => $stateCreatedAt[$jsonData],
				'sUpdated_at' => $stateUpdatedAt[$jsonData]
			);
		}
		return json_encode($data);
	}
}