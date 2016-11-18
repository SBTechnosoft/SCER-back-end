<?php
namespace ERP\Core\Settings\Templates\Entities;

use ERP\Core\Settings\Templates\Entities\Template;
use Carbon;
use ERP\Core\Companies\Services\CompanyService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends CompanyService
{
	public function getEncodedAllData($status)
	{
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$template = new Template();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$templateId[$decodedData] = $decodedJson[$decodedData]['template_id'];
			$templateName[$decodedData] = $decodedJson[$decodedData]['template_name'];
			$templateType[$decodedData] = $decodedJson[$decodedData]['template_type'];
			$templateBody[$decodedData] = $decodedJson[$decodedData]['template_body'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			//get the company detail from database
			$encodeDataClass = new EncodeAllData();
			$companyStatus[$decodedData] = $encodeDataClass->getCompanyData($companyId[$decodedData]);
			$companyDecodedJson[$decodedData] = json_decode($companyStatus[$decodedData],true);
			$companyId[$decodedData]= $companyDecodedJson[$decodedData]['companyId'];
			$companyName[$decodedData]= $companyDecodedJson[$decodedData]['companyName'];
			$companyIsDisplay[$decodedData]= $companyDecodedJson[$decodedData]['isDisplay'];
			$companyCreatedAt[$decodedData]= $companyDecodedJson[$decodedData]['createdAt'];
			$companyUpdatedAt[$decodedData]= $companyDecodedJson[$decodedData]['updatedAt'];
			$companyDispName[$decodedData]= $companyDecodedJson[$decodedData]['companyDisplayName'];
			$companyAddress1[$decodedData]= $companyDecodedJson[$decodedData]['address1'];
			$companyAddress2[$decodedData]= $companyDecodedJson[$decodedData]['address2'];
			$companyPincode[$decodedData]= $companyDecodedJson[$decodedData]['pincode'];
			$companyPanNo[$decodedData]= $companyDecodedJson[$decodedData]['pan'];
			$companyTinNo[$decodedData]= $companyDecodedJson[$decodedData]['tin'];
			$companyVatNo[$decodedData]= $companyDecodedJson[$decodedData]['vatNo'];
			$companyServiceTaxNo[$decodedData]= $companyDecodedJson[$decodedData]['serviceTaxNo'];
			$companybasicCurrencySymbol[$decodedData]= $companyDecodedJson[$decodedData]['basicCurrencySymbol'];
			$companyFormalName[$decodedData]= $companyDecodedJson[$decodedData]['formalName'];
			$companyNoOfDecimalPoints[$decodedData]= $companyDecodedJson[$decodedData]['noOfDecimalPoints'];
			$companyCurrencySymbol[$decodedData]= $companyDecodedJson[$decodedData]['currencySymbol'];
			$companyDocumentName[$decodedData]= $companyDecodedJson[$decodedData]['documentName'];
			$companyDocumentUrl[$decodedData]= $companyDecodedJson[$decodedData]['documentUrl'];
			$companyDocumentSize[$decodedData]= $companyDecodedJson[$decodedData]['documentSize'];
			$companyDocumentFormat[$decodedData]= $companyDecodedJson[$decodedData]['documentFormat'];
			$companyIsDefault[$decodedData]= $companyDecodedJson[$decodedData]['isDefault'];
			$companyStateAbb[$decodedData]= $companyDecodedJson[$decodedData]['state']['stateAbb'];
			$companyCityId[$decodedData]= $companyDecodedJson[$decodedData]['city']['cityId'];
			
			//date format conversion
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
		}
		$template->setCreated_at($convertedCreatedDate);
		$getCreatedDate = $template->getCreated_at();
		$template->setUpdated_at($convertedUpdatedDate);
		$getUpdatedDate = $template->getUpdated_at();
		
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'templateId'=>$templateId[$jsonData],
				'templateName' => $templateName[$jsonData],
				'templateType' => $templateType[$jsonData],
				'templateBody' => $templateBody[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				'company' => array(
					'companyId' => $companyId[$jsonData],
					'companyName' => $companyName[$jsonData],
					'isDisplay' => $companyIsDisplay[$jsonData],
					'createdAt' => $companyCreatedAt[$jsonData],
					'updatedAt' => $companyUpdatedAt[$jsonData],
					'companyDisplayName' => $companyDispName[$jsonData],
					'address1' => $companyAddress1[$jsonData],
					'address2' => $companyAddress2[$jsonData],
					'pincode' => $companyPincode[$jsonData],
					'pan' => $companyPanNo[$jsonData],
					'tin' => $companyTinNo[$jsonData],
					'vatNo' => $companyVatNo[$jsonData],
					'serviceTaxNo' => $companyServiceTaxNo[$jsonData],
					'baicCurrencySymbol' => $companybasicCurrencySymbol[$jsonData],
					'formalName' => $companyFormalName[$jsonData],
					'noOfDecimalPoints' => $companyNoOfDecimalPoints[$jsonData],
					'currencySymbol' => $companyCurrencySymbol[$jsonData],
					'documentName' => $companyDocumentName[$jsonData],
					'documentUrl' => $companyDocumentUrl[$jsonData],
					'documentSize' => $companyDocumentSize[$jsonData],
					'documentFormat' => $companyDocumentFormat[$jsonData],
					'isDefault' => $companyIsDefault[$jsonData],
					'stateAbb' => $companyStateAbb[$jsonData],
					'cityId' => $companyCityId[$jsonData]
				)
			);
		}
		return json_encode($data);
	}
}