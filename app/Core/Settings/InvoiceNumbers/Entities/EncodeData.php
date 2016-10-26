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
		
		$data['company_id']= array(
			'company_id' => $companyDecodedJson['company_id'],	
			'company_name' => $companyDecodedJson['company_name'],
			'company_display_name' => $companyDecodedJson['company_display_name'],	
			'address1' => $companyDecodedJson['address1'],	
			'address2' => $companyDecodedJson['address2'],
			'pincode'=> $companyDecodedJson['pincode'],	
			'pan' => $companyDecodedJson['pan'],	
			'tin' => $companyDecodedJson['tin'],	
			'vat_no' => $companyDecodedJson['vat_no'],
			'service_tax_no' => $companyDecodedJson['service_tax_no'],	
			'basic_currency_symbol'=> $companyDecodedJson['basic_currency_symbol'],
			'formal_name'=> $companyDecodedJson['formal_name'],
			'no_of_decimal_points' => $companyDecodedJson['no_of_decimal_points'],	
			'currency_symbol' => $companyDecodedJson['currency_symbol'],
			'document_name'=> $companyDecodedJson['document_name'],
			'document_url' => $companyDecodedJson['document_url'],
			'document_size' => $companyDecodedJson['document_size'],	
			'document_format' => $companyDecodedJson['document_format'],	
			'is_display' => $companyDecodedJson['is_display'],	
			'is_default' => $companyDecodedJson['is_default'],	
			'created_at' => $companyDecodedJson['created_at'],	
			'updated_at' => $companyDecodedJson['updated_at'],	
			'state_abb' => $companyDecodedJson['state_abb'],
			'city_id' => $companyDecodedJson['city_id'],
			'state_name' => $companyDecodedJson['state_name'],	
			'city_name' => $companyDecodedJson['city_name']
		);
		$encodeData = json_encode($data);
		return $encodeData;
	}
}