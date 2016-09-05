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
class EncodeData extends StateService 
{
	
    public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
			
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$branchId= $decodedJson[0]['branch_id'];
		$branchName= $decodedJson[0]['branch_name'];
		$address1= $decodedJson[0]['address1'];
		$address2= $decodedJson[0]['address2'];
		$pincode = $decodedJson[0]['pincode'];
		$isDisplay= $decodedJson[0]['is_display'];
		$isDefault= $decodedJson[0]['is_default'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		$companyId= $decodedJson[0]['company_id'];
		
		//get the state_name from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		$stateName= $stateDecodedJson['state_name'];
		
		//get the city_name from database
		$cityName  = new CityName();
		$getCityName = $cityName->getCityName($cityId);
		
		//get the company_name from database
		$companyName  = new CompanyName();
		$getCompanyName = $companyName->getCompanyName($companyId);
		
		//date format conversion['created_at','updated_at']
		$branch = new Branch();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$branch->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $branch->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$branch->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $branch->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['branch_id'] = $branchId;
		$data['branch_name'] = $branchName;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['pincode'] = $pincode;
		$data['is_display'] = $isDisplay;
		$data['is_default'] = $isDefault;
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		$data['state_name'] = $stateName;	
		$data['city_name'] = $getCityName;	
		$data['company_name'] = $getCompanyName;	
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}