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
	public function generatePdf($data)
	{
		$decodedData = json_decode($data);
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$headerPart = '<table border="2">
						<thead>
							<tr>
								<th>product Name</th>
								<th>Price</th>
								<th>Vat</th>
								<th>Final Amount</th>
							</tr>
						</thead>';
						
		$bodyPart = "";
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			$bodyPart = $bodyPart."	<tr><td>".$decodedData[$arrayData]->product->productName."</td>
									<td>".$decodedData[$arrayData]->price."</td>
									<td>".$decodedData[$arrayData]->tax."</td>
									<td>".$decodedData[$arrayData]->price*$decodedData[$arrayData]->qty."</td></tr>";
		}
		$footerPart = "</table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		//generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['priceList'];
		$documentPathName = $path.$documentName;
		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}