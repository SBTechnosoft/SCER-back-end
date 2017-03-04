<?php
namespace ERP\Core\Products\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Alignment;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PriceListMpdf extends ConstantClass
{
	/**
     * get the specified resource.
     * @param $header-date and product transaction array-data
	 * @return document-path 
     */
	public function generatePdf($headerData,$data)
	{
		$decodedData = json_decode($data);
		// print_r($decodedData);
		// exit;
		//generate pdf
		$constantClass = new PriceListMpdf();
		$constantArray = $constantClass->constantVariable();
		$headerPart = "<table style='border: 1px solid black; width:100%'>
						<thead style='border: 1px solid black;'>
							<tr style='border: 1px solid black;'>
								<th style='border: 1px solid black;'>product-Category</th>
								<th style='border: 1px solid black;'>product-Name</th>
								<th style='border: 1px solid black;'>product-Group</th>
								<th style='border: 1px solid black;'>Price</th>
								<th style='border: 1px solid black;'>Vat</th>
								<th style='border: 1px solid black;'>A.Vat</th>
								<th style='border: 1px solid black;'>Final Amount</th>
							</tr>
						</thead><tbody>";
		$bodyPart = "";
		
		$productCatId = array();
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
				$additionalTax[$arrayData] = ($decodedData[$arrayData]->additionalTax/100)*$decodedData[$arrayData]->purchasePrice;
			}	
			else
			{
				$wholeSaleMargin[$arrayData] = ($decodedData[$arrayData]->wholesaleMargin/100)*$decodedData[$arrayData]->purchasePrice;
				$wholeSaleMargin[$arrayData] = $wholeSaleMargin[$arrayData]+$decodedData[$arrayData]->wholesaleMarginFlat;
				$decodedData[$arrayData]->purchasePrice = $decodedData[$arrayData]->purchasePrice +$wholeSaleMargin[$arrayData];
				$decodedData[$arrayData]->vat = ($decodedData[$arrayData]->vat/100)*$decodedData[$arrayData]->purchasePrice;;
				$totalAmount[$arrayData] = $decodedData[$arrayData]->purchasePrice+$decodedData[$arrayData]->vat;
				$additionalTax[$arrayData] = ($decodedData[$arrayData]->additionalTax/100)*$decodedData[$arrayData]->purchasePrice;
			}
			
			//convert amount(round) into their company's selected decimal points
			$decodedData[$arrayData]->purchasePrice = round($decodedData[$arrayData]->purchasePrice,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$decodedData[$arrayData]->vat = round($decodedData[$arrayData]->vat,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$totalAmount[$arrayData] = round($totalAmount[$arrayData],$decodedData[$arrayData]->company->noOfDecimalPoints);
			$additionalTax[$arrayData] = round($additionalTax[$arrayData],$decodedData[$arrayData]->company->noOfDecimalPoints);
			
			$bodyPart = $bodyPart."	<tr style='border: 1px solid black;'>";
			if($arrayData!=0)
			{
				$productCatId[$arrayData] = $decodedData[$arrayData]->productCategory->productCategoryId;
				if($productCatId[$arrayData]!=$productCatId[$arrayData-1])
				{
					$bodyPart = $bodyPart."<td style='border: 1px solid black;'>".$decodedData[$arrayData]->productCategory->productCategoryName."</td>";
				}
				else
				{
					$bodyPart = $bodyPart."<td style='border: 1px solid black;'></td>";
				}
			}
			else
			{
				$bodyPart = $bodyPart."<td style='border: 1px solid black;'>".$decodedData[$arrayData]->productCategory->productCategoryName."</td>";
				$productCatId[$arrayData] = $decodedData[$arrayData]->productCategory->productCategoryId;
			}
			$bodyPart = $bodyPart."<td style='border: 1px solid black;'>".$decodedData[$arrayData]->productName."</td>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->productGroup->productGroupName."</td>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->purchasePrice."</td>
									<td style='border: 1px solid black;'>".$decodedData[$arrayData]->vat."</td>
									<td style='border: 1px solid black;'>".$additionalTax[$arrayData]."</td>
									<td style='border: 1px solid black;'>".$totalAmount[$arrayData]."</td></tr>";
			
		}
		$footerPart = "</tbody></table>";
		$htmlBody = $headerPart.$bodyPart.$footerPart;
		
		//make unique name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		
		$path = $constantArray['priceListPdf'];
		$documentPathName = $path.$documentName;
		$mpdf = new mPDF('A4','landscape');
		$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold; font-size:20px;">Price List</div>');
		$mpdf->SetDisplayMode('fullpage');
		
		//delete older files
		$files = glob($path.'*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}
		
		$mpdf->WriteHTML($htmlBody);
		$mpdf->Output($documentPathName,'F');
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
	
	/**
     * get the specified resource.
     * @param $header-date and product transaction array-data
	 * @return document-path 
     */
	public function generateExcelFile($headerData,$data)
	{
		$constantClass = new PriceListMpdf();
		$constantArray = $constantClass->constantVariable();
		
		$decodedData = json_decode($data);
		
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
		$objPHPExcel->getActiveSheet()->setTitle('PriceList');
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,1, 'Price-List');
		
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,3, 'Category-Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,3, 'Product-Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,3, 'Group-Name');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,3, 'Purchase-Price');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,3, 'Vat');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,3, 'A.Vat');
		$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,3, 'Total');
		
		$productCatId = array();
		
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
				$additionalTax[$arrayData] = ($decodedData[$arrayData]->additionalTax/100)*$decodedData[$arrayData]->purchasePrice;
			}	
			else
			{
				$wholeSaleMargin[$arrayData] = ($decodedData[$arrayData]->wholesaleMargin/100)*$decodedData[$arrayData]->purchasePrice;
				$wholeSaleMargin[$arrayData] = $wholeSaleMargin[$arrayData]+$decodedData[$arrayData]->wholesaleMarginFlat;
				$decodedData[$arrayData]->purchasePrice = $decodedData[$arrayData]->purchasePrice +$wholeSaleMargin[$arrayData];
				$decodedData[$arrayData]->vat = ($decodedData[$arrayData]->vat/100)*$decodedData[$arrayData]->purchasePrice;;
				$totalAmount[$arrayData] = $decodedData[$arrayData]->purchasePrice+$decodedData[$arrayData]->vat;
				$additionalTax[$arrayData] = ($decodedData[$arrayData]->additionalTax/100)*$decodedData[$arrayData]->purchasePrice;
			}
			
			// convert amount(round) into their company's selected decimal points
			$decodedData[$arrayData]->purchasePrice = round($decodedData[$arrayData]->purchasePrice,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$decodedData[$arrayData]->vat = round($decodedData[$arrayData]->vat,$decodedData[$arrayData]->company->noOfDecimalPoints);
			$totalAmount[$arrayData] = round($totalAmount[$arrayData],$decodedData[$arrayData]->company->noOfDecimalPoints);
			$additionalTax[$arrayData] = round($additionalTax[$arrayData],$decodedData[$arrayData]->company->noOfDecimalPoints);
			
			if($arrayData!=0)
			{
				$productCatId[$arrayData] = $decodedData[$arrayData]->productCategory->productCategoryId;
				if($productCatId[$arrayData]!=$productCatId[$arrayData-1])
				{
					$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+4, $decodedData[$arrayData]->productCategory->productCategoryName);
				}
			}
			else
			{
				$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(0,$arrayData+4, $decodedData[$arrayData]->productCategory->productCategoryName);
				$productCatId[$arrayData] = $decodedData[$arrayData]->productCategory->productCategoryId;
			}
			
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(1,$arrayData+4, $decodedData[$arrayData]->productName);
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(2,$arrayData+4, $decodedData[$arrayData]->productGroup->productGroupName);
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(3,$arrayData+4, $decodedData[$arrayData]->purchasePrice);
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(4,$arrayData+4, $decodedData[$arrayData]->vat);
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(5,$arrayData+4, $additionalTax[$arrayData]);
			$objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow(6,$arrayData+4, $totalAmount[$arrayData]);
		}
		
		// style for header
		$headerStyleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'black'),
			'size'  => 10,
			'name'  => 'Verdana'
		));
		
		// style for Title
		$titleStyleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'black'),
			'size'  => 15,
			'name'  => 'Verdana'
		));
		
		// set header style
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($headerStyleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($headerStyleArray);
		
		// set title style
		$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($titleStyleArray);
		
		// make unique name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".xls"; //xslx
		$path = $constantArray['priceListExcel'];
		$documentPathName = $path.$documentName;
		
		//delete older files
		$files = glob($path.'*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($documentPathName);
		$pathArray = array();
		$pathArray['documentPath'] = $documentPathName;
		return $pathArray;
	}
}