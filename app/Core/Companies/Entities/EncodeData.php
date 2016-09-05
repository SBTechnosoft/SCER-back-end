<?php
namespace ERP\Core\Companies\Entities;

use ERP\Core\Companies\Entities\Company;
use ERP\Core\States\Services\StateService;
use ERP\Core\Companies\Entities\CityName;
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
		$companyName= $decodedJson[0]['company_name'];
		$companyDisplayName = $decodedJson[0]['company_display_name'];
		$address1= $decodedJson[0]['address1'];
		$address2= $decodedJson[0]['address2'];
		$pincode = $decodedJson[0]['pincode'];
		$pan= $decodedJson[0]['pan'];
		$tin= $decodedJson[0]['tin'];
		$vat_no= $decodedJson[0]['vat_no'];
		$serviceTaxNo= $decodedJson[0]['service_tax_no'];
		$basicCurrencySymbol = $decodedJson[0]['basic_currency_symbol'];
		$formalName= $decodedJson[0]['formal_name'];
		$noOfDecimalPoints= $decodedJson[0]['no_of_decimal_points'];
		$currencySymbol= $decodedJson[0]['currency_symbol'];
		$documentName= $decodedJson[0]['document_name'];
		$documentUrl= $decodedJson[0]['document_url'];
		$documentSize= $decodedJson[0]['document_size'];
		$documentFormat= $decodedJson[0]['document_format'];
		$isDisplay= $decodedJson[0]['is_display'];
		$isDefault= $decodedJson[0]['is_default'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		
		//get the state_name from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		$stateName= $stateDecodedJson['state_name'];
		
		//get the city_name from database
		$cityName  = new CityName();
		$getCityName = $cityName->getCityName($cityId);
		
		
		//date format conversion['created_at','updated_at']
		$company = new Company();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$company->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $company->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$company->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $company->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['company_name'] = $companyName;
		$data['company_display_name'] = $companyDisplayName;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['pincode'] = $pincode;
		$data['pan'] = $pan;
		$data['tin'] = $tin;
		$data['vat_no'] = $vat_no;
		$data['service_tax_no'] = $serviceTaxNo;
		$data['basic_currency_symbol'] = $basicCurrencySymbol;
		$data['formal_name'] = $formalName;
		$data['no_of_decimal_points'] = $noOfDecimalPoints;
		$data['currency_symbol'] = $currencySymbol;
		$data['document_name'] = $documentName;
		$data['document_url'] = $documentUrl;
		$data['document_size'] = $documentSize;
		$data['document_format'] = $documentFormat;
		$data['is_display'] = $isDisplay;
		$data['is_default'] = $isDefault;
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		$data['state_name'] = $stateName;	
		$data['city_name'] = $getCityName;	
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}