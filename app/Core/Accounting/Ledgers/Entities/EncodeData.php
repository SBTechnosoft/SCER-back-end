<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\LedgerGrpDetail;
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
		$ledgerId= $decodedJson[0]['ledger_id'];
		$ledgerName= $decodedJson[0]['ledger_name'];
		$alias= $decodedJson[0]['alias'];
		$inventoryAffected= $decodedJson[0]['inventory_affected'];
		$address1= $decodedJson[0]['address1'];
		$address2= $decodedJson[0]['address2'];
		$panNo = $decodedJson[0]['pan'];
		$tinNo = $decodedJson[0]['tin'];
		$serviceTaxNo= $decodedJson[0]['service_tax_no'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		$ledgerGrpId= $decodedJson[0]['ledger_grp_id'];
		
		//get the state details from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		
		//get the city details from database
		$cityDetail = new CityDetail();
		$getCityDetail = $cityDetail->getCityDetail($cityId);
		
		//get the company details from database
		$ledgerGrpDetail = new LedgerGrpDetail();
		$getLedgerGrpDetail = $ledgerGrpDetail->getLedgerGrpDetails($ledgerGrpId);
		
		//date format conversion
		$ledger = new Ledger();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$ledger->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $ledger->getCreated_at();
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$ledger->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $ledger->getUpdated_at();
		
		//set all data into json array
		$data = array();
		$data['ledger_id'] = $ledgerId;
		$data['ledger_name'] = $ledgerName;
		$data['alias'] = $alias;
		$data['inventory_affected'] = $inventoryAffected;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['pan'] = $panNo;
		$data['tin'] = $tinNo;
		$data['service_tax_no'] = $serviceTaxNo;
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		
		$data['ledger_grp_id'] = $ledgerGrpId;	
		$data['ledger_grp_name'] = $getLedgerGrpDetail[0]['ledger_grp_name'];	
		$data['under_what'] = $getLedgerGrpDetail[0]['under_what'];	
		
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