<?php
namespace ERP\Core\Companies\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
  //a-zA-Z0-9 *,-\/_`#\[\]&().\'
class CompanyValidate
{
	public function validate($request)
	{
		$rules = array(
			'company_name'=> 'between:1,35|regex:/^[a-zA-Z0-9 -#&_()\'`.]+$/', 
			'company_display_name'=>'between:1,50|regex:/^[a-zA-Z0-9 -#&_()\'`.]+$/',
			'address1'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]().\']+$/',
			'address2'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]&().\']+$/',
			'pincode'=>'between:6,10|regex:/^[0-9]+$/',
			'pan'=>'max:10|min:10|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
			'tin'=>'max:11|min:11|regex:/^([a-zA-Z0-9])+$/',
			'vat_no'=>'max:11|min:11|regex:/^([a-zA-Z0-9])+$/',
			'service_tax_no'=>'between:1,35|regex:/^([a-zA-Z0-9])+$/',
			'basic_currency_symbol'=>'max:3|min:3',
			'formal_name'=>'between:1,35',
			'document_name'=>'between:1,35',
			'document_url'=>'max:2083',
			'document_size'=>'Integer',
			'document_format'=>'max:10',
		);
		$messages = [
			'company_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'company_name.regex' => 'Enter the character',
			'company_display_name.between' => 'StringLengthException :Enter the :attribute less then 50 character',
			'company_display_name.regex' => 'company display name',
			'address1.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'address1.regex' => 'address1',
			'address2.between' => 'StringLengthException :Enter the :attribute less then 35 character',
			'address2.regex' => 'address2',
			'pincode.between' => 'NumberFormatException :Enter the :attribute between 6 and 10 character',
			'pincode.regex' => 'pincode',
			'pan.max' => 'NumberFormatException :Enter the :attribute number of 10 character',
			'pan.min' => 'NumberFormatException :Enter the :attribute number of 10 character',
			'pan.regex' => 'pan',
			'tin.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'tin.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'tin.regex' => 'tin',
			'vat_no.regex' => 'vat no',
			'vat_no.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'vat_no.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'tin.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'tin.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
			'tin.regex' => 'tin',
			'service_tax_no.between' => 'NumberFormatException :Enter the:attribute less then 15 character',
			'service_tax_no.regex' => 'service tax no',
			'basic_currency_symbol.min' => 'StringLengthException :Enter the :attribute of 3 character',
			'basic_currency_symbol.max' => 'StringLengthException :Enter the :attribute of 3 character',
			'basic_currency_symbol.regex' => 'basic currency symbol',
			'formal_name.between' => 'StringLengthException :Enter the :attribute less the 35 character',
			'formal_name.regex' => 'formal name',
			'document_name.between' => 'StringLengthException :Enter the :attribute less the 35 character',
			'document_url.max' => 'StringLengthException :Enter the :attribute less the 2083 character',
			'document_size.Integer' => 'NumberFormatException :Enter the :attribute in integer',
			'document_format.max' => 'StringLengthException :Enter the :attribute less then 10 character',
		];
		$validator = Validator::make($request,$rules,$messages);
		if ($validator->fails()) 
		{
			$errors = $validator->errors()->toArray();
			$validate = array();
			for($data=0;$data<count($errors);$data++)
			{
				$detail[$data] = $errors[array_keys($errors)[$data]];
				$key[$data] = array_keys($errors)[$data];
				$validate[$data]= array($key[$data]=>$detail[$data][0]);
			}
			print_r($validate);
			return json_encode($validate);
		}
		else {
			return "Success";
		}
	}
	public function validateUpdateData($keyName,$value,$request)
	{
		$validationArray = array(
			'company_name'=> 'between:1,35|regex:/^[a-zA-Z0-9 -#&_()\'`.]+$/', 
			'company_display_name'=>'between:1,50|regex:/^[a-zA-Z0-9 -#&_()\'`.]+$/',
			'address1'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]&().\']+$/',
			'address2'=>'between:1,35|regex:/^[a-zA-Z0-9 *,-\/_`#\[\]&().\']+$/',
			'pincode'=>'between:6,10|regex:/^[0-9]+$/',
			'pan'=>'max:10|min:10|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
			'tin'=>'max:11|min:11|regex:/^([a-zA-Z0-9])+$/',
			'vat_no'=>'max:11|min:11|regex:/^([a-zA-Z0-9])+$/',
			'service_tax_no'=>'between:1,35|regex:/^([a-zA-Z0-9])+$/',
			'basic_currency_symbol'=>'max:3|min:3',
			'formal_name'=>'between:1,35',
			'document_name'=>'between:1,35',
			'document_url'=>'max:2083',
			'document_size'=>'Integer',
			'document_format'=>'max:10');
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
				$key=> $rules[$key]
			);
			$messages = [
				'company_name.between' => 'StringLengthException :Enter the :attribute less then 35 character',
				'company_name.regex' => 'Enter the character',
				'company_display_name.between' => 'StringLengthException :Enter the :attribute less then 50 character',
				'company_display_name.regex' => 'company display name',
				'address1.between' => 'StringLengthException :Enter the :attribute less then 35 character',
				'address1.regex' => 'address1',
				'address2.between' => 'StringLengthException :Enter the :attribute less then 35 character',
				'address2.regex' => 'address2',
				'pincode.between' => 'NumberFormatException :Enter the :attribute between 6 and 10 character',
				'pincode.regex' => 'pincode',
				'pan.max' => 'NumberFormatException :Enter the :attribute number of 10 character',
				'pan.min' => 'NumberFormatException :Enter the :attribute number of 10 character',
				'pan.regex' => 'pan',
				'tin.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'tin.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'tin.regex' => 'tin',
				'vat_no.regex' => 'vat no',
				'vat_no.max' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'vat_no.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'tin.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'tin.min' => 'NumberFormatException :Enter the :attribute number of 11 character',
				'tin.regex' => 'tin',
				'service_tax_no.between' => 'NumberFormatException :Enter the:attribute less then 15 character',
				'service_tax_no.regex' => 'service tax no',
				'basic_currency_symbol.min' => 'StringLengthException :Enter the :attribute of 3 character',
				'basic_currency_symbol.max' => 'StringLengthException :Enter the :attribute of 3 character',
				'basic_currency_symbol.regex' => 'basic currency symbol',
				'formal_name.between' => 'StringLengthException :Enter the :attribute less the 35 character',
				'formal_name.regex' => 'formal name',
				'document_name.between' => 'StringLengthException :Enter the :attribute less the 35 character',
				'document_url.max' => 'StringLengthException :Enter the :attribute less the 2083 character',
				'document_size.Integer' => 'NumberFormatException :Enter the :attribute in integer',
				'document_format.max' => 'StringLengthException :Enter the :attribute less then 10 character',
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