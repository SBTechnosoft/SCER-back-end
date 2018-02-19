<?php
namespace ERP\Core\Accounting\Quotations\Entities;

use ERP\Core\Accounting\Quotations\Entities\Quotation;
use ERP\Core\Clients\Services\ClientService;
use ERP\Core\Entities\CompanyDetail;
use ERP\Entities\Constants\ConstantClass;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeAllData extends ClientService
{
	public function getEncodedAllData($status)
	{
		$constantClass = new ConstantClass();		
		$constantArray = $constantClass->constantVariable();
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$decodedJson = json_decode($status,true);
		$deocodedJsonData = json_decode($decodedJson['quotationData']);
		$decodedDocumentData = json_decode($decodedJson['documentData']);
		$quotation = new Quotation();
		
		for($decodedData=0;$decodedData<count($deocodedJsonData);$decodedData++)
		{
			$quotationBillId[$decodedData] = $deocodedJsonData[$decodedData]->quotation_bill_id;
			$productArray[$decodedData] = $deocodedJsonData[$decodedData]->product_array;
			$quotationNumber[$decodedData] = $deocodedJsonData[$decodedData]->quotation_number;
			$total[$decodedData] = $deocodedJsonData[$decodedData]->total;
			$totalDiscounttype[$decodedData] = $deocodedJsonData[$decodedData]->total_discounttype;
			$totalDiscount[$decodedData] = $deocodedJsonData[$decodedData]->total_discount;
			$extraCharge[$decodedData] = $deocodedJsonData[$decodedData]->extra_charge;
			$tax[$decodedData] = $deocodedJsonData[$decodedData]->tax;
			$grandTotal[$decodedData] = $deocodedJsonData[$decodedData]->grand_total;
			$remark[$decodedData] = $deocodedJsonData[$decodedData]->remark;
			$entryDate[$decodedData] = $deocodedJsonData[$decodedData]->entry_date;
			$clientId[$decodedData] = $deocodedJsonData[$decodedData]->client_id;
			$jfId[$decodedData] = $deocodedJsonData[$decodedData]->jf_id;
			$companyId[$decodedData] = $deocodedJsonData[$decodedData]->company_id;
			$createdAt[$decodedData] = $deocodedJsonData[$decodedData]->created_at;
			$updatedAt[$decodedData] = $deocodedJsonData[$decodedData]->updated_at;

			//get the client detail from database
			$encodeAllData = new EncodeAllData();
			$getClientDetails[$decodedData] = $encodeAllData->getClientData($clientId[$decodedData]);

			//get the company detail from database
			$companyDetail  = new CompanyDetail();
			$getCompanyDetails[$decodedData] = $companyDetail->getCompanyDetails($companyId[$decodedData]);
			
			//convert amount(round) into their company's selected decimal points
			$total[$decodedData] = number_format($total[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$totalDiscount[$decodedData] = number_format($totalDiscount[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$tax[$decodedData] = number_format($tax[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			
			//date format conversion
			$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$quotation->setCreated_at($convertedCreatedDate);
			$getCreatedDate[$decodedData] = $quotation->getCreated_at();
			if(strcmp($updatedAt[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getUpdatedDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				$quotation->setUpdated_at($convertedUpdatedDate);
				$getUpdatedDate[$decodedData] = $quotation->getUpdated_at();
			}
			if(strcmp($entryDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getEntryDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedEntryDate = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');
				$quotation->setEntryDate($convertedEntryDate);
				$getEntryDate[$decodedData] = $quotation->getEntryDate();
			}
			$documentId[$decodedData] = array();
			$documentQuotationId[$decodedData] = array();
			$documentName[$decodedData] = array();
			$documentSize[$decodedData] = array();
			$documentFormat[$decodedData] = array();
			$documentType[$decodedData] = array();
			$documentCreatedAt[$decodedData] = array();
			$documentUpdatedAt[$decodedData] = array();
			$getDocumentCreatedDate[$decodedData] = array();
			$getDocumentUpdatedDate[$decodedData] = array();
			
			//get document data
			for($documentArray=0;$documentArray<count($decodedDocumentData[$decodedData]);$documentArray++)
			{
				$documentId[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->document_id;
				$documentQuotationId[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->quotation_bill_id;
				$documentName[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->document_name;
				$documentSize[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->document_size;
				$documentFormat[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->document_format;
				$documentType[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->document_type;
				$documentCreatedAt[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->created_at;
				$documentUpdatedAt[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->updated_at;
			
				//date format conversion
				if(strcmp($documentCreatedAt[$decodedData][$documentArray],'0000-00-00 00:00:00')==0)
				{
					$getDocumentCreatedDate[$decodedData][$documentArray] = "00-00-0000";
				}
				else
				{
					$documentCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $documentCreatedAt[$decodedData][$documentArray])->format('d-m-Y');
					$quotation->setCreated_at($documentCreatedDate);
					$getDocumentCreatedDate[$decodedData][$documentArray] = $quotation->getCreated_at();
				}
				if(strcmp($documentUpdatedAt[$decodedData][$documentArray],'0000-00-00 00:00:00')==0)
				{
					$getDocumentUpdatedDate[$decodedData][$documentArray] = "00-00-0000";
				}
				else
				{
					$documentUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $documentUpdatedAt[$decodedData][$documentArray])->format('d-m-Y');
					$quotation->setUpdated_at($documentUpdatedDate);
					$getDocumentUpdatedDate[$decodedData][$documentArray] = $quotation->getUpdated_at();
				}
			}
		}
		$documentData = array();
		$innerArrayData = array();
		$arrayData = array();
		$data = array();
		for($jsonData=0;$jsonData<count($deocodedJsonData);$jsonData++)
		{
			$arrayData[$jsonData] = array();
			for($innerArrayData=0;$innerArrayData<count($decodedDocumentData[$jsonData]);$innerArrayData++)
			{
				if(strcmp($documentFormat[$jsonData][$innerArrayData],"pdf")==0)
				{
					$arrayData[$jsonData][$innerArrayData] = array(
						'documentId'=>$documentId[$jsonData][$innerArrayData],
						'quotationBillId'=>$documentQuotationId[$jsonData][$innerArrayData],
						'documentName'=>$documentName[$jsonData][$innerArrayData],
						'documentSize'=>$documentSize[$jsonData][$innerArrayData],
						'documentFormat'=>$documentFormat[$jsonData][$innerArrayData],
						'documentType'=>$documentType[$jsonData][$innerArrayData],
						'documentUrl'=>$constantArray['billUrl'],
						'createdAt'=>$getDocumentCreatedDate[$jsonData][$innerArrayData],
						'updatedAt'=>$getDocumentUpdatedDate[$jsonData][$innerArrayData]
					);
				}	
				else
				{
					$arrayData[$jsonData][$innerArrayData] = array(
						'documentId'=>$documentId[$jsonData][$innerArrayData],
						'quotationBillId'=>$documentQuotationId[$jsonData][$innerArrayData],
						'documentName'=>$documentName[$jsonData][$innerArrayData],
						'documentSize'=>$documentSize[$jsonData][$innerArrayData],
						'documentFormat'=>$documentFormat[$jsonData][$innerArrayData],
						'documentType'=>$documentType[$jsonData][$innerArrayData],
						'documentUrl'=>$constantArray['billDocumentUrl'],
						'createdAt'=>$getDocumentCreatedDate[$jsonData][$innerArrayData],
						'updatedAt'=>$getDocumentUpdatedDate[$jsonData][$innerArrayData]
					);
				}
			}
			$clientData = json_decode($getClientDetails[$jsonData]);
			$data[$jsonData]= array(
				'quotationBillId'=>$quotationBillId[$jsonData],
				'productArray'=>$productArray[$jsonData],
				'quotationNumber'=>$quotationNumber[$jsonData],
				'total'=>$total[$jsonData],
				'totalDiscounttype'=>$totalDiscounttype[$jsonData],
				'totalDiscount'=>$totalDiscount[$jsonData],
				'extraCharge'=>$extraCharge[$jsonData],
				'tax'=>$tax[$jsonData],
				'grandTotal'=>$grandTotal[$jsonData],
				'remark'=>$remark[$jsonData],
				'jfId'=>$jfId[$jsonData],
				'createdAt'=>$getCreatedDate[$jsonData],
				'updatedAt'=>$getUpdatedDate[$jsonData],
				'entryDate'=>$getEntryDate[$jsonData],
				'client' => array(
					'clientId'=>$clientData->clientId,
					'clientName'=>$clientData->clientName,
					'companyName'=>$clientData->companyName,
					'contactNo'=>$clientData->contactNo,
					'contactNo1'=>$clientData->contactNo1,
					'emailId'=>$clientData->emailId,
					'professionId'=>$clientData->professionId,
					'address1'=>$clientData->address1,
					'isDisplay'=>$clientData->isDisplay,
					'createdAt'=>$clientData->createdAt,
					'updatedAt'=>$clientData->updatedAt,
					'stateAbb'=>$clientData->state->stateAbb,
					'cityId'=>$clientData->city->cityId
				),
				'company' => array(	
					'companyId' => $getCompanyDetails[$jsonData]['companyId'],
					'companyName' => $getCompanyDetails[$jsonData]['companyName'],	
					'companyDisplayName' => $getCompanyDetails[$jsonData]['companyDisplayName'],	
					'address1' => $getCompanyDetails[$jsonData]['address1'],	
					'address2'=> $getCompanyDetails[$jsonData]['address2'],	
					'pincode' => $getCompanyDetails[$jsonData]['pincode'],	
					'pan' => $getCompanyDetails[$jsonData]['pan'],	
					'tin'=> $getCompanyDetails[$jsonData]['tin'],	
					'cgst'=> $getCompanyDetails[$jsonData]['cgst'],	
					'sgst'=> $getCompanyDetails[$jsonData]['sgst'],	
					'vatNo' => $getCompanyDetails[$jsonData]['vatNo'],	
					'serviceTaxNo' => $getCompanyDetails[$jsonData]['serviceTaxNo'],	
					'basicCurrencySymbol' => $getCompanyDetails[$jsonData]['basicCurrencySymbol'],	
					'formalName' => $getCompanyDetails[$jsonData]['formalName'],	
					'noOfDecimalPoints' => $getCompanyDetails[$jsonData]['noOfDecimalPoints'],	
					'currencySymbol' => $getCompanyDetails[$jsonData]['currencySymbol'],	
					'logo'=> array(
						'documentName' => $getCompanyDetails[$jsonData]['logo']['documentName'],
						'documentUrl' => $getCompanyDetails[$jsonData]['logo']['documentUrl'],	
						'documentSize' =>$getCompanyDetails[$jsonData]['logo']['documentSize'],	
						'documentFormat' => $getCompanyDetails[$jsonData]['logo']['documentFormat']
					),
					'isDisplay' => $getCompanyDetails[$jsonData]['isDisplay'],	
					'isDefault' => $getCompanyDetails[$jsonData]['isDefault'],
					'createdAt' => $getCompanyDetails[$jsonData]['createdAt'],
					'updatedAt' => $getCompanyDetails[$jsonData]['updatedAt'],
					'stateAbb' => $getCompanyDetails[$jsonData]['state']['stateAbb'],
					'cityId' => $getCompanyDetails[$jsonData]['city']['cityId']	
				)		
			);
			$data[$jsonData]['file'] = $arrayData[$jsonData];
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}