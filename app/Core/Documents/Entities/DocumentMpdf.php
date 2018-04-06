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
use ERP\Model\Crm\JobForm\JobFormModel;
use ERP\Model\Crm\Conversations\ConversationModel;
use Carbon;
use stdClass;
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
		$commentArray = $constantClass->getCommentMessage();
		$smsSettingArray = $constantClass->setSmsPassword();
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
		$finalDiscount = 0;
		$gstSummarySizeManage = 0;
		$trClose = "</td></tr>";
		$gstSummaryArray = array();
		if(strcmp($decodedBillData->salesType,"retail_sales")==0)
		{
			$totalCm = 12;
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
				$finalDiscount = $finalDiscount + $discountValue[$productArray];
				$finalVatValue = $totalPrice - $discountValue[$productArray];
				
				//calculate vat value;
				$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*$finalVatValue;
				
				//calculate additional tax
				$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
				$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray] +$additionalTaxValue[$productArray];
				
				$price = number_format($decodedArray->inventory[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
				
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
				
				$product_hsnCode = $decodedData[$productArray]->hsn ? $decodedData[$productArray]->hsn : "";
				$output =$output."".
					'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
				   <td class="tg-m36b thsrno" style="font-size: 14px; height: 0.7cm; text-align:center; padding:0 0 0 0;border-right: 1px solid black;">'.$index.'</td>
				   <td class="tg-m36b theqp" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid black;" colspan="3">'. $decodedData[$productArray]->productName.'</td>
				   <!--td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid black;">'. $decodedArray->inventory[$productArray]->color.' | '.$decodedArray->inventory[$productArray]->size.'</td-->
				   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid black;">'. $product_hsnCode.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black;">'. $decodedArray->inventory[$productArray]->qty.'</td>
				   <td class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid black;">'. $price.'</td>
				   <!--td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black;">'. $discountValue[$productArray].'</td-->
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid black;">'. $decodedData[$productArray]->vat.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 14px; height: 0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid black;">'.$vatValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid black;">'.$decodedData[$productArray]->additionalTax.'%</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid black;">'.$additionalTaxValue[$productArray].'</td>
				   <td class="tg-ullm thamt" style="font-size: 14px;  height: 0.7cm; text-align: right; padding:0 5px 0 0;">'.$total[$productArray];

				if($productArray != count($decodedArray->inventory)-1)
				{
					$output = $output.$trClose;
				
				}
				if($productArray==(count($decodedArray->inventory)-1))
				{
					$totalProductSpace = $index*0.7;	
					
					$finalProductBlankSpace = $totalCm-$totalProductSpace;
					$output =$output."<tr class='trhw' style='font-family: Calibri; text-align: left; height:  ".$finalProductBlankSpace."cm;background-color: transparent;'>
				   <td class='tg-m36b thsrno' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-m36b theqp' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; padding:0 0 0 0;border-right: 1px solid black' colspan='3'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;   height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px; height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thsrno' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;   height:  ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;border-right: 1px solid black'></td>
				   <td class='tg-ullm thamt' style='font-size: 12px;  height: ".$finalProductBlankSpace."cm; text-align: center; padding:0 0 0 0;'></td></tr>";

				}
				$index++;
			}
		}
		else
		{
			$totalCm = 12-0.7;
			$inventoryCount = count($decodedArray->inventory);
			for($productArray=0;$productArray<$inventoryCount;$productArray++)
			{
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				
				$marginPrice[$productArray] = ($decodedData[$productArray]->wholesaleMargin/100)*$decodedArray->inventory[$productArray]->price;
				$marginPrice[$productArray] = $marginPrice[$productArray]+$decodedData[$productArray]->wholesaleMarginFlat;
				
				$totalPrice[$productArray] = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				
				$discountValue[$productArray] = strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0
												? $decodedArray->inventory[$productArray]->discount
												: ($decodedArray->inventory[$productArray]->discount/100)*$totalPrice[$productArray];
				$finalDiscount = $finalDiscount + $discountValue[$productArray];
				$finalVatValue = $totalPrice[$productArray]-$discountValue[$productArray];
				$discountInPercentage = strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0 
										? $decodedArray->inventory[$productArray]->discount : $decodedArray->inventory[$productArray]->discount ." %";
				$discount = $decodedArray->inventory[$productArray]->discount == "" ? "0.0" :$decodedArray->inventory[$productArray]->discount;
				//calculate vat value;
				$vatValue[$productArray]=$decodedArray->inventory[$productArray]->cgstAmount;
				$vatValue[$productArray] = number_format($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				//calculate additional tax
				$additionalTaxValue[$productArray] = $decodedArray->inventory[$productArray]->sgstAmount;
				$additionalTaxValue[$productArray] = number_format($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);				
				
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
				$product_hsnCode = $decodedData[$productArray]->hsn
								   ? $decodedData[$productArray]->hsn : "";
				
				$totalAmount=$totalAmount+$decodedArray->inventory[$productArray]->amount;
				$totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray]+$vatValue[$productArray];
				$totalQty=$totalQty+$decodedArray->inventory[$productArray]->qty;
				// convert (number_format)as per company's selected decimal points
				$totalPrice[$productArray] = number_format($totalPrice[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$total[$productArray] = number_format($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
				$rate = number_format($decodedArray->inventory[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints);
				$discountValue[$productArray] = number_format($this->checkValue($discountValue[$productArray]),$decodedData[$productArray]->company->noOfDecimalPoints);
				$amount[$productArray] = number_format($decodedArray->inventory[$productArray]->amount,$decodedData[$productArray]->company->noOfDecimalPoints);
				$mainPrice = $decodedArray->inventory[$productArray]->price * $decodedArray->inventory[$productArray]->qty;
				$mainPrice = number_format($mainPrice,$decodedData[$productArray]->company->noOfDecimalPoints);
				$finalVatValue1 = number_format($finalVatValue,$decodedData[$productArray]->company->noOfDecimalPoints);
				$cgst = $this->checkValue($decodedArray->inventory[$productArray]->cgstPercentage);
				$sgst = $this->checkValue($decodedArray->inventory[$productArray]->sgstPercentage);
				$igst = $this->checkValue($decodedArray->inventory[$productArray]->igstPercentage);
				$totalTax = $cgst + $sgst + $igst;
				// $frameNo = $decodedArray->inventory[$productArray]->frameNo==""? "" :$decodedArray->inventory[$productArray]->frameNo;
				$product_hsnCode1 = $product_hsnCode=="" ? "-" :$product_hsnCode;
				$output = $output."<tr  style='font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;'><td  style='font-size: 11px; height: 0.7cm; text-align:center; padding:0 0 0 0;border-right: 1px solid black;'>". $index .
				"</td><td colspan='3' style='font-size: 11px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' >&nbsp;"
				. $decodedData[$productArray]->productName .
				"</td><td  style='font-size: 11px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);text-align:center'>". $product_hsnCode1 .
				"</td><td colspan='2' style='font-size: 11px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);text-align:center'>". $decodedArray->inventory[$productArray]->color ." | ". $decodedArray->inventory[$productArray]->size .
				"</td><td  style='font-size: 11px;  height:  0.7cm; padding:0 0 0 0; text-align: center;border-right: 1px solid rgba(0, 0, 0, .3);'>". $decodedArray->inventory[$productArray]->frameNo  .
				"</td><td  style='font-size: 11px;  height:  0.7cm; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);text-align:center'>". $decodedArray->inventory[$productArray]->qty .
				"</td><td  style='font-size: 11px;   height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $rate .
				"&nbsp;</td><td  style='font-size: 11px;   height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $mainPrice .
				"&nbsp;</td><td  style='font-size: 11px; height:  0.7cm; text-align: center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $discount .
				"</td><td class='tg-ullm thamt' style='font-size: 11px;  height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $discountValue[$productArray] .
				"&nbsp;</td><td class='tg-ullm thamt' style='font-size: 11px;  height:  0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $finalVatValue1 .
				"&nbsp;</td><td class='tg-ullm thamt' style='font-size: 11px; height: 0.7cm; text-align: center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $totalTax .
				"%</td><td class='tg-ullm thamt' style='font-size: 11px;  height: 0.7cm; text-align: right; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);'>". $amount[$productArray] ."&nbsp;".$trClose;
				// if($productArray != count($decodedArray->inventory)-1)
				// {
					// $output = $output.$trClose;
				
				// }
				
				$copyFlag=0;
				$gstSummaryLength = count($gstSummaryArray);
				if($gstSummaryLength>1 && $product_hsnCode!='')
				{
					$summaryIndex=0;
					while($summaryIndex<$gstSummaryLength)
					{
						if(strcmp($gstSummaryArray[$summaryIndex]->hsnCode,$product_hsnCode)==0)
						{
							$copyFlag=1;
							$gstSummaryArray[$summaryIndex]->taxableValue = $gstSummaryArray[$summaryIndex]->taxableValue+$finalVatValue;
							$gstSummaryArray[$summaryIndex]->cgstAmount = ($gstSummaryArray[$summaryIndex]->taxableValue*$gstSummaryArray[$summaryIndex]->cgstPercentage)/100;
							$gstSummaryArray[$summaryIndex]->sgstAmount = ($gstSummaryArray[$summaryIndex]->taxableValue*$gstSummaryArray[$summaryIndex]->sgstPercentage)/100;
							$gstSummaryArray[$summaryIndex]->igstAmount = ($gstSummaryArray[$summaryIndex]->taxableValue*$gstSummaryArray[$summaryIndex]->igstPercentage)/100;
							break;
						}
						$summaryIndex++;
					}
				}
				if($copyFlag==0)
				{
					if($cgst>0 || $sgst>0 || $igst>0)
					{
						$tempObject = new stdClass();
						$tempObject->hsnCode = $product_hsnCode;
						$tempObject->taxableValue = $finalVatValue;
						$tempObject->cgstPercentage = $cgst;
						$tempObject->cgstAmount = $decodedArray->inventory[$productArray]->cgstAmount;
						$tempObject->sgstPercentage = $sgst;
						$tempObject->sgstAmount = $decodedArray->inventory[$productArray]->sgstAmount;
						$tempObject->igstPercentage = $igst;
						$tempObject->igstAmount = $decodedArray->inventory[$productArray]->igstAmount;
						array_push($gstSummaryArray,$tempObject);
						$gstSummarySizeManage++;
					}
				}
				if($productArray==(count($decodedArray->inventory)-1))
				{
					$lastManageSpace = $index+$gstSummarySizeManage;
					$totalProductSpace = $lastManageSpace*0.7;	
					
					$finalProductBlankSpace = $totalCm-$totalProductSpace;
					$output = $output . "<tr  style='height:".$finalProductBlankSpace."cm; background-color: transparent;'><td style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' colspan='3' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td colspan='2' style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td><td  style='font-size: 12px; height: ".$finalProductBlankSpace."cm; text-align:center; padding:0 0 0 0;border-right: 1px solid rgba(0, 0, 0, .3);' ></td></tr>";
				}
				$index++;
			}    
		}
		$totalTaxableAmount =0;
		$totalCgst =0;
		$totalCgstAmount =0;
		$totalSgst =0;
		$totalSgstAmount =0;
		$totalIgst =0;
		$totalIgstAmount =0;
		$gstIndex=0;
		$gstOutput='';
		$gstSummaryLength = count($gstSummaryArray);
		while($gstIndex < $gstSummaryLength)
		{
			$singleGstData = $gstSummaryArray[$gstIndex];

			$totalTaxableAmount = $totalTaxableAmount+$singleGstData->taxableValue;
			$totalCgst = $totalCgst+$singleGstData->cgstPercentage;
			$totalCgstAmount = $totalCgstAmount+$singleGstData->cgstAmount;
			$totalSgst = $totalSgst+$singleGstData->sgstPercentage;
			$totalSgstAmount = $totalSgstAmount+$singleGstData->sgstAmount;
			$totalIgst = $totalIgst+$singleGstData->igstPercentage;
			$totalIgstAmount = $totalIgstAmount+$singleGstData->igstAmount;
			$taxableValue = $singleGstData->taxableValue;
			$cgstAmount = number_format($singleGstData->cgstAmount,$decodedData[0]->company->noOfDecimalPoints);
			$sgstAmount = number_format($singleGstData->sgstAmount,$decodedData[0]->company->noOfDecimalPoints);
			$igstAmount = number_format($singleGstData->igstAmount,$decodedData[0]->company->noOfDecimalPoints);
			$taxableValue = number_format($taxableValue,$decodedData[0]->company->noOfDecimalPoints);
			if($gstIndex==0)
			{
				$gstOutput = $gstOutput.$trClose;
			}
			$hsnCode = $singleGstData->hsnCode=="" || $singleGstData->hsnCode==null || strcmp($singleGstData->hsnCode,'undefined')==0 ? '-': $singleGstData->hsnCode;
			// gstSummary Array
			$gstOutput = $gstOutput . '<tr style="background-color: transparent; height: 15px;"><td colspan=2  align="center" valign=middle  style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $hsnCode.
			'</td><td colspan=2 align="right" valign=bottom  style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $taxableValue.
			'&nbsp;</td><td align="center" valign=bottom  style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $singleGstData->cgstPercentage.
			'</td><td colspan=2 align="right" valign=bottom  style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $cgstAmount.
			'&nbsp;</td><td align="center" valign=bottom style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $singleGstData->sgstPercentage.
			'</td><td colspan=2 align="right" valign=bottom style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px">'
			. $sgstAmount.
			'&nbsp;</td><td align="center" valign=bottom style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px" >'
			.$singleGstData->igstPercentage.
			'</td><td colspan=2 align="right" valign=bottom style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px" >'
			. $igstAmount.
			'&nbsp;</td><td align="center" valign=bottom style="border-right: 1px solid rgba(0, 0, 0, .3);font-size:11px" ></td><td colspan=2 align="right" valign=bottom style="" >&nbsp;</td>';

			// End
			if($gstIndex != $gstSummaryLength-1)
			{
				$gstOutput = $gstOutput.$trClose;
			}
			$gstIndex++;
		}
		$discountableValue = $totalAmount+$decodedBillData->extraCharge;
		//calculation of total-discount 
		$totalDiscount = strcmp($decodedBillData->totalDiscounttype,'flat')==0
						? $decodedBillData->totalDiscount
						: (($decodedBillData->totalDiscount/100)*$discountableValue);
		$address = $decodedBillData->client->address1;
		$companyAddress = $decodedBillData->company->address1.",".$decodedBillData->company->address2;
		
		$typeSale = strcmp($decodedBillData->salesType,"retail_sales")==0
					? "RETAIL" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TAX";
		
		//add 1 month in entry date for displaying expiry date
		$date = date_create($decodedBillData->entryDate);
		date_add($date, date_interval_create_from_date_string('30 days'));
		$expiryDate = date_format($date, 'd-m-Y');
		$totalTax = $totalVatValue+$totalAdditionalTax;
		// convert amount(number_format) into their company's selected decimal points
		$totalTax = number_format($totalTax,$decodedData[0]->company->noOfDecimalPoints,'.','');
		
		$roundAmountTotal = ($totalAmount+$decodedBillData->extraCharge)-$totalDiscount;
		$roundTotal = round($roundAmountTotal);
		
		$roundUpFigure = $roundTotal-$roundAmountTotal;
		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($roundTotal);
		$roundUpFigure = number_format($roundUpFigure,$decodedData[0]->company->noOfDecimalPoints);
		$totalAmount = number_format($totalAmount,$decodedData[0]->company->noOfDecimalPoints);
		$roundTotal = number_format($roundTotal,$decodedData[0]->company->noOfDecimalPoints);
		$roundAmountTotal = number_format($roundAmountTotal,$decodedData[0]->company->noOfDecimalPoints);
		$extraCharge = number_format($decodedBillData->extraCharge,$decodedData[0]->company->noOfDecimalPoints);
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$decodedBillData->client->clientName;
		$billArray['Company']="<span style='font-size:22px'>".$decodedBillData->company->companyName."</span>";
		$billArray['Total']=$totalAmount;
		$billArray['serviceDate']=$decodedBillData->serviceDate;
		$billArray['CLIENTTINNO']=$decodedBillData->client->gst;
		$billArray['RoundTotal']=$roundTotal;
		$billArray['RoundFigure']=$roundUpFigure;
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
		$billArray['ChallanNo']="";
		$billArray['ChallanDate']="";
		$billArray['Transport']="";
		$billArray['GCLRNO']="";
		$billArray['Reference']="";
		$billArray['GCLRNO']="";
		$billArray['REMARK']=$decodedBillData->remark;
		$billArray['TotalDiscount']=$totalDiscount;
		$billArray['TotalRoundableAmount']=$roundAmountTotal;
		$billArray['ExtraCharge']=$extraCharge;
		$billArray['PONO']=$decodedBillData->poNumber;
		$billArray['CompanyWebsite']=$decodedBillData->company->websiteName;
		$billArray['CompanyContact']=$decodedBillData->company->customerCare;
		$billArray['CompanyEmail']=$decodedBillData->company->emailId;
		$billArray['BILLLABEL'] = array_key_exists("issalesorder",$headerData) ? "Sales Order" : "Tax Invoice";
		
		//gst-summary
		$billArray['gstSummary']=$gstOutput;
		$billArray['TotalTaxableAmt']=number_format($totalTaxableAmount,$decodedData[0]->company->noOfDecimalPoints);
		$billArray['TotalCgst']=$totalCgst;
		$billArray['TotalCgstAmt']=number_format($totalCgstAmount,$decodedData[0]->company->noOfDecimalPoints);
		$billArray['TotalSgst']=$totalSgst;
		$billArray['TotalSgstAmt']=number_format($totalSgstAmount,$decodedData[0]->company->noOfDecimalPoints);
		$billArray['TotalIgst']=$totalIgst;
		$billArray['TotalIgstAmt']=number_format($totalIgstAmount,$decodedData[0]->company->noOfDecimalPoints);
		// $mpdf = new mPDF('A4','landscape');
		 $mpdf = new mPDF('','A4','','agency','5','5','0','0','0','0','landscape');
		// $mpdf = new mPDF('','', 0, '', 10, 5, 5, 10, 0, 0, 'L');
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
		$mpdf->Output($documentPathName,"F");
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
			if(array_key_exists("operation",$headerData))
			{
				if(strcmp($headerData['operation'][0],'preprint')==0)
				{
					$pathArray = array();
					$pathArray['documentPath'] = $documentPathName;
					$pathArray['preprintDocumentPath'] = $documentPreprintPathName;
				}
			}
			else
			{
				$pathArray = array();
				$pathArray['documentPath'] = $documentPathName;
			}
			if($decodedBillData->client->emailId!="")
			{
				// mail send
				$result = $this->mailSending($decodedBillData->client->emailId,$documentPathName,$emailTemplateData,$decodedBillData->client->clientName,$decodedBillData->company->companyName,$decodedBillData->invoiceNumber,$documentType);
				if(strcmp($result,$exceptionArray['Email'])==0)
				{
					return $result;
				}	
				else
				{
					$subject = $constantArray['emailSubject'];
					$conversationType = $constantArray['emailType'];
					$conversation = $result;
					$documentPath = $documentPathName;
					$comment = $commentArray->billMailSend;
					$emailId = $decodedBillData->client->emailId;
					$companyId = $decodedBillData->company->companyId;
					$clientId = $decodedBillData->client->clientId;
					// mail description saved in conversation-database
					$conversationModel = new ConversationModel();
					$conversationResult = $conversationModel->saveMailDataFromBill($emailId,$subject,$conversationType,$conversation,$documentName,$documentFormat,$documentPath,$comment,$companyId,$clientId,$headerData);
				}		
			}
			//sms send
			if($decodedBillData->client->contactNo!=0 || $decodedBillData->client->contactNo!="")
			{
				if($decodedBillData->company->companyId==7)
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
					// $smsSettingArray
					$data = array(
						'user' => $smsSettingArray['user'],
						'password' =>$smsSettingArray['password'],
						'msisdn' => $decodedBillData->client->contactNo,
						'sid' => $smsSettingArray['sid'],
						'msg' => $smsHtmlBody,
						'fl' =>"0",
						'gwid'=>"2"
					);
					// list($header,$content) = $this->postRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
				}
				else if($decodedBillData->company->companyId==9)
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
				else if($decodedBillData->company->companyId==8)
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
						'sid' => "ERPPMC",
						'msg' => $smsHtmlBody,
						'fl' =>"0",
						'gwid'=>"2"
					);
					// list($header,$content) = $this->postRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
				}
			}
			return $pathArray;
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
	public function mailSending($emailId,$documentPathName,$emailTemplate,$clientName,$companyName,$invoiceNumber,$documentType)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$constantEmailArray = $constantClass->setEmailPassword();
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
		// $mail->IsSMTP();  
        // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // swaminarayancycles.com Specify main and backup server //
		$mail->Port =  587;      // Set the SMTP port 465
		
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;  // Enable SMTP authentication
		// SMTP password
		$mail->SMTPSecure = 'tls'; // Enable encryption, 'ssl' also accepted
		$mail->Username = $constantEmailArray['emailId'];  // SMTP username support@swaminarayancycles.com Abcd@1234
		$mail->Password = $constantEmailArray['password']; 
		$mail->From = $constantEmailArray['emailId'];
		$mail->FromName = $constantEmailArray['emailId'];
		$doc = $documentPathName;
		 // Add a recipient
		$splitedTime = explode(' ',$mytime);
		$documentName = explode("/",$documentPathName);
		
		if(strcmp("quotation",$documentType)==0)
		{
			$name = "Quotation#".$invoiceNumber."(".$splitedTime.").pdf";
			$documentPathName = $constantArray['quotationDocUrl'].$documentName[2];
		}
		else
		{
			$name = "Invoice#".$invoiceNumber."(".$splitedTime.").pdf";
			$documentPathName = $constantArray['billUrl'].$documentName[2];
		}
		$mail->AddAddress($email); 
		$mail->AddAttachment($documentPathName,$name,'base64','application/octet-stream');	
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $constantArray['emailSubject'];
		$mail->Body    = $message;
		$mail->AltBody = $message;
		if(!$mail->Send()) {
		 	
			// print_r($mail->ErrorInfo);
		   return $exceptionArray['Email'];
		}
		return $message;
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
	public function quotationMpdfGenerate($templateData,$quotationData,$emailTemplateData,$headerData)
	{		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$commentArray = $constantClass->getCommentMessage();
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
			   <td colspan="2" class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $price.'</td>
				<td  class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">PCS</td>
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
		$roundTotal = round($totalAmount);
		$roundUpFigure = $roundTotal-$totalAmount;
		$roundUpFigure = number_format($roundUpFigure,$decodedData[0]->company->noOfDecimalPoints,'.','');
		
		//calculation of currecy to word conversion
		$currecyToWordConversion = new DocumentMpdf();
		$currencyResult = $currecyToWordConversion->conversion($roundTotal);
		
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$quotationData->client->clientName;
		$billArray['Company']="<span style='font-size:22px'>".$quotationData->company->companyName."</span>";
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
		ini_set('memory_limit', '256M');
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
			if($quotationData->client->emailId!="")
			{
				// mail send
				$result = $this->mailSending($quotationData->client->emailId,$documentPathName,$emailTemplateData,$quotationData->client->clientName,$quotationData->company->companyName,$quotationData->quotationNumber,$documentType);
				if(strcmp($result,$exceptionArray['Email'])==0)
				{
					return $result;
				}	
				else
				{
					$subject = $constantArray['emailSubject'];
					$conversationType = $constantArray['emailType'];
					$conversation = $result;
					$documentPath = $documentPathName;
					$comment = $commentArray->quotationMailSend;
					$emailId = $quotationData->client->emailId;
					$companyId = $quotationData->company->companyId;
					$clientId = $quotationData->client->clientId;
					// mail description saved in conversation-database
					$conversationModel = new ConversationModel();
					$conversationResult = $conversationModel->saveMailDataFromBill($emailId,$subject,$conversationType,$conversation,$documentName,$documentFormat,$documentPath,$comment,$companyId,$clientId,$headerData);
				}		
			}
		}	
		return $pathArray;
	}
	
	/**
	* pdf generation
	* @param template-data and job-form data
	* @return error-message/document-path
	*/
	public function jobFormMpdfGenerate($templateData,$jobFormData)
	{		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$htmlBody = json_decode($templateData)[0]->templateBody;
		
		$jobCardId = $jobFormData[0]->jobCardId;
		
		$decodedArray = json_decode($jobFormData[0]->productArray);
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
		for($productArray=0;$productArray<count($decodedArray);$productArray++)
		{
			//get product-data
			$productData[$productArray] = $productService->getProductData($decodedArray[$productArray]->productId);
			$decodedData[$productArray] = json_decode($productData[$productArray]);
			
			//calculate margin value
			$marginValue[$productArray]=($decodedData[$productArray]->margin/100)*$decodedArray[$productArray]->price;
			$marginValue[$productArray] = $marginValue[$productArray]+$decodedData[$productArray]->marginFlat;
			
			$totalPrice = $decodedArray[$productArray]->price*$decodedArray[$productArray]->qty;
			if(strcmp($decodedArray[$productArray]->discountType,"flat")==0)
			{
				$discountValue[$productArray] = $decodedArray[$productArray]->discount;

			}
			else
			{
				$discountValue[$productArray] = ($decodedArray[$productArray]->discount/100)*$totalPrice;
			}
			$finalVatValue = $totalPrice - $discountValue[$productArray];
			
			//calculate vat value;
			$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*$finalVatValue;
			
			//calculate additional tax
			$additionalTaxValue[$productArray] = ($decodedData[$productArray]->additionalTax/100)*$finalVatValue;
			$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray] +$additionalTaxValue[$productArray];
			
			$price = number_format($decodedArray[$productArray]->price,$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
			$trClose = "</td></tr>";
			if($productArray==0)
			{
				$output =$output.$trClose;
			}
			if(empty($decodedArray[$productArray]->color))
			{
				$decodedArray[$productArray]->color="";
			}
			if(empty($decodedArray[$productArray]->frameNo))
			{
				$decodedArray[$productArray]->frameNo="";
			}
			$totalVatValue = $totalVatValue+$vatValue[$productArray];
			$totalAdditionalTax=$totalAdditionalTax+$additionalTaxValue[$productArray];
			$totalQty=$totalQty+$decodedArray[$productArray]->qty;
			$totalAmount=$totalAmount+$total[$productArray];
			
			//convert (number_format)as per company's selected decimal points
			$vatValue[$productArray] = number_format($vatValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
			$additionalTaxValue[$productArray] = number_format($additionalTaxValue[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints);
			$total[$productArray] = number_format($total[$productArray],$decodedData[$productArray]->company->noOfDecimalPoints,'.','');
			$output =$output."".
				'<tr class="trhw" style="font-family: Calibri; text-align: left; height:  0.7cm; background-color: transparent;">
			   <td class="tg-m36b thsrno" style="font-size: 14px; height: 0.7cm; text-align:center; padding:0 0 0 0;">'.$index.'</td>
			   <td colspan="3" class="tg-m36b theqp" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;">'. $decodedData[$productArray]->productName.'</td>
			   <td colspan="2" class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;"></td>
			   <td class="tg-ullm thsrno" style="font-size: 14px;  height:  0.7cm; padding:0 0 0 0;"></td>
			   <td class="tg-ullm thsrno" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $decodedArray[$productArray]->qty.'</td>
			   <td colspan="2" class="tg-ullm thsrno" style="font-size: 14px; height:  0.7cm; text-align: center; padding:0 0 0 0;">'. $price.'</td>
				<td  class="tg-ullm thamt" style="font-size: 14px;   height:  0.7cm; text-align: center; padding:0 0 0 0;">PCS</td>
			   <td class="tg-ullm thamt" style="font-size: 14px;  height: 0.7cm; text-align: center; padding:0 0 0 0;">'.$total[$productArray];
			if($productArray != count($decodedArray)-1)
			{
				$output = $output.$trClose;
			
			}
			if($productArray==(count($decodedArray)-1))
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
		$address = $jobFormData[0]->client->address1;
		$companyAddress = $jobFormData[0]->company->address1.",".$jobFormData[0]->company->address2;
		
		//add 1 month in entry date for displaying expiry date
		$date = date_create($jobFormData[0]->entryDate);
		date_add($date, date_interval_create_from_date_string('30 days'));
		$expiryDate = date_format($date, 'd-m-Y');
		$totalTax = $totalVatValue+$totalAdditionalTax;
		// convert amount(number_format) into their company's selected decimal points
		$totalTax = number_format($totalTax,$decodedData[0]->company->noOfDecimalPoints,'.','');
		$totalAmount = number_format($totalAmount,$decodedData[0]->company->noOfDecimalPoints,'.','');

		$jobFormArray = array();
		$jobFormArray['Description']=$output;
		$jobFormArray['ClientName']=$jobFormData[0]->client->clientName;
		$jobFormArray['Company']="<span style='font-family: algerian;font-size:22px'>".$jobFormData[0]->company->companyName."</span>";
		$jobFormArray['Total']=$totalAmount;
		$jobFormArray['Mobile']=$jobFormData[0]->client->contactNo;
		$jobFormArray['QuotationNo']=$jobFormData[0]->jobCardNo;
		$jobFormArray['CLIENTADD']=$address;
		$jobFormArray['OrderDate']=$jobFormData[0]->entryDate;
		$jobFormArray['TotalQty']=$totalQty;
		$jobFormArray['TotalInWord']=$currencyResult;
		$jobFormArray['displayNone']='none';
		$jobFormArray['CMPLOGO']="<img src='".$constantArray['mainLogo']."MainLogo.png'/>";
		$jobFormArray['CompanyAdd']=$companyAddress;
		$jobFormArray['CLIENTTINNO']="";
		$mpdf = new mPDF('A4','landscape');
		// $mpdf = new mPDF('','A4','','agency','0','0','0','0','0','0','landscape');
		$mpdf->SetDisplayMode('fullpage');
		foreach($jobFormArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		
		$mpdf->WriteHTML($htmlBody);
		$path = $constantArray['jobFormDocUrl'];
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999)."_jobForm.pdf";
		$documentPathName = $path.$documentName;
		$documentFormat="pdf";
		$documentType ="job_card";
		
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
		//pdf generate
		$mpdf->Output($documentPathName,'F');
		
		//insertion quotation document data into database
		$jobFormModel = new JobFormModel();
		$jobFormDocumentStatus = $jobFormModel->jobFormDocumentData($jobFormData[0]->jobCardId,$documentName,$documentFormat,$documentType);
		
		if(strcmp($exceptionArray['500'],$jobFormDocumentStatus)==0)
		{
			return $jobFormDocumentStatus;
		}
		else
		{
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return json_encode($pathArray);
		}	
	}

	/**
	* check value
	* @param integer value
	* @return tax-value/0
	*/
	public function checkValue($tax)
	{
		if($tax=='' || strcmp($tax,'undefined')==0 || is_NaN(floatval($tax)) || $tax==null)
		{
			return 0;
		}
		return $tax;	
	}
}
