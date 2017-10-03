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
class EncodeAllDraftData extends ClientService
{
	public function getEncodedAllData($status)
	{
		$constantClass = new ConstantClass();		
		$constantArray = $constantClass->constantVariable();
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$deocodedJsonData = json_decode($status,true);
		$bill = new Bill();
		for($decodedData=0;$decodedData<count($deocodedJsonData);$decodedData++)
		{
			$saleId[$decodedData] = $deocodedJsonData[$decodedData]['sale_id'];
			$productArray[$decodedData] = $deocodedJsonData[$decodedData]['product_array'];
			$paymentMode[$decodedData] = $deocodedJsonData[$decodedData]['payment_mode'];
			$bankName[$decodedData] = $deocodedJsonData[$decodedData]['bank_name'];
			$invoiceNumber[$decodedData] = $deocodedJsonData[$decodedData]['invoice_number'];
			$jobCardNumber[$decodedData] = $deocodedJsonData[$decodedData]['job_card_number'];
			$checkNumber[$decodedData] = $deocodedJsonData[$decodedData]['check_number'];
			$total[$decodedData] = $deocodedJsonData[$decodedData]['total'];
			$totalDiscounttype[$decodedData] = $deocodedJsonData[$decodedData]['total_discounttype'];
			$totalDiscount[$decodedData] = $deocodedJsonData[$decodedData]['total_discount'];
			$extraCharge[$decodedData] = $deocodedJsonData[$decodedData]['extra_charge'];
			$tax[$decodedData] = $deocodedJsonData[$decodedData]['tax'];
			$grandTotal[$decodedData] = $deocodedJsonData[$decodedData]['grand_total'];
			$advance[$decodedData] = $deocodedJsonData[$decodedData]['advance'];
			$balance[$decodedData] = $deocodedJsonData[$decodedData]['balance'];
			$remark[$decodedData] = $deocodedJsonData[$decodedData]['remark'];
			$refund[$decodedData] = $deocodedJsonData[$decodedData]['refund'];
			$entryDate[$decodedData] = $deocodedJsonData[$decodedData]['entry_date'];
			$clientId[$decodedData] = $deocodedJsonData[$decodedData]['client_id'];
			$jfId[$decodedData] = $deocodedJsonData[$decodedData]['jf_id'];
			$salesType[$decodedData] = $deocodedJsonData[$decodedData]['sales_type'];
			$companyId[$decodedData] = $deocodedJsonData[$decodedData]['company_id'];
			$createdAt[$decodedData] = $deocodedJsonData[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $deocodedJsonData[$decodedData]['updated_at'];
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
			$advance[$decodedData] = number_format($advance[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$balance[$decodedData] = number_format($balance[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
			$refund[$decodedData] = number_format($refund[$decodedData],$getCompanyDetails[$decodedData]['noOfDecimalPoints'],'.','');
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
		}
		$data = array();
		for($jsonData=0;$jsonData<count($deocodedJsonData);$jsonData++)
		{
			$clientData = json_decode($getClientDetails[$jsonData]);
			$data[$jsonData]= array(
				'saleId'=>$saleId[$jsonData],
				'productArray'=>$productArray[$jsonData],
				'paymentMode'=>$paymentMode[$jsonData],
				'bankName'=>$bankName[$jsonData],
				'invoiceNumber'=>$invoiceNumber[$jsonData],
				'jobCardNumber'=>$jobCardNumber[$jsonData],
				'checkNumber'=>$checkNumber[$jsonData],
				'total'=>$total[$jsonData],
				'totalDiscounttype'=>$totalDiscounttype[$jsonData],
				'totalDiscount'=>$totalDiscount[$jsonData],
				'extraCharge'=>$extraCharge[$jsonData],
				'tax'=>$tax[$jsonData],
				'grandTotal'=>$grandTotal[$jsonData],
				'advance'=>$advance[$jsonData],
				'balance'=>$balance[$jsonData],
				'remark'=>$remark[$jsonData],
				'salesType'=>$salesType[$jsonData],
				'refund'=>$refund[$jsonData],
				'jfId'=>$jfId[$jsonData],
				'createdAt'=>$getCreatedDate[$jsonData],
				'updatedAt'=>$getUpdatedDate[$jsonData],
				'entryDate'=>$getEntryDate[$jsonData],
				'client' => array(
					'clientId'=>$clientData->clientId,
					'clientName'=>$clientData->clientName,
					'companyName'=>$clientData->companyName,
					'contactNo'=>$clientData->contactNo,
					'emailId'=>$clientData->emailId,
					'address1'=>$clientData->address1,
					'isDisplay'=>$clientData->isDisplay,
					'createdAt'=>$clientData->createdAt,
					'updatedAt'=>$clientData->updatedAt,
					'professionId'=>$clientData->professionId,
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
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}