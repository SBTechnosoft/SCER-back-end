<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Core\States\Services\StateService;
use ERP\Core\Entities\LedgerGroupDetail;
use ERP\Core\Entities\CityDetail;
use ERP\Core\Entities\CompanyDetail;
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
		$gstNo= $decodedJson[0]['gst'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		$ledgerGrpId= $decodedJson[0]['ledger_grp_id'];
		$companyId= $decodedJson[0]['company_id'];
		
		//get the state details from database
		$encodeStateDataClass = new EncodeData();
		$stateStatus = $encodeStateDataClass->getStateData($stateAbb);
		$stateDecodedJson = json_decode($stateStatus,true);
		
		//get the city details from database
		$cityDetail = new CityDetail();
		$getCityDetail = $cityDetail->getCityDetail($cityId);
		
		//get the ledger-group details from database
		$ledgerGrpDetail = new LedgerGroupDetail();
		$getLedgerGrpDetail = $ledgerGrpDetail->getLedgerGrpDetails($ledgerGrpId);
		
		//get the company details from database
		$companyDetail = new CompanyDetail();
		$companyDetails = $companyDetail->getCompanyDetails($companyId);
		
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
		$data['gst_no'] = $gstNo;
		$data['created_at'] = $getCreatedDate;
		$data['updated_at'] = $getUpdatedDate;	
		$data['state_abb'] = $stateAbb;
		$data['city_id'] = $cityId;
		$data['ledger_grp_id'] = $ledgerGrpId;	
		$data['company_id'] = $companyDetails['company_id'];
		
		$data['ledger_grp_id']= array(
			'ledger_grp_name' => $getLedgerGrpDetail[0]['ledger_grp_name'],
			'under_what' => $getLedgerGrpDetail[0]['under_what']
		);
		$data['state_abb'] = array(
			'state_name' => $stateDecodedJson['state_name'],
			'is_display' => $stateDecodedJson['is_display'],	
			'created_at' => $stateDecodedJson['created_at'],	
			'updated_at' => $stateDecodedJson['updated_at']	
		);
		$data['city_id'] = array(
			'city_name' => $getCityDetail['city_name'],	
			'is_display'=> $getCityDetail['is_display'],	
			'created_at' => $getCityDetail['created_at'],	
			'updated_at' => $getCityDetail['updated_at'],	
			'state_abb'=> $getCityDetail['state_abb']
		);
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
			'state_name' => $companyDetails['state_name'],	
			'city_name' => $companyDetails['city_name']
		);
		$encodeData = json_encode($data);
		return $encodeData;
	}
}