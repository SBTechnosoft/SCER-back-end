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
// use ERP\Core\Documents\Entities\CssStyleMpdf;
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
				$htmlBody = json_decode($blankTemplateData)[0]->templateBody;
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
				// convert amount(round) into their company's selected decimal points
				$marginValue[$productArray] = round($marginValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$totalPrice = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				$totalPrice = round($totalPrice,$decodedData[$productArray]->company->noOfDecimalPoints);
				
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
				// convert amount(round) into their company's selected decimal points
				$vatValue[$productArray] = round($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				
				//calculate additional tax
				$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
				
				//convert amount(round) into their company's selected decimal points
				$additionalTaxValue[$productArray] = round($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray] +$additionalTaxValue[$productArray];
				
				// convert amount(round) into their company's selected decimal points
				$total[$productArray] = round($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
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

				$output =$output."".
					'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
				   <td class="tg-m36b thsrno" style="font-size: 12px; height: 0.7cm; text-align:center; padding:0 0 0 0;">'.$index.'</td>
				   <td class="tg-m36b theqp" style="font-size: 12px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->color.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->frameNo.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->qty.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->price.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $discountValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedData[$productArray]->vat.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 12px; height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$vatValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$decodedData[$productArray]->additionalTax.'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'.$additionalTaxValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$total[$productArray];
				if($productArray != count($decodedArray->inventory)-1)
				{
					$output = $output.$trClose;
				
				}
				
			    
			    $totalVatValue = $totalVatValue+$vatValue[$productArray];
			    $totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray];
			    $totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
			 
			    $totalAmount=$totalAmount+$total[$productArray];
			    // convert amount(round) into their company's selected decimal points
				$totalAmount = round($totalAmount,$decodedData[$productArray]->company->noOfDecimalPoints);
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
			for($productArray=0;$productArray<count($decodedArray->inventory);$productArray++)
			{
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				
				$marginPrice[$productArray] = ($decodedData[$productArray]->wholesaleMargin/100)*$decodedArray->inventory[$productArray]->price;
				$marginPrice[$productArray] = $marginPrice[$productArray]+$decodedData[$productArray]->wholesaleMarginFlat;
				
				// convert amount(round) into their company's selected decimal points
				$marginPrice[$productArray] = round($marginPrice[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				
				$totalPrice[$productArray] = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				// convert amount(round) into their company's selected decimal points
				$totalPrice[$productArray] = round($totalPrice[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
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
				// convert amount(round) into their company's selected decimal points
				$vatValue[$productArray] = round($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				
				
				//calculate additional tax
				$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
				// convert amount(round) into their company's selected decimal points
				$additionalTaxValue[$productArray] = round($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);				
				
				$total[$productArray] = $finalVatValue+$vatValue[$productArray]+$additionalTaxValue[$productArray];
				// convert amount(round) into their company's selected decimal points
				$total[$productArray] = round($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				
				if(empty($decodedArray->inventory[$productArray]->color))
				{
					$decodedArray->inventory[$productArray]->color="";
				}
				if(empty($decodedArray->inventory[$productArray]->frameNo))
				{
					$decodedArray->inventory[$productArray]->frameNo="";
				}
				$output =$output."".
				'<tr class="trhw" style="font-family: Calibri; height: 50px; background-color: transparent; text-align: left;">
				<td class="tg-m36b thsrno" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'.$index.'</span></td>
				<td class="tg-m36b theqp" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'. $decodedData[$productArray]->productName.'</span></td>
				<td class="tg-ullm thsrno" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'. $decodedArray->inventory[$productArray]->color.'</span></td>
				<td class="tg-ullm thsrno" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'. $decodedArray->inventory[$productArray]->frameNo.'</span></td>
				<td class="tg-ullm thsrno" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'. $decodedArray->inventory[$productArray]->qty.'</span></td>
				<td class="tg-ullm thsrno" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'. $decodedArray->inventory[$productArray]->price.'</span></td>
				<td class="tg-ullm thsrno" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'. $discountValue[$productArray].'</span></td>
				<td class="tg-ullm thamt" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'.$decodedData[$productArray]->vat.'%</span></td>
				<td class="tg-ullm thamt" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'.$vatValue[$productArray].'</span></td>
				<td class="tg-ullm thamt" style="font-size: 12px; text-align: center; height: 50px;"><span style="color: #000000;">'.$decodedData[$productArray]->additionalTax.'</span></td>
				<td class="tg-ullm thamt" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'.$additionalTaxValue[$productArray].'</span></td>
				<td class="tg-ullm thamt" style="font-size: 12px;  text-align: center; height: 50px;"><span style="color: #000000;">'.$total[$productArray].'</span></td>
				</tr>';
				 $index++;
				 $totalAmount=$totalAmount+$total[$productArray];
				 $totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray]+$vatValue[$productArray];
				 $totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
				
				// convert amount(round) into their company's selected decimal points
				$totalAmount = round($totalAmount,$decodedData[$productArray]->company->noOfDecimalPoints);
			}
		}

		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($totalAmount);
		$address = $decodedBillData->client->address1.",".$decodedBillData->client->address2;
		$companyAddress = $decodedBillData->company->address1.",".$decodedBillData->company->address2;
		if(strcmp($decodedBillData->salesType,"retail_sales")==0)
		{
			$typeSale = "RETAIL";
		}
		else
		{
			$typeSale = "TAX";

		}
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
		$billArray['TotalTax']=$totalVatValue+$totalAdditionalTax;
		$billArray['TotalQty']=$totalQty;
		$billArray['TotalInWord']=$currencyResult;
		$billArray['displayNone']='none';
		$billArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png'/>";
		$billArray['CompanyAdd']=$companyAddress;
		$billArray['CreditCashMemo']="<span style='font-family: algerian;'>CASH</span>";
		$billArray['RetailOrTax']=$typeSale;
		
		//$mpdf = new mPDF('A4','landscape');
		$mpdf = new mPDF('','A4','','agency','0','0','0','0','0','0','landscape');
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		// $mpdf->SetFont('algerian');
		// echo "sss";
		// $cssStyle = file_get_contents('CssStyleMpdf.css');
		// $mpdf->WriteHTML($cssStyle,1);
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
		
		//insertion bill document data into database
		$billModel = new BillModel();
		$billDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);
		if(array_key_exists("operation",$headerData))
		{
			if(strcmp($headerData['operation'][0],'preprint')==0)
			{
				//change the name of document-name
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
				$documentPreprintPathName = $path.$documentName;
				$documentFormat="pdf";
				$documentType ="preprint-bill";

				$preprintBillDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);

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
				//$result = $this->mailSending($decodedBillData->client->emailId,$documentPathName);
				 //if(strcmp($result,$exceptionArray['Email'])==0)
				// {
					// return $result;
				// }
			}
			
			$message = "Your Bill Is Generated...";
			
			//sms send
			if($decodedBillData->client->contactNo!=0 || $decodedBillData->client->contactNo!="")
			{
				if($decodedBillData->company->companyId==9)
				{
					$smsTemplateBody = json_decode($smsTemplateData)[0]->templateBody;
					$smsArray = array();
					$smsArray['ClientName'] = $decodedBillData->client->clientName;
					foreach($smsArray as $key => $value)
					{
						$smsHtmlBody = str_replace('['.$key.']', $value, $smsTemplateBody);
					}
					//replace 'p' tag
					$smsHtmlBody = str_replace('<p>','', $smsHtmlBody);
					$smsHtmlBody = str_replace('</p>','', $smsHtmlBody);
					$data = array(
						'user' => "siliconbrain",
						'password' => "demo54321",
						'msisdn' => $decodedBillData->client->contactNo,
						'sid' => "ERPJSC",
						'msg' => $smsHtmlBody,
						'fl' =>"0",
						'gwid'=>"2"
					);
					// list($header,$content) = $this->postRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
				}
			}
			// print_r($url);
			if(array_key_exists("operation",$headerData))
			{
				if(strcmp($headerData['operation'][0],'preprint')==0)
				{
					//pdf generate
					$mpdf->Output($documentPathName,'F');
					$mpdf->Output($documentPreprintPathName,'F');

					$pathArray = array();
					$pathArray['documentPath'] = $documentPathName;
					$pathArray['preprintDocumentPath'] = $documentPreprintPathName;
					return $pathArray;
				}
			}
			else
			{
				//pdf generate
				$mpdf->Output($documentPathName,'F');
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
			if($decodedBillData->client->emailId!="")
			{
				// mail send
				$result = $this->mailSending($decodedBillData->client->emailId,$documentPathName);
				// if(strcmp($result,$exceptionArray['Email'])==0)
				// {
					// return $result;
				// }
			}
			// sms send
			$message = "Your Bill Is Generated...";
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
			$mpdf->Output($documentPathName,'F');
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
	public function mailSending($emailId,$documentPathName)
	{
		echo "enter";
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$mail = new PHPMailer;
		$email = $emailId;
		$message = "Your bill is generated...";
		$mail->IsSMTP();  
		
              // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                // Specify main and backup server //sg2plcpnl0073.prod.sin2.secureserver.net port=465
		$mail->Port =  465;                                    // Set the SMTP port 465
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		
		// SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
		$mail->Username = 'shaikhfarhan05@gmail.com';                // SMTP username
		$mail->Password = 'farhanboss420840'; 
		$mail->From = 'shaikhfarhan05@gmail.com';
		$mail->FromName = 'shaikhfarhan05@gmail.com';
		$mail->AddAddress($email);  // Add a recipient
		//$name = "abc";
		$mail->isHTML(true);
		//$mail->AddAttachment('http://api.siliconbrain.co.in/'.$documentPathName,$name,$encoding ='base64',$type = 'application/octet-stream');	
		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Cycle Store';
		$mail->Body    = $message;
		$mail->AltBody = $message;
		print_r($mail);
		if(!$mail->Send()) {
		   print_r($mail->ErrorInfo);	
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
}
