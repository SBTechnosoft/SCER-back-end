<?php
namespace ERP\Core\Products\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
  //a-zA-Z0-9 ,-_`&().\'
class ProductValidate
{
	public function validate($request)
	{
		$rules = array(
			'product_name'=> "between:1,35|regex:/^[a-zA-Z0-9 ,-\/_`().\']+$/", 
			// 'measurement_unit'=>"between:1,15|regex:/^[a-z ]+$/",
        );
		$messages = [
			'product_name.between' => 'StringLengthException :Enter the product name less then 35 character',
			'product_name.regex' => 'RegularExpressionFormatException :Enter the proper prouct name',
			// 'measurement_unit.between' => 'StringLengthException :Enter the measurement_unit less then 15 character ',
			// 'measurement_unit.regex' => 'RegularExpressionFormatException :Enter the proper measurement unit',
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
	public function validateUpdateData($keyName,$value)
	{
		$validationArray = array(
			'product_name'=> "between:1,35|regex:/^[a-zA-Z0-9 ,-_`&()\.]+$/", 
			// 'measurement_unit'=>"between:1,15|regex:/^[a-z ]+$/"
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
				'product_name.regex' => 'RegularExpressionFormatException :Enter the proper prouct name',
				// 'measurement_unit.between' => 'StringLengthException :Enter the measurement_unit less then 15 character ',
				// 'measurement_unit.regex' => 'RegularExpressionFormatException :Enter the proper measurement unit',
			];
			$validator = Validator::make(Input::all(),$rules,$messages);
			
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