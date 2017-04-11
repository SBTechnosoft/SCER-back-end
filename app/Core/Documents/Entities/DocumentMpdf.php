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
	public function mpdfGenerate($templateData,$status,$headerData)
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
				$htmlBody = "



<div style='background-size: 100% 100%; height: 28.3cm; width: 20.5cm;'>
<table style='border-collapse: collapse; border-spacing: 0px; margin-left: auto; margin-right: auto;height: 27.8cm; width: 21.3cm;' border='1' cellspacing='0'>
<tbody>
<tr style='height: 6cm;'>
<th style='height: 6cm; text-align: left;' colspan='12'></th>
</tr>
<tr style='background-color: transparent; height: 0.8cm;  text-align: left;'>
<td style='font-family: Calibri; font-size: 12px; vertical-align: top; height: 0.8cm;width:13.6cm; text-align: left;' colspan='7' rowspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color: #000000;'><strong>[ClientName]</strong></span> <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[CLIENTADD]</span></td>
<td style='font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; text-align: left; height: 0.8cm;width:7.7cm;' colspan='5'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color: #000000;'>[INVID]</span></td>
</tr>
<tr class='trhw' style='height: 0.8cm; text-align: left;'>

<td class='tg-vi9z' style='font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;' colspan='12'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[OrderDate]</span></td>
</tr>
<tr class='trhw' style='background-color: transparent; height: 0.8cm; text-align: left;'>
<td class='tg-vi9z' style='font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;' colspan='7'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >[TINNO]</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Mobile No:</strong>&nbsp;&nbsp;<span>[Mobile]</span></td>
<td class='tg-vi9z' style='font-family: Calibri; font-size: 12px; vertical-align: middle; color: #4e4e4e; height: 0.8cm;' colspan='5'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >0</span></td>
</tr>
<tr class='trhw' style='font-family: Calibri; height: 1.2cm; text-align: left;'>
<td class='tg-m36b thsrno' style='font-size: 12px; padding: 5px; text-align: center; height: 1.2cm;'><span style='color: #000000;'><strong>Sr.No</strong></span></td>
<td class='tg-m36b theqp' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: left;'><span style='color: #000000;'><strong>Particulars</strong></span></td>
<td class='tg-ullm thsrno' style='font-size: 12px; height: 1.2cm; text-align: center; padding: 5px 2px 5px 2px;'><span style='color: #000000;'><strong>Color | Size</strong></span></td>
<td class='tg-ullm thsrno' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: left;'><span style='color: #000000;'><strong>Frame No</strong></span></td>
<td class='tg-ullm thsrno' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>Qty</strong></span></td>
<td class='tg-ullm thsrno' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>Rate</strong></span></td>
<td class='tg-ullm thsrno' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>Discount</strong></span></td>
<td class='tg-ullm thamt' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>VAT%</strong></span></td>
<td class='tg-ullm thamt' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>VAT</strong></span></td>
<td class='tg-ullm thamt' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>A.Vat%</strong></span></td>
<td class='tg-ullm thamt' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>A.Vat</strong></span></td>
<td class='tg-ullm thamt' style='font-size: 12px; padding: 5px; height: 1.2cm; text-align: center;'><span style='color: #000000;'><strong>Amount</strong></span></td>
</tr>

<tr class='trhw' style='font-family: Calibri; text-align: left; height: 9.8cm; background-color: transparent; display: [displayNone];'>
	<td class='tg-m36b thsrno' style='font-size: 12px; color: #000000; height: 9.8cm;' colspan='12'>[Description]</td>
	
</tr>



<tr  style='height: 0.7cm; text-align: left;'>
<td class='tg-jtyd' style='font-size: 10px; padding: 5px; height: 0.7cm; text-align: left;border-top: 1px solid black;border-bottom: 1px solid black;' colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color: #000000;'>[TotalInWord]</span></td>
<td class='tg-jtyd' style='font-size: 12px; padding: 5px; height: 0.7cm; text-align: left;border-top: 1px solid black;border-bottom: 1px solid black;' colspan='3' ><span style='color: #000000;'><strong>&nbsp;&nbsp;&nbsp;[TotalQty]</strong></span></td>

<td class='tg-jtyd' style='text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;' colspan='2'>&nbsp;</td>
<td class='tg-jtyd' style='text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;'><strong><span style='color: #000000;'>Total</span></strong></td>
<td class='tg-jtyd' style='text-align: right; font-size: 12px; padding: 5px; height: 0.7cm;border-top: 1px solid black;border-bottom: 1px solid black;'><span style='color: #000000;'><strong>[TotalTax]</strong>&nbsp;</span></td>
<td class='tg-3gzm' style='font-size: 12px; padding: 5px; color: #4e4e4e; height: 0.7cm; text-align: center;border-top: 1px solid black;border-bottom: 1px solid black;'><strong>&nbsp;<span style='color: #000000;'>[Total]</span></strong></td>
</tr>
<tr style='background-color: transparent; height: 2.85cm; text-align: left;'>
<td class='tg-3gzm' style='text-align: center; vertical-align: bottom; height: 2.85cm;' colspan='12'></td>
</tr>
<tr class='trhw' style='background-color: transparent; height: 2.4cm; text-align: left;'>
<td class='tg-vi9z' style='padding: 5px; height: 2.4cm; text-align: center; vertical-align: middle;' colspan='4'>
<p style='visibility: hidden;'>&nbsp;</p>
<p style='padding-top: 10px;'><span style='color: #000000;font-size: 22px;'><strong> [REMAINAMT]</strong></span></p>
</td>
<td class='tg-vi9z' style='padding: 5px; height: 2.4cm; text-align: center; vertical-align: middle;visibility: hidden;' colspan='4'>
<p></p>
<p>&nbsp;</p>
</td>
<td class='tg-vi9z' style='padding: 5px; height: 2.4cm; text-align: center; vertical-align: middle;' colspan='4'>
<p style='padding: 0 0 5px 0;visibility: hidden;'>&nbsp;</p>
<p><span style='color: #000000;'><strong style='padding: 5px;font-size: 22px;'>&nbsp;[Total]</strong></span></p>
</td>
</tr>
<tr style='background-color: transparent; height: 2.2cm; text-align: left;'>
<td style='padding: 5px; height: 2.2cm; text-align: center; vertical-align: middle;' colspan='12'>
</td>
</tr>
</tbody>
</table>
</div>
";
			}
		}
		else
		{
			$htmlBody = json_decode($templateData)[0]->templateBody;
		}		$decodedBillData = json_decode($status);
		
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
		$billArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png'/>";

		//$mpdf = new mPDF('A4','landscape');
		$mpdf = new mPDF('','A4','','','0','0','0','0','0','0','landscape');
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
			// $url = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=siliconbrain&password=demo54321&msisdn=".$decodedBillData->client->contactNo."&sid=COTTSO&msg=".$message."&fl=0&gwid=2";
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
				$result = $this->mailSending($decodedBillData->client->emailId,$documentPathName);
				// if(strcmp($result,$exceptionArray['Email'])==0)
				// {
					// return $result;
				// }
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
}
