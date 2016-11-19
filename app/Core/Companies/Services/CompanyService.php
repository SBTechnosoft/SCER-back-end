<?php
namespace ERP\Core\Companies\Services;

use ERP\Core\Companies\Persistables\CompanyPersistable;
use ERP\Core\Companies\Entities\Company;
use ERP\Model\Companies\CompanyModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Companies\Entities\EncodeData;
use ERP\Core\Companies\Entities\EncodeAllData;
use ERP\Core\Documents\Services\DocumentService;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyService extends AbstractService 
{
    /**
     * @var companyService
	 * $var companyModel
     */
    private $companyService;
    private $companyModel;
	
    /**
     * @param CompanyService $companyService
     */
    public function initialize(CompanyService $companyService)
    {		
		echo "init";
    }
	
    /**
     * @param CompanyPersistable $persistable
     */
    public function create(CompanyPersistable $persistable)
    {
		return "create method of CompanyService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param CompanyPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$documentName="";
		$documentSize="";
		$documentFormat="";
		$companyArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$companyArray = func_get_arg(0);
		for($data=0;$data<count($companyArray);$data++)
		{
			$funcName[$data] = $companyArray[$data][0]->getName();
			$getData[$data] = $companyArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $companyArray[$data][0]->getkey();
			// document data is set into the last object..so
			if($data==(count($companyArray)-1))
			{
				//get document data
				$documentName = $companyArray[$data][0]->getDocumentName();
				$documentSize = $companyArray[$data][0]->getDocumentSize();
				$documentFormat = $companyArray[$data][0]->getDocumentFormat();
			}
		}
		//data pass to the model object for insert
		$companyModel = new CompanyModel();
		$status = $companyModel->insertData($getData,$keyName);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($status,$fileSizeArray['500'])==0)
		{
			return $status;
		}
		//data inserted successfully
		else
		{
			if($documentName!="")
			{
				//insert document data(update in company_mst table)
				$documentStatus = DocumentService::insertDocumentData($documentName,$documentSize,$documentFormat,$status);
				return $documentStatus;	
			}
			else
			{
				//if document is not inserted..
				return $fileSizeArray['200'];
			}
		}
	}
	
	/**
     * get all the data call the model for database selection opertation
     * @return status
     */
	public function getAllCompanyData()
	{
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
		$companyModel = new CompanyModel();
		$status = $companyModel->getAllData();
		if(strcmp($status,$fileSizeArray['204'])==0)
		{
			return $status;
		}
		else
		{
			$documentStatus = DocumentService::getAllDocumentData();
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status,$documentStatus);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @param company_id
     * @return status
     */
	public function getCompanyData($companyId)
	{
		$companyModel = new CompanyModel();
		$status = $companyModel->getData($companyId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($status,$fileSizeArray['404'])==0)
		{
			return $status;
		}
		else
		{
			$decodedJsonDoc = json_decode($status,true);
			$companyId= $decodedJsonDoc[0]['company_id'];
			$documentStatus = DocumentService::getDocumentData($companyId);
			$encoded = new EncodeData();
			$encodeData = $encoded->getEncodedData($status,$documentStatus);
			return $encodeData;
		}
	}
	
    /**
     * get the data from persistable object and call the model for database update opertation
     * @param CompanyPersistable $persistable
     * @param updateOptions $options [optional]
     * parameter is in array form.
     * @return status
     */
    public function update()
    {
		if(count($_POST)==0)
		{
			echo "success";
		}
		else
		{
			$companyArray = array();
			$getData = array();
			$funcName = array();
			$companyArray = func_get_arg(0);
			for($data=0;$data<count($companyArray);$data++)
			{
				$funcName[$data] = $companyArray[$data][0]->getName();
				$getData[$data] = $companyArray[$data][0]->$funcName[$data]();
				$keyName[$data] = $companyArray[$data][0]->getkey();
				
				// document data is set into the last object..so
				if($data==(count($companyArray)-1))
				{
					//get document data
					$documentName = $companyArray[$data][0]->getDocumentName();
					$documentSize = $companyArray[$data][0]->getDocumentSize();
					$documentFormat = $companyArray[$data][0]->getDocumentFormat();
				}
			}
			
			$companyId = $companyArray[0][0]->getCompanyId();
			//data pass to the model object for update
			$companyModel = new CompanyModel();
			$status = $companyModel->updateData($getData,$keyName,$companyId);
			
			//get exception message
			$exception = new ExceptionMessage();
			$fileSizeArray = $exception->messageArrays();
			
			if(strcmp($status,$fileSizeArray['500'])==0)
			{
				return $status;
			}
			//data updated successfully
			else
			{
				
				if($documentName!='')
				{
					//insert document data(update in company_mst table)
					$documentStatus = DocumentService::updateDocumentData($documentName,$documentSize,$documentFormat,$companyId);
					return $documentStatus;	
				}
				else
				{
					//if document is not changed..
					return $fileSizeArray['200'];
				}
			}
		}
	}
	public function updateDocument(CompanyPersistable $companyPersistable)
	{
		$companyId = $companyPersistable->getCompanyId();
		$documentName = $companyPersistable->getDocumentName();
		$documentSize = $companyPersistable->getDocumentSize();
		$documentFormat = $companyPersistable->getDocumentFormat();
		
		//insert document data(update in company_mst table)
		$documentStatus = DocumentService::updateDocumentData($documentName,$documentSize,$documentFormat,$companyId);
		return $documentStatus;
	}
    /**
     * get and invoke method is of Container Interface method
     * @param int $id,$name
     */
    public function get($id,$name)
    {
		echo "get";		
    }   
	public function invoke(callable $method)
	{
		echo "invoke";
	}
    /**
     * delete
     * @param CompanyPersistable $persistable
     */
    public function delete(CompanyPersistable $persistable)
    {      
		$companyId = $persistable->getId();
        $companyModel = new CompanyModel();
		$status = $companyModel->deleteData($companyId);
		return $status;
    }   
}