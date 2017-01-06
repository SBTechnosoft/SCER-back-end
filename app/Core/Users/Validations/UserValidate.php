<?php
namespace ERP\Core\Users\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use ERP\Core\Users\Services\UserService;
use ERP\Exceptions\ExceptionMessage;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class UserValidate
{
	/**
     * @param Request $request
     * @return success/error-message
     */
	public function validate($request)
	{
		//emailId,contactNo pending
		$rules = array(
			'user_name'=>"between:1,35|regex:/^[a-zA-Z &-]+$/",
			'address'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
			'pincode'=>'between:6,10|regex:/^[0-9]+$/'
		);
		$messages = [
			'user_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'user_name.regex' => 'user-name contains character from "a-zA-Z -&" only',
			'address.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'address.regex' => 'address contains character from "a-zA-Z0-9 *,-\/_`#\[\]().\'" only',
			'pincode.between' => 'NumberFormatException :Enter the :attribute between 6 and 10 character',
			'pincode.regex' => 'pincode contains numbers only'
		];
		
		$validator = Validator::make($request,$rules,$messages);
		if ($validator->fails()) {
			$errors = $validator->errors()->toArray();
			$validate = array();
			for($data=0;$data<count($errors);$data++)
			{
				$detail[$data] = $errors[array_keys($errors)[$data]];
				$key[$data] = array_keys($errors)[$data];
				$validate[$data]= array($key[$data]=>$detail[$data][0]);
			}
			return json_encode($validate);
		}
		else 
		{
			return "Success";
		}
	}
	
	/**
     * @param array of trimRequest
     * @return trimRequest/error-message
     */
	public function emailIdCheck($trimRequest)
	{
		$emailFlag=0;
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//get all user data
		$userService = new UserService();
		$userData = $userService->getAllUserData();
		$decodedUserData = json_decode($userData);
		for($arrayData=0;$arrayData<count($decodedUserData);$arrayData++)
		{
			if(strcmp($decodedUserData[$arrayData]->emailId,$trimRequest['email_id'])==0)
			{
				$emailFlag=1;
				break;
			}
		}
		if($emailFlag==1)
		{
			return $msgArray['content'];
		}
		return $trimRequest;
	}
	
	/**
     * @param Request $request
     * @return success/error-message
     */
	public function validateUpdateData($keyName,$value,$request)
	{
		$validationArray = array('state_name'=>"between:1,35|regex:/^[a-zA-Z &-]+$/");
		$rules = array();
		foreach ($validationArray as $key => $value) 
		{
			if($key == $keyName)
			{
				$rules[$key]=$value;
				break;
			}
		}
		if(!empty($rules))
		{
			$rules = array(
				$key=> $rules[$key]
			);
			$messages = [
				'state_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
				'state_name.regex' => 'state-name contains character from "a-zA-Z -&" only',
			];
			$validator = Validator::make($request,$rules,$messages);
			
			if ($validator->fails()) 
			{
				$errors = $validator->errors()->toArray();
				$validate = array();
				for($data=0;$data<count($errors);$data++)
				{
					$detail[$data] = $errors[array_keys($errors)[$data]];
					$key[$data]=array_keys($errors)[$data];
					$validate[$data]= array($key=>$detail[$data][0]);
				}
				return $validate;
			}
			else {
				return "Success";
			}
		}
		else
		{
			return "Success";
		}
	}
}