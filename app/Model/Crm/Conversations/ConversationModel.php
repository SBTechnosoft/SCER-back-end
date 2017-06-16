<?php
namespace ERP\Model\Crm\Conversations;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Clients\ClientModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConversationModel extends Model
{
	protected $table = 'conversation_dtl';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertEmailData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$getDataArray = func_get_arg(0);
		$getKeyData = func_get_arg(1);
		$document = func_get_arg(2);
		$errorArray = func_get_arg(3);
		$inputData = func_get_arg(4);
		if(is_array($errorArray))
		{
			$errorArray = $errorArray['error'];
		}
		$conversationData='';
		$keyName = "";
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($data=0;$data<count($getDataArray);$data++)
		{
			if($data == (count($getDataArray)-1))
			{
				if(strcmp('email_id',$getKeyData[$data])!=0)
				{
					$conversationData = $conversationData."'".$getDataArray[$data]."'";
					$keyName =$keyName.$getKeyData[$data];
				}
				
			}
			else
			{
				if(strcmp('email_id',$getKeyData[$data])!=0)
				{
					$conversationData = $conversationData."'".$getDataArray[$data]."',";
					$keyName =$keyName.$getKeyData[$data].",";
				}
			}
		}
		$raw='';
		//request input data
		for($dataArray=0;$dataArray<count($inputData['client']);$dataArray++)
		{
			$emailId='';
			//check error is exist..if yes then cant insert data into database
			if(is_array($errorArray))
			{
				if(count($errorArray)!=0)
				{
					$errorFlag=0;
					for($innerArray=0;$innerArray<count($errorArray);$innerArray++)
					{
						if($inputData['client'][$dataArray]['clientId']==array_keys($errorArray)[$innerArray])
						{
							$errorFlag=1;
							break;
						}
					}
				}
				if($errorFlag==0)
				{
					//check email-id is given/not..
					if(array_key_exists('emailId',$inputData) && $inputData['emailId']!='')
					{
						$emailId = $inputData['emailId'];
					}
					else
					{
						//find client email-id and store it to the database
						$clientModel = new ClientModel();
						$clientDataResult = $clientModel->getData($inputData['client'][$dataArray]['clientId']);
						$decodedClientData = json_decode($clientDataResult);
						$emailId=$decodedClientData[0]->email_id;
					}
				}
			}
			else
			{
				if(array_key_exists('emailId',$inputData) && $inputData['emailId']!='')
				{
					$emailId = $inputData['emailId'];
				}
				else
				{
					//find client email-id and store it to the database
					$clientModel = new ClientModel();
					$clientDataResult = $clientModel->getData($inputData['client'][$dataArray]['clientId']);
					$decodedClientData = json_decode($clientDataResult);
					$emailId=$decodedClientData[0]->email_id;
				}
			}
			if($emailId!='')
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("insert into conversation_dtl(".$keyName.",email_id)
				values(".$conversationData.",'".$emailId."')");
				DB::commit();
			}
		}
		if($raw==1)
		{
			if(count($errorArray)!=0)
			{
				return $errorArray;
			}
			return $exceptionArray['200'];
		}
		else
		{
			if(count($errorArray)!=0)
			{
				return $errorArray;
			}
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertSmsData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$getDataArray = func_get_arg(0);
		$getKeyData = func_get_arg(1);
		$document = func_get_arg(2);
		$errorArray = func_get_arg(3);
		$inputData = func_get_arg(4);
		$conversationData='';
		$keyName = "";
		for($data=0;$data<count($getDataArray);$data++)
		{
			if($data == (count($getDataArray)-1))
			{
				if(strcmp('contact_no',$getKeyData[$data])!=0)
				{
					$conversationData = $conversationData."'".$getDataArray[$data]."'";
					$keyName =$keyName.$getKeyData[$data];
				}
				else
				{
					$conversationData = rtrim($conversationData,",");
					$keyName = rtrim($keyName,",");
				}
			}
			else
			{
				if(strcmp('contact_no',$getKeyData[$data])!=0)
				{
					$conversationData = $conversationData."'".$getDataArray[$data]."',";
					$keyName =$keyName.$getKeyData[$data].",";
				}
			}
		}
		$raw='';
		//request input data
		for($dataArray=0;$dataArray<count($inputData['client']);$dataArray++)
		{
			$contactNo='';
			
			if(array_key_exists('contactNo',$inputData) && $inputData['contactNo']!='')
			{
				$contactNo = $inputData['contactNo'];
			}
			else
			{
				//find client contact-no and store it to the database
				$clientModel = new ClientModel();
				$clientDataResult = $clientModel->getData($inputData['client'][$dataArray]['clientId']);
				$decodedClientData = json_decode($clientDataResult);
				$contactNo=$decodedClientData[0]->contact_no;
			}
			
			if($contactNo!='')
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("insert into conversation_dtl(".$keyName.",contact_no)
				values(".$conversationData.",'".$contactNo."')");
				DB::commit();
			}
		}
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
