<?php
namespace ERP\Core\Accounting\Bills\Entities;

use mPDF;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Accounting\Bills\BillModel;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillMpdf 
{
	public function mpdfGenerate($templateData,$status)
	{
		$htmlBody = json_decode($templateData)[0]->template_body;
		if(is_object(json_decode($status)))
		{
			$saleId = json_decode($status)->saleId;		
		}
		else
		{
			$saleId = json_decode($status)[0]->sale_id;
		}

		$billArray = array();
		$billArray['Company']="Siliconbrain";
		$billArray['ClientName']="Reema";
		$billArray['OrderDate']="25-11-2016";
		$billArray['CLIENTADD']="dfs,sg/sgs-343434";
		$billArray['INVID']="rgrfd";
		$billArray['ClientCharge']="500";
		$billArray['Discount']="50%";
		$billArray['TaxAmt']="100";
		$billArray['Total']="300";
		
		$mpdf = new mPDF();
		$mpdf->SetDisplayMode('fullpage');
		foreach($billArray as $key => $value)
		{
			$htmlBody = str_replace('['.$key.']', $value, $htmlBody);
		}
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$mpdf->WriteHTML($htmlBody);
		$path = $constantArray['billUrl'];
		
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".pdf";
		$documentPathName = $path.$documentName;
		$documentFormat="pdf";
		$documentType ="bill";
		//insertion bill document data into database
		$billModel = new BillModel();
		$billDocumentStatus = $billModel->billDocumentData($saleId,$documentName,$documentFormat,$documentType);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($exceptionArray['500'],$billDocumentStatus)==0)
		{
			return $billDocumentStatus;
		}
		else
		{
			$mpdf->Output($documentPathName,'F');
			$pathArray = array();
			$pathArray['documentPath'] = $documentPathName;
			return $pathArray;
		}	
	}
}
