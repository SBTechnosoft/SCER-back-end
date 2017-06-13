<?php
namespace ERP\Core\Documents\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Accounting\Bills\BillModel;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Products\Services\ProductService;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Container\Container;
use ERP\Core\Documents\Entities\CurrencyToWordConversion;
use PHPMailer;
use SMTP;
use ERP\Model\Accounting\Quotations\QuotationModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentMpdf extends CurrencyToWordConversion
{
	/**
	* pdf generation and mail-sms send
	* @param template-data and bill data
	* @return error-message/document-path
	*/
	public function mpdfGenerate($templateData,$status,$headerData,$emailTemplateData,$blankTemplateData,$smsTemplateData)
	{		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(array_key_exists("operation",$headerData))
		{
			if(strcmp($headerData['operation'][0],'preprint')==0)
			{
				$printHtmlBody = json_decode($blankTemplateData)[0]->templateBody;
				$htmlBody = json_decode($templateData)[0]->templateBody;
			}
		}
		else
		{
			$htmlBody = json_decode($templateData)[0]->templateBody;
		}
		$decodedBillData = json_decode($status);
		if(is_object($decodedBillData))
		{
			$saleId = $decodedBillData->saleId;		
		}
		else
		{
			$saleId = $decodedBillData[0]->sale_id;
			$decodedBillData = $decodedBillData[0];
		}
		$decodedArray = json_decode($decodedBillData->productArray);
		$productService = new ProductService();
		
		$productData = array();
		$decodedData = array();
		$index=1;
	
		$output="";
		$totalAmount =0;
		$totalVatValue=0;
		$totalAdditionalTax=0;
		$totalQty=0;
		if(strcmp($decodedBillData->salesType,"retail_sales")==0)
		{
			$totalCm = 10.4;
			for($productArray=0;$productArray<count($decodedArray->inventory);$productArray++)
			{
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				
				//calculate margin value
				$marginValue[$productArray]=($decodedData[$productArray]->margin/100)*$decodedArray->inventory[$productArray]->price;
				$marginValue[$productArray] = $marginValue[$productArray]+$decodedData[$productArray]->marginFlat;
				
				$totalPrice = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				if(strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0)
				{
					$discountValue[$productArray] = $decodedArray->inventory[$productArray]->discount;

				}
				else
				{
					$discountValue[$productArray] = ($decodedArray->inventory[$productArray]->discount/100)*$totalPrice;
				}
				$finalVatValue = $totalPrice - $discountValue[$productArray];
				
				//calculate vat value;
				$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*$finalVatValue;
				
				//calculate additional tax
				$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
				$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray] +$additionalTaxValue[$productArray];
				
				$price = number_format($decodedArray->inventory[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
				$trClose = "</td></tr>";
				if($productArray==0)
				{
					$output =$output.$trClose;
				}
				if(empty($decodedArray->inventory[$productArray]->color))
				{
					$decodedArray->inventory[$productArray]->color="";
				}
				if(empty($decodedArray->inventory[$productArray]->frameNo))
				{
					$decodedArray->inventory[$productArray]->frameNo="";
				}
				
				$totalVatValue = $totalVatValue+$vatValue[$productArray];
			    $totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray];
			    $totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
				$totalAmount=$totalAmount+$total[$productArray];
				
				//convert (number_format)as per company's selected decimal points
				$vatValue[$productArray] = number_format($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$additionalTaxValue[$productArray] = number_format($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$total[$productArray] = number_format($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
				
				$output =$output."".
					'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
				   <td class="tg-m36b thsrno" style="font-size: 14px; height: 0.7cm; text-align:center; padding:0 0 0 0;">'.$index.'</td>
				   <td class="tg-m36b theqp" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->color.' | '.$decodedArray->inventory[$productArray]->size.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->frameNo.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->qty.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $price.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $discountValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedData[$productArray]->vat.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 14px; height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$vatValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$decodedData[$productArray]->additionalTax.'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$additionalTaxValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$total[$productArray];

				if($productArray != count($decodedArray->inventory)-1)
				{
					$output = $output.$trClose;
				
				}
				if($productArray==(count($decodedArray->inventory)-1))
				{
					$totalProductSpace = $index*0.7;	
					
					$finalProductBlankSpace = $totalCm-$totalProductSpace;
					$output =$output."<tr class='trhw' style='font-family: Calibri; text-align: left; height:  ".$finalProductBlankSpace."cm;background-color: transparent;'>
				   <td class='tg-m36b thsrno' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;'></td>
				   <td class='tg-m36b theqp' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;   height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px; height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;   height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td></tr>";

				}
				$index++;
			}
		}
		else
		{
			$totalCm = 10.4;
			for($productArray=0;$productArray<count($decodedArray->inventory);$productArray++)
			{
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				
				$marginPrice[$productArray] = ($decodedData[$productArray]->wholesaleMargin/100)*$decodedArray->inventory[$productArray]->price;
				$marginPrice[$productArray] = $marginPrice[$productArray]+$decodedData[$productArray]->wholesaleMarginFlat;
				
				$totalPrice[$productArray] = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				
				if(strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0)
				{
					$discountValue[$productArray] = $decodedArray->inventory[$productArray]->discount;
				}
				else
				{
					$discountValue[$productArray] = ($decodedArray->inventory[$productArray]->discount/100)*$totalPrice[$productArray];
				}
				
				$finalVatValue = $totalPrice[$productArray]-$discountValue[$productArray];
				
				//calculate vat value;
				$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*$finalVatValue;
				
				//calculate additional tax
				$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
				
				$total[$productArray] = $finalVatValue+$vatValue[$productArray]+$additionalTaxValue[$productArray];
				$trClose = "</td></tr>";
				if($productArray==0)
				{
					$output =$output.$trClose;
				}
				if(empty($decodedArray->inventory[$productArray]->color))
				{
					$decodedArray->inventory[$productArray]->color="";
				}
				if(empty($decodedArray->inventory[$productArray]->frameNo))
				{
					$decodedArray->inventory[$productArray]->frameNo="";
				}
				
				$totalAmount=$totalAmount+$total[$productArray];
				$totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray]+$vatValue[$productArray];
				$totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
				
				// convert (number_format)as per company's selected decimal points
				$totalPrice[$productArray] = number_format($totalPrice[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$vatValue[$productArray] = number_format($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$additionalTaxValue[$productArray] = number_format($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);				
				$total[$productArray] = number_format($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$price = number_format($decodedArray->inventory[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints);
				
				$output =$output."".'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
				   <td class="tg-m36b thsrno" style="font-size: 14px; height: 0.7cm; text-align:center; padding:0 0 0 0;">'.$index.'</td>
				   <td class="tg-m36b theqp" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->color.' | '.$decodedArray->inventory[$productArray]->size.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->frameNo.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->qty.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $price.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $discountValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedData[$productArray]->vat.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 14px; height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$vatValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$decodedData[$productArray]->additionalTax.'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$additionalTaxValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$total[$productArray];
                				   
				if($productArray != count($decodedArray->inventory)-1)
				{
					$output = $output.$trClose;
				
				}
				if($productArray==(count($decodedArray->inventory)-1))
				{
					$totalProductSpace = $index*0.7;	
					
					$finalProductBlankSpace = $totalCm-$totalProductSpace;
					$output =$output."<tr class='trhw' style='font-family: Calibri; text-align: left; height:  ".$finalProductBlankSpace."cm;background-color: transparent;'>
				   <td class='tg-m36b thsrno' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;'></td>
				   <td class='tg-m36b theqp' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;   height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px; height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;   height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td></tr>";

				}
				$index++;
		    }    
		}
		
		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($totalAmount);
		$address = $decodedBillData->client->address1;
		$companyAddress = $decodedBillData->company->address1.",".$decodedBillData->company->address2;
		if(strcmp($decodedBillData->salesType,"retail_sales")==0)
		{
			$typeSale = "RETAIL";
		}
		else
		{
			$typeSale = "TAX";

		}
		//add 1 month in entry date for displaying expiry date
		$date = date_create($decodedBillData->entryDate);
		date_add($date, date_interval_create_from_date_string('30 days'));
		$expiryDate = date_format($date, 'd-m-Y');
		$totalTax = $totalVatValue+$totalAdditionalTax;
		
		// convert amount(number_format) into their company's selected decimal points
		$totalTax = number_format($totalTax,$decodedData[0]->company->noOfDecimalPoints,'.','');
		$totalAmount = number_format($totalAmount,$decodedData[0]->company->noOfDecimalPoints,'.','');
				
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$decodedBillData->client->clientName;
		$billArray['Company']="<span style='font-family: algerian;font-size:22px'>".$decodedBillData->company->companyName."</span>";
		$billArray['Total']=$totalAmount;
		$billArray['Mobile']=$decodedBillData->client->contactNo;
		$billArray['INVID']=$decodedBillData->invoiceNumber;
		$billArray['CLIENTADD']=$address;
		$billArray['OrderDate']=$decodedBillData->entryDate;
		$billArray['REMAINAMT']=$decodedBillData->balance;
		$billArray['TotalTax']=$totalTax;
		$billArray['TotalQty']=$totalQty;
		$billArray['TotalInWord']=$currencyResult;
		$billArray['displayNone']='none';
		$billArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png'/>";
		$billArray['CompanyAdd']=$companyAddress;
		$billArray['CreditCashMemo']="CASH";
		$billArray['RetailOrTax']=$typeSale;
		$billArray['ExpireDate']=$expiryDate;
		$billArray['CompanySGST']=$decodedBillData->company->sgst;
		$billArray['CompanyCGST']=$decodedBillData->company->cgst;
		$billArray['CLIENTTINNO']="";
		
		$mpdf = new mPDF('A4','landscape');
		// $mpdf = new mPDF('','A4','','agency','0','0','0','0','0','0','landscape');
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		$mpdf->WriteHTML($htmlBody);
		$path = $constantArray['billUrl'];
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		$documentPathName = $path.$documentName;
		$documentFormat="pdf";
		$documentType ="bill";
		
		//pdf generate
		$mpdf->Output($documentPathName,'F');
				
		//insertion bill document data into database
		$billModel = new BillModel();
		$billDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);
		if(array_key_exists("operation",$headerData))
		{
			if(strcmp($headerData['operation'][0],'preprint')==0)
			{
				$printMpdf = new mPDF('','A4','','agency','0','0','0','0','0','0','landscape');
				$printMpdf->SetDisplayMode('fullpage');
				foreach($billArray as $key => $value)
				{
					$printHtmlBody = str_replace('['.$key.']', $value, $printHtmlBody);
				}
				$printMpdf->WriteHTML($printHtmlBody);
		
				//change the name of document-name
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999)."_preprint.pdf";
				$documentPreprintPathName = $path.$documentName;
				$documentFormat="pdf";
				$documentType ="preprint-bill";

				$preprintBillDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);
				
				//pdf generate
				$printMpdf->Output($documentPreprintPathName,'F');
			}
		}
		if(strcmp($exceptionArray['500'],$billDocumentStatus)==0)
		{
			return $billDocumentStatus;
		}
		else
		{
			if($decodedBillData->client->emailId!="")
			{
				// mail send
				// $result = $this->mailSending($decodedBillData->client->emailId,$documentPathName,$emailTemplateData,$decodedBillData->client->clientName,$decodedBillData->company->companyName);
				// if(strcmp($result,$exceptionArray['Email'])==0)
				// {
					// return $result;
				// }
			}
			
			//sms send
			// if($decodedBillData->client->contactNo!=0 || $decodedBillData->client->contactNo!="")
			// {
				// if($decodedBillData->company->companyId==9)
				// {
					// $smsTemplateBody = json_decode($smsTemplateData)[0]->templateBody;
					// $smsArray = array();
					// $smsArray['ClientName'] = $decodedBillData->client->clientName;
					// foreach($smsArray as $key => $value)
					// {
						// $smsHtmlBody = str_replace('['.$key.']', $value, $smsTemplateBody);
					// }
					// replace 'p' tag
					// $smsHtmlBody = str_replace('<p>','', $smsHtmlBody);
					// $smsHtmlBody = str_replace('</p>','', $smsHtmlBody);
					// $data = array(
						// 'user' => "siliconbrain",
						// 'password' => "demo54321",
						// 'msisdn' => $decodedBillData->client->contactNo,
						// 'sid' => "ERPJSC",
						// 'msg' => $smsHtmlBody,
						// 'fl' =>"0",
						// 'gwid'=>"2"
					// );
					// list($header,$content) = $this->postRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
				// }
			// }
			// print_r($url);
			if(array_key_exists("operation",$headerData))
			{
				if(strcmp($headerData['operation'][0],'preprint')==0)
				{
					$pathArray = array();
					$pathArray['documentPath'] = $documentPathName;
					$pathArray['preprintDocumentPath'] = $documentPreprintPathName;
					return $pathArray;
				}
			}
			else
			{
				$pathArray = array();
				$pathArray['documentPath'] = $documentPathName;
				return $pathArray;

			}

			
		}	
	} 
	
	/**
     * pdf generation and mail-sms send
     * @param template-data and bill data
     * @return error-message/document-path
     */
	public function mpdfPaymentGenerate($templateData,$status,$emailTemplateData,$blankTemplateData,$smsTemplateData)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$htmlBody = json_decode($templateData)[0]->templateBody;
		$decodedBillData = json_decode($status);
		
		$billModel = new BillModel();
		if(is_object($decodedBillData))
		{
			$saleId = $decodedBillData->saleId;		
		}
		else
		{
			$saleId = $decodedBillData[0]->sale_id;
			$decodedBillData = $decodedBillData[0];
		}
		
		//get last 2 records of bill from bill_transaction
		$transactionResult = $billModel->getTransactionData($saleId);
		if(strcmp($transactionResult[0]->payment_trn,"refund")==0)
		{
			$amount = $transactionResult[0]->refund-$transactionResult[1]->refund;
		}	
		else if(strcmp($transactionResult[0]->payment_trn,"payment")==0)
		{
			$amount = $transactionResult[0]->advance-$transactionResult[1]->advance;
		}
		
		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($amount);
		
		$billArray = array();
		$billArray['INVID']=$decodedBillData->invoiceNumber;
		$billArray['ClientName']=$decodedBillData->client->clientName;
		$billArray['Total']=$amount;
		$billArray['TotalInWord']=$currencyResult;
		$billArray['TransType']=$transactionResult[0]->payment_trn;
		$billArray['Date']=$decodedBillData->entryDate;
		$companyName = "ABC";
		$mpdf = new mPDF('A4','landscape');
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		$mpdf->WriteHTML($htmlBody);
		$path = $constantArray['billUrl'];
		
		// change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		$documentPathName = $path.$documentName;
		$documentFormat="pdf";
		$documentType ="bill";
		
		// insertion bill document data into database
		
		$billDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);
		
		if(strcmp($exceptionArray['500'],$billDocumentStatus)==0)
		{
			return $billDocumentStatus;
		}
		else
		{
			$mpdf->Output($documentPathName,'F');
			if($decodedBillData->client->emailId!="")
			{
				// mail send
				// $result = $this->mailSending($decodedBillData->client->emailId,$documentPathName,$emailTemplateData,$decodedBillData->client->clientName,$companyName);
				// if(strcmp($result,$exceptionArray['Email'])==0)
				// {
					// return $result;
				// }
			}
			// sms send
			// $data = array(
				// 'user' => "siliconbrain",
				// 'password' => "demo54321",
				// 'msisdn' => $decodedBillData->client->contactNo,
				// 'sid' => "ERPJSC",
				// 'msg' => $message,
				// 'fl' =>"0",
				// 'gwid'=>"2"
			// );
			// list($header,$content) = PostRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
			
			// $url = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=siliconbrain&password=demo54321&msisdn=".$decodedBillData->client->contactNo."&sid=COTTSO&msg=".$message."&fl=0&gwid=2";
			// pdf generate
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return $pathArray;
		}	
	}
	
	 /**
     * sending message
     * @param mail-address
     * @return error-message/status
     */
	public function mailSending($emailId,$documentPathName,$emailTemplate,$clientName,$companyName)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$htmlBody = json_decode($emailTemplate)[0]->templateBody;
		$emailArray = array();
		$emailArray['Company']=$companyName;
		$emailArray['ClientName']=$clientName;
		foreach($emailArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		$mail = new PHPMailer;
		$email = $emailId;
		$message = $htmlBody;
		$mail->IsSMTP();  
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		
		// Set mailer to use SMTP
		$mail->Host = 'swaminarayancycles.com';                // Specify main and backup server //sg2plcpnl0073.prod.sin2.secureserver.net port=465
		$mail->Port =  465;                                    // Set the SMTP port 465
		$mail->Username = 'support@swaminarayancycles.com';                // SMTP username
		$mail->Password = 'Abcd@1234'; 
		$mail->From = 'support@swaminarayancycles.com';
		$mail->FromName = 'support@swaminarayancycles.com';
		$mail->AddAddress($email);  // Add a recipient
		
		$mail->AddAttachment($documentPathName); //,"abc",'base8','mime/type'
		$mail->IsHTML(true);   
		$mail->Subject = 'Cycle Store';
		$mail->Body    = $message;
		$mail->AltBody = $message;
		if(!$mail->Send()) {
		   return $exceptionArray['Email'];
		}
		
	}
	
	public function postRequest($url,$_data) 
	{
		// convert variables array to string:
		$data = array();
		while(list($n,$v) = each($_data))
		{
			$data[] = "$n=$v";
		}

		$data = implode('&', $data);
		$url = parse_url($url);

		if ($url['scheme'] != 'http') {
		die('Only HTTP request are supported !');
		}
		// extract host and path:
		$host = $url['host'];
		$path = $url['path'];

		// open a socket connection on port 80
		$fp = fsockopen($host, 80);

		// send the request headers:
		fputs($fp, "POST $path HTTP/1.1\r\n");
		fputs($fp, "Host: $host\r\n");
		//fputs($fp, "Referer: $referer\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ". strlen($data)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data);
		$result = '';
		while(!feof($fp)) {
		// receive the results of the request
		$result .= fgets($fp, 128);
		}

		// close the socket connection:
		fclose($fp);
		// split the result header from the content
		$result = explode("\r\n\r\n", $result, 2);

		$header = isset($result[0]) ? $result[0] : '';

		$content = isset($result[1]) ? $result[1] : '';
		// return as array:
		return array($header, $content);
	}
	
	/**
	* pdf generation
	* @param template-data and quotation data
	* @return error-message/document-path
	*/
	public function quotationMpdfGenerate($templateData,$quotationData)
	{		
		echo "in";
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$htmlBody = json_decode($templateData)[0]->templateBody;
		
		$quotationBillId = $quotationData->quotationBillId;
		
		$decodedArray = json_decode($quotationData->productArray);
		$productService = new ProductService();
		$productData = array();
		$decodedData = array();
		$index=1;
	
		$output="";
		$totalAmount =0;
		$totalVatValue=0;
		$totalAdditionalTax=0;
		$totalQty=0;
		
		$totalCm = 10.4;
		echo "ff";
		for($productArray=0;$productArray<count($decodedArray->inventory);$productArray++)
		{
			//get product-data
			$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
			$decodedData[$productArray] = json_decode($productData[$productArray]);
			
			//calculate margin value
			$marginValue[$productArray]=($decodedData[$productArray]->margin/100)*$decodedArray->inventory[$productArray]->price;
			$marginValue[$productArray] = $marginValue[$productArray]+$decodedData[$productArray]->marginFlat;
			
			$totalPrice = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
			if(strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0)
			{
				$discountValue[$productArray] = $decodedArray->inventory[$productArray]->discount;

			}
			else
			{
				$discountValue[$productArray] = ($decodedArray->inventory[$productArray]->discount/100)*$totalPrice;
			}
			$finalVatValue = $totalPrice - $discountValue[$productArray];
			
			//calculate vat value;
			$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*$finalVatValue;
			
			//calculate additional tax
			$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
			$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray] +$additionalTaxValue[$productArray];
			
			$price = number_format($decodedArray->inventory[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
			$trClose = "</td></tr>";
			if($productArray==0)
			{
				$output =$output.$trClose;
			}
			if(empty($decodedArray->inventory[$productArray]->color))
			{
				$decodedArray->inventory[$productArray]->color="";
			}
			if(empty($decodedArray->inventory[$productArray]->frameNo))
			{
				$decodedArray->inventory[$productArray]->frameNo="";
			}
			
			$totalVatValue = $totalVatValue+$vatValue[$productArray];
			$totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray];
			$totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
			$totalAmount=$totalAmount+$total[$productArray];
			
			//convert (number_format)as per company's selected decimal points
			$vatValue[$productArray] = number_format($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
			$additionalTaxValue[$productArray] = number_format($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
			$total[$productArray] = number_format($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
			
			$output =$output."".
				'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
			   <td class="tg-m36b thsrno" style="font-size: 14px; height: 0.7cm; text-align:center; padding:0 0 0 0;">'.$index.'</td>
			   <td colspan="3" class="tg-m36b theqp" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
			   <td colspan="2" class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->color.' | '.$decodedArray->inventory[$productArray]->size.'</td>
			   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->frameNo.'</td>
			   <td class="tg-ullm thsrno" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->qty.'</td>
			   <td class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $price.'</td>
				<td colspan="2" class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">PCS</td>
			   <td class="tg-ullm thamt" style="font-size: 14px;  height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$total[$productArray];

			if($productArray != count($decodedArray->inventory)-1)
			{
				$output = $output.$trClose;
			
			}
			if($productArray==(count($decodedArray->inventory)-1))
			{
				$totalProductSpace = $index*0.7;	
				
				$finalProductBlankSpace = $totalCm-$totalProductSpace;
				$output =$output."<tr class='trhw' style='font-family: Calibri; text-align: left; height:  ".$finalProductBlankSpace."cm;background-color: transparent;'>
			   <td colspan='12' class='tg-m36b thsrno' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;'></td></tr>";
			}
			$index++;
		}
		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($totalAmount);
		$address = $quotationData->client->address1;
		$companyAddress = $quotationData->company->address1.",".$quotationData->company->address2;
		//add 1 month in entry date for displaying expiry date
		$date = date_create($quotationData->entryDate);
		date_add($date, date_interval_create_from_date_string('30 days'));
		$expiryDate = date_format($date, 'd-m-Y');
		$totalTax = $totalVatValue+$totalAdditionalTax;
		// convert amount(number_format) into their company's selected decimal points
		$totalTax = number_format($totalTax,$decodedData[0]->company->noOfDecimalPoints,'.','');
		$totalAmount = number_format($totalAmount,$decodedData[0]->company->noOfDecimalPoints,'.','');
		
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$quotationData->client->clientName;
		$billArray['Company']="<span style='font-family: algerian;font-size:22px'>".$quotationData->company->companyName."</span>";
		$billArray['Total']=$totalAmount;
		$billArray['Mobile']=$quotationData->client->contactNo;
		$billArray['QuotationNo']=$quotationData->quotationNumber;
		$billArray['CLIENTADD']=$address;
		$billArray['OrderDate']=$quotationData->entryDate;
		$billArray['TotalQty']=$totalQty;
		$billArray['TotalInWord']=$currencyResult;
		$billArray['displayNone']='none';
		$billArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png'/>";
		$billArray['CompanyAdd']=$companyAddress;
		$billArray['CLIENTTINNO']="";
		$mpdf = new mPDF('A4','landscape');
		// $mpdf = new mPDF('','A4','','agency','0','0','0','0','0','0','landscape');
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		
		$mpdf->WriteHTML($htmlBody);
		$path = $constantArray['quotationDocUrl'];
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999)."_quotation.pdf";
		$documentPathName = $path.$documentName;
		$documentFormat="pdf";
		$documentType ="quotation";
		
		//pdf generate
		$mpdf->Output($documentPathName,'F');
		
		//insertion quotation document data into database
		$quotationModel = new QuotationModel();
		$quotationDocumentStatus = $quotationModel->quotationDocumentData($quotationBillId,$documentName,$documentFormat,$documentType);
		
		if(strcmp($exceptionArray['500'],$quotationDocumentStatus)==0)
		{
			return $quotationDocumentStatus;
		}
		else
		{
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return $pathArray;
		}	
	}
}
