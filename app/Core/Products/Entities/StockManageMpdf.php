<?php
namespace ERP\Core\Products\Entities;

use mPDF;
use stdClass;
use ERP\Entities\Constants\ConstantClass;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StockManageMpdf
{
	public function calculateBalance($data)
	{
		$decodedData = json_decode($data);
		$balanceArray = array();
		$balance = array();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$decodedData = $decodedData[0];
		
		for($productTrnData = 0;$productTrnData<count($decodedData);$productTrnData++)
		{
			$inward = new stdClass();
			$outward = new stdClass();
			if(strcmp($decodedData[$productTrnData]->transactionType,'Inward')==0)
			{
				if(count($balanceArray)==0)
				{
					$inward->qty = $decodedData[$productTrnData]->qty;
					$inward->price = $decodedData[$productTrnData]->qty * $decodedData[$productTrnData]->price;
					$inward->transactionDate = $decodedData[$productTrnData]->transactionDate;
					array_push($balanceArray,$inward);
				}
				else
				{
					if($balanceArray[0]->qty <0)
					{
					   	$outwardExtra = new stdClass();
						$outwardExtra->qty =0;
						$outwardExtra->transactionDate = $decodedData[$productTrnData]->transactionDate;
						$inward->qty = $decodedData[$productTrnData]->qty;
						$index=0;
						for($arrayData=0;$arrayData<count($balanceArray);$arrayData++)
						{
							 $diff =  $inward->qty+$balanceArray[$index]->qty;
							 if($diff==0 || $diff>0)
							 {
								$inward->qty = $diff;
								if($arrayData == count($balanceArray)-1 && $inward->qty>0)
								{
									$balanceArray[0] = $inward;
								}
								else
								{
									array_splice($balanceArray,$index,1);
								}
							}
							 else if($diff<0)
							 {							
								 $purchasePrice = $balanceArray[$index]->price/$balanceArray[$index]->qty;
								 $outwardExtra->qty = $balanceArray[$index]->qty + $inward->qty;
								 $outwardExtra->price = 1000;
								 $extra = new stdClass();
								 $extra = clone $outwardExtra;
								 $balanceArray[$index] = $extra;
								 $inward->qty = 0;
								 $index++;
							 }
							 else
							 {
							 }
						}
					}
					else
					{
						$inward->qty = $decodedData[$productTrnData]->qty;
						$inward->price = $decodedData[$productTrnData]->qty * $decodedData[$productTrnData]->price;
						$inward->transactionDate = $decodedData[$productTrnData]->transactionDate;
						array_push($balanceArray,$inward);
					}
				}
			}
			else
			{
				 $outwardExtra = new stdClass();
				 $outwardExtra->qty=0;
				 $outwardExtra->price=$decodedData[$productTrnData]->price;
				 $outwardExtra->transactionDate=$decodedData[$productTrnData]->transactionDate;
				
				 $outward->qty=$decodedData[$productTrnData]->qty;
				 $outward->price=$decodedData[$productTrnData]->price;
				 $outward->transactionDate=$decodedData[$productTrnData]->transactionDate;
				 if(count($balanceArray)==0)
				 {
					 $minusObject = new stdClass();
					 $minusObject->qty=$decodedData[$productTrnData]->qty * -1;
					 $minusObject->price=$decodedData[$productTrnData]->price;
					 $minusObject->transactionDate=$decodedData[$productTrnData]->transactionDate;
					 array_push($balanceArray,$minusObject);
				 }
				 else
				 {
					 if($balanceArray[0]->qty > $outward->qty)
					 {
						 $purchasePrice = $balanceArray[0]->price/$balanceArray[0]->qty;
						 $outward->qty = $balanceArray[0]->qty-$outward->qty;
						 $outward->price = $outward->qty*$purchasePrice;
						 $balanceArray[0] = $outward;
					 } 
					 else if($balanceArray[0]->qty == $outward->qty)
					 {
						 array_splice($balanceArray,0,1);
					 }
					 else if($balanceArray[0]->qty < $outward->qty)
					 {
						 $index=0;
						 $countBalanceArray = count($balanceArray);
						 for($balanceArrayData=0;$balanceArrayData<$countBalanceArray;$balanceArrayData++)
						 {
							 $diff = $outward->qty - $balanceArray[$index]->qty;
							 if($diff==0 || $diff>0)
							 {
								 $outward->qty = $diff;
								 if($balanceArrayData == $countBalanceArray-1 && $outward->qty > 0)
								 {
									 $outwardExtra->qty = $outward->qty * -1;
									 $balanceArray[0] = $outwardExtra;
								 }
								 else
								 {
									 array_splice($balanceArray,$index,1);
								 }
							}
							 else if($diff<0)
							 {							
								 $purchasePrice = $balanceArray[$index]->price/$balanceArray[$index]->qty;
								 $outwardExtra->qty = $balanceArray[$index]->qty - $outward->qty;
								 $outwardExtra->price = $outwardExtra->qty * $purchasePrice;
								 $extra = new stdClass();
								 $extra = clone $outwardExtra;
								 $balanceArray[$index] = $extra;
								 $outward->qty = 0;
								 $index++;
							 }
							 else
							 {
							 }
						 }
					 }
					 else
					 {
					 }
				 }		
			}
			 
		
			$balance[$productTrnData] = array_slice($balanceArray,0);
			$decodedData[$productTrnData]->balance = $balance[$productTrnData];
		}
		
		$headerPart = "<table style='border: 1px solid black; width:100%'>
			<thead style='border: 1px solid black;'>
				<tr style='border: 1px solid black;'>
					<th  colspan='4' style='border: 1px solid black;'>Inward/Outward</th>
					
					<th  colspan='3' style='border: 1px solid black;'>Balance</th>
				</tr>
				<tr>
					<th style='border: 1px solid black;'>Dt.</th>
					<th style='border: 1px solid black;'>Sale/purchase</th>
					<th style='border: 1px solid black;'>Qty</th>
					<th style='border: 1px solid black;'>Amount</th>
					
					<th style='border: 1px solid black;'>Dt.</th>
					<th style='border: 1px solid black;'>Qty</th>
					<th style='border: 1px solid black;'>Amount</th>
				</tr>
			</thead>";
			
		$footerPart = '</table>';
		$bodyPart="";
		$mainPart = "";
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			$balanceCount = count($decodedData[$arrayData]->balance);
			$ifCondition="";
			if($balanceCount==0)
			{
				$balanceCount=1;
			}
			
			if(strcmp($decodedData[$arrayData]->transactionType,'Inward')==0)
			{
				$transactionType = "purchase";
			}
			else
			{
				$transactionType = "sales";
			}
			$bodyPart =
				"<tbody>
					<tr style='background-color: white; border: 1px solid black;'>
				";
				
				if($arrayData==0)
				{
					$ifCondition = 
						"<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[0]->transactionDate."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$transactionType."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[0]->qty."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[0]->price."</td>";
				}
				else
				{
					$ifCondition = 
						"<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[$arrayData]->transactionDate."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$transactionType."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[$arrayData]->qty."</td>
						<td rowspan='".$balanceCount."' style='border: 1px solid black;'>".$decodedData[$arrayData]->price."</td>";
				}
				$middleOne = "<td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[0]->transactionDate."</td>
						<td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[0]->qty."</td>
						<td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[0]->price."</td></tr>";
				
				
				
				$loopPart="";
				for($balanceArrayData=1;$balanceArrayData<count($decodedData[$arrayData]->balance);$balanceArrayData++)
				{
					$loopPart= $loopPart.
						"<tr style='border: 1px solid black;'><td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[$balanceArrayData]->transactionDate."</td>
						<td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[$balanceArrayData]->qty."</td>
						<td style='border: 1px solid black;'>".$decodedData[$arrayData]->balance[$balanceArrayData]->price."</td></tr>";
				}
				$lastOne = "</tbody>";
				$finalSettle = $bodyPart.$ifCondition.$middleOne.$loopPart.$lastOne;
				$mainPart = $mainPart.$finalSettle;
		}
		//generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['stockUrl'];
		$documentPathName = $path.$documentName;
		$htmlBody = $headerPart.$mainPart.$footerPart;
		$mpdf = new mPDF('A4','landscape');
		$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold; font-size:20px;">Stock Manage</div>');
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}