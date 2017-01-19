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
				// print_r($decodedArray);
				//get product-data
				$productData[$productArray] = $productService->getProductData($decodedArray->inventory[$productArray]->productId);
				$decodedData[$productArray] = json_decode($productData[$productArray]);
				//calculate retail price
				
				//calculate margin value
				$marginValue[$productArray]=($decodedData[$productArray]->margin/100)*$decodedData[$productArray]->purchasePrice;
				
				// $finalRetailValue = $decodedData[$productArray]->purchasePrice+$marginValue[$productArray]-
				//calculate vat value;
				$vatValue[$productArray]=($decodedData[$productArray]->vat/100)*($decodedData[$productArray]->purchasePrice+$marginValue[$productArray]);
				
				
				$retailValue = $decodedData[$productArray]->purchasePrice+$vatValue[$productArray]+$marginValue[$productArray];
				if($retailValue=="" || $retailValue==0)
				{
					$retailValue=$decodedData[$productArray]->mrp;
					$decodedData[$productArray]->purchasePrice=$decodedData[$productArray]->mrp;
				}
				$total[$productArray] =($decodedData[$productArray]->purchasePrice*$decodedArray->inventory[$productArray]->qty)+$decodedArray->inventory[$productArray]->discount+$marginValue[$productArray]+$vatValue[$productArray];
				$output =$output."".
				'<tr><td class= style="padding: 10px 5px; top: 0px;font-family: Calibri; font-size: 12px; vertical-align: bottom; color: black;" colspan="0">'.$index.'</td> 
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1;" colspan="1"> '. $decodedData[$productArray]->productName.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->qty.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedData[$productArray]->purchasePrice.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->discount.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedData[$productArray]->vat.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$vatValue[$productArray].'</td>
				  <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$decodedData[$productArray]->margin.'</td>
				   <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$marginValue[$productArray].'</td>
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
				$marginPrice[$productArray] = ($decodedData[$productArray]->wholesaleMargin/100)*$decodedData[$productArray]->purchasePrice;
				$totalHalf[$productArray] = $decodedData[$productArray]->purchasePrice*$decodedArray->inventory[$productArray]->qty;
				$total[$productArray] = $totalHalf[$productArray]+$decodedArray->inventory[$productArray]->discount+$marginPrice[$productArray];
				$output =$output."".
				'<tr><td class= style="padding: 10px 5px; top: 0px;font-family: Calibri; font-size: 12px; vertical-align: bottom; color: black;" colspan="0">'.$index.'</td> 
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1;" colspan="1"> '. $decodedData[$productArray]->productName.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->qty.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedData[$productArray]->purchasePrice.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedArray->inventory[$productArray]->discount.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">0</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">0</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '. $decodedData[$productArray]->wholesaleMargin.'</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1"> '.$marginPrice[$productArray]. '</td>
				 <td class="tg-vi9z" style="background-color: #9a9a9a; padding: 20px 5px; font-family: Calibri; font-size: 12px; vertical-align: bottom; color: #e1e1e1; text-align:right;" colspan="1">'.$total[$productArray].'</td></tr>';
				 $index++;
				 $totalAmount=$totalAmount+$total[$productArray];
			}
		}
		exit;
		$billArray = array();
		$billArray['Description']=$output;
		$billArray['ClientName']=$decodedBillData->client->clientName;
		$billArray['Company']=$decodedBillData->company->companyName;
		// $billArray['OrderDate']=$decodedBillData->createdAt;
		// $billArray['OrderName']="dfs,sg/sgs-343434";
		// $billArray['Venue']=$decodedBillData->company->address1;
		// $billArray['OrderId']="500";
		// $billArray['ClientCharge']="50%";
		// --$billArray['Discount']="100";
		// --$billArray['TaxAmt']="300";
		$billArray['Total']=$totalAmount;
		// $billArray['TaxRate']="300";
		// $billArray['DeliveryDate']="300";
		// $billArray['Organization']="300";
		// $billArray['Banner_Img']="300";
		// $billArray['OrderDesc']="300";
		// $billArray['Email']="300";
		// $billArray['HomeMob']="300";
		// $billArray['WorkMob']="300";
		$billArray['Mobile']=$decodedBillData->client->contactNo;
		// $billArray['ADATE']="300";
		$billArray['INVID']=$decodedBillData->invoiceNumber;
		// $billArray['CLIENTADD']="300";
		// $billArray['CMPLOGO']="300";
		// $billArray['PAIDAMT']="300";
		// $billArray['REMAINAMT']="300";
		// $billArray['OPERATOR']="300";
		// echo "ooooooo";
		$mpdf = new mPDF();
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
