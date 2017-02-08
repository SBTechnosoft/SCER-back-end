<?php
namespace ERP\Core\Accounting\TrialBalance\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TrialBalanceMpdf
{
	public function generatePdf($data)
	{
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black;'>
							<tr style='border: 1px solid black;'>
								<th style='border: 1px solid black;'>Particular</th>
								<th style='border: 1px solid black;'>Debit</th>
								<th style='border: 1px solid black;'>Credit</th>
							</tr>
						</thead><tbody>";
		$bodyPart = "";
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			if(strcmp($decodedData[$arrayData]->amountType,"credit")==0)
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->ledger->ledgerName."</td>
									<td style='border: 1px solid black; text-align:center;'> - </td>
									<td style='border: 1px solid black; text-align:center;'>".$decodedData[$arrayData]->amount."</td></tr>";
			}
			else
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->ledger->ledgerName."</td>
									<td style='border: 1px solid black; text-align:center;'>".$decodedData[$arrayData]->amount."</td>
									<td style='border: 1px solid black; text-align:center;'> - </td></tr>";
			}
		}
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		//generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['trialBalanceUrl'];
		$documentPathName = $path.$documentName;
		$mpdf = new mPDF('A4','landscape');
		
		$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold; font-size:20px;">Trial Balance</div>');
		
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}