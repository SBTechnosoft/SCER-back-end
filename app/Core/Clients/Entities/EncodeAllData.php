<?php
namespace ERP\Core\Clients\Entities;

use ERP\Core\Clients\Entities\Client;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends StateService
{
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$client = new Client();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$clientId[$decodedData] = $decodedJson[$decodedData]['client_id'];
			$clientName[$decodedData] = $decodedJson[$decodedData]['client_name'];
			$companyName[$decodedData] = $decodedJson[$decodedData]['company_name'];
			$contactNo[$decodedData] = $decodedJson[$decodedData]['contact_no'];
			$workNo[$decodedData] = $decodedJson[$decodedData]['work_no'];
			$emailId[$decodedData] = $decodedJson[$decodedData]['email_id'];
			$address1[$decodedData] = $decodedJson[$decodedData]['address1'];
			$address2[$decodedData] = $decodedJson[$decodedData]['address2'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			
			//get the state detail from database
			$encodeDataClass = new EncodeAllData();
			$stateStatus[$decodedData] = $encodeDataClass->getStateData($stateAbb[$decodedData]);
			$stateDecodedJson[$decodedData] = json_decode($stateStatus[$decodedData],true);
			$stateName[$decodedData]= $stateDecodedJson[$decodedData]['stateName'];
			$stateIsDisplay[$decodedData]= $stateDecodedJson[$decodedData]['isDisplay'];
			$stateCreatedAt[$decodedData]= $stateDecodedJson[$decodedData]['createdAt'];
			$stateUpdatedAt[$decodedData]= $stateDecodedJson[$decodedData]['updatedAt'];
			
			//get the city details from database
			$cityDetail = new CityDetail();
			$getCityDetail[$decodedData] = $cityDetail->getCityDetail($cityId[$decodedData]);
			 
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
			
			$client->setCreated_at($convertedCreatedDate[$decodedData]);
			$getCreatedDate[$decodedData] = $client->getCreated_at();
			$client->setUpdated_at($convertedUpdatedDate[$decodedData]);
			$getUpdatedDate[$decodedData] = $client->getUpdated_at();
		}
		
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'clientId'=>$clientId[$jsonData],
				'clientName' => $clientName[$jsonData],
				'companyName' => $companyName[$jsonData],
				'contactNo' => $contactNo[$jsonData],
				'workNo' => $workNo[$jsonData],
				'emailId' => $emailId[$jsonData],
				'address1' => $address1[$jsonData],
				'address2' => $address2[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				
				'state' => array(
					'stateAbb' => $stateAbb[$jsonData],
					'stateName' => $stateName[$jsonData],
					'isDisplay' => $stateIsDisplay[$jsonData],
					'createdAt' => $stateCreatedAt[$jsonData],
					'updatedAt' => $stateUpdatedAt[$jsonData]
				),
				
				'city' => array(
					'cityId' => $cityId[$jsonData],
					'cityName' => $getCityDetail[$jsonData]['cityName'],
					'isDisplay' => $getCityDetail[$jsonData]['isDisplay'],
					'createdAt' => $getCityDetail[$jsonData]['createdAt'],
					'updatedAt' => $getCityDetail[$jsonData]['updatedAt'],
					'stateAbb' => $getCityDetail[$jsonData]['state']['stateAbb']
				)
			);
		}
		return json_encode($data);
	}
}