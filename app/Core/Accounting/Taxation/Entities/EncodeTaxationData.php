<?php
namespace ERP\Core\Accounting\Taxation\Entities;

use ERP\Core\Clients\Services\ClientService;
use ERP\Core\Companies\Services\CompanyService;
use Carbon;
use ERP\Core\Products\Services\ProductService;
use ERP\Entities\Constants\ConstantClass;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeTaxationData extends ProductService
{
	public function getEncodedAllData($status,$headerData)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		$data = array();
		$totalAmount = 0;
		$totalTax = 0;
		$totalAdditioanalTax = 0;
		$totalGrandTotal = 0;
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$calculateAdditionalTax=0;
			$calculateVat=0;
			$decodedProductArrayData = json_decode($decodedJson[$decodedData]['product_array']);
			for($arrayData=0;$arrayData<count($decodedProductArrayData->inventory);$arrayData++)
			{
				$productService = new EncodeTaxationData();
				$productData = $productService->getProductData($decodedProductArrayData->inventory[$arrayData]->productId);
				$productDecodedData = json_decode($productData);
				
				$vat = ($productDecodedData->purchasePrice/100)*$productDecodedData->vat;
				$calculateVat = $calculateVat+$vat;
				
				$additionalTax = ($productDecodedData->purchasePrice/100)*$productDecodedData->additionalTax;
				$calculateAdditionalTax = $calculateAdditionalTax+$additionalTax;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$tax[$decodedData] = $decodedJson[$decodedData]['tax'];
			$grandTotal[$decodedData] = $decodedJson[$decodedData]['grand_total'];
			$advance[$decodedData] = $decodedJson[$decodedData]['advance'];
			$balance[$decodedData] = $decodedJson[$decodedData]['balance'];
			$refund[$decodedData] = $decodedJson[$decodedData]['refund'];
			$entryDate[$decodedData] = $decodedJson[$decodedData]['entry_date'];
			$clientId[$decodedData] = $decodedJson[$decodedData]['client_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			$calculateGrandTotal[$decodedData] = $total[$decodedData]+$calculateVat+$calculateAdditionalTax;
			
			$clientService = new ClientService();
			$clientData[$decodedData]  = $clientService->getClientData($clientId[$decodedData]);
			$decodedClientData[$decodedData] = json_decode($clientData[$decodedData]);
			
			// convert amount(round) into their company's selected decimal points
			$companyData[$decodedData] = $companyService->getCompanyData($companyId[$decodedData]);
			$companyDecodedData[$decodedData] = json_decode($companyData[$decodedData]);
				
			$totalAmount  = $totalAmount+$total[$decodedData];
			$totalTax = $totalTax+$calculateVat;
			$totalAdditioanalTax = $totalAdditioanalTax+$calculateAdditionalTax;
			$totalGrandTotal = $totalGrandTotal+$calculateGrandTotal[$decodedData];
			
			$total[$decodedData] = number_format($total[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$advance[$decodedData] = number_format($advance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$balance[$decodedData] = number_format($balance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$refund[$decodedData] = number_format($refund[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateVat = number_format($calculateVat,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateAdditionalTax = number_format($calculateAdditionalTax,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			
			//date format conversion
			if(strcmp($entryDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$convertedEntryDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedEntryDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');
			}
			$data[$decodedData]= array(
				'invoiceNumber'=>$decodedJson[$decodedData]['invoice_number'],
				'salesType'=>$decodedJson[$decodedData]['sales_type'],
				'total'=>$total[$decodedData],
				'tax'=>$calculateVat,
				'grandTotal'=>$calculateGrandTotal[$decodedData],
				'advance'=>$advance[$decodedData],
				'balance'=>$balance[$decodedData],
				'refund'=>$refund[$decodedData],
				'entryDate'=>$convertedEntryDate[$decodedData],
				'clientName'=>$decodedClientData[$decodedData]->clientName,
				'additionalTax'=>$calculateAdditionalTax
			);
		}
		if(array_key_exists('operation',$headerData))
		{
			if(strcmp($headerData['operation'][0],'excel')==0)
			{
				$totalAmount = number_format($totalAmount,$companyDecodedData[0]->noOfDecimalPoints);
				$totalTax = number_format($totalTax,$companyDecodedData[0]->noOfDecimalPoints);
				$totalAdditioanalTax = number_format($totalAdditioanalTax,$companyDecodedData[0]->noOfDecimalPoints);
				$totalGrandTotal = number_format($totalGrandTotal,$companyDecodedData[0]->noOfDecimalPoints);
			
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
				$objPHPExcel->getActiveSheet()->setTitle('SALETAX');
				
				//heading-start
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,1, '1');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'FORM 201B');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, '2');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Tax Invoice Number');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Date');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,2, 'Name with RC number of the registered dealer from whom goods purchase');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,2, 'Turnover of purchase of taxable goods');
				
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D2:E2');
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:J2');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,3, '3');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,3, 'Purchase of goods from registered dealer');
		
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:C3');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,4, '4');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,4, 'Tax Invoice Number');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,4, 'Date');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,4, 'Name');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,4, 'R.C No');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,4, 'Goods With HSN');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,4, 'Value Of Goods');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,4, 'Tax');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,4, 'Additional Tax');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,4, 'Total');
				//heading-end
				
				for($dataArray=0;$dataArray<count($data);$dataArray++)
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$dataArray+5,$dataArray+5);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$dataArray+5,$data[$dataArray]['invoiceNumber']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$dataArray+5,$data[$dataArray]['entryDate']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$dataArray+5,$data[$dataArray]['clientName']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,$dataArray+5,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,$dataArray+5,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,$dataArray+5,$data[$dataArray]['total']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,$dataArray+5,$data[$dataArray]['tax']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,$dataArray+5,$data[$dataArray]['additionalTax']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,$dataArray+5,$data[$dataArray]['grandTotal']);
				}
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($data)+5,count($data)+5);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($data)+5,'Total');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,count($data)+5,$totalAmount);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,count($data)+5,$totalTax);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,count($data)+5,$totalAdditioanalTax);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,count($data)+5,$totalGrandTotal);
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
				$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B2:J2')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->applyFromArray($headerStyleArray);
				
				// set title style
				// $objPHPExcel->getActiveSheet()->getStyle('B2:J2')->applyFromArray($titleStyleArray);
				
				// make unique name
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
				$path = $constantArray['saleTaxUrl'];
				$documentPathName = $path.$documentName;
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save($documentPathName);
				
				$pathArray = array();
				$pathArray['documentPath'] = $documentPathName;
				return $pathArray;
			}
		}
		else
		{
			$jsonEncodedData = json_encode($data);
			return $jsonEncodedData;
		}
	}
	
	public function getPurchaseTaxEncodedAllData($status,$headerData)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		$data = array();
		$totalAmount = 0;
		$totalTax = 0;
		$totalAdditioanalTax = 0;
		$totalGrandTotal = 0;
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$calculateAdditionalTax=0;
			$calculateVat=0;
			$decodedProductArrayData = json_decode($decodedJson[$decodedData]['product_array']);
			for($arrayData=0;$arrayData<count($decodedProductArrayData->inventory);$arrayData++)
			{
				$productService = new EncodeTaxationData();
				$productData = $productService->getProductData($decodedProductArrayData->inventory[$arrayData]->productId);
				$productDecodedData = json_decode($productData);
				
				$vat = ($productDecodedData->purchasePrice/100)*$productDecodedData->vat;
				$calculateVat = $calculateVat+$vat;
				
				$additionalTax = ($productDecodedData->purchasePrice/100)*$productDecodedData->additionalTax;
				$calculateAdditionalTax = $calculateAdditionalTax+$additionalTax;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$tax[$decodedData] = $decodedJson[$decodedData]['tax'];
			$grandTotal[$decodedData] = $decodedJson[$decodedData]['grand_total'];
			$transactionDate[$decodedData] = $decodedJson[$decodedData]['transaction_date'];
			$clientName[$decodedData] = $decodedJson[$decodedData]['client_name'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			$calculateGrandTotal[$decodedData] = $total[$decodedData]+$calculateVat+$calculateAdditionalTax;
			
			// convert amount(round) into their company's selected decimal points
			$companyData[$decodedData] = $companyService->getCompanyData($companyId[$decodedData]);
			$companyDecodedData[$decodedData] = json_decode($companyData[$decodedData]);
			
			$totalAmount  = $totalAmount+$total[$decodedData];
			$totalTax = $totalTax+$calculateVat;
			$totalAdditioanalTax = $totalAdditioanalTax+$calculateAdditionalTax;
			$totalGrandTotal = $totalGrandTotal+$calculateGrandTotal[$decodedData];
			
			$total[$decodedData] = number_format($total[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateVat = number_format($calculateVat,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateAdditionalTax = number_format($calculateAdditionalTax,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			
			// date format conversion
			if(strcmp($transactionDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$convertedTransactionDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedTransactionDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $transactionDate[$decodedData])->format('d-m-Y');
			}
			
			$data[$decodedData]= array(
				'billNumber'=>$decodedJson[$decodedData]['bill_number'],
				'transactionType'=>$decodedJson[$decodedData]['transaction_type'],
				'total'=>$total[$decodedData],
				'tax'=>$calculateVat,
				'grandTotal'=>$calculateGrandTotal[$decodedData],
				'transactionDate'=>$convertedTransactionDate[$decodedData],
				'clientName'=>$clientName[$decodedData],
				'additionalTax'=>$calculateAdditionalTax
			);
		}
		
		if(array_key_exists('operation',$headerData))
		{
			if(strcmp($headerData['operation'][0],'excel')==0)
			{
				$totalAmount = number_format($totalAmount,$companyDecodedData[0]->noOfDecimalPoints);
				$totalTax = number_format($totalTax,$companyDecodedData[0]->noOfDecimalPoints);
				$totalAdditioanalTax = number_format($totalAdditioanalTax,$companyDecodedData[0]->noOfDecimalPoints);
				$totalGrandTotal = number_format($totalGrandTotal,$companyDecodedData[0]->noOfDecimalPoints);
			
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
				$objPHPExcel->getActiveSheet()->setTitle('PURCHASETAX');
				
				//heading-start
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,1, '1');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'FORM 201B');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, '2');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Tax Invoice Number');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Date');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,2, 'Name with RC number of the registered dealer from whom goods purchase');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,2, 'Turnover of purchase of taxable goods');
				
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D2:E2');
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:J2');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,3, '3');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,3, 'Purchase of goods from registered dealer');
		
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:C3');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,4, '4');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,4, 'Tax Invoice Number');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,4, 'Date');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,4, 'Name');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,4, 'R.C No');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,4, 'Goods With HSN');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,4, 'Value Of Goods');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,4, 'Tax');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,4, 'Additional Tax');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,4, 'Total');
				//heading-end
				
				for($dataArray=0;$dataArray<count($data);$dataArray++)
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$dataArray+5,$dataArray+5);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$dataArray+5,$data[$dataArray]['billNumber']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$dataArray+5,$data[$dataArray]['transactionDate']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$dataArray+5,$data[$dataArray]['clientName']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,$dataArray+5,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,$dataArray+5,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,$dataArray+5,$data[$dataArray]['total']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,$dataArray+5,$data[$dataArray]['tax']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,$dataArray+5,$data[$dataArray]['additionalTax']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,$dataArray+5,$data[$dataArray]['grandTotal']);
				}
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,count($data)+5,count($data)+5);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,count($data)+5,'Total');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,count($data)+5,$totalAmount);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,count($data)+5,$totalTax);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,count($data)+5,$totalAdditioanalTax);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,count($data)+5,$totalGrandTotal);
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
				$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B2:J2')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->applyFromArray($headerStyleArray);
				
				// set title style
				// $objPHPExcel->getActiveSheet()->getStyle('B2:J2')->applyFromArray($titleStyleArray);
				
				// make unique name
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
				$path = $constantArray['purchaseTaxUrl'];
				$documentPathName = $path.$documentName;
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save($documentPathName);
				
				$pathArray = array();
				$pathArray['documentPath'] = $documentPathName;
				return $pathArray;
			}
		}
		else
		{
			$jsonEncodedData = json_encode($data);
			return $jsonEncodedData;
		}
	}
	
	public function getPurchaseEncodedAllData($status,$headerData)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		$data = array();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$calculateAdditionalTax=0;
			$decodedProductArrayData = json_decode($decodedJson[$decodedData]['product_array']);
			for($arrayData=0;$arrayData<count($decodedProductArrayData->inventory);$arrayData++)
			{
				$productService = new EncodeTaxationData();
				$productData = $productService->getProductData($decodedProductArrayData->inventory[$arrayData]->productId);
				$productDecodedData = json_decode($productData);
				$additionalTax = ($productDecodedData->purchasePrice/100)*$productDecodedData->additionalTax;
				$calculateAdditionalTax = $calculateAdditionalTax+$additionalTax;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$tax[$decodedData] = $decodedJson[$decodedData]['tax'];
			$grandTotal[$decodedData] = $decodedJson[$decodedData]['grand_total'];
			$transactionDate[$decodedData] = $decodedJson[$decodedData]['transaction_date'];
			$clientName[$decodedData] = $decodedJson[$decodedData]['client_name'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			// convert amount(round) into their company's selected decimal points
			$companyData[$decodedData] = $companyService->getCompanyData($companyId[$decodedData]);
			$companyDecodedData[$decodedData] = json_decode($companyData[$decodedData]);
				
			$total[$decodedData] = number_format($total[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			
			// date format conversion
			if(strcmp($transactionDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$convertedTransactionDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedTransactionDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $transactionDate[$decodedData])->format('d-m-Y');
			}
			$data[$decodedData]= array(
				'billNumber'=>$decodedJson[$decodedData]['bill_number'],
				'transactionType'=>$decodedJson[$decodedData]['transaction_type'],
				'total'=>$total[$decodedData],
				'grandTotal'=>$grandTotal[$decodedData],
				'transactionDate'=>$convertedTransactionDate[$decodedData],
				'clientName'=>$clientName[$decodedData],
				'tax'=>$tax[$decodedData]
			);
		}
		if(array_key_exists('operation',$headerData))
		{
			if(strcmp($headerData['operation'][0],'excel')==0)
			{
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
				$objPHPExcel->getActiveSheet()->setTitle('PURCHASEDETAILS');
				
				//heading-start
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,1, '1');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,1, 'PURCHASE DETAILS');
				
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:K1');
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,2, '2');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,2, 'Invoice Number');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,2, 'Invoice Date');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,2, 'Seller Tin No.');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,2, 'Seller Name');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,2, 'State');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,2, 'Goods With HSN');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,2, 'Value Of Goods');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,2, 'Tax');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,2, 'Total');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(10,2, 'Form Type');
				//heading-end
				
				for($dataArray=0;$dataArray<count($data);$dataArray++)
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$dataArray+3,$dataArray+3);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$dataArray+3,$data[$dataArray]['billNumber']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$dataArray+3,$data[$dataArray]['transactionDate']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$dataArray+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,$dataArray+3,$data[$dataArray]['clientName']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,$dataArray+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,$dataArray+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,$dataArray+3,'');
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,$dataArray+3,$data[$dataArray]['tax']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,$dataArray+3,$data[$dataArray]['total']);
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(10,$dataArray+3,'');
				}
				
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
				$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->applyFromArray($headerStyleArray);
				$objPHPExcel->getActiveSheet()->getStyle('B1:K1')->applyFromArray($headerStyleArray);
				// set title style
				// $objPHPExcel->getActiveSheet()->getStyle('B1:K1')->applyFromArray($titleStyleArray);
				
				// make unique name
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
				$path = $constantArray['purchaseTaxationUrl'];
				$documentPathName = $path.$documentName;
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save($documentPathName);
				
				$pathArray = array();
				$pathArray['documentPath'] = $documentPathName;
				return $pathArray;
			}
		}
		else
		{
			$jsonEncodedData = json_encode($data);
			return $jsonEncodedData;
		}
	}
}