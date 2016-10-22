<?php
namespace ERP\Api\V1_0\Companies\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Companies\Persistables\CompanyPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ERP\Core\Sample\Persistables\DocumentPersistable;
use ERP\Core\Companies\Validations\CompanyValidate;
use ERP\Api\V1_0\Companies\Transformers\CompanyTransformer;
use ERP\Api\V1_0\Entities\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyProcessor extends BaseProcessor
{   
	/**
     * @var companyPersistable
	 * @var request
     */
	private $companyPersistable;
	private $request;    
	/**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Branch Persistable object
     */	
	public function createPersistable(Request $request)
	{	
		date_default_timezone_set("Asia/Calcutta");
		$this->request = $request;	
		$companyValue = array();
		$tKeyValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		$docFlag=0;
		$documentName="";
		$documentUrl="";
		$documentFormat="";
		$documentSize="";
		
		$file = $request->file();
		//change the name of document-name
		$dateTime = date("d-m-Y h-i-s");
		$convertedDateTime = str_replace(" ","-",$dateTime);
		$splitDateTime = explode("-",$convertedDateTime);
		$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
			
		if(in_array(true,$file))
		{
			$documentUrl = 'Storage/Document/';
			$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".".$file['file'][0]->getClientOriginalExtension();
			$documentFormat = $file['file'][0]->getClientOriginalExtension();
			$documentSize = $file['file'][0]->getClientSize();
			$file['file'][0]->move($documentUrl,$documentName);	
			$docFlag=1;
		}
		
		//trim an input 
		$companyTransformer = new CompanyTransformer();
		$tRequest = $companyTransformer->trimInsertData($this->request);
		
		//validation
		$companyValidate = new CompanyValidate();
		$status = $companyValidate->validate($tRequest);
		if($status=="Success")
		{
			foreach ($tRequest as $key => $value)
			{
				if(!is_numeric($value))
				{
					if (strpos($value, '\'') !== FALSE)
					{
						$companyValue[$data]= str_replace("'","\'",$value);
						$keyName[$data] = $key;
					}
					else
					{
						$companyValue[$data] = $value;
						$keyName[$data] = $key;
					}
				}
				else
				{
					$companyValue[$data]= $value;
					$keyName[$data] = $key;
				}
				$data++;
			}
			// set data to the persistable object
			for($data=0;$data<count($companyValue);$data++)
			{
				//set the data in persistable object
				$companyPersistable = new CompanyPersistable();	
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
				
				//make function name dynamically
				$setFuncName = 'set'.$str;
				$getFuncName[$data] = 'get'.$str;
				$companyPersistable->$setFuncName($companyValue[$data]);
				$companyPersistable->setName($getFuncName[$data]);
				$companyPersistable->setKey($keyName[$data]);
				$companyArray[$data] = array($companyPersistable);
				if($data==(count($companyValue)-1))
				{
					if($docFlag==1)
					{
						if($documentFormat=='jpg' || $documentFormat=='jpeg' || $documentFormat=='gif' || $documentFormat=='png' || $documentFormat=='pdf')
						{	
							if(($documentSize/1048576)<=5)
							{
								$companyPersistable->setDocumentName($documentName);
								$companyPersistable->setDocumentUrl($documentUrl);
								$companyPersistable->setDocumentSize($documentSize);
								$companyPersistable->setDocumentFormat($documentFormat);
								$companyArray[$data] = array($companyPersistable);
							}
							else
							{
									
								return "FileNotFoundException: The file is too long";
							}
						}
						else
						{
							$exception = new ExceptionMessage();
							$fileSizeArray = $exception->errorMessage();
							return $fileSizeArray['fileFormat'];
						}
					}
				}
			}
			return $companyArray;
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * $param company_id
     * @return Company Persistable object
     */	
	public function createPersistableChange(Request $request,$companyId)
	{
		date_default_timezone_set("Asia/Calcutta");
		$errorCount=0;
		$flag=0;
		$errorStatus=array();
		$docFlag=0;
		$documentName="";
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			$companyPersistable;
			$companyArray = array();
			$companyValue = array();
			$companyValidate = new CompanyValidate();
			$status;
			
			//change the name of document-name
			$dateTime = date("d-m-Y h-i-s");
			$convertedDateTime = str_replace(" ","-",$dateTime);
			$splitDateTime = explode("-",$convertedDateTime);
			$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
			
			//file uploading
			$file = $request->file();
			if(in_array(true,$file))
			{
				//get document detail
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".".$file['file'][0]->getClientOriginalExtension();
				$documentFormat = $file['file'][0]->getClientOriginalExtension();
				$documentSize = $file['file'][0]->getClientSize();
				$path = 'Storage/Document/';
				$file['file'][0]->move($path,$documentName);
				$docFlag=1;
				
			}
			//if data is not available in update request
			if(count($_POST)==0)
			{
				$status = "204: No Content Found For Update";
				return $status;
			}
			//data is avalilable for update
			else
			{
				for($data=0;$data<count($_POST);$data++)
				{
					//set the data in persistable object
					$companyPersistable = new CompanyPersistable();	
					$value[$data] = $_POST[array_keys($_POST)[$data]];
					$key[$data] = array_keys($_POST)[$data];
					
					//trim an input 
					$companyTransformer = new CompanyTransformer();
					$tRequest = $companyTransformer->trimUpdateData($key[$data],$value[$data]);
					//get data from trim array
					
					$tKeyValue[$data] = array_keys($tRequest[0])[0];
					$tValue[$data] = $tRequest[0][array_keys($tRequest[0])[0]];
					
					//validation
					$status = $companyValidate->validateUpdateData($tKeyValue[$data],$tValue[$data],$tRequest[0]);
					
					//enter data is valid(one data validate status return)
					if($status=="Success")
					{
						// check data is string or not
						if(!is_numeric($tValue[$data]))
						{
							if (strpos($tValue[$data], '\'') !== FALSE)
							{
								$companyValue[$data] = str_replace("'","\'",$tValue[$data]);
							}
							else
							{
								$companyValue[$data] = $tValue[$data];
							}
						}
						else
						{
							$companyValue[$data] = $tValue[$data];
						}
						// flag=0...then data is valid(consider one data at a time)
						if($flag==0)
						{
							$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue[$data])));
							// make function name dynamically
							$setFuncName = 'set'.$str;
							$getFuncName[$data] = 'get'.$str;
							$companyPersistable->$setFuncName($companyValue[$data]);
							$companyPersistable->setName($getFuncName[$data]);
							$companyPersistable->setKey($key[$data]);
							$companyPersistable->setCompanyId($companyId);
							$companyArray[$data] = array($companyPersistable);
							
							if($data==(count($_POST)-1))
							{
								if($docFlag==1)
								{
									if($documentFormat=='jpg' || $documentFormat=='jpeg' || $documentFormat=='gif' || $documentFormat=='png' || $documentFormat=='pdf')
									{	
										if(($documentSize/1048576)<=5)
										{
											$companyPersistable->setDocumentName($documentName);
											$companyPersistable->setDocumentUrl($path);
											$companyPersistable->setDocumentSize($documentSize);
											$companyPersistable->setDocumentFormat($documentFormat);
											$companyArray[$data] = array($companyPersistable);
										}
										else
										{
											return "FileNotFoundException: The file is too long";
										}
									}
									else
									{
										return "FileNotFoundException: The file formate is not valid";
									}
								}
							}
						}
					}
					// enter data is not valid
					else
					{
						// if flag==1 then enter data is not valid ..so error is stored in an array.
						$flag=1;
						if(!empty($status[0]))
						{
							$errorStatus[$errorCount]=$status[0];
							$errorCount++;
						}
					}
					if($data==(count($_POST)-1))
					{
						if($flag==1)
						{
							return json_encode($errorStatus);
						}
						else
						{
							return $companyArray;
						}
					}
				}
			}
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setId($companyId);			
			return $companyPersistable;
		}
	}	
}