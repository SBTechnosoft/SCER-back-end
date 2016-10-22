<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\CityDetail;
use ERP\Core\Entities\LedgerGroupDetail;
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
			$serviceTaxNo[$decodedData] = $decodedJson[$decodedData]['service_tax_no'];
			$stateAbb[$decodedData] = $decodedJson[$decodedData]['state_abb'];
			$cityId[$decodedData] = $decodedJson[$decodedData]['city_id'];
			$ledgerGrpId[$decodedData] = $decodedJson[$decodedData]['ledger_grp_id'];
			
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
			
			//get the company details from database
			$ledgerGrpDetail = new LedgerGroupDetail();
			$getLedgerGrpDetails[$decodedData] = $ledgerGrpDetail->getLedgerGrpDetails($ledgerGrpId[$decodedData]);
			
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
				'service_tax_no'=> $serviceTaxNo[$jsonData],
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
				
				'ledger_grp_id' => $getLedgerGrpDetails[$jsonData][0]['ledger_grp_id'],	
				'ledger_grp_name' => $getLedgerGrpDetails[$jsonData][0]['ledger_grp_name'],	
				'under_what' => $getLedgerGrpDetails[$jsonData][0]['under_what']
			);
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}