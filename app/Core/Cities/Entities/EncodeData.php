<?php
namespace ERP\Core\Cities\Entities;

use ERP\Core\Cities\Entities\City;
use ERP\Core\States\Services\StateService;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData extends StateService
{
	public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$cityName= $decodedJson[0]['city_name'];
		$cityId = $decodedJson[0]['city_id'];
		$isDisplay= $decodedJson[0]['is_display'];
		$stateAbb= $decodedJson[0]['state_abb'];
		
		//get the state_name from database
		$encodeDataClass = new EncodeData();
		$stateStatus = $encodeDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		$stateName= $stateDecodedJson['state_name'];
		
		//date format conversion['created_at','updated_at']
		$city = new City();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$city->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $city->getCreated_at();
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$city->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $city->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['city_name'] = $cityName;
		$data['state_name'] = $stateName;
		$data['is_display'] = $isDisplay;
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		$encodeData = json_encode($data);
		return $encodeData;
	}
}