<?php
namespace ERP\Core\Products\Validations;

use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Products\ProductModel;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class ProductValidate extends ProductModel
{
	public function validate($request)
	{
		$rules = array(
			'product_name'=> 'between:1,35|regex:/^[a-zA-Z0-9 &,\/_`#().\'-]+$/', 
			'purchase_price'=> 'regex:/^[0-9 .]+$/', 
			'wholesale_margin'=> 'regex:/^[0-9 .]+$/', 
			'semi_wholesale_margin'=> 'regex:/^[0-9 .]+$/', 
			'margin'=> 'regex:/^[0-9 .]+$/', 
			'vat'=> 'regex:/^[0-9 .]+$/', 
			'mrp'=> 'regex:/^[0-9 .]+$/', 
		);
		$messages = [
			'product_name.between' => 'StringLengthException :Enter the product name less then 35 character',
			'product_name.regex' => 'product-name contains character from "a-zA-Z0-9 &,\/_`#().\'-" only',
			'purchase_price.regex' => 'purchase-price contains character from "0-9" only',
			'wholesale_margin.regex' => 'wholesale-margin contains character from "0-9" only',
			'semi_wholesale_margin.regex' => 'semi-wholesale-margin contains character from "0-9" only',
			'margin.regex' => 'margin contains character from "0-9" only',
			'vat.regex' => 'vat contains character from "0-9" only',
			'mrp.regex' => 'mrp contains character from "0-9" only',
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
			'purchase_price'=> 'regex:/^[0-9 .]+$/', 
			'wholesale_margin'=> 'regex:/^[0-9 .]+$/', 
			'semi_wholesale_margin'=> 'regex:/^[0-9 .]+$/', 
			'margin'=> 'regex:/^[0-9 .]+$/', 
			'vat'=> 'regex:/^[0-9 .]+$/', 
			'mrp'=> 'regex:/^[0-9 .]+$/',
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
				'purchase_price.regex' => 'purchase-price contains character from "0-9" only',
				'wholesale_margin.regex' => 'wholesale-margin contains character from "0-9" only',
				'semi_wholesale_margin.regex' => 'semi-wholesale-margin contains character from "0-9" only',
				'margin.regex' => 'margin contains character from "0-9" only',
				'vat.regex' => 'vat contains character from "0-9" only',
				'mrp.regex' => 'mrp contains character from "0-9" only',
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
			'discount'=> 'regex:/^[0-9 .]*$/',
			'price'=> 'regex:/^[0-9 .]*$/',
			'tax'=> 'regex:/^[0-9 .]*$/',
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
				'price.regex' => 'price contains character from "0-9" only',
				'tax.regex' => 'tax contains character from "0-9" only',
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
	
	/**
     * validate data for product name
     * $param trim request data
     * @return error messgage/trim request array
     */	
	public function productNameValidate($tRequest)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get product-data
		$productValidation = new ProductValidate();
		$productResult = $productValidation->getProductName($tRequest['product_name'],$tRequest['company_id']);
		
		if(!is_array($productResult))
		{
			return $tRequest;
		}
		else
		{
			return $exceptionArray['content'];
		}
	}
	
	/**
     * validate update data for product name
     * $param trim request data
     * @return error messgage/trim request array
     */	
	public function productNameValidateUpdate($tRequest,$productId)
	{
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// get product-data
		$productValidation = new ProductValidate();
		$productData = $productValidation->getData($productId);
		$decodedProductdata = json_decode($productData);
		
		$productResult = $productValidation->getProductName($tRequest['product_name'],$decodedProductdata[0]->company_id);
		if(!is_array($productResult))
		{
			return $tRequest;
		}
		else
		{
			if($productResult[0]->product_id==$productId)
			{
				return $tRequest;
			}
			else
			{
				return $exceptionArray['content'];
			}
		}
	}
}