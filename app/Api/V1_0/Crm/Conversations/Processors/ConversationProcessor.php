<?php
namespace ERP\Api\V1_0\Crm\Conversations\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Crm\Conversations\Persistables\ConversationPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Crm\Conversations\Validations\ConversationValidate;
use ERP\Api\V1_0\Crm\Conversations\Transformers\ConversationTransformer;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Model\Clients\ClientModel;
use PHPMailer;
use SMTP;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConversationProcessor extends BaseProcessor
{
	/**
     * @var conversationPersistable
	 * @var request
     */
	private $conversationPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Job-Form Persistable object
     */	
    public function createPersistable(Request $request,$conversationType)
	{	
		$this->request = $request;
		$conversationArray = array();
		$conversationValue = array();
		$keyName = array();
		$value = array();
		$data=0;		
		$docFlag=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		//save documents in folder
		$file = $request->file();
		$processedData = array();
		if(in_array(true,$file))
		{
			$constantClass = new ConstantClass();
			$constantArray = $constantClass->constantVariable();
			$documentController =new DocumentController(new Container());
			$processedData = $documentController->insertUpdate($request,$constantArray['emailDocumentUrl']);
			if(is_array($processedData))
			{
				$docFlag=1;
			}
			else
			{
				return $processedData;
			}
		}
		
		if(count($_POST)==0)
		{
			return $msgArray['204'];
		}
		else
		{
			//trim an input 
			$conversationTransformer = new ConversationTransformer();
			$tRequest = $conversationTransformer->trimInsertData($this->request,$conversationType);
			
			//validation
			$conversationValidate = new ConversationValidate();
			$status = $conversationValidate->validate($tRequest);
			
			if($status=="Success")
			{
				//mail/sms send
				$result = $this->mailOrSmsSend($tRequest,$processedData);
				if(is_array($result))
				{	
					if(!array_key_exists('flag',$result))
					{
						return $msgArray['Email'];
					}
					else if(array_key_exists('flag',$result))
					{
						$result= array_splice($result,0,-1);
					}
				}
				$trimRequest = $tRequest;
				$tRequest= array_splice($tRequest,0,-1);
				
				foreach ($tRequest as $key => $value)
				{
					if(!is_numeric($value))
					{
						if (strpos($value, '\'') !== FALSE)
						{
							$conversationValue[$data]= str_replace("'","\'",$value);
							$keyName[$data] = $key;
						}
						else
						{
							$conversationValue[$data] = $value;
							$keyName[$data] = $key;
						}
					}
					else
					{
						$conversationValue[$data]= $value;
						$keyName[$data] = $key;
					}
					$data++;
				}
				
				// set data to the persistable object
				for($data=0;$data<count($conversationValue);$data++)
				{
					//set the data in persistable object
					$conversationPersistable = new ConversationPersistable();	
					$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
					
					//make function name dynamically
					$setFuncName = 'set'.$str;
					$getFuncName[$data] = 'get'.$str;
					$conversationPersistable->$setFuncName($conversationValue[$data]);
					$conversationPersistable->setName($getFuncName[$data]);
					$conversationPersistable->setKey($keyName[$data]);
					$conversationArray[$data] = array($conversationPersistable);
					if($data==(count($conversationValue)-1))
					{
						if($docFlag==1)
						{
							$conversationArray[$data+1]=$processedData;
						}
					}
				}
				array_push($conversationArray,$result);
				return $conversationArray;
			}
			else
			{
				return $status;
			}
		}
	}	
	
	 /**
     * send email/sms
     * $param trim-request array
     * @return error-message/status
     */	
	public function mailOrSmsSend($trimRequest,$documentData=null)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		$emailflag=0;
		if(strcmp($trimRequest['conversation_type'],'email')==0)
		{
			$errorArray = array();
			for($clientArray=0;$clientArray<count($trimRequest['client_id']);$clientArray++)
			{
				//send an email
				if($trimRequest['email_id']=='')
				{
					// get email from client-id
					$clientModel = new ClientModel();
					$clientDataResult = $clientModel->getData($trimRequest['client_id'][$clientArray]);
					$decodedClientData = json_decode($clientDataResult);
					if(is_array($decodedClientData))
					{
						if($decodedClientData[0]->email_id=='')
						{
							//error message: email-id is required
							$errorArray[$trimRequest['client_id'][$clientArray]] = $exceptionArray['requiredEmail'];
						}
						else
						{
							$email = $decodedClientData[0]->email_id;
						}
					}
					else
					{
						//error message: client-id doesnt exist
						$errorArray[$trimRequest['client_id'][$clientArray]] = $exceptionArray['404'];
					}
				}
				else
				{
					$email = $trimRequest['email_id'];
				}
				
				$mail = new PHPMailer(); // create a new object
				$mail->IsSMTP(); // enable SMTP
				$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
				$mail->SMTPAuth = true; // authentication enabled
				$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
				$mail->Host = "smtp.gmail.com";
				$mail->Port = 465; // or 587
				$mail->IsHTML(true);
				$mail->Username = $email;
				$mail->Password = "parents family512";
				$mail->SetFrom("reemap79@gmail.com");
				$mail->Subject = $trimRequest['subject'];
				$mail->Body = $trimRequest['conversation'];
				$mail->AddAddress($email);
								
				if(count($documentData)!=0)
				{
					$name = "Attachment";
					$documentPath = $documentData[0][3].$documentData[0][0];
					$mail->AddAttachment($documentPath,$name,$encoding ='base64',$type = 'application/octet-stream');
				}
				$result = $mail->Send();
				if($result!=1) {
					// echo "Mailer Error: " . $mail->ErrorInfo;
					//error message: mail could not be sent
					$errorArray[$trimRequest['client_id'][$clientArray]] = $exceptionArray['Email'];
				} 
				else 
				{
					$emailflag=1;
				}
			}
			$emailArray = array();
			if(count($errorArray)==0 && $emailflag==1)
			{
				//mail successfully send
				return $exceptionArray['successEmail'];
			}
			else if(count($errorArray)!=0 && $emailflag!=1)
			{
				//cant send an email
				$emailArray['error'] = $errorArray;
				return $emailArray;
			}
			else
			{
				//cant send an email to somewhat client...send an error-message array
				$emailArray['error'] = $errorArray;
				$emailArray['flag'] = 1;
				return $emailArray;
			}
		}
		else
		{
			for($clientArray=0;$clientArray<count($trimRequest['client_id']);$clientArray++)
			{
				//send an email
				if($trimRequest['contact_no']=='')
				{
					// get email from client-id
					$clientModel = new ClientModel();
					$clientDataResult = $clientModel->getData($trimRequest['client_id'][$clientArray]);
					$decodedClientData = json_decode($clientDataResult);
					$contactNo = $decodedClientData[0]->contact_no;
				}
				else
				{
					$contactNo = $trimRequest['contact_no'];
				}
				//send sms
				// $data = array(
					// 'user' => "siliconbrain",
					// 'password' => "demo54321",
					// 'msisdn' => $contactNo,
					// 'sid' => "ERPJSC",
					// 'msg' => $message,
					// 'fl' =>"0",
					// 'gwid'=>"2"
				// );
				// list($header,$content) = PostRequest("http://login.arihantsms.com//vendorsms/pushsms.aspx",$data);
				
				// //$url = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=siliconbrain&password=demo54321&msisdn=".$contactNo."&sid=COTTSO&msg=".$message."&fl=0&gwid=2";
			}
			//sms successfully send
			return $exceptionArray['successSms'];
		}
		
	}
}