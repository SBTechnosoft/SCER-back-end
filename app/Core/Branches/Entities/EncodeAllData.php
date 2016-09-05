<?php
namespace ERP\Core\Branches\Entities;

use ERP\Core\Branches\Entities\Branch;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityName;
use ERP\Core\Entities\CompanyName;
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
		$branch = new Branch();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$branchId[$decodedData] = $decodedJson[$decodedData]['branch_id'];
			$branchName[$decodedData] = $decodedJson[$decodedData]['branch_name'];
			$address1[$decodedData] = $decodedJson[$decodedData]['address1'];
			$address2[$decodedData] = $decodedJson[$decodedData]['address2'];
			$pincode[$decodedData] = $decodedJson[$decodedData]['pincode'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			$isDefault[$decodedData] = $decodedJson[$decodedData]['is_default'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			//get the state_name from database
			$encodeDataClass = new EncodeAllData();
			$stateStatus[$decodedData] = $encodeDataClass->getStateData($stateAbb[$decodedData]);
			$stateDecodedJson[$decodedData] = json_decode($stateStatus[$decodedData],true);
			$stateName[$decodedData]= $stateDecodedJson[$decodedData]['state_name'];
			
			//get the city_name from database
			$cityName = new CityName();
			$getCityName[$decodedData] = $cityName->getCityName($cityId[$decodedData]);//get the 
			
			//company_name from database
			$companyName = new CompanyName();
			$getCompanyName[$decodedData] = $companyName->getCompanyName($companyId[$decodedData]);
			
			
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
				
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
		}
		$branch->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $branch->getCreated_at();
			
		$branch->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $branch->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'branch_id'=>$branchId[$jsonData],
				'branch_name' => $branchName[$jsonData],
				'address1' => $address1[$jsonData],
				'address2' => $address2[$jsonData],
				'pincode'=> $pincode[$jsonData],
				'is_display' => $isDisplay[$jsonData],
				'is_default' => $isDefault[$jsonData],
				'state_abb' => $stateAbb[$jsonData],
				'city_id' => $cityId[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData],
				'state_name' => $stateName[$jsonData],
				'city_name' => $getCityName[$jsonData],
				'company_name' => $getCompanyName[$jsonData]
			);
		}
		return json_encode($data);
	}
}