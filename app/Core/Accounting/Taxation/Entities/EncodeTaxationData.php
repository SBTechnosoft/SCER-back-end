<?php
namespace ERP\Core\Accounting\Taxation\Entities;

use ERP\Core\Clients\Services\ClientService;
use ERP\Core\Companies\Services\CompanyService;
use Carbon;
use ERP\Core\Products\Services\ProductService;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Entities\Constants\ConstantClass;
use ERP\Exceptions\ExceptionMessage;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeTaxationData extends ProductService
{
	/**
	 * convert necessary sale-tax data and generate excel-sheet of that data
	 * returns the array-data/exception message/excel-sheet path
	*/
	public function getEncodedAllData($status,$headerData)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
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
			$productDataArray = array();
			$inventoryCount = count($decodedProductArrayData->inventory);
			for($arrayData=0;$arrayData<$inventoryCount;$arrayData++)
			{
				$productService = new EncodeTaxationData();
				$productData = $productService->getProductData($decodedProductArrayData->inventory[$arrayData]->productId);
				$productDecodedData = json_decode($productData);
				
				$vat = ($productDecodedData->purchasePrice/100)*$productDecodedData->vat;
				$calculateVat = $calculateVat+$vat;
				
				$additionalTax = ($productDecodedData->purchasePrice/100)*$productDecodedData->additionalTax;
				$calculateAdditionalTax = $calculateAdditionalTax+$additionalTax;
				$productDataArray[$arrayData] = $decodedProductArrayData->inventory[$arrayData];
				$productDataArray[$arrayData]->product = $productDecodedData;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$totalDiscount[$decodedData] = $decodedJson[$decodedData]['total_discount'];
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
			$totalDiscount[$decodedData] = number_format($totalDiscount[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$advance[$decodedData] = number_format($advance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$balance[$decodedData] = number_format($balance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$refund[$decodedData] = number_format($refund[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateVat = number_format($calculateVat,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateAdditionalTax = number_format($calculateAdditionalTax,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			
			//date format conversion
			$convertedEntryDate[$decodedData] = strcmp($entryDate[$decodedData],'0000-00-00 00:00:00')==0
												? "00-00-0000"
												: Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');	
			$data[$decodedData]= array(
				'invoiceNumber'=>$decodedJson[$decodedData]['invoice_number'],
				'salesType'=>$decodedJson[$decodedData]['sales_type'],
				'total'=>$total[$decodedData],
				'totalDiscount'=>$totalDiscount[$decodedData],
				'totalDiscounttype'=>$decodedJson[$decodedData]['total_discounttype'],
				'tax'=>$calculateVat,
				'grandTotal'=>$calculateGrandTotal[$decodedData],
				'advance'=>$advance[$decodedData],
				'balance'=>$balance[$decodedData],
				'refund'=>$refund[$decodedData],
				'entryDate'=>$convertedEntryDate[$decodedData],
				'additionalTax'=>$calculateAdditionalTax,
				'client'=>$decodedClientData[$decodedData],
				'company'=>$companyDecodedData[$decodedData],
				'product'=>$productDataArray
			);
			//get ledger-data from invoice-number
			$ledgerModel = new LedgerModel();
			$ledgerData[$decodedData] = $ledgerModel->getDataAsPerContactNo($companyDecodedData[$decodedData]->companyId,$decodedClientData[$decodedData]->contactNo);
			if(strcmp($ledgerData[$decodedData],$exceptionArray['500'])!=0)
			{
				$decodedLedgerData[$decodedData] = json_decode($ledgerData[$decodedData]);
				$data[$decodedData]['ledger'] = $decodedLedgerData[$decodedData][0];
			}
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
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$dataArray+5,$data[$dataArray]['client']->clientName);
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
			else
			{
				$jsonEncodedData = json_encode($data);
				return $jsonEncodedData;
			}
		}
		else
		{
			$jsonEncodedData = json_encode($data);
			return $jsonEncodedData;
		}
	}
	
	/**
	 * convert necessary purchase-tax data and generate excel-sheet of that data
	 * returns the array-data/exception message/excel-sheet path
	*/
	public function getPurchaseTaxEncodedAllData($status,$headerData)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		$data = array();
		$totalAmount = 0;
		$totalTax = 0;
		$totalAdditioanalTax = 0;
		$totalGrandTotal = 0;
		// print_r($decodedJson);
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$calculateAdditionalTax=0;
			$calculateVat=0;
			$productDataArray = array();
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
				
				$productDataArray[$arrayData] = $decodedProductArrayData->inventory[$arrayData];
				$productDataArray[$arrayData]->product = $productDecodedData;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$totalDiscount[$decodedData] = $decodedJson[$decodedData]['total_discount'];
			$tax[$decodedData] = $decodedJson[$decodedData]['tax'];
			$grandTotal[$decodedData] = $decodedJson[$decodedData]['grand_total'];
			$transactionDate[$decodedData] = $decodedJson[$decodedData]['transaction_date'];
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
			$totalDiscount[$decodedData] = number_format($totalDiscount[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateVat = number_format($calculateVat,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			$calculateAdditionalTax = number_format($calculateAdditionalTax,$companyDecodedData[$decodedData]->noOfDecimalPoints);
			// date format conversion
			if(strcmp($transactionDate[$decodedData],'0000-00-00')==0)
			{
				$convertedTransactionDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedTransactionDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $transactionDate[$decodedData])->format('d-m-Y');
			}
			//get ledger-data from invoice-number
			$ledgerService = new LedgerService();
			$ledgerData = $ledgerService->getLedgerData($decodedJson[$decodedData]['vendor_id']);
			if(strcmp($ledgerData,$exceptionArray['404'])!=0)
			{
				$decodedLedgerData[$decodedData] = json_decode($ledgerData);
				// $data[$decodedData]['ledger'] = $decodedLedgerData[$decodedData];
			}
			else
			{
				return $exceptionArray['404'];
			}
			$data[$decodedData]= array(
				'billNumber'=>$decodedJson[$decodedData]['bill_number'],
				'entryDate'=>$decodedJson[$decodedData]['entry_date'],
				'transactionType'=>$decodedJson[$decodedData]['transaction_type'],
				'total'=>$total[$decodedData],
				'totalDiscount'=>$totalDiscount[$decodedData],
				'totalDiscounttype'=>$decodedJson[$decodedData]['total_discounttype'],
				'tax'=>$calculateVat,
				'grandTotal'=>$calculateGrandTotal[$decodedData],
				'transactionDate'=>$convertedTransactionDate[$decodedData],
				'additionalTax'=>$calculateAdditionalTax,
				'ledger'=>$decodedLedgerData[$decodedData],
				'company'=>$companyDecodedData[$decodedData],
				'product'=>$productDataArray
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
			else
			{
				$jsonEncodedData = json_encode($data);
				return $jsonEncodedData;
			}
		}
		else
		{
			$jsonEncodedData = json_encode($data);
			return $jsonEncodedData;
		}
	}
	
	/**
	 * convert necessary purchase-detail data and generate excel-sheet of that data
	 * returns the array-data/exception message/excel-sheet path
	*/
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
	
	/**
	 * generate excel-sheet of gst-return data
	 * returns the array-data/exception message/excel-sheet path
	*/
	public function getGstReturnExcelPath($saleTaxData,$purchaseTaxResult)
	{
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$saleTaxDecodedData = json_decode($saleTaxData);
		$purchaseTaxDecodedData = json_decode($purchaseTaxResult);
		
		// generate excel
		$objPHPExcel = new \PHPExcel();
		//first sheet (Sales Invoice)
		$objPHPExcel->setActiveSheetIndex(0);
		// Set properties comment
		$objPHPExcel->getProperties()->setCreator("ThinkPHP")
						->setLastModifiedBy("Daniel Schlichtholz")
						->setTitle("Office 2007 XLSX Test Document")
						->setSubject("Office 2007 XLSX Test Document")
						->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
						->setKeywords("office 2007 openxml php")
						->setCategory("Test result file");
		$objPHPExcel->getActiveSheet(0)->setTitle('Sales Invoice');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,3, 'Sr.');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,3, 'Invoice Number');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,3, 'Invoice Date');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,3, 'Buyer\'s Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,3, 'Buyer\'s Gst No');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,3, 'Place of supplier(State)');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,3, 'Particulars');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,3, 'HSN Code');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,3, 'Taxable Value');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(10,3, 'GST Rate');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(11,3, 'CGST Amount');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(12,3, 'SGST Amount');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(13,3, 'IGST Amount');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(14,3, 'CESS Amount');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(15,3, 'Invoice Value(Total)');
		$dataCount = count($saleTaxDecodedData);
		$loopCount=0;
		for($data=0;$data<$dataCount;$data++)
		{
			$productCount = count($saleTaxDecodedData[$data]->product);
			$totalCgst=0;
			$totalSgst=0;
			$totalIgst=0;
			//calculating total-cgst & total-sgst
			for($productArray=0;$productArray<$productCount;$productArray++)
			{
				$totalCgst = $saleTaxDecodedData[$data]->product[$productArray]->product->vat;
				$totalSgst = $saleTaxDecodedData[$data]->product[$productArray]->product->additionalTax;
				$totalIgst = $saleTaxDecodedData[$data]->product[$productArray]->product->igst;
				
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$loopCount+4+$productArray,$data+1);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->invoiceNumber);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->entryDate);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->client->clientName);
				if(array_key_exists('ledger',$saleTaxDecodedData[$data]))
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->ledger->cgst);
				}
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->client->state->stateName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(7,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->product[$productArray]->product->productName);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(8,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->product[$productArray]->product->hsn);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(9,$loopCount+4+$productArray,'Taxable Value');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(10,$loopCount+4+$productArray,'GST Rate');
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(11,$loopCount+4+$productArray,$totalCgst);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(12,$loopCount+4+$productArray,$totalSgst);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(13,$loopCount+4+$productArray,$totalIgst);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(14,$loopCount+4+$productArray,$saleTaxDecodedData[$data]->company->cess);
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(15,$loopCount+4+$productArray,'Invoice Value(Total)');
			}
			$loopCount = $productCount+$data;
		}
		$styleArray = array(
			'borders' => array(
			  'allborders' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
			  )
			)
		);
		$borderCount = "P".($loopCount+4-1);
		$objPHPExcel->getActiveSheet()->getStyle("B3:".$borderCount)->applyFromArray($styleArray);
		
		//create 2nd sheet (purchase-invoice)
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet(1)->setTitle('Purchase Invoice');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(1,3, 'Sr.');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(2,3, 'Invoice Number');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(3,3, 'Invoice Date');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(4,3, 'Buyer\'s Name');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(5,3, 'Buyer\'s Gst No');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(6,3, 'Place of supplier(State)');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(7,3, 'Particulars');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(8,3, 'HSN Code');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(9,3, 'Taxable Value');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(10,3, 'GST Rate');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(11,3, 'CGST Amount');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(12,3, 'SGST Amount');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(13,3, 'IGST Amount');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(14,3, 'CESS Amount');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(15,3, 'Invoice Value(Total)');
		//heading-end

		$loopCount=0;
		$purchaseDataCount = count($purchaseTaxDecodedData);
		for($data=0;$data<$purchaseDataCount;$data++)
		{
			$productCount = count($purchaseTaxDecodedData[$data]->product);
			//entry-date conversion
			$splitedDate = explode("-",trim($purchaseTaxDecodedData[$data]->entryDate));
			$entryDate[$data] = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
			// calculating total-cgst & total-sgst
			for($productArray=0;$productArray<$productCount;$productArray++)
			{
				$totalCgst = $purchaseTaxDecodedData[$data]->product[$productArray]->product->vat;
				$totalSgst = $purchaseTaxDecodedData[$data]->product[$productArray]->product->additionalTax;
				$totalIgst = $purchaseTaxDecodedData[$data]->product[$productArray]->product->igst;
				
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(1,$loopCount+4+$productArray,$data+1);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(2,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->billNumber);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(3,$loopCount+4+$productArray,$entryDate[$data]);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(4,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->ledger->ledgerName);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(5,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->ledger->cgst);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(6,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->ledger->state->stateName);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(7,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->product[$productArray]->product->productName);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(8,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->product[$productArray]->product->hsn);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(9,$loopCount+4+$productArray,'Taxable Value');
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(10,$loopCount+4+$productArray,'GST Rate');
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(11,$loopCount+4+$productArray,$totalCgst);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(12,$loopCount+4+$productArray,$totalSgst);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(13,$loopCount+4+$productArray,$totalIgst);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(14,$loopCount+4+$productArray,$purchaseTaxDecodedData[$data]->company->cess);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(15,$loopCount+4+$productArray,'Invoice Value(Total)');
			}
			$loopCount = $productCount+$data;
		}
		$borderCount = "P".($loopCount+4-1);
		$objPHPExcel->getActiveSheet(1)->getStyle("B3:".$borderCount)->applyFromArray($styleArray);
		// make unique name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999)."_GSTReturn.xls"; //xslx
		$path = $constantArray['taxReturnUrl'];
		$documentPathName = $path.$documentName;
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($documentPathName);
		
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}