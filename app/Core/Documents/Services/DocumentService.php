<?php
namespace ERP\Core\Documents\Services;

use ERP\Model\Documents\DocumentModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentService extends DocumentModel
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
	 
	// public static function getAllDocumentData()
	// {
		// $documentModel = new DocumentModel();
		// $status = $documentModel->getAllData();
		// return $status;
	// }
	// public static function getDocumentData($companyId)
	// {
		// $documentModel = new DocumentModel();
		// $status = $documentModel->getData($companyId);
		// return $status;
	// }
	// public static function insertDocumentData($documentName,$documentSize,$documentFormat,$status)
	// {
		// $documentModel = new DocumentModel();
		// $status = $documentModel->insertData($documentName,$documentSize,$documentFormat,$status);
		// return $status;
	// }
	// public static function updateDocumentData($documentName,$documentSize,$documentFormat,$companyId)
	// {
		// $documentModel = new DocumentModel();
		// $status = $documentModel->updateData($documentName,$documentSize,$documentFormat,$companyId);
		// return $status;
	// }
}