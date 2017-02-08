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
	public function getSaleData($saleId)
	{
		$documentService = new DocumentService();
		$saleData = $documentService->getSaleIdData($saleId);
		
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
			$templateType = $templateArray['invoiceTemplate'];
			$templateService = new TemplateService();
			$templateData = $templateService->getSpecificData($decodedSaleData->company->companyId,$templateType);
		
			if(strcmp($templateData,$exceptionArray['404'])==0)
			{
				return $templateData;
			}
			else
			{
				$documentMpdf = new DocumentMpdf();
				$documentMpdf = $documentMpdf->mpdfGenerate($templateData,$encodeData);
				return $documentMpdf;
			}
		}
	}
}