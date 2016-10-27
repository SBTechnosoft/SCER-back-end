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
	public static function getAllDocumentData()
	{
		$documentModel = new DocumentModel();
		$status = $documentModel->getAllData();
		return $status;
	}
	public static function getDocumentData($companyId)
	{
		$documentModel = new DocumentModel();
		$status = $documentModel->getData($companyId);
		return $status;
	}
	public static function insertDocumentData($documentName,$documentUrl,$documentSize,$documentFormat,$status)
	{
		$documentModel = new DocumentModel();
		$status = $documentModel->insertData($documentName,$documentUrl,$documentSize,$documentFormat,$status);
		return $status;
	}
	public static function updateDocumentData($documentName,$documentUrl,$documentSize,$documentFormat,$companyId)
	{
		$documentModel = new DocumentModel();
		$status = $documentModel->updateData($documentName,$documentUrl,$documentSize,$documentFormat,$companyId);
		return $status;
	}
}