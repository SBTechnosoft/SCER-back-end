<?php
namespace ERP\Core\Accounting\Bills\Entities;

use ERP\Core\Accounting\Bills\Entities\Bill;
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
		$deocodedJsonData = json_decode($decodedJson['salesData']);
		$decodedDocumentData = json_decode($decodedJson['documentData']);
		$bill = new Bill();
		
		for($decodedData=0;$decodedData<count($deocodedJsonData);$decodedData++)
		{
			$saleId[$decodedData] = $deocodedJsonData[$decodedData]->sale_id;
			$productArray[$decodedData] = $deocodedJsonData[$decodedData]->product_array;
			$paymentMode[$decodedData] = $deocodedJsonData[$decodedData]->payment_mode;
			$bankName[$decodedData] = $deocodedJsonData[$decodedData]->bank_name;
			$invoiceNumber[$decodedData] = $deocodedJsonData[$decodedData]->invoice_number;
			$checkNumber[$decodedData] = $deocodedJsonData[$decodedData]->check_number;
			$total[$decodedData] = $deocodedJsonData[$decodedData]->total;
			$tax[$decodedData] = $deocodedJsonData[$decodedData]->tax;
			$grandTotal[$decodedData] = $deocodedJsonData[$decodedData]->grand_total;
			$advance[$decodedData] = $deocodedJsonData[$decodedData]->advance;
			$balance[$decodedData] = $deocodedJsonData[$decodedData]->balance;
			$remark[$decodedData] = $deocodedJsonData[$decodedData]->remark;
			$entryDate[$decodedData] = $deocodedJsonData[$decodedData]->entry_date;
			$clientId[$decodedData] = $deocodedJsonData[$decodedData]->client_id;
			$jfId[$decodedData] = $deocodedJsonData[$decodedData]->jf_id;
			$salesType[$decodedData] = $deocodedJsonData[$decodedData]->sales_type;
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
			$total[$decodedData] = round($total[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$tax[$decodedData] = round($tax[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$grandTotal[$decodedData] = round($grandTotal[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$advance[$decodedData] = round($advance[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			$balance[$decodedData] = round($balance[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints']);
			
			//date format conversion
			$convertedCreatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$bill->setCreated_at($convertedCreatedDate);
			$getCreatedDate[$decodedData] = $bill->getCreated_at();
			if(strcmp($updatedAt[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getUpdatedDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
				$bill->setUpdated_at($convertedUpdatedDate);
				$getUpdatedDate[$decodedData] = $bill->getUpdated_at();
			}
			if(strcmp($entryDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$getEntryDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedEntryDate = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');
				$bill->setEntryDate($convertedEntryDate);
				$getEntryDate[$decodedData] = $bill->getEntryDate();
			}
			$documentId[$decodedData] = array();
			$documentSaleId[$decodedData] = array();
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
				$documentSaleId[$decodedData][$documentArray] = $decodedDocumentData[$decodedData][$documentArray]->sale_id;
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
					$bill->setCreated_at($documentCreatedDate);
					$getDocumentCreatedDate[$decodedData][$documentArray] = $bill->getCreated_at();
				}
				if(strcmp($documentUpdatedAt[$decodedData][$documentArray],'0000-00-00 00:00:00')==0)
				{
					$getDocumentUpdatedDate[$decodedData][$documentArray] = "00-00-0000";
				}
				else
				{
					$documentUpdatedDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $documentUpdatedAt[$decodedData][$documentArray])->format('d-m-Y');
					$bill->setUpdated_at($documentUpdatedDate);
					$getDocumentUpdatedDate[$decodedData][$documentArray] = $bill->getUpdated_at();
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
						'saleId'=>$documentSaleId[$jsonData][$innerArrayData],
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
						'saleId'=>$documentSaleId[$jsonData][$innerArrayData],
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
				'saleId'=>$saleId[$jsonData],
				'productArray'=>$productArray[$jsonData],
				'paymentMode'=>$paymentMode[$jsonData],
				'bankName'=>$bankName[$jsonData],
				'invoiceNumber'=>$invoiceNumber[$jsonData],
				'checkNumber'=>$checkNumber[$jsonData],
				'total'=>$total[$jsonData],
				'tax'=>$tax[$jsonData],
				'grandTotal'=>$grandTotal[$jsonData],
				'advance'=>$advance[$jsonData],
				'balance'=>$balance[$jsonData],
				'remark'=>$remark[$jsonData],
				'salesType'=>$salesType[$jsonData],
				'jfId'=>$jfId[$jsonData],
				'createdAt'=>$getCreatedDate[$jsonData],
				'updatedAt'=>$getUpdatedDate[$jsonData],
				'entryDate'=>$getEntryDate[$jsonData],
				'client' => array(
					'clientId'=>$clientData->clientId,
					'clientName'=>$clientData->clientName,
					'companyName'=>$clientData->companyName,
					'contactNo'=>$clientData->contactNo,
					'workNo'=>$clientData->workNo,
					'emailId'=>$clientData->emailId,
					'address1'=>$clientData->address1,
					'address2'=>$clientData->address2,
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