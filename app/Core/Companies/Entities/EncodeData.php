<?php
namespace ERP\Core\Companies\Entities;

use ERP\Core\Companies\Entities\Company;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData extends StateService 
{
	//date conversion and merge with json data and returns json array
    public function getEncodedData($status,$documentStatus)
	{
		$decodedJson = json_decode($status,true);
		$decodedJsonDoc = json_decode($documentStatus,true);
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$companyId= $decodedJson[0]['company_id'];
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
		$documentName= $decodedJsonDoc[0]['document_name'];
		$documentUrl= $decodedJsonDoc[0]['document_url'];
		$documentSize= $decodedJsonDoc[0]['document_size'];
		$documentFormat= $decodedJsonDoc[0]['document_format'];
		$isDisplay= $decodedJson[0]['is_display'];
		$isDefault= $decodedJson[0]['is_default'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		
		//get the state_name from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		
		//get the city_name from database
		$cityDetail = new CityDetail();
		$getCityDetail = $cityDetail->getCityDetail($cityId);
			
		//date format conversion
		$company = new Company();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$company->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $company->getCreated_at();
			
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$company->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $company->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['companyId'] = $companyId;
		$data['companyName'] = $companyName;
		$data['companyDisplayName'] = $companyDisplayName;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['pincode'] = $pincode;
		$data['pan'] = $pan;
		$data['tin'] = $tin;
		$data['vatNo'] = $vat_no;
		$data['serviceTaxNo'] = $serviceTaxNo;
		$data['basicCurrencySymbol'] = $basicCurrencySymbol;
		$data['formalName'] = $formalName;
		$data['noOfDecimalPoints'] = $noOfDecimalPoints;
		$data['currencySymbol'] = $currencySymbol;
		$data['documentName'] = $documentName;
		$data['documentUrl'] = $documentUrl;
		$data['documentSize'] = $documentSize;
		$data['documentFormat'] = $documentFormat;
		$data['isDisplay'] = $isDisplay;
		$data['isDefault'] = $isDefault;
		$data['createdAt'] = $getCreatedDate;
		$data['updatedAt'] = $getUpdatedDate;	
		$data['stateAbb'] = $stateAbb;
		$data['cityId'] = $cityId;
		
		$data['state'] = array(
			'stateName' => $stateDecodedJson['stateName'],	
			'isDisplay' => $stateDecodedJson['isDisplay'],	
			'createdAt' => $stateDecodedJson['createdAt'],	
			'updatedAt' => $stateDecodedJson['updatedAt']
		);
		$data['city'] = array(
			'cityName' => $getCityDetail['cityName'],
			'isDisplay' => $getCityDetail['isDisplay'],	
			'createdAt' => $getCityDetail['createdAt'],	
			'updatedAt' => $getCityDetail['updatedAt'],	
			'stateAbb' => $getCityDetail['stateAbb']
		);
		$encodeData = json_encode($data);
		return $encodeData;
	}
}