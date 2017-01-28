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
		
		for($productTrnData = 0;$productTrnData<count($decodedData);$productTrnData++)
		{
			$inward = new stdClass();
			$outward = new stdClass();
			if(strcmp($decodedData[$productTrnData]->transactionType,'Inward')==0)
			{
				$inward->qty = $decodedData[$productTrnData]->qty;
				$inward->price = $decodedData[$productTrnData]->qty * $decodedData[$productTrnData]->price;
				$inward->transactionDate = $decodedData[$productTrnData]->transactionDate;
				array_push($balanceArray,$inward);
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
						 $diff = $outward->qty-$balanceArray[$index]->qty;
						 if($diff==0 || $diff>0)
						 {
							$outward->qty = $diff;
							 array_splice($balanceArray,$index,1);
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
					 }
				 }
				 else
				 {
				 }
			}
			$balance[$productTrnData] = array_slice($balanceArray,0);
			$decodedData[$productTrnData]->balance = $balance[$productTrnData];
		}
		
		$headerPart = '<table border="2">
			<thead>
				<tr>
					<th  colspan="4" >Inward/Outward</th>
					
					<th  colspan="3" >Balance</th>
				</tr>
				<tr>
					<th >Dt.</th>
					<th >Sale/purchase</th>
					<th>Qty</th>
					<th >Amount</th>
					
					<th >Dt.</th>
					<th>Qty</th>
					<th >Amount</th>
				</tr>
			</thead>';
			
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
					<tr style='background-color: white;'>
				";
				
				if($arrayData==0)
				{
					$ifCondition = 
						"<td rowspan='".$balanceCount."'>".$decodedData[0]->transactionDate."</td>
						<td rowspan='".$balanceCount."'>".$transactionType."</td>
						<td rowspan='".$balanceCount."'>".$decodedData[0]->qty."</td>
						<td rowspan='".$balanceCount."'>".$decodedData[0]->price."</td>";
				}
				else
				{
					$ifCondition = 
						"<td rowspan='".$balanceCount."'>".$decodedData[$arrayData]->transactionDate."</td>
						<td rowspan='".$balanceCount."'>".$transactionType."</td>
						<td rowspan='".$balanceCount."'>".$decodedData[$arrayData]->qty."</td>
						<td rowspan='".$balanceCount."'>".$decodedData[$arrayData]->price."</td>";
				}
				$middleOne = "<td>".$decodedData[$arrayData]->balance[0]->transactionDate."</td>
						<td>".$decodedData[$arrayData]->balance[0]->qty."</td>
						<td>".$decodedData[$arrayData]->balance[0]->price."</td></tr>";
				
				
				
				$loopPart="";
				for($balanceArrayData=1;$balanceArrayData<count($decodedData[$arrayData]->balance);$balanceArrayData++)
				{
					$loopPart= $loopPart.
						"<tr><td>".$decodedData[$arrayData]->balance[$balanceArrayData]->transactionDate."</td>
						<td>".$decodedData[$arrayData]->balance[$balanceArrayData]->qty."</td>
						<td>".$decodedData[$arrayData]->balance[$balanceArrayData]->price."</td></tr>";
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
		$mpdf = new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}