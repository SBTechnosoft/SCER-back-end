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
		
		//get the state details from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		
		//get the city details from database
		$cityDetail = new CityDetail();
		$getCityDetail = $cityDetail->getCityDetail($cityId);
		
		//get the company details from database
		$companyDetail = new CompanyDetail();
		$companyDetails = $companyDetail->getCompanyDetails($companyId);
		
		//date format conversion
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
		
		$data['company_id'] = $companyDetails['company_id'];	
		$data['company_name'] = $companyDetails['company_name'];	
		$data['company_display_name'] = $companyDetails['company_display_name'];	
		$data['companyAddress1'] = $companyDetails['address1'];	
		$data['companyAddress2'] = $companyDetails['address2'];	
		$data['companyPincode'] = $companyDetails['pincode'];	
		$data['pan'] = $companyDetails['pan'];	
		$data['tin'] = $companyDetails['tin'];	
		$data['vat_no'] = $companyDetails['vat_no'];	
		$data['service_tax_no'] = $companyDetails['service_tax_no'];	
		$data['basic_currency_symbol'] = $companyDetails['basic_currency_symbol'];	
		$data['formal_name'] = $companyDetails['formal_name'];	
		$data['no_of_decimal_points'] = $companyDetails['no_of_decimal_points'];	
		$data['currency_symbol'] = $companyDetails['currency_symbol'];	
		$data['document_name'] = $companyDetails['document_name'];	
		$data['document_url'] = $companyDetails['document_url'];	
		$data['document_size'] = $companyDetails['document_size'];	
		$data['document_format'] = $companyDetails['document_format'];	
		$data['companyIs_display'] = $companyDetails['is_display'];	
		$data['companyIs_default'] = $companyDetails['is_default'];	
		$data['companyCreated_at'] = $companyDetails['created_at'];	
		$data['companyUpdated_at'] = $companyDetails['updated_at'];	
		$data['companyState_abb'] = $companyDetails['state_abb'];	
		$data['companyCity_id'] = $companyDetails['city_id'];	
		$data['companyState_name'] = $companyDetails['state_name'];	
		$data['companyCity_name'] = $companyDetails['city_name'];
		
		$data['state_name'] = $stateDecodedJson['state_name'];
		$data['stateIs_display'] = $stateDecodedJson['is_display'];	
		$data['stateCreated_at'] = $stateDecodedJson['created_at'];	
		$data['stateUpdated_at'] = $stateDecodedJson['updated_at'];	
		
		$data['city_name'] = $getCityDetail['city_name'];	
		$data['cityIs_display'] = $getCityDetail['is_display'];	
		$data['cityCreated_at'] = $getCityDetail['created_at'];	
		$data['cityUpdated_at'] = $getCityDetail['updated_at'];	
		$data['cityState_abb'] = $getCityDetail['state_abb'];
		$encodeData = json_encode($data);
		return $encodeData;
	}
}