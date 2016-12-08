<?php
namespace ERP\Core\States\Entities;

use ERP\Core\States\Entities\State;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData
{
	//date conversion and merge with json data and returns json array
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$state = new State();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$stateName[$decodedData] = $decodedJson[$decodedData]['state_name'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
			
			$state->setCreated_at($convertedCreatedDate[$decodedData]);
			$getCreatedDate[$decodedData] = $state->getCreated_at();
			$state->setUpdated_at($convertedUpdatedDate[$decodedData]);
			$getUpdatedDate[$decodedData] = $state->getUpdated_at();
		}
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'stateName' => $stateName[$jsonData],
				'stateAbb' =>$stateAbb[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' =>$getUpdatedDate[$jsonData]
				
			);	
		}
		return json_encode($data);
	}
}