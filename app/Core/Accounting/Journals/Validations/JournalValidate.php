<?php
namespace ERP\Core\Accounting\Journals\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class JournalValidate
{
	public function validate($request)
	{
		$rules = array(
			'jf_id'=> 'regex:/^[0-9]*$/' 
		);
		$messages = [
			'jf_id.regex' => 'journal folio id contains character from "0-9" only'
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