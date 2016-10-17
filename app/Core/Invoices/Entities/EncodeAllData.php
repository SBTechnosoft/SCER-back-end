<?php
namespace ERP\Core\Invoices\Entities;

use ERP\Core\Invoices\Entities\Invoice;
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
		$invoice = new Invoice();
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
				
				'company_name' => $companyName[$jsonData],
				'companyIs_display' => $companyIsDisplay[$jsonData],
				'companyCreated_at' => $companyCreatedAt[$jsonData],
				'companyUpdated_at' => $companyUpdatedAt[$jsonData],
				'company_display_name' => $companyDispName[$jsonData],
				'compantAddress1' => $companyAddress1[$jsonData],
				'companyAddress2' => $companyAddress2[$jsonData],
				'companyPincode' => $companyPincode[$jsonData],
				'companyPan_no' => $companyPanNo[$jsonData],
				'companyTin_no' => $companyTinNo[$jsonData],
				'companyService_tax_no' => $companyServiceTaxNo[$jsonData],
				'companyBaic_currency_symbol' => $companybasicCurrencySymbol[$jsonData],
				'companyFormal_name' => $companyFormalName[$jsonData],
				'companyNo_of_decimal_points' => $companyNoOfDecimalPoints[$jsonData],
				'companyCurrency_symbol' => $companyCurrencySymbol[$jsonData],
				'companyDocument_name' => $companyDocumentName[$jsonData],
				'companyDocument_url' => $companyDocumentUrl[$jsonData],
				'companyDocument_size' => $companyDocumentSize[$jsonData],
				'companyDocument_format' => $companyDocumentFormat[$jsonData],
				'companyIs_default' => $companyIsDefault[$jsonData],
				'companyState_abb' => $companyStateAbb[$jsonData],
				'companyCity_id' => $companyCityId[$jsonData],
			);
		}
		return json_encode($data);
	}
}