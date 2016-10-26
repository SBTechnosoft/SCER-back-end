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
				
		}
		$company->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $company->getCreated_at();
		$company->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $company->getUpdated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'companyId'=>$companyId[$jsonData],
				'companyName' => $companyName[$jsonData],
				'companyDisplayName' => $companyDisplayName[$jsonData],
				'address1' => $address1[$jsonData],
				'address2' => $address2[$jsonData],
				'pincode'=> $pincode[$jsonData],
				'pan' => $pan[$jsonData],
				'tin' => $tin[$jsonData],
				'vatNo' =>$vat_no[$jsonData],
				'serviceTaxNo' => $serviceTaxNo[$jsonData],
				'basicCurrencySymbol' => $basicCurrencySymbol[$jsonData],
				'formalName' => $formalName[$jsonData],
				'noOfDecimalPoints' => $noOfDecimalPoints[$jsonData],
				'currencySymbol' => $currencySymbol[$jsonData],
				'documentName'=> $documentName[$jsonData],
				'documentUrl' => $documentUrl[$jsonData],
				'documentSize' => $documentSize[$jsonData],
				'documentFormat' => $documentFormat[$jsonData],
				'isDisplay' => $isDisplay[$jsonData],
				'isDefault' => $isDefault[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				'stateAbb' => $stateAbb[$jsonData],
				'cityId' => $cityId[$jsonData],
				
				'state' => array(
					'stateAbb' => $stateAbb[$jsonData],
					'stateName' => $stateName[$jsonData],
					'isDisplay' => $stateIsDisplay[$jsonData],
					'createdAt' => $stateCreatedAt[$jsonData],
					'updatedAt' => $stateUpdatedAt[$jsonData]
				),
				'city'=> array(
					'cityId' => $cityId[$jsonData],
					'cityName' => $getCityDetail[$jsonData]['cityName'],
					'isDisplay' => $getCityDetail[$jsonData]['isDisplay'],
					'createdAt' => $getCityDetail[$jsonData]['createdAt'],
					'updatedAt' => $getCityDetail[$jsonData]['updatedAt'],
					'stateAbb' => $getCityDetail[$jsonData]['stateAbb']
				)
			);
		}
		return json_encode($data);
	}
}