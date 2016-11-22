<?php
namespace ERP\Api\V1_0\Documents\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Documents\Persistables\DocumentPersistable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Entities\Constants\ConstantClass;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentProcessor extends BaseProcessor
{
	/**
     * @var documentPersistable
	 * @var request
     */
	private $documentPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Document Persistable object
     */	
    public function createPersistable(Request $request,$documentPath)
	{	
		$documentUrl=array();
		$documentName=array();
		$documentFormat=array();
		$documentSize=array();
		$persistableArray = array();
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
		
		//get constant document-url from document
		$constDocumentUrl =  new ConstantClass();
		$documentArray = $constDocumentUrl->constantVariable();
		
		$file = $request->file();
		
		//get document data and store documents in folder		
		for($fileArray=0;$fileArray<count($request->file()['file']);$fileArray++)
		{
			$documentPersistable = array();
			$documentPersistable[$fileArray] = new DocumentPersistable();
			
			$documentUrl[$fileArray] = $documentPath;
			$documentName[$fileArray] =$combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".".$file['file'][$fileArray]->getClientOriginalExtension();
			$documentFormat[$fileArray] = $file['file'][$fileArray]->getClientOriginalExtension();
			$documentSize[$fileArray] = $file['file'][$fileArray]->getClientSize();
			$file['file'][$fileArray]->move($documentUrl[$fileArray],$documentName[$fileArray]);
			
			if($documentFormat[$fileArray]=='jpg' || $documentFormat[$fileArray]=='jpeg' || $documentFormat[$fileArray]=='gif' || $documentFormat[$fileArray]=='png' || $documentFormat[$fileArray]=='pdf')
			{	
				if(($documentSize[$fileArray]/1048576)<=5)
				{
					$documentPersistable[$fileArray]->setDocumentName($documentName[$fileArray]);
					$documentPersistable[$fileArray]->setDocumentSize($documentSize[$fileArray]);
					$documentPersistable[$fileArray]->setDocumentFormat($documentFormat[$fileArray]);
					$documentPersistable[$fileArray]->setDocumentUrl($documentUrl[$fileArray]);
					$persistableArray[$fileArray] = $documentPersistable[$fileArray];
				}
				else
				{
					return $msgArray['fileSize'];
				}
			}
			else
			{
				return $msgArray['415'];
			}
		}
		return $persistableArray;
			
	}
}