<?php
namespace ERP\Core\Products\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PriceListMpdf
{
	public function generatePdf($headerData,$data)
	{
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black;'>
							<tr style='border: 1px solid black;'>
								<th style='border: 1px solid black;'>product Name</th>
								<th style='border: 1px solid black;'>Price</th>
								<th style='border: 1px solid black;'>Vat</th>
								<th style='border: 1px solid black;'>Final Amount</th>
							</tr>
						</thead><tbody>";
		$bodyPart = "";
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			if(strcmp($headerData['salestype'][0],'retail_sales')==0)
			{
				if($decodedData[$arrayData]->purchasePrice==0 || $decodedData[$arrayData]->purchasePrice=="")
				{
					$decodedData[$arrayData]->purchasePrice = $decodedData[$arrayData]->mrp;
				}
				$margin[$arrayData] = ($decodedData[$arrayData]->margin/100)*$decodedData[$arrayData]->purchasePrice;
				$margin[$arrayData] = $margin[$arrayData]+$decodedData[$arrayData]->marginFlat;
				$decodedData[$arrayData]->purchasePrice = $decodedData[$arrayData]->purchasePrice +$margin[$arrayData];
				$decodedData[$arrayData]->vat = ($decodedData[$arrayData]->vat/100)*$decodedData[$arrayData]->purchasePrice;
				$totalAmount[$arrayData] = $decodedData[$arrayData]->purchasePrice+$decodedData[$arrayData]->vat;
			}	
			else
			{
				$wholeSaleMargin[$arrayData] = ($decodedData[$arrayData]->wholesaleMargin/100)*$decodedData[$arrayData]->purchasePrice;
				$wholeSaleMargin[$arrayData] = $wholeSaleMargin[$arrayData]+$decodedData[$arrayData]->wholesaleMarginFlat;
				$decodedData[$arrayData]->purchasePrice = $decodedData[$arrayData]->purchasePrice +$wholeSaleMargin[$arrayData];
				$decodedData[$arrayData]->vat = ($decodedData[$arrayData]->vat/100)*$decodedData[$arrayData]->purchasePrice;;
				$totalAmount[$arrayData] = $decodedData[$arrayData]->purchasePrice+$decodedData[$arrayData]->vat;
			}
			
			//convert amount(round) into their company's selected decimal points
			$decodedData[$arrayData]->purchasePrice = round($decodedData[$arrayData]->purchasePrice,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$decodedData[$arrayData]->vat = round($decodedData[$arrayData]->vat,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$totalAmount[$arrayData] = round($totalAmount[$arrayData],$decodedData[$arrayData]->company->noOfDecimalPoints);
			
			$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->productName."</td>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->purchasePrice."</td>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->vat."</td>
									<td style='border: 1px solid black;'>".$totalAmount[$arrayData]."</td></tr>";
			
		}
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		//generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['priceList'];
		$documentPathName = $path.$documentName;
		$mpdf = new mPDF('A4','landscape');
		
		$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold; font-size:20px;">Price List</div>');
		
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}