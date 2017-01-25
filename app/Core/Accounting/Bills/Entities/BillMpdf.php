<?php
namespace ERP\Core\Accounting\Bills\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Accounting\Bills\BillModel;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Products\Services\ProductService;
use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers\InvoiceController;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillMpdf 
{
	public function mpdfGenerate($templateData,$status)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$htmlBody = json_decode($templateData)[0]->template_body;
		$decodedBillData = json_decode($status);
		if(is_object($decodedBillData))
		{
			$saleId = $decodedBillData->saleId;		
		}
		else
		{
			$saleId = $decodedBillData[0]->sale_id;
		}
		
		//update invoice data (endAt)
		$decodedArray = json_decode($decodedBillData->productArray);
		$productService = new ProductService();
		$productData = array();
		$decodedData = array();
		$index=1;
		
		$invoiceService = new InvoiceService();	
		$invoiceData = $invoiceService->getLatestInvoiceData($decodedBillData->company->companyId);
		if(strcmp($exceptionArray['204'],$invoiceData)==0)
		{
			return $invoiceData;
		}
		$endAt = json_decode($invoiceData)->endAt;
		$invoiceController = new InvoiceController(new Container());
		$invoiceMethod=$constantArray['postMethod'];
		$invoicePath=$constantArray['invoiceUrl'];
		$invoiceDataArray = array();
		$invoiceDataArray['endAt'] = $endAt+1;
		
		$invoiceRequest = Request::create($invoicePath,$invoiceMethod,$invoiceDataArray);
		$updateResult = $invoiceController->update($invoiceRequest,json_decode($invoiceData)->invoiceId);
		
		$output="";
		$totalAmount =0;
		
		if(strcmp($decodedBillData->salesType,"retail_sales")==0)
		{
			for($productArray=0;$productArray<count($decodedArray->inventory);$productArray++)
			{
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				
				// $retailValue = $decodedData[$productArray]->purchasePrice;
				// if($retailValue=="" || $retailValue==0)
				// {
					// $retailValue=$decodedData[$productArray]->mrp;
					// $decodedData[$productArray]->purchasePrice=$decodedData[$productArray]->mrp;
				// }
				
				//calculate margin value
				$marginValue[$productArray]=($decodedData[$productArray]->margin/100)*$decodedArray->inventory[$productArray]->price;
				
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
				
				
				
				$total[$productArray] =($totalPrice)-$discountValue[$productArray]+$vatValue[$productArray];
				$output =$output."".
				'<tr><td class= style="padding: 10px 5px; top: 0px;font-family: Calibri; font-size: 12px; vertical-align: bottom; color: black;" colspan="0">'.$index.'</td> 
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1;" colspan="1"> '. $decodedData[$productArray]->productName.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->qty.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->price.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $discountValue[$productArray].'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedData[$productArray]->vat.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$vatValue[$productArray].'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$total[$productArray].'</td></tr>';
				 $index++;
				 $totalAmount=$totalAmount+$total[$productArray];
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
				$totalPrice[$productArray] = $decodedArray->inventory[$productArray]->price*$decodedArray->inventory[$productArray]->qty;
				if(strcmp($decodedArray->inventory[$productArray]->discountType,"flat")==0)
				{
					$discountValue[$productArray] = $decodedArray->inventory[$productArray]->discount;
				}
				else
				{
					$discountValue[$productArray] = ($decodedArray->inventory[$productArray]->discount/100)*$totalPrice[$productArray];
				}
				
				$total[$productArray] = $totalPrice[$productArray]-$discountValue[$productArray];
				$output =$output."".
				'<tr><td class= style="padding: 10px 5px; top: 0px;font-family: Calibri; font-size: 12px; vertical-align: bottom; color: black;" colspan="0">'.$index.'</td> 
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1;" colspan="1"> '. $decodedData[$productArray]->productName.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->qty.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->price.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $discountValue[$productArray].'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">0</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">0</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$total[$productArray].'</td></tr>';
				 $index++;
				 $totalAmount=$totalAmount+$total[$productArray];
			}
		}
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
		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
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
			$mpdf->Output($documentPathName,'F');
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return $pathArray;
		}	
	}
}
