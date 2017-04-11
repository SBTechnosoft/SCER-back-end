<?php
namespace ERP\Core\Accounting\TrialBalance\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Companies\Services\CompanyService;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use stdclass;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TrialBalanceOperation extends CompanyService
{
	/**
	 * set data into pdf file
	 * $param database decoded-data
	 * @return the array-result
	*/
	public function generatePdf($data)
	{
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black; width:100%;'>
							<tr style='border: 1px solid black;width:100%;'>
								<th style='border: 1px solid black; width:50%;'>Particular</th>
								<th style='border: 1px solid black;width:25%;'>Debit</th>
								<th style='border: 1px solid black;width:25%;'>Credit</th>
							</tr>
						</thead><tbody>";
		$bodyPart = "";
		$creditAmountTotal = 0;
		$debitAmountTotal = 0;
		$trialBalance = new TrialBalanceOperation();
		$companyDetail = $trialBalance->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			
			if(strcmp($decodedData[$arrayData]->amountType,"credit")==0)
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>".$decodedData[$arrayData]->ledger->ledgerName."</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'> - </td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$decodedData[$arrayData]->amount."</td></tr>";
				$creditAmountTotal = $creditAmountTotal+$decodedData[$arrayData]->amount;
			}
			else
			{
				$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>".$decodedData[$arrayData]->ledger->ledgerName."</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$decodedData[$arrayData]->amount."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'> - </td></tr>";
				$debitAmountTotal = $debitAmountTotal+$decodedData[$arrayData]->amount;
			}
		}
		$debitAmountTotal = number_format($debitAmountTotal,$decodedCompanyData->noOfDecimalPoints);
		$creditAmountTotal = number_format($creditAmountTotal,$decodedCompanyData->noOfDecimalPoints);
		$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>Total</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$debitAmountTotal."</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$creditAmountTotal."</td></tr>";
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		// generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['trialBalancePdf'];
			
		//delete older files
		// $files = glob($path.'*'); // get all file names
		// foreach($files as $file)
		// { 
			// iterate files
			// if(is_file($file))
			// {
				// unlink($file); // delete file
			// }
		// }
		
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
	
	/**
	 * set data into pdf file
	 * $param database decoded-data
	 * @return the array-result
	*/
	public function generateTwoSidePdf($data)
	{
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black; width:100%;'>
							<tr style='border: 1px solid black;width:100%;'>
								<th style='border: 1px solid black; width:50%;'>Ledger Name</th>
								<th style='border: 1px solid black;width:25%;'>Debit</th>
								<th style='border: 1px solid black; width:50%;'>Ledger Name</th>
								<th style='border: 1px solid black;width:25%;'>Credit</th>
							</tr>
						</thead><tbody>";
		$bodyPart = "";
		$creditAmountTotal = 0;
		$debitAmountTotal = 0;
		$trialBalance = new TrialBalanceOperation();
		$companyDetail = $trialBalance->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		
		$calculatedData = $this->getCalculatedData($decodedData);
		$decodedData = $calculatedData['arrayData'];
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			if(count($decodedData[$arrayData])==2)
			{
				$bodyPart = $bodyPart."<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>".$decodedData[$arrayData][1]->ledgerName."</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$decodedData[$arrayData][1]->debitAmount."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$decodedData[$arrayData][0]->ledgerName."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$decodedData[$arrayData][0]->creditAmount."</td></tr>";
			}
			else
			{
				if(array_key_exists("0",$decodedData[$arrayData]))
				{
					$bodyPart = $bodyPart."<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'></td>
									<td style='border: 1px solid black;width:25%; text-align:center;'></td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$decodedData[$arrayData][0]->ledgerName."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$decodedData[$arrayData][0]->creditAmount."</td></tr>";
				}
				else
				{
					$bodyPart = $bodyPart."<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>".$decodedData[$arrayData][1]->ledgerName."</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$decodedData[$arrayData][1]->debitAmount."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'></td>
									<td style='border: 1px solid black; width:25%;text-align:center;'></td></tr>";
				}
			}
		}
		if($calculatedData['totalDebit']>$calculatedData['totalCredit'])
		{
			$differenceDr = number_format($calculatedData['totalDebit']-$calculatedData['totalCredit']);
			$differenceCr = "-";
		}
		else
		{
			$differenceCr = number_format($calculatedData['totalCredit']-$calculatedData['totalDebit']);
			$differenceDr = "-";
		}
		$calculatedData['totalDebit'] = number_format($calculatedData['totalDebit'],$decodedCompanyData->noOfDecimalPoints);
		$calculatedData['totalCredit'] = number_format($calculatedData['totalCredit'],$decodedCompanyData->noOfDecimalPoints);
		
		$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>Total</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$calculatedData['totalDebit']."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>Total</td>;
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$calculatedData['totalCredit']."</td></tr>";
		$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>
									<td style='border: 1px solid black; width:50%;'>Difference</td>
									<td style='border: 1px solid black;width:25%; text-align:center;'>".$differenceDr."</td>
									<td style='border: 1px solid black; width:25%;text-align:center;'>Difference</td>;
									<td style='border: 1px solid black; width:25%;text-align:center;'>".$differenceCr."</td></tr>";
									
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		// generate pdf
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['trialBalancePdf'];
			
		//delete older files
		// $files = glob($path.'*'); // get all file names
		// foreach($files as $file)
		// { 
			// iterate files
			// if(is_file($file))
			// {
				// unlink($file); // delete file
			// }
		// }
		
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
	
	/**
	 * set data into excel file
	 * $param database decoded-data
	 * @return the array-result
	*/
	public function generateTwoSideExcel($data)
	{
		//decode the database data
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$companyService = new TrialBalanceOperation();
		$companyDetail = $companyService->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		
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
		$objPHPExcel->getActiveSheet()->setTitle('TrialBalance');
		
		$calculatedData = $this->getCalculatedData($decodedData);
		$decodedData = $calculatedData['arrayData'];
		
		//heading-start
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'Trial-Balance');
		
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, 'Ledger Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Debit');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, 'Ledger Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Credit');
		//heading-end
	
		//set data into excel-sheet
		$creditAmountTotal = 0;
		$debitAmountTotal = 0;
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			if(count($decodedData[$arrayData])==2)
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+3,$decodedData[$arrayData][1]->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+3,$decodedData[$arrayData][1]->debitAmount);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+3,$decodedData[$arrayData][0]->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$arrayData+3,$decodedData[$arrayData][0]->creditAmount);
			}
			else
			{
				if(array_key_exists("0",$decodedData[$arrayData]))
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+3,$decodedData[$arrayData][0]->ledgerName);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$arrayData+3,$decodedData[$arrayData][0]->creditAmount);
				}
				else
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+3,$decodedData[$arrayData][1]->ledgerName);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+3,$decodedData[$arrayData][1]->debitAmount);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$arrayData+3,'');
				}
			}
		}
		if($calculatedData['totalDebit']>$calculatedData['totalCredit'])
		{
			$differenceDr = number_format($calculatedData['totalDebit']-$calculatedData['totalCredit']);
			$differenceCr = "-";
		}
		else
		{
			$differenceCr = number_format($calculatedData['totalCredit']-$calculatedData['totalDebit']);
			$differenceDr = "-";
		}
		
		$calculatedData['totalDebit'] = number_format($calculatedData['totalDebit'],$decodedCompanyData->noOfDecimalPoints);
		$calculatedData['totalCredit'] = number_format($calculatedData['totalCredit'],$decodedCompanyData->noOfDecimalPoints);
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($decodedData)+3,'Total');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($decodedData)+3,$calculatedData['totalDebit']);
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,count($decodedData)+3,'Total');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,count($decodedData)+3,$calculatedData['totalCredit']);
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($decodedData)+4,'Difference');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($decodedData)+4,$differenceDr);
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,count($decodedData)+4,'Difference');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,count($decodedData)+4,$differenceCr);
		
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
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($headerStyleArray);
		
		// set title style
		$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($titleStyleArray);
		
		// make unique name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
		$path = $constantArray['trialBalanceExcel'];
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
	 * set data into excel file
	 * $param database decoded-data
	 * @return the array-result
	*/
	public function generateExcel($data)
	{
		//decode the database data
		$decodedData = json_decode($data);
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$companyService = new TrialBalanceOperation();
		$companyDetail = $companyService->getCompanyData($decodedData[0]->ledger->companyId);
		$decodedCompanyData = json_decode($companyDetail);
		
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
		$objPHPExcel->getActiveSheet()->setTitle('TrialBalance');
		
		//heading-start
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'Trial-Balance');
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, 'Particular');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Debit');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Credit');
		//heading-end
	
		//set data into excel-sheet
		$creditAmountTotal = 0;
		$debitAmountTotal = 0;
		for($arrayData=0;$arrayData<count($decodedData);$arrayData++)
		{
			
			if(strcmp($decodedData[$arrayData]->amountType,"credit")==0)
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+3,$decodedData[$arrayData]->ledger->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+3,'-');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+3,$decodedData[$arrayData]->amount);
				$creditAmountTotal = $creditAmountTotal+$decodedData[$arrayData]->amount;
			}
			else
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+3,$decodedData[$arrayData]->ledger->ledgerName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+3,$decodedData[$arrayData]->amount);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+3,'-');
				$debitAmountTotal = $debitAmountTotal+$decodedData[$arrayData]->amount;
			}
		}
		$debitAmountTotal = number_format($debitAmountTotal,$decodedCompanyData->noOfDecimalPoints);
		$creditAmountTotal = number_format($creditAmountTotal,$decodedCompanyData->noOfDecimalPoints);
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($decodedData)+3,'Total');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($decodedData)+3,$debitAmountTotal);
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,count($decodedData)+3,$creditAmountTotal);
		
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
		$path = $constantArray['trialBalanceExcel'];
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