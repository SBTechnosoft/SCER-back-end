<?php
namespace ERP\Core\Settings\InvoiceNumbers\Entities;

use ERP\Core\Settings\InvoiceNumbers\Entities\InvoiceNumber;
use ERP\Core\Companies\Services\CompanyService;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeData extends CompanyService 
{
	public function getEncodedData($status)
	{
		$decodedJson = json_decode($status,true);
		$createdAt = $decodedJson[0]['created_at'];
		$invoiceId= $decodedJson[0]['invoice_id'];
		$invoiceLabel= $decodedJson[0]['invoice_label'];
		$invoiceType= $decodedJson[0]['invoice_type'];
		$startAt= $decodedJson[0]['start_at'];
		$endAt = $decodedJson[0]['end_at'];
		$companyId= $decodedJson[0]['company_id'];
		
		//get the company details from database
		$encodeCompanyDataClass = new EncodeData();
		$companyStatus = $encodeCompanyDataClass->getCompanyData($companyId);
		$companyDecodedJson = json_decode($companyStatus,true);
		
		//date format conversion
		$invoice = new InvoiceNumber();
		$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->format('d-m-Y');
		$invoice->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $invoice->getCreated_at();
		
		//set all data into json array
		$data = array();
		$data['invoice_id'] = $invoiceId;
		$data['invoice_label'] = $invoiceLabel;
		$data['invoice_type'] = $invoiceType;
		$data['start_at'] = $startAt;
		$data['end_at'] = $endAt;
		$data['created_at'] = $getCreatedDate;
		
		$data['company_id'] = $companyDecodedJson['company_id'];	
		$data['company_name'] = $companyDecodedJson['company_name'];	
		$data['company_display_name'] = $companyDecodedJson['company_display_name'];	
		$data['companyAddress1'] = $companyDecodedJson['address1'];	
		$data['companyAddress2'] = $companyDecodedJson['address2'];	
		$data['companyPincode'] = $companyDecodedJson['pincode'];	
		$data['pan'] = $companyDecodedJson['pan'];	
		$data['tin'] = $companyDecodedJson['tin'];	
		$data['vat_no'] = $companyDecodedJson['vat_no'];	
		$data['service_tax_no'] = $companyDecodedJson['service_tax_no'];	
		$data['basic_currency_symbol'] = $companyDecodedJson['basic_currency_symbol'];	
		$data['formal_name'] = $companyDecodedJson['formal_name'];	
		$data['no_of_decimal_points'] = $companyDecodedJson['no_of_decimal_points'];	
		$data['currency_symbol'] = $companyDecodedJson['currency_symbol'];	
		$data['document_name'] = $companyDecodedJson['document_name'];	
		$data['document_url'] = $companyDecodedJson['document_url'];	
		$data['document_size'] = $companyDecodedJson['document_size'];	
		$data['document_format'] = $companyDecodedJson['document_format'];	
		$data['companyIs_display'] = $companyDecodedJson['is_display'];	
		$data['companyIs_default'] = $companyDecodedJson['is_default'];	
		$data['companyCreated_at'] = $companyDecodedJson['created_at'];	
		$data['companyUpdated_at'] = $companyDecodedJson['updated_at'];	
		$data['companyState_abb'] = $companyDecodedJson['state_abb'];	
		$data['companyCity_id'] = $companyDecodedJson['city_id'];	
		$data['companyState_name'] = $companyDecodedJson['state_name'];	
		$data['companyCity_name'] = $companyDecodedJson['city_name'];
		
		$encodeData = json_encode($data);
		return $encodeData;
	}
}