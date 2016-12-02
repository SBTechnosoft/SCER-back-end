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
	
	public function validateUpdateData($keyName,$value,$request)
	{
		
		$validationArray = array(
			'amount'=> 'regex:/^[0-9]*$/',
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
				'amount.regex' => 'amount contains character from "0-9" only',
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