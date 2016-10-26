<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use ERP\Core\Entities\LedgerGroupDetail;
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
		$ledger = new Ledger();
		
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$ledgerId[$decodedData] = $decodedJson[$decodedData]['ledger_id'];
			$ledgerName[$decodedData] = $decodedJson[$decodedData]['ledger_name'];
			$alias[$decodedData] = $decodedJson[$decodedData]['alias'];
			$inventoryAffected[$decodedData] = $decodedJson[$decodedData]['inventory_affected'];
			$address1[$decodedData] = $decodedJson[$decodedData]['address1'];
			$address2[$decodedData] = $decodedJson[$decodedData]['address2'];
			$panNo[$decodedData] = $decodedJson[$decodedData]['pan'];
			$tinNo[$decodedData] = $decodedJson[$decodedData]['tin'];
			$gstNo[$decodedData] = $decodedJson[$decodedData]['gst'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			$ledgerGrpId[$decodedData] = $decodedJson[$decodedData]['ledger_grp_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			//get the state detail from database
			$encodeDataClass = new EncodeAllData();
			$stateStatus[$decodedData] = $encodeDataClass->getStateData($stateAbb[$decodedData]);
			$stateDecodedJson[$decodedData] = json_decode($stateStatus[$decodedData],true);
			$stateName[$decodedData]= $stateDecodedJson[$decodedData]['state_name'];
			$stateIsDisplay[$decodedData]= $stateDecodedJson[$decodedData]['is_display'];
			$stateCreatedAt[$decodedData]= $stateDecodedJson[$decodedData]['created_at'];
			$stateUpdatedAt[$decodedData]= $stateDecodedJson[$decodedData]['updated_at'];
			// print_r($stateUpdatedAt[$decodedData]);
			
			//get the city details from database
			$cityDetail = new CityDetail();
			$getCityDetail[$decodedData] = $cityDetail->getCityDetail($cityId[$decodedData]);
			
			//get the ledger-group details from database
			$ledgerGrpDetail = new LedgerGroupDetail();
			$getLedgerGrpDetails[$decodedData] = $ledgerGrpDetail->getLedgerGrpDetails($ledgerGrpId[$decodedData]);
			
			//get the company details from database
			$companyDetail = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
		
		}
		$ledger->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $ledger->getCreated_at();
		$ledger->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $ledger->getUpdated_at();
		$data = array();
		
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'ledger_id'=>$ledgerId[$jsonData],
				'ledger_name' => $ledgerName[$jsonData],
				'alias' => $alias[$jsonData],
				'inventory_affected' => $inventoryAffected[$jsonData],
				'address1' => $address1[$jsonData],
				'address2' => $address2[$jsonData],
				'pan'=> $panNo[$jsonData],
				'tin'=> $tinNo[$jsonData],
				'gst_no'=> $gstNo[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'updated_at' => $getUpdatedDate[$jsonData],
				'state_abb' => $stateAbb[$jsonData],
				'city_id' => $cityId[$jsonData],
				'ledger_grp_id' => $getLedgerGrpDetails[$jsonData][0]['ledger_grp_id'],	
				'company_id' => $getCompanyDetails[$jsonData]['company_id'],
				
				'state_abb' => array(
					'state_abb' => $stateAbb[$jsonData],
					'state_name' => $stateName[$jsonData],
					'is_display' => $stateIsDisplay[$jsonData],
					'created_at' => $stateCreatedAt[$jsonData],
					'updated_at' => $stateUpdatedAt[$jsonData]
				),
				
				'city_id'=> array(
					'city_id' => $cityId[$jsonData],
					'city_name' => $getCityDetail[$jsonData]['city_name'],
					'is_display' => $getCityDetail[$jsonData]['is_display'],
					'created_at' => $getCityDetail[$jsonData]['created_at'],
					'updated_at' => $getCityDetail[$jsonData]['updated_at'],
					'state_abb' => $getCityDetail[$jsonData]['state_abb']
				),
				
				'ledger_grp_id'=> array(
					'ledger_grp_id' => $getLedgerGrpDetails[$jsonData][0]['ledger_grp_id'],	
					'ledger_grp_name' => $getLedgerGrpDetails[$jsonData][0]['ledger_grp_name'],	
					'under_what' => $getLedgerGrpDetails[$jsonData][0]['under_what']
				),
				
				'company_id' => array(	
					'company_id' => $getCompanyDetails[$jsonData]['company_id'],
					'company_name' => $getCompanyDetails[$jsonData]['company_name'],	
					'company_display_name' => $getCompanyDetails[$jsonData]['company_display_name'],	
					'address1' => $getCompanyDetails[$jsonData]['address1'],	
					'address2'=> $getCompanyDetails[$jsonData]['address2'],	
					'pincode' => $getCompanyDetails[$jsonData]['pincode'],	
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
					'is_display' => $getCompanyDetails[$jsonData]['is_display'],	
					'is_default' => $getCompanyDetails[$jsonData]['is_default'],	
					'created_at' => $getCompanyDetails[$jsonData]['created_at'],	
					'updated_at' => $getCompanyDetails[$jsonData]['updated_at'],	
					'state_abb' => $getCompanyDetails[$jsonData]['state_abb'],	
					'city_id' => $getCompanyDetails[$jsonData]['city_id'],	
					'state_name' => $getCompanyDetails[$jsonData]['state_name'],	
					'city_name' => $getCompanyDetails[$jsonData]['city_name']
				)		
			);
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}