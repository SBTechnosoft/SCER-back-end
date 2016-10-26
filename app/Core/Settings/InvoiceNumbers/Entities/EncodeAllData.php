<?php
namespace ERP\Core\Settings\InvoiceNumbers\Entities;

use ERP\Core\Settings\InvoiceNumbers\Entities\InvoiceNumber;
use ERP\Core\Companies\Services\CompanyService;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends CompanyService
{
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$invoice = new InvoiceNumber();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$invoiceId[$decodedData] = $decodedJson[$decodedData]['invoice_id'];
			$invoiceLabel[$decodedData] = $decodedJson[$decodedData]['invoice_label'];
			$invoiceType[$decodedData] = $decodedJson[$decodedData]['invoice_type'];
			$startAt[$decodedData] = $decodedJson[$decodedData]['start_at'];
			$endAt[$decodedData] = $decodedJson[$decodedData]['end_at'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			//get the company detail from database
			$encodeDataClass = new EncodeAllData();
			$companyStatus[$decodedData] = $encodeDataClass->getCompanyData($companyId[$decodedData]);
			$companyDecodedJson[$decodedData] = json_decode($companyStatus[$decodedData],true);
			$companyName[$decodedData]= $companyDecodedJson[$decodedData]['company_name'];
			$companyIsDisplay[$decodedData]= $companyDecodedJson[$decodedData]['is_display'];
			$companyCreatedAt[$decodedData]= $companyDecodedJson[$decodedData]['created_at'];
			$companyUpdatedAt[$decodedData]= $companyDecodedJson[$decodedData]['updated_at'];
			$companyDispName[$decodedData]= $companyDecodedJson[$decodedData]['company_display_name'];
			$companyAddress1[$decodedData]= $companyDecodedJson[$decodedData]['address1'];
			$companyAddress2[$decodedData]= $companyDecodedJson[$decodedData]['address2'];
			$companyPincode[$decodedData]= $companyDecodedJson[$decodedData]['pincode'];
			$companyPanNo[$decodedData]= $companyDecodedJson[$decodedData]['pan'];
			$companyTinNo[$decodedData]= $companyDecodedJson[$decodedData]['tin'];
			$companyVatNo[$decodedData]= $companyDecodedJson[$decodedData]['vat_no'];
			$companyServiceTaxNo[$decodedData]= $companyDecodedJson[$decodedData]['service_tax_no'];
			$companybasicCurrencySymbol[$decodedData]= $companyDecodedJson[$decodedData]['basic_currency_symbol'];
			$companyFormalName[$decodedData]= $companyDecodedJson[$decodedData]['formal_name'];
			$companyNoOfDecimalPoints[$decodedData]= $companyDecodedJson[$decodedData]['no_of_decimal_points'];
			$companyCurrencySymbol[$decodedData]= $companyDecodedJson[$decodedData]['currency_symbol'];
			$companyDocumentName[$decodedData]= $companyDecodedJson[$decodedData]['document_name'];
			$companyDocumentUrl[$decodedData]= $companyDecodedJson[$decodedData]['document_url'];
			$companyDocumentSize[$decodedData]= $companyDecodedJson[$decodedData]['document_size'];
			$companyDocumentFormat[$decodedData]= $companyDecodedJson[$decodedData]['document_format'];
			$companyIsDefault[$decodedData]= $companyDecodedJson[$decodedData]['is_default'];
			$companyStateAbb[$decodedData]= $companyDecodedJson[$decodedData]['state_abb'];
			$companyCityId[$decodedData]= $companyDecodedJson[$decodedData]['city_id'];
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
		}
		$invoice->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $invoice->getCreated_at();
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'invoice_id'=>$invoiceId[$jsonData],
				'invoice_label' => $invoiceLabel[$jsonData],
				'invoice_type' => $invoiceType[$jsonData],
				'start_at' => $startAt[$jsonData],
				'end_at'=> $endAt[$jsonData],
				'created_at' => $getCreatedDate[$jsonData],
				'company_id' => $companyId[$jsonData],
				
				'company_id' => array(
					'company_name' => $companyName[$jsonData],
					'is_display' => $companyIsDisplay[$jsonData],
					'created_at' => $companyCreatedAt[$jsonData],
					'updated_at' => $companyUpdatedAt[$jsonData],
					'company_display_name' => $companyDispName[$jsonData],
					'address1' => $companyAddress1[$jsonData],
					'address2' => $companyAddress2[$jsonData],
					'pincode' => $companyPincode[$jsonData],
					'pan_no' => $companyPanNo[$jsonData],
					'tin_no' => $companyTinNo[$jsonData],
					'service_tax_no' => $companyServiceTaxNo[$jsonData],
					'baic_currency_symbol' => $companybasicCurrencySymbol[$jsonData],
					'formal_name' => $companyFormalName[$jsonData],
					'no_of_decimal_points' => $companyNoOfDecimalPoints[$jsonData],
					'currency_symbol' => $companyCurrencySymbol[$jsonData],
					'document_name' => $companyDocumentName[$jsonData],
					'document_url' => $companyDocumentUrl[$jsonData],
					'document_size' => $companyDocumentSize[$jsonData],
					'document_format' => $companyDocumentFormat[$jsonData],
					'is_default' => $companyIsDefault[$jsonData],
					'state_abb' => $companyStateAbb[$jsonData],
					'city_id' => $companyCityId[$jsonData]
				)
			);
		}
		return json_encode($data);
	}
}