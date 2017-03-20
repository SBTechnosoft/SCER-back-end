<?php
namespace ERP\Core\Accounting\ProfitLoss\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Companies\Services\CompanyService;
use stdclass;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProfitLossOperation extends CompanyService
{
	/**
	 * set Data into the pdf file 
	 * $param database data
	 * @return the array of document-path/exception message
	*/
	public function generatePdf($data)
	{
		$decodedData = json_decode($data);
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//calculate data
		$balanceSheetArrayData = $this->getCalculatedData($decodedData);
		$balanceArray = $balanceSheetArrayData['arrayData'];
		print_r($balanceArray);
		exit;
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black; width:100%;'>
							<tr style='border: 1px solid black;width:100%;'>
								<th style='border: 1px solid black; width:50%;'>Ledger-Name</th>
								<th style='border: 1px solid black;width:25%;'>Income</th>
								<th style='border: 1px solid black;width:25%;'>Expense</th>
							</tr>
						</thead><tbody>";
		$profitLoss = new ProfitLossOperation();
		$companyDetail = $profitLoss->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		
		//make a table and set data for pdf
		$expenseTotal = 0;
		$incomeTotal = 0;
		$bodyPart = "";
		for($balanceSheetArray=0;$balanceSheetArray<count($decodedData);$balanceSheetArray++)
		{
			if(strcmp($decodedData[$balanceSheetArray]->amountType,'credit')==0)
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
					  <td style='border: 1px solid black; width:50%; text-align:center;'>".$decodedData[$balanceSheetArray]->ledger->ledgerName."</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>".$decodedData[$balanceSheetArray]->amount."</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>-</td>";
				$incomeTotal = $incomeTotal+$decodedData[$balanceSheetArray]->amount;
			}
			else
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
					  <td style='border: 1px solid black; width:50%; text-align:center;'>".$decodedData[$balanceSheetArray]->ledger->ledgerName."</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>-</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>".$decodedData[$balanceSheetArray]->amount."</td>";
				$expenseTotal = $expenseTotal+$decodedData[$balanceSheetArray]->amount;
			}
		}
		$difference = $incomeTotal-$expenseTotal;
		
		//convert into decimal points
		$difference = number_format($difference,$decodedCompanyData->noOfDecimalPoints);
		$expenseTotal = number_format($expenseTotal,$decodedCompanyData->noOfDecimalPoints);
		$incomeTotal = number_format($incomeTotal,$decodedCompanyData->noOfDecimalPoints);
		
		$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
					  <td style='border: 1px solid black; width:50%; text-align:center;'>Total</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>".$incomeTotal."</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>".$expenseTotal."</td>";
		
		$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
					  <td style='border: 1px solid black; width:50%; text-align:center;'>Difference</td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'></td>
					  <td style='border: 1px solid black;width:25%; text-align:center;'>".$difference."</td>";
					  
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		//generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['profitLossPdf'];
		
		//delete older files
		$files = glob($path.'*'); // get all file names
		foreach($files as $file)
		{ 
			// iterate files
			if(is_file($file))
			{
				unlink($file); // delete file
			}
		}
		
		$documentPathName = $path.$documentName;
		$mpdf = new mPDF('A4','landscape');
		
		$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold; font-size:20px;">Profit Loss</div>');
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
	
	/**
	 * set Data into the excel file 
	 * $param database data
	 * @return the array of document-path/exception message
	*/
	public function generateExcel($data)
	{
		//decode the database data
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$companyService = new ProfitLossOperation();
		$companyDetail = $companyService->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		// print_r($decodedData);
		// exit;
		// generate excel
		$objPHPExcel = new \PHPExcel();
		// Set properties comment
		$objPHPExcel->getProperties()->setCreator("ThinkPHP")
						->setLastModifiedBy("Daniel Schlichtholz")
						->setTitle("Office 2007 XLSX Test Document")
						->setSubject("Office 2007 XLSX Test Document")
						->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
						->setKeywords("office 2007 openxml php")
						->setCategory("Test result file");
		$objPHPExcel->getActiveSheet()->setTitle('ProfitLoss');
		
		//heading-start
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'Profit-Loss');
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, 'Ledger-Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Income');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Expense');
		//heading-end
		
		//set data into excel-sheet
		$expenseTotal = 0;
		$incomeTotal = 0;
		for($balanceSheetArray=0;$balanceSheetArray<count($decodedData);$balanceSheetArray++)
		{
			if(strcmp($decodedData[$balanceSheetArray]->amountType,'credit')==0)
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$balanceSheetArray+3,$decodedData[$balanceSheetArray]->ledger->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$balanceSheetArray+3,$decodedData[$balanceSheetArray]->amount);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$balanceSheetArray+3,"-");
				$incomeTotal = $incomeTotal+$decodedData[$balanceSheetArray]->amount;
			}
			else
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$balanceSheetArray+3,$decodedData[$balanceSheetArray]->ledger->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$balanceSheetArray+3,"-");
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$balanceSheetArray+3,$decodedData[$balanceSheetArray]->amount);
				$expenseTotal = $expenseTotal+$decodedData[$balanceSheetArray]->amount;
			}
		}
		$difference = $incomeTotal-$expenseTotal;
		
		$difference= number_format($difference,$decodedCompanyData->noOfDecimalPoints);
		$incomeTotal= number_format($incomeTotal,$decodedCompanyData->noOfDecimalPoints);
		$expenseTotal= number_format($expenseTotal,$decodedCompanyData->noOfDecimalPoints);
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($decodedData)+3,'Total');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($decodedData)+3,$incomeTotal);
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,count($decodedData)+3,$expenseTotal);
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($decodedData)+4,'Difference');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($decodedData)+4,"-");
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,count($decodedData)+4,$difference);
		
		// style for header
		$headerStyleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '#00000'),
			'size'  => 10,
			'name'  => 'Verdana'
		));
		
		// style for Title
		$titleStyleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'Black'),
			'size'  => 15,
			'name'  => 'Verdana'
		));
		
		// set header style
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($headerStyleArray);
		
		// set title style
		$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($titleStyleArray);
		
		// make unique name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
		$path = $constantArray['profitLossExcel'];
		$documentPathName = $path.$documentName;
		
		//delete older files
		// $files = glob($path.'*'); // get all file names
		// print_r($files);
		// foreach($files as $file)
		// { 
			// iterate files
			// if(is_file($file))
			// {
				// echo $file;
				// unlink($file); // delete file
				// echo "eee";
			// }
		// }
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($documentPathName);
		
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
	
	/**
	 * calculate given data and returns the result
	 * $param database decoded-data
	 * @return the array-result
	*/
	public function getCalculatedData($decodedData)
	{
		$trialBalanceArray = array();
		$totalDebit = 0;
		$totalCredit = 0;
		$dataLength = count($decodedData)-1;
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++) 
		{
			$dataOfTrial = $decodedData[$arrayData];
			$innerArray = array();
			$trialObject = new stdclass();
			$trialObject->ledgerId = $dataOfTrial->ledger->ledgerId;
			$trialObject->ledgerName = $dataOfTrial->ledger->ledgerName;
			$trialObject->amountType = $dataOfTrial->amountType;
			if($dataOfTrial->amountType == 'debit')
            {
				$trialObject->debitAmount = $dataOfTrial->amount;
				$trialObject->creditAmount = "-";
				$totalDebit +=$dataOfTrial->amount;
				$cntLen = count($trialBalanceArray);
				if($cntLen > 0)
				{
					$inFlag = 0;
					for($innerLoop=0;$innerLoop<$cntLen;$innerLoop++)
					{
						if(!array_key_exists("1",$trialBalanceArray[$innerLoop]))
						{	
							$inFlag = 1;
							$trialBalanceArray[$innerLoop][1] = $trialObject;
							break;
						}
					}
					if($inFlag == 0)
					{
						$innerArray[1] = $trialObject;
						array_push($trialBalanceArray,$innerArray);
					}
				}
				else
				{
					$innerArray[1] = $trialObject;
					array_push($trialBalanceArray,$innerArray);
				}
			}
			else
			{
				$trialObject->debitAmount = "-";
				$trialObject->creditAmount = $dataOfTrial->amount;
				
				$totalCredit += $dataOfTrial->amount;
				$cntLen = count($trialBalanceArray);
				if($cntLen > 0)
				{
					$inFlag = 0;
					for($innerLoop=0;$innerLoop<$cntLen;$innerLoop++)
					{
						if(!array_key_exists("0",$trialBalanceArray[$innerLoop]))
						{
							$inFlag = 1;
							$trialBalanceArray[$innerLoop][0] = $trialObject;
							break;
						}
					}
					if($inFlag == 0)
					{
						$innerArray[0] = $trialObject;
						array_push($trialBalanceArray,$innerArray);
					}
				}
				else
				{
					$innerArray[0] = $trialObject;
					array_push($trialBalanceArray,$innerArray);
				}
			}
		}
		$finalArray = array();
		$finalArray['totalCredit'] = $totalCredit;
		$finalArray['totalDebit'] = $totalDebit;
		$finalArray['arrayData'] = $trialBalanceArray;
		return $finalArray;
	}
}