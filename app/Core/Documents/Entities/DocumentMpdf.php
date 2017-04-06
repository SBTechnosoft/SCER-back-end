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
	public function mpdfGenerate($templateData,$status)
	{
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$htmlBody = json_decode($templateData)[0]->templateBody;
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
					'<tr class="trhw" style="font-family: Calibri; text-align: left; height: 25px; background-color: transparent;">
				   <td class="tg-m36b thsrno" style="font-size: 12px; height: 25px; text-align:center; padding:0 0 0 0;">'.$index.'</td>
				   <td class="tg-m36b theqp" style="font-size: 12px;  height: 25px; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height: 25px; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->color.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height: 25px; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->frameNo.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;   height: 25px; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->qty.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px; height: 25px; text-align: center; padding:0 0 0 0;">'. $decodedArray->inventory[$productArray]->price.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 12px;  height: 25px; text-align: center; padding:0 0 0 0;">'. $discountValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height: 25px; text-align: center; padding:0 0 0 0;">'. $decodedData[$productArray]->vat.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 12px; height: 25px; text-align: center; padding:0 0 0 0;">'.$vatValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height: 25px; text-align: center; padding:0 0 0 0;">'.$decodedData[$productArray]->additionalTax.'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;   height: 25px; text-align: center; padding:0 0 0 0;">'.$additionalTaxValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 12px;  height: 25px; text-align: center; padding:0 0 0 0;">'.$total[$productArray];
				if($productArray != count($decodedArray->inventory)-1)
				{
					$output = $output.$trClose;
				}

			    $index++;
			    $totalVatValue = $totalVatValue+$vatValue[$productArray];
			    $totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray];
			    $totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
			 
			    $totalAmount=$totalAmount+$total[$productArray];
			    // convert amount(round) into their company's selected decimal points
				$totalAmount = round($totalAmount,$decodedData[$productArray]->company->noOfDecimalPoints);
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
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$decodedBillData->client->clientName;
		$billArray['Company']=$decodedBillData->company->companyName;
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
		$billArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png' height='100%' width='100%' />";


		$mpdf = new mPDF('A4','landscape');
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
		
		//insertion bill document data into database
		$billModel = new BillModel();
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
				$result = $this->mailSending($decodedBillData->client->emailId);
				//if(strcmp($result,$exceptionArray['Email'])==0)
				//{
					//return $result;
				//}
			}
			$message = "Your Bill Is Generated...";
			//sms send
			// $url = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=siliconbrain&password=demo54321&msisdn=".$decodedBillData->client->contactNo."&sid=COTTSO&msg=".$message."&fl=0&gwid=2";
			//pdf generate
			
			$mpdf->Output($documentPathName,'F');
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return $pathArray;
		}	
	} 
	
	/**
     * pdf generation and mail-sms send
     * @param template-data and bill data
     * @return error-message/document-path
     */
	public function mpdfPaymentGenerate($templateData,$status)
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
				$result = $this->mailSending($decodedBillData->client->emailId);
				//if(strcmp($result,$exceptionArray['Email'])==0)
				//{
					//return $result;
				//}
			}
			$message = "Your Bill Is Generated...";
			// sms send
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
	public function mailSending($emailId)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mail = new PHPMailer;
		$email = $emailId;
		$message = "Your bill is generated...";
		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'sg2plcpnl0073.prod.sin2.secureserver.net';                // Specify main and backup server //sg2plcpnl0073.prod.sin2.secureserver.net port=465
		$mail->Port =  465;                                    // Set the SMTP port
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		
		// SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
		$mail->Username = 'reema.p@siliconbrain.in';                // SMTP username
		$mail->Password = 'Abcd@1234'; 
		$mail->From = 'reema.p@siliconbrain.in';
		$mail->FromName = 'reema.p@siliconbrain.in';
		$mail->AddAddress($email);  // Add a recipient

		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Cycle Store';
		$mail->Body    = $message;
		$mail->AltBody = 'Your bill is generated...';

		if(!$mail->Send()) {
		   return $exceptionArray['Email'];
		}
	}
}
