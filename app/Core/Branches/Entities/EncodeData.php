<?php
namespace ERP\Core\Branches\Entities;

use ERP\Core\Branches\Entities\Branch;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CompanyDetail;
use ERP\Core\Entities\CityDetail;
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
		
		// get the state details from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		
		// get the city details from database
		$cityDetail = new CityDetail();
		$getCityDetail = $cityDetail->getCityDetail($cityId);
		
		// get the company details from database
		$companyDetail = new CompanyDetail();
		$companyDetails = $companyDetail->getCompanyDetails($companyId);
		// print_r($companyDetails);
		// date format conversion
		$branch = new Branch();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$branch->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $branch->getCreated_at();
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$branch->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $branch->getUpdated_at();
		
		// set all data into json array
		$data = array();
		$data['branch_id'] = $branchId;
		$data['branch_name'] = $branchName;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['pincode'] = $pincode;
		$data['is_display'] = $isDisplay;
		$data['is_default'] = $isDefault;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['company_id'] = $companyDetails['company_id'];
		// echo $companyDetails['state_abb'];
		$data['company_id']= array(
			'company_name' => $companyDetails['company_name'],	
			'company_display_name' => $companyDetails['company_display_name'],	
			'address1' => $companyDetails['address1'],	
			'address2' => $companyDetails['address2'],	
			'pincode' => $companyDetails['pincode'],
			'pan' => $companyDetails['pan'],	
			'tin' => $companyDetails['tin'],
			'vat_no' =>$companyDetails['vat_no'],
			'service_tax_no' => $companyDetails['service_tax_no'],
			'basic_currency_symbol' => $companyDetails['basic_currency_symbol'],
			'formal_name' => $companyDetails['formal_name'],
			'no_of_decimal_points' => $companyDetails['no_of_decimal_points'],
			'currency_symbol' => $companyDetails['currency_symbol'],	
			'document_name' => $companyDetails['document_name'],	
			'document_url' => $companyDetails['document_url'],	
			'document_size' => $companyDetails['document_size'],
			'document_format' => $companyDetails['document_format'],	
			'is_display' => $companyDetails['is_display'],	
			'is_default' => $companyDetails['is_default'],	
			'created_at' => $companyDetails['created_at'],	
			'updated_at' => $companyDetails['updated_at'],	
			'state_abb' => $companyDetails['state_abb'],	
			'city_id' => $companyDetails['city_id'],
			'state_name' => $companyDetails['state']['state_name'],	
			'city_name' => $companyDetails['city']['city_name']
		);
		$data['state_abb']= array(
			'state_name' => $stateDecodedJson['stateName'],
			'is_display' => $stateDecodedJson['isDisplay'],	
			'created_at' => $stateDecodedJson['createdAt'],	
			'updated_at' => $stateDecodedJson['updatedAt']	
		);
		$data['city_id']= array(
			'city_name' => $getCityDetail['city_name'],	
			'is_display' => $getCityDetail['is_display'],	
			'created_at' => $getCityDetail['created_at'],	
			'updated_at' => $getCityDetail['updated_at'],	
			'state_abb'=> $getCityDetail['state_abb']
		);
		$encodeData = json_encode($data);
		return $encodeData;
	}
}