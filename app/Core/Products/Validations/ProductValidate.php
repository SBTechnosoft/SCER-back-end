<?php
namespace ERP\Core\Products\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class ProductValidate
{
	public function validate($request)
	{
		$rules = array(
			'product_name'=> 'between:1,35|regex:/^[a-zA-Z0-9 &,\/_`#().\'-]+$/', 
		);
		$messages = [
			'product_name.between' => 'StringLengthException :Enter the product name less then 35 character',
			'product_name.regex' => 'product-name contains character from "a-zA-Z0-9 &,\/_`#().\'-" only',
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
	
	public function validateInOutward($request)
	{
		$rules = array(
			'company_id'=> 'regex:/^[0-9]*$/' 
		);
		$messages = [
			'company_id.regex' => 'company id contains character from "0-9" only'
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
	public function validateUpdateData($keyName,$value,$request)
	{
		$validationArray = array(
			'product_name'=> 'between:1,35|regex:/^[a-zA-Z0-9 &,\/_`#().\'-]+$/', 
		);
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
				'product_name.between' => 'StringLengthException :Enter the product name less then 35 character',
				'product_name.regex' => 'product-name contains character from "a-zA-Z0-9 &,\/_`#().\'-" only',
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
	public function validateTransactionUpdateData($keyName,$value,$request)
	{
		
		$validationArray = array(
			'discount'=> 'regex:/^[0-9]*$/',
			// 'entry_date'=>'regex:/^[0-9]*$/'
			//entry-date
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
				'discount.regex' => 'discount contains character from "0-9" only',
				// 'entry_date.regex'=>'entry-date contains number and "-" only'
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
}