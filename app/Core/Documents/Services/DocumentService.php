<?php
namespace ERP\Core\Documents\Services;

use ERP\Model\Accounting\Bills\BillModel;
use ERP\Core\Settings\Templates\Entities\TemplateTypeEnum;
use ERP\Core\Settings\Templates\Services\TemplateService;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Documents\Entities\DocumentMpdf;
use ERP\Core\Accounting\Bills\Entities\EncodeData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentService extends BillModel
{
    /**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function insert()
	{
        //get the data from persistable object 
		$documentArray = func_get_arg(0);
		for($filaArray=0;$filaArray<count($documentArray);$filaArray++)
		 {
			 $simpleArray[$filaArray] = array();
			 $simpleArray[$filaArray][0] = $documentArray[$filaArray]->getDocumentName();
			 $simpleArray[$filaArray][1] = $documentArray[$filaArray]->getDocumentSize();
			 $simpleArray[$filaArray][2] = $documentArray[$filaArray]->getDocumentFormat();
			 $simpleArray[$filaArray][3] = $documentArray[$filaArray]->getDocumentUrl();
		 }
		 return $simpleArray;
	 }
	 
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getSaleData($saleId,$headerData)
	{
		$documentService = new DocumentService();
		if(array_key_exists("issalesorder",$headerData))
		{
			$saleData = $documentService->getSaleOrderIdData($saleId);
		}
		else
		{
			$saleData = $documentService->getSaleIdData($saleId);
		}
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($saleData,$exceptionArray['404'])==0)
		{
			return $saleData;
		}
		else
		{
			$encoded = new EncodeData();
			$encodeData = $encoded->getEncodedData($saleData);
			$decodedSaleData = json_decode($encodeData);
			
			$templateType = new TemplateTypeEnum();
			$templateArray = $templateType->enumArrays();
			if(strcmp($_SERVER['REQUEST_URI'],"/accounting/bills/".$saleId."/payment")==0)
			{
				$templateType = $templateArray['paymentTemplate'];
			}
			else
			{
				$templateType = $templateArray['invoiceTemplate'];
			}
			$emailTemplateType = $templateArray['emailTemplate'];
			$blankTemplateType = $templateArray['blankTemplate'];
			$smsTemplateType = $templateArray['smsTemplate'];
			
			$templateService = new TemplateService();
			$templateData = $templateService->getSpecificData($decodedSaleData->company->companyId,$templateType);
			$emailTemplateData = $templateService->getSpecificData($decodedSaleData->company->companyId,$emailTemplateType);
			$blankTemplateData = $templateService->getSpecificData($decodedSaleData->company->companyId,$blankTemplateType);
			$smsTemplateData = $templateService->getSpecificData($decodedSaleData->company->companyId,$smsTemplateType);
			if(strcmp($templateData,$exceptionArray['404'])==0)
			{
				return $templateData;
			}
			else
			{
				$documentMpdf = new DocumentMpdf();
				if(strcmp($_SERVER['REQUEST_URI'],"/accounting/bills/".$saleId."/payment")==0)
				{
					$documentMpdf = $documentMpdf->mpdfPaymentGenerate($templateData,$encodeData,$emailTemplateData,$blankTemplateData,$smsTemplateData);
					return $documentMpdf;
				}
				else
				{
					$documentMpdf = $documentMpdf->mpdfGenerate($templateData,$encodeData,$headerData,$emailTemplateData,$blankTemplateData,$smsTemplateData);
					return $documentMpdf;
				}
			}
		}
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getQuotationData($quotationBillId,$companyId,$quotationData,$headerData)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$templateType = new TemplateTypeEnum();
		$templateArray = $templateType->enumArrays();
		$templateType = $templateArray['quotationTemplate'];
		$emailTemplateType = $templateArray['emailTemplate'];
		$templateService = new TemplateService();
		$templateData = $templateService->getSpecificData($companyId,$templateType);
		$emailTemplateData = $templateService->getSpecificData($companyId,$emailTemplateType);
		if(strcmp($templateData,$exceptionArray['404'])==0)
		{
			return $templateData;
		}
		else
		{
			$headerArray = $headerData;
			$documentMpdf = new DocumentMpdf();
			$documentMpdf = $documentMpdf->quotationMpdfGenerate($templateData,$quotationData,$emailTemplateData,$headerArray);
			return $documentMpdf;
		}
		
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getJobformData($inputData)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$templateType = new TemplateTypeEnum();
		$templateArray = $templateType->enumArrays();
		$templateType = $templateArray['jobCardTemplate'];
		$templateService = new TemplateService();
		$companyId = $inputData[0]->company->companyId;
		$templateData = $templateService->getSpecificData($companyId,$templateType);
		if(strcmp($templateData,$exceptionArray['404'])==0)
		{
			return $templateData;
		}
		else
		{
			$documentMpdf = new DocumentMpdf();
			$documentMpdf = $documentMpdf->jobFormMpdfGenerate($templateData,$inputData);
			return $documentMpdf;
		}
		
	}
}