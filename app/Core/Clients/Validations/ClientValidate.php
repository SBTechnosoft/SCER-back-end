<?php
namespace ERP\Core\Clients\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Clients\ClientModel;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class ClientValidate extends ClientModel
{
	 /**
     * validate client insertion data
     * $param Request object [Request $request]
     * @return error message/success message
     */	
	public function validate($request)
	{
		$rules = array(
			'client_name'=> 'between:1,100|regex:/^[a-zA-Z &_`#().\'-]*$/', 
			'company_name'=> 'between:2,50|regex:/^[a-zA-Z &_`#().\'-]+$/', 
			'contact_no'=> 'between:10,12|regex:/^[1-9][0-9]+$/', 
			'work_no'=> 'between:10,12|regex:/^[1-9][0-9]+$/', 
			'email_id'=> 'regex:/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', 
			'address1'=>'between:1,200|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
			'address2'=>'between:1,50|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
		);
		$messages = [
			'client_name.between' => 'StringLengthException :Enter the :attribute less then 100 character',
			'client_name.regex' => 'client-name contains character from "a-zA-Z &_`#().\'-" only','company_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'company_name.regex' => 'company-name contains character from "a-zA-Z &_`#().\'-" only',
			'contact_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
			// 'contact_no.required' => 'contact-no is required',
			'contact_no.regex' => 'contact-no contains character from "0-9" only',
			'work_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
			'work_no.regex' => 'work-no contains character from "0-9" only',
			'email_id.regex' => 'please enter your email-address in proper format',
			'address1.between' => 'StringLengthException :Enter the :attribute less then 200 character',
			'address1.regex' => 'address1 contains character from "a-zA-Z0-9* ,- /_`#[]().\" only',
			'address2.between' => 'StringLengthException :Enter the :attribute less then 50 character',
			'address2.regex' => 'address2 contains character from "a-zA-Z0-9* ,- /_`#[]().\" only',
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
		else {
			return "Success";
		}
	}
	
	 /**
     * validate client update data
     * $param Request object [Request $request]
     * @return error message/success message
     */
	public function validateUpdateData($keyName,$value,$request)
	{
		$validationArray = array(
			'client_name'=> 'between:1,100|regex:/^[a-zA-Z &_`#().\'-]*$/', 
			'company_name'=> 'between:2,50|regex:/^[a-zA-Z &_`#().\'-]+$/', 
			'contact_no'=> 'between:10,12|regex:/^[1-9][0-9]+$/', 
			'work_no'=> 'between:10,12|regex:/^[1-9][0-9]+$/', 
			'email_id'=> 'regex:/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/', 
			'address1'=>'between:1,200|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
			'address2'=>'between:1,50|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/'
		);
		$rules =array();
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
				$key=> $rules[$key],
			);
			$messages = [
				'client_name.between' => 'StringLengthException :Enter the :attribute less then 100 character',
				'client_name.regex' => 'client-name contains character from "a-zA-Z &_`#().\'-" only','company_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
				'company_name.regex' => 'company-name contains character from "a-zA-Z &_`#().\'-" only',
				'contact_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
				// 'contact_no.required' => 'contact-no is required',
				'contact_no.regex' => 'contact-no contains character from "0-9" only',
				'work_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
				'work_no.regex' => 'work-no contains character from "0-9" only',
				'email_id.regex' => 'please enter your email-address in proper format',
				'address1.between' => 'StringLengthException :Enter the :attribute less then 200 character',
				'address1.regex' => 'address1 contains character from "a-zA-Z0-9* ,- /_`#[]().\" only',
				'address2.between' => 'StringLengthException :Enter the :attribute less then 50 character',
				'address2.regex' => 'address2 contains character from "a-zA-Z0-9* ,- /_`#[]().\" only',
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
			else 
			{
				return "Success";
			}
		}
		else
		{
			return "Success";
		}
	}
	
	/**
     * validate data for client name
     * $param trim request data
     * @return error messgage/trim request array
     */	
	public function clientNameValidate($tRequest)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get product-data
		$clientValidation = new ClientValidate();
		$clientResult = $clientValidation->getClientName($tRequest['client_name']);
		
		if(!is_array($clientResult))
		{
			return $tRequest;
		}
		else
		{
			return $exceptionArray['content'];
		}
	}
	
	/**
     * validate update data for client name
     * $param trim request data
     * @return error messgage/trim request array
     */	
	public function clientNameValidateUpdate($tRequest,$clientId)
	{
		$flag=0;
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// get product-data
		$clientValidation = new ClientValidate();
		$clientData = $clientValidation->getData($clientId);
		$decodedClientdata = json_decode($clientData);
		
		$clientResult = $clientValidation->getClientName($tRequest['client_name']);
		if(!is_array($clientResult))
		{
			return $tRequest;
		}
		else
		{
			for($arrayData=0;$arrayData<count($clientResult);$arrayData++)
			{
				if($clientResult[$arrayData]->client_id!=$clientId)
				{
					$flag=1;
					break;
				}
			}
			if($flag==1)
			{
				return $exceptionArray['content'];
			}
			else
			{
				return $tRequest;
			}
		}
	}
}