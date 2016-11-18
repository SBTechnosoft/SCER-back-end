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
		$contactNo= $decodedJson[0]['contact_no'];
		$emailId= $decodedJson[0]['email_id'];
		$panNo = $decodedJson[0]['pan'];
		$tinNo = $decodedJson[0]['tin'];
		$gstNo= $decodedJson[0]['gst'];
		$stateAbb= $decodedJson[0]['state_abb'];
		$cityId= $decodedJson[0]['city_id'];
		$ledgerGrpId= $decodedJson[0]['ledger_group_id'];
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
		$data['ledgerId'] = $ledgerId;
		$data['ledgerName'] = $ledgerName;
		$data['alias'] = $alias;
		$data['inventoryAffected'] = $inventoryAffected;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['contactNo'] = $contactNo;
		$data['emailId'] = $emailId;
		$data['pan'] = $panNo;
		$data['tin'] = $tinNo;
		$data['gstNo'] = $gstNo;
		$data['createdAt'] = $getCreatedDate;
		$data['updatedAt'] = $getUpdatedDate;	
		$data['ledgerGroup']= array(
			'ledgerGroupId' => $ledgerGrpId,	
			'ledgerGroupName' => $getLedgerGrpDetail['ledgerGroupName'],
			'alias' => $getLedgerGrpDetail['alias'],
			'underWhat' => $getLedgerGrpDetail['underWhat'],
			'natureOfGroup' => $getLedgerGrpDetail['natureOfGroup'],
			'affectedGroupProfit' => $getLedgerGrpDetail['affectedGroupProfit']
		);
		$data['state'] = array(
			'stateAbb' => $stateAbb,
			'stateName' => $stateDecodedJson['stateName'],
			'isDisplay' => $stateDecodedJson['isDisplay'],	
			'createdAt' => $stateDecodedJson['createdAt'],	
			'updatedAt' => $stateDecodedJson['updatedAt']	
		);
		$data['city'] = array(
			'cityId' => $cityId,
			'cityName' => $getCityDetail['cityName'],	
			'isDisplay'=> $getCityDetail['isDisplay'],	
			'createdAt' => $getCityDetail['createdAt'],	
			'updatedAt' => $getCityDetail['updatedAt'],	
			'stateAbb'=> $getCityDetail['state']['stateAbb']
		);
		$data['company']= array(
			'companyId' => $companyId,
			'companyName' => $companyDetails['companyName'],	
			'companyDisplayName' => $companyDetails['companyDisplayName'],	
			'address1' => $companyDetails['address1'],	
			'address2' => $companyDetails['address2'],	
			'pincode' => $companyDetails['pincode'],
			'pan' => $companyDetails['pan'],	
			'tin' => $companyDetails['tin'],
			'vatNo' =>$companyDetails['vatNo'],
			'serviceTaxNo' => $companyDetails['serviceTaxNo'],
			'basicCurrencySymbol' => $companyDetails['basicCurrencySymbol'],
			'formalName' => $companyDetails['formalName'],
			'noOfDecimalPoints' => $companyDetails['currencySymbol'],	
			'logo'=> array(
				'documentName' => $companyDetails['logo']['documentName'],	
				'documentUrl' => $companyDetails['logo']['documentUrl'],	
				'documentSize' => $companyDetails['logo']['documentSize'],
				'documentFormat' => $companyDetails['logo']['documentFormat']
			),
			'isDisplay' => $companyDetails['isDisplay'],	
			'isDefault' => $companyDetails['isDefault'],	
			'createdAt' => $companyDetails['createdAt'],	
			'updatedAt' => $companyDetails['updatedAt'],	
			'stateAbb' => $companyDetails['state']['stateAbb'],	
			'cityId' => $companyDetails['city']['cityId']
		);
		$encodeData = json_encode($data);
		return $encodeData;
	}
}