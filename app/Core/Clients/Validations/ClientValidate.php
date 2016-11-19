<?php
namespace ERP\Core\Clients\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class ClientValidate
{
	public function validate($request)
	{
		$rules = array(
			'client_name'=> 'between:1,35|regex:/^[a-zA-Z &_`#().\'-]*$/', 
			'company_name'=> 'between:1,35|regex:/^[a-zA-Z &_`#().\'-]*$/', 
			'contact_no'=> 'between:10,12|regex:/^[0-9]*$/', 
			'work_no'=> 'between:10,12|regex:/^[0-9]*$/', 
			'email_id'=> 'regex:/^[a-zA-Z]*$/', 
			'address1'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
			'address2'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
		);
		$messages = [
			'client_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'client_name.regex' => 'client-name contains character from "a-zA-Z &_`#().\'-" only','company_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'company_name.regex' => 'company-name contains character from "a-zA-Z &_`#().\'-" only',
			'contact_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
			'contact_no.regex' => 'contact-no contains character from "0-9" only',
			'work_no.between' => 'StringLengthException :Enter the :attribute between 10-12 number',
			'work_no.regex' => 'work-no contains character from "0-9" only',
			'email_id.regex' => 'entered email id is not correct',
			'address1.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'address1.regex' => 'address1 contains character from "a-zA-Z0-9 *,-\/_`#\[\]().\'" only',
			'address2.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'address2.regex' => 'address2 contains character from "a-zA-Z0-9 *,-\/_`#\[\]().\'" only',
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
}