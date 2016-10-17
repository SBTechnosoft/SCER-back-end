<?php
namespace ERP\Core\Invoices\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class InvoiceValidate
{
	public function validate($request)
	{
		$rules = array(
			'invoice_label'=> 'between:1,35|regex:/^[a-zA-Z &_`#().\'-]*$/', 
		);
		$messages = [
			'invoice_label.between' => 'StringLengthException :Enter the :attribute less then 35 character',
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