<?php
namespace ERP\Core\ProductGroups\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
  //regex:/^[a-zA-Z0-9 ,-_`&(\.]+$/
class ProductGroupValidate
{
	public function validate($request)
	{
		$rules = array(
			'product_group_name'=> "between:1,35|regex:/^[a-zA-Z0-9 ,-\/_`().\']+$/", 
			// 'product_cat_desc'=>"between:1,50",
        );
		$messages = [
			'product_group_name.between' => 'StringLengthException :Enter the product group name less then 35 character',
			'product_group_name.regex' => 'RegularExpressionFormatException :Enter the proper prouct group name',
			// 'product_cat_desc.between' => 'StringLengthException :Enter the measurement_unit less then 15 character ',
			// 'product_cat_desc.regex' => 'RegularExpressionFormatException :Enter the proper measurement unit',
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
			'product_group_name'=> "between:1,35|regex:/^[a-zA-Z0-9 ,-_`&()\.]+$/", 
			// 'product_cat_desc'=>"between:1,50",
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
				'product_group_name.between' => 'StringLengthException :Enter the product category name less then 35 character',
				'product_group_name.regex' => 'RegularExpressionFormatException :Enter the proper prouct category name',
				// 'product_cat_desc.between' => 'StringLengthException :Enter the measurement_unit less then 15 character ',
				// 'product_cat_desc.regex' => 'RegularExpressionFormatException :Enter the proper measurement unit',
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