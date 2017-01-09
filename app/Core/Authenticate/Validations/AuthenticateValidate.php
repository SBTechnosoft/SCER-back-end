<?php
namespace ERP\Core\Authenticate\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use ERP\Core\Users\Services\UserService;
use ERP\Exceptions\ExceptionMessage;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class AuthenticateValidate extends UserService
{
	public function insertValidate($request)
	{
		$matchFlag=0;
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//convert password into base64_encode
		$decodedPassword = base64_decode($request['password']);
		
		//get user data
		$userService = new AuthenticateValidate();
		$getAllData = $userService->getAllUserData();
		$decodedUserData = json_decode($getAllData);
		for($arrayData=0;$arrayData<count($decodedUserData);$arrayData++)
		{
			if(strcmp($decodedUserData[$arrayData]->emailId,$request['email_id'])==0 && strcmp($decodedUserData[$arrayData]->password,$decodedPassword)==0)
			{
				$matchFlag=1;
				$userId = $decodedUserData[$arrayData]->userId;
				$emailId = $decodedUserData[$arrayData]->emailId;
				$password = $decodedUserData[$arrayData]->password;
				$createdAt = $decodedUserData[$arrayData]->createdAt;
				break;
			}
		}
		if($matchFlag==0)
		{
			return $msgArray['content'];
		}
		else
		{
			$requestArray = array();
			$requestArray['userId'] = $userId;
			$requestArray['emailId'] = $emailId;
			$requestArray['password'] = $password;
			$requestArray['createdAt'] = $createdAt;
			return $requestArray;
		}	
	}
}