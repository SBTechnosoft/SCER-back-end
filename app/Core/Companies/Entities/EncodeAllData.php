<?php
namespace ERP\Core\Companies\Entities;

use ERP\Core\Companies\Entities\Company;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends StateService
{
	//date conversion and merge with json data and returns json array
	public function getEncodedAllData($status,$documentStatus)
	{
		$convertedCreatedDate = array();
		$convertedUpdatedDate = array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$decodedJsonDoc = json_decode($documentStatus,true);
		$company = new Company();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			$companyName[$decodedData] = $decodedJson[$decodedData]['company_name'];
			$companyDisplayName[$decodedData] = $decodedJson[$decodedData]['company_display_name'];
			$address1[$decodedData] = $decodedJson[$decodedData]['address1'];
			$address2[$decodedData] = $decodedJson[$decodedData]['address2'];
			$pincode[$decodedData] = $decodedJson[$decodedData]['pincode'];
			$pan[$decodedData] = $decodedJson[$decodedData]['pan'];
			$tin[$decodedData] = $decodedJson[$decodedData]['tin'];
			$vat_no[$decodedData] = $decodedJson[$decodedData]['vat_no'];
			$serviceTaxNo[$decodedData] = $decodedJson[$decodedData]['service_tax_no'];
			$basicCurrencySymbol[$decodedData] = $decodedJson[$decodedData]['basic_currency_symbol'];
			$formalName[$decodedData] = $decodedJson[$decodedData]['formal_name'];
			$noOfDecimalPoints[$decodedData] = $decodedJson[$decodedData]['no_of_decimal_points'];
			$currencySymbol[$decodedData] = $decodedJson[$decodedData]['currency_symbol'];
			$documentName[$decodedData] = $decodedJsonDoc[$decodedData]['document_name'];
			$documentUrl[$decodedData] = $decodedJsonDoc[$decodedData]['document_url'];
			$documentSize[$decodedData] = $decodedJsonDoc[$decodedData]['document_size'];
			$documentFormat[$decodedData] = $decodedJsonDoc[$decodedData]['document_format'];
			$isDisplay[$decodedData] = $decodedJson[$decodedData]['is_display'];
			$isDefault[$decodedData] = $decodedJson[$decodedData]['is_default'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			
			//get the state details from database
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
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				
		}
		$company->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $company->getCreated_at();
		$company->setCreated_at($convertedUpdatedDate);
		$getUpdatedDate = $company->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'company_id'=>$companyId[$jsonData],
				'company_name' => $companyName[$jsonData],
				'company_display_name' => $companyDisplayName[$jsonData],
				'address1' => $address1[$jsonData],
				'address2' => $address2[$jsonData],
				'pincode'=> $pincode[$jsonData],
				'pan' => $pan[$jsonData],
				'tin' => $tin[$jsonData],
				'vat_no' =>$vat_no[$jsonData],
				'service_tax_no' => $serviceTaxNo[$jsonData],
				'basic_currency_symbol' => $basicCurrencySymbol[$jsonData],
				'formal_name' => $formalName[$jsonData],
				'no_of_decimal_points' => $noOfDecimalPoints[$jsonData],
				'currency_symbol' => $currencySymbol[$jsonData],
				'document_name'=> $documentName[$jsonData],
				'document_url' => $documentUrl[$jsonData],
				'document_size' => $documentSize[$jsonData],
				'document_format' => $documentFormat[$jsonData],
				'is_display' => $isDisplay[$jsonData],
				'is_default' => $isDefault[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData],
				'state_abb' => $stateAbb[$jsonData],
				'city_id' => $cityId[$jsonData],
				
				'state' => array(
					'state_abb' => $stateAbb[$jsonData],
					'state_name' => $stateName[$jsonData],
					'is_display' => $stateIsDisplay[$jsonData],
					'created_at' => $stateCreatedAt[$jsonData],
					'updated_at' => $stateUpdatedAt[$jsonData]
				),
				'city'=> array(
					'city_id' => $cityId[$jsonData],
					'city_name' => $getCityDetail[$jsonData]['city_name'],
					'is_display' => $getCityDetail[$jsonData]['is_display'],
					'created_at' => $getCityDetail[$jsonData]['created_at'],
					'updated_at' => $getCityDetail[$jsonData]['updated_at'],
					'state_abb' => $getCityDetail[$jsonData]['state_abb']
				)
			);
		}
		return json_encode($data);
	}
}