<?php
namespace ERP\Core\Branches\Entities;

use ERP\Core\Branches\Entities\Branch;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use ERP\Core\Entities\CompanyDetail;
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
			
			//get the state detail from database
			$encodeDataClass = new EncodeAllData();
			$stateStatus[$decodedData] = $encodeDataClass->getStateData($stateAbb[$decodedData]);
			$stateDecodedJson[$decodedData] = json_decode($stateStatus[$decodedData],true);
			$stateName[$decodedData]= $stateDecodedJson[$decodedData]['state_name'];
			$stateIsDisplay[$decodedData]= $stateDecodedJson[$decodedData]['is_display'];
			$stateCreatedAt[$decodedData]= $stateDecodedJson[$decodedData]['created_at'];
			$stateUpdatedAt[$decodedData]= $stateDecodedJson[$decodedData]['updated_at'];
			
			//get the city details from database
			$cityDetail = new CityDetail();
			$getCityDetail[$decodedData] = $cityDetail->getCityDetail($cityId[$decodedData]);
			 
			//get the company details from database
			$companyDetail = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//date format conversion
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
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData],
				
				'state_abb' => $stateAbb[$jsonData],
				'state_name' => $stateName[$jsonData],
				'stateIs_display' => $stateIsDisplay[$jsonData],
				'stateCreated_at' => $stateCreatedAt[$jsonData],
				'stateUpdated_at' => $stateUpdatedAt[$jsonData],
				
				'city_id' => $cityId[$jsonData],
				'city_name' => $getCityDetail[$jsonData]['city_name'],
				'cityIs_display' => $getCityDetail[$jsonData]['is_display'],
				'cityCreated_at' => $getCityDetail[$jsonData]['created_at'],
				'cityUpdated_at' => $getCityDetail[$jsonData]['updated_at'],
				'cityState_abb' => $getCityDetail[$jsonData]['state_abb'],
				
				'company_id' => $getCompanyDetails[$jsonData]['company_id'],	
				'company_name' => $getCompanyDetails[$jsonData]['company_name'],	
				'company_display_name' => $getCompanyDetails[$jsonData]['company_display_name'],	
				'companyAddress1' => $getCompanyDetails[$jsonData]['address1'],	
				'companyAddress2'=> $getCompanyDetails[$jsonData]['address2'],	
				'companyPincode' => $getCompanyDetails[$jsonData]['pincode'],	
				'pan' => $getCompanyDetails[$jsonData]['pan'],	
				'tin'=> $getCompanyDetails[$jsonData]['tin'],	
				'vat_no' => $getCompanyDetails[$jsonData]['vat_no'],	
				'service_tax_no' => $getCompanyDetails[$jsonData]['service_tax_no'],	
				'basic_currency_symbol' => $getCompanyDetails[$jsonData]['basic_currency_symbol'],	
				'formal_name' => $getCompanyDetails[$jsonData]['formal_name'],	
				'no_of_decimal_points' => $getCompanyDetails[$jsonData]['no_of_decimal_points'],	
				'currency_symbol' => $getCompanyDetails[$jsonData]['currency_symbol'],	
				'document_name' => $getCompanyDetails[$jsonData]['document_name'],	
				'document_url' => $getCompanyDetails[$jsonData]['document_url'],	
				'document_size' =>$getCompanyDetails[$jsonData]['document_size'],	
				'document_format' => $getCompanyDetails[$jsonData]['document_format'],	
				'companyIs_display' => $getCompanyDetails[$jsonData]['is_display'],	
				'companyIs_default' => $getCompanyDetails[$jsonData]['is_default'],	
				'companyCreated_at' => $getCompanyDetails[$jsonData]['created_at'],	
				'companyUpdated_at' => $getCompanyDetails[$jsonData]['updated_at'],	
				'companyState_abb' => $getCompanyDetails[$jsonData]['state_abb'],	
				'companyCity_id' => $getCompanyDetails[$jsonData]['city_id'],	
				'companyState_name' => $getCompanyDetails[$jsonData]['state_name'],	
				'companyCity_name' => $getCompanyDetails[$jsonData]['city_name']	
			);
		}
		return json_encode($data);
	}
}