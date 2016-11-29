<?php
namespace ERP\Core\Accounting\Bills\Entities;

use ERP\Core\Accounting\Bills\Entities\Bill;
use ERP\Core\Clients\Services\ClientService;
use ERP\Core\Entities\CompanyDetail;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData extends ClientService 
{
	public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
		$createdAt = $decodedJson[0]['created_at'];
		$updatedAt= $decodedJson[0]['updated_at'];
		$saleId= $decodedJson[0]['sale_id'];
		$productArray= $decodedJson[0]['product_array'];
		$paymentMode= $decodedJson[0]['payment_mode'];
		$bankName= $decodedJson[0]['bank_name'];
		$invoiceNumber= $decodedJson[0]['invoice_number'];
		$checkNumber= $decodedJson[0]['check_number'];
		$total= $decodedJson[0]['total'];
		$tax= $decodedJson[0]['tax'];
		$grandTotal= $decodedJson[0]['grand_total'];
		$advance = $decodedJson[0]['advance'];
		$balance = $decodedJson[0]['balance'];
		$remark= $decodedJson[0]['remark'];
		$entryDate= $decodedJson[0]['entry_date'];
		$clientId= $decodedJson[0]['client_id'];
		$companyId= $decodedJson[0]['company_id'];
		
		//get the client details from database
		$encodeStateDataClass = new EncodeData();
		$clientStatus = $encodeStateDataClass->getClientData($clientId);
		$clientDecodedJson = json_decode($clientStatus,true);
		
		//get the company details from database
		$companyDetail = new CompanyDetail();
		$companyDetails = $companyDetail->getCompanyDetails($companyId);
		
		//date format conversion
		$bill = new Bill();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$bill->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $bill->getCreated_at();
		$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->format('d-m-Y');
		$bill->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $bill->getUpdated_at();
		$convertedEntryDate = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate)->format('d-m-Y');
		$bill->setEntryDate($convertedEntryDate);
		$getEntryDate = $bill->getEntryDate();
		
		//set all data into json array
		$data = array();
		$data['saleId'] = $saleId;
		$data['productArray'] = $productArray;
		$data['paymentMode'] = $paymentMode;
		$data['bankName'] = $bankName;
		$data['invoiceNumber'] = $invoiceNumber;
		$data['checkNumber'] = $checkNumber;
		$data['total'] = $total;
		$data['tax'] = $tax;
		$data['grandTotal'] = $grandTotal;
		$data['advance'] = $advance;
		$data['balance'] = $balance;
		$data['createdAt'] = $getCreatedDate;
		$data['remark'] = $remark;
		$data['entryDate'] = $getEntryDate;
		$data['clientId'] = $clientId;
		$data['companyId'] = $companyId;
		$data['updatedAt'] = $getUpdatedDate;	
		$data['client']= array(
			'clientId' => $clientDecodedJson['clientId'],	
			'clientName' => $clientDecodedJson['clientName'],	
			'companyName' => $clientDecodedJson['companyName'],	
			'contactNo' => $clientDecodedJson['contactNo'],	
			'workNo' => $clientDecodedJson['workNo'],	
			'emailId' => $clientDecodedJson['emailId'],	
			'address1' => $clientDecodedJson['address1'],	
			'address1' => $clientDecodedJson['address2'],	
			'isDisplay' => $clientDecodedJson['isDisplay'],	
			'createdAt' => $clientDecodedJson['createdAt'],	
			'updatedAt' => $clientDecodedJson['updatedAt'],	
			'stateAbb' => $clientDecodedJson['state']['stateAbb'],	
			'cityId' => $clientDecodedJson['city']['cityId']
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