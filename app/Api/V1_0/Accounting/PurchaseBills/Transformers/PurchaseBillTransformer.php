<?php
namespace ERP\Api\V1_0\Accounting\PurchaseBills\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Accounting\Bills\Entities\PaymentModeEnum;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
use ERP\Core\Accounting\PurchaseBills\Entities\BillTypeEnum;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PurchaseBillTransformer
{
    /**
	 * trim input data 	
     * @param Request Object
     * @return array/error message
     */
    public function trimInsertData(Request $request)
    {
		$purchaseBillArray = array();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$discountTypeEnum = new DiscountTypeEnum();
		$discountTypeEnumArray = $discountTypeEnum->enumArrays();
		
		$billTypeEnum = new BillTypeEnum();
		$billTypeEnumArray = $billTypeEnum->enumArrays();
		
		$paymentModeEnum = new PaymentModeEnum();
		$paymentModeArray = $paymentModeEnum->enumArrays();
		
		//date conversation
		if(array_key_exists('transactionDate',$request->input()))
		{
			$splitedDate = explode("-",trim($request->input()['transactionDate']));
			$transactionDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
			$purchaseBillArray['transactionDate'] = $transactionDate;
		}
		//date conversation
		if(array_key_exists('entryDate',$request->input()))
		{
			$splitedDate = explode("-",trim($request->input()['entryDate']));
			$entryDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
			$purchaseBillArray['entryDate'] = $entryDate;
		}
		$purchaseBillArray['vendorId'] = array_key_exists('vendorId',$request->input())? trim($request->input()['vendorId']):0;
		$purchaseBillArray['companyId'] = array_key_exists('companyId',$request->input())? trim($request->input()['companyId']):0;
		$purchaseBillArray['billNumber'] = array_key_exists('billNumber',$request->input())? trim($request->input()['billNumber']):'';
		$purchaseBillArray['total'] = array_key_exists('total',$request->input())? trim($request->input()['total']):0;
		$purchaseBillArray['tax'] = array_key_exists('tax',$request->input())? trim($request->input()['tax']):0;
		$purchaseBillArray['grandTotal'] = array_key_exists('grandTotal',$request->input())? trim($request->input()['grandTotal']):0;
		$purchaseBillArray['advance'] = array_key_exists('advance',$request->input())? trim($request->input()['advance']):0;
		$purchaseBillArray['balance'] = array_key_exists('balance',$request->input())? trim($request->input()['balance']):0;
		$purchaseBillArray['extraCharge'] = array_key_exists('extraCharge',$request->input())? trim($request->input()['extraCharge']):0;
		$purchaseBillArray['bankName'] = array_key_exists('bankName',$request->input())? trim($request->input()['bankName']):'';
		$purchaseBillArray['checkNumber'] = array_key_exists('checkNumber',$request->input())? trim($request->input()['checkNumber']):'';
		$purchaseBillArray['remark'] = array_key_exists('remark',$request->input())? trim($request->input()['remark']):'';
		$purchaseBillArray['totalDiscount'] = array_key_exists('totalDiscount',$request->input())? trim($request->input()['totalDiscount']):0;
		$purchaseBillArray['billType'] = $billTypeEnumArray['purchaseBillType'];
		if(array_key_exists('totalDiscounttype',$request->input()))
		{
			if(strcmp(trim($request->input()['totalDiscounttype']),$discountTypeEnumArray['flatType'])==0 || strcmp(trim($request->input()['totalDiscounttype']),$discountTypeEnumArray['percentageType'])==0)
			{
				$purchaseBillArray['totalDiscounttype'] = trim($request->input()['totalDiscounttype']);
			}
			else
			{
				return $exceptionArray['content'];
			}
		}
		if(array_key_exists('paymentMode',$request->input()))
		{
			if(strcmp(trim($request->input()['paymentMode']),$paymentModeArray['cashPayment'])==0 || strcmp(trim($request->input()['paymentMode']),$paymentModeArray['bankPayment'])==0|| strcmp(trim($request->input()['paymentMode']),$paymentModeArray['cardPayment'])==0)
			{
				$purchaseBillArray['paymentMode'] = trim($request->input()['paymentMode']);
			}
			else
			{
				return $exceptionArray['content'];
			}
		}
		if(array_key_exists('inventory',$request->input()))
		{
			$inventoryCount = count($request->input()['inventory']);
			$inventoryData = array();
			for($inventoryArray=0;$inventoryArray<$inventoryCount;$inventoryArray++)
			{
				$inventoryData[$inventoryArray] = $request->input()['inventory'][$inventoryArray];
				$purchaseBillArray['inventory'][$inventoryArray]['productId'] = array_key_exists('productId',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['productId']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['discount'] = array_key_exists('discount',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['discount']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['price'] = array_key_exists('price',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['price']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['qty'] = array_key_exists('qty',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['qty']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['color'] = array_key_exists('color',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['color']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['frameNo'] = array_key_exists('frameNo',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['frameNo']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['size'] = array_key_exists('size',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['size']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['amount'] = array_key_exists('amount',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['amount']) : 0;
				if(array_key_exists('discountType',$inventoryData[$inventoryArray]))
				{
					if(strcmp($inventoryData[$inventoryArray]['discountType'],$discountTypeEnumArray['flatType'])==0 || strcmp($inventoryData[$inventoryArray]['discountType'],$discountTypeEnumArray['percentageType'])==0)
					{
						$purchaseBillArray['inventory'][$inventoryArray]['discountType'] = trim($inventoryData[$inventoryArray]['discountType']);
					}
				}
			}
		}
		return $purchaseBillArray;
	}
	
	/**
	 * trim update data 	
     * @param Request Object
     * @return array/error message
     */
    public function trimUpdateData(Request $request)
    {
		$purchaseBillArray = array();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$discountTypeEnum = new DiscountTypeEnum();
		$discountTypeEnumArray = $discountTypeEnum->enumArrays();
		$billTypeEnum = new BillTypeEnum();
		$billTypeEnumArray = $billTypeEnum->enumArrays();
		$paymentModeEnum = new PaymentModeEnum();
		$paymentModeArray = $paymentModeEnum->enumArrays();
		$inputData = $request->input();
		if(array_key_exists('inventory',$request->input()))
		{
			$inputData = array_except($inputData,['inventory']);
			$purchaseDataCount = count($inputData);
		}
		else
		{
			$purchaseDataCount = count($request->input());
		}
		$input = $inputData;		
		for($arrayData=0;$arrayData<$purchaseDataCount;$arrayData++)
		{
			//date conversation
			if(strcmp(array_keys($input)[$arrayData],'transactionDate')==0)
			{
				$splitedDate = explode("-",trim($input['transactionDate']));
				$transactionDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
				$purchaseBillArray['transactionDate'] = $transactionDate;
			}
			else if(strcmp(array_keys($input)[$arrayData],'entryDate')==0)
			{
				$splitedDate = explode("-",trim($input['entryDate']));
				$entryDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
				$purchaseBillArray['entryDate'] = $entryDate;
			}
			else
			{
				if(strcmp(array_keys($input)[$arrayData],'totalDiscounttype')==0 || strcmp(array_keys($input)[$arrayData],'billType')==0 
					|| strcmp(array_keys($input)[$arrayData],'paymentMode')==0)
				{
					if(array_key_exists('totalDiscounttype',$inputData))
					{
						if(strcmp(trim($inputData['totalDiscounttype']),$discountTypeEnumArray['flatType'])==0 || strcmp(trim($inputData['totalDiscounttype']),$discountTypeEnumArray['percentageType'])==0)
						{
							$purchaseBillArray['totalDiscounttype'] = trim($inputData['totalDiscounttype']);
						}
						else
						{
							return $exceptionArray['content'];
						}
					}
					if(array_key_exists('paymentMode',$inputData))
					{
						if(strcmp(trim($inputData['paymentMode']),$paymentModeArray['cashPayment'])==0 || strcmp(trim($inputData['paymentMode']),$paymentModeArray['bankPayment'])==0|| strcmp(trim($inputData['paymentMode']),$paymentModeArray['cardPayment'])==0)
						{
							$purchaseBillArray['paymentMode'] = trim($inputData['paymentMode']);
						}
						else
						{
							return $exceptionArray['content'];
						}
					}
				}
				else
				{
					$key = array_keys($input)[$arrayData];
					$value = $input[$key];
					$purchaseBillArray[$key]=trim($value);
				}
			}
		}
		if(array_key_exists('inventory',$request->input()))
		{
			$inventoryCount = count($request->input()['inventory']);
			$inventoryData = array();
			for($inventoryArray=0;$inventoryArray<$inventoryCount;$inventoryArray++)
			{
				$inventoryData[$inventoryArray] = $request->input()['inventory'][$inventoryArray];
				$purchaseBillArray['inventory'][$inventoryArray]['productId'] = array_key_exists('productId',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['productId']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['discount'] = array_key_exists('discount',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['discount']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['price'] = array_key_exists('price',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['price']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['qty'] = array_key_exists('qty',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['qty']) : 0;
				$purchaseBillArray['inventory'][$inventoryArray]['color'] = array_key_exists('color',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['color']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['frameNo'] = array_key_exists('frameNo',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['frameNo']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['size'] = array_key_exists('size',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['size']) : '';
				$purchaseBillArray['inventory'][$inventoryArray]['amount'] = array_key_exists('amount',$inventoryData[$inventoryArray])? trim($inventoryData[$inventoryArray]['amount']) : '';
				if(array_key_exists('discountType',$inventoryData[$inventoryArray]))
				{
					if(strcmp($inventoryData[$inventoryArray]['discountType'],$discountTypeEnumArray['flatType'])==0 || strcmp($inventoryData[$inventoryArray]['discountType'],$discountTypeEnumArray['percentageType'])==0)
					{
						$purchaseBillArray['inventory'][$inventoryArray]['discountType'] = trim($inventoryData[$inventoryArray]['discountType']);
					}
				}
			}
		}
		return $purchaseBillArray;
	}
	
	
	
	/**
     * trim bill update data and check enum data type
	 * @param request data 
     * @return array/error message
     */
	public function trimBillUpdateData(Request $request)
	{
		$convertedValue="";
		$dataFlag=0;
		$discountTypeFlag=0;
		$paymentModeFlag = 0;
		$isDisplayFlag = 0;
		$tempArrayFlag=0;
		$tempArray = array();
		$tBillArray = array();
		$billArrayData = $request->input();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(array_key_exists('paymentMode',$billArrayData))
		{
			if(strcmp($billArrayData['paymentMode'],'cash')==0 || strcmp($billArrayData['paymentMode'],'card')==0)
			{
				$billArrayData['bankName'] = "";
				$billArrayData['checkNumber'] = "";
			}
		}			
		for($inputArrayData=0;$inputArrayData<count($billArrayData);$inputArrayData++)
		{
			if(strcmp(array_keys($billArrayData)[$inputArrayData],'inventory')==0)
			{
				$enumDiscountTypeArray = array();
				$discountTypeEnum = new DiscountTypeEnum();
				$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
				for($inventoryArray=0;$inventoryArray<count($billArrayData['inventory']);$inventoryArray++)
				{
					$tempArrayFlag=1;
					$tempArray[$inventoryArray] = array();
					$tempArray[$inventoryArray]['productId'] = trim($billArrayData['inventory'][$inventoryArray]['productId']);
					$tempArray[$inventoryArray]['discount'] = trim($billArrayData['inventory'][$inventoryArray]['discount']);
					$tempArray[$inventoryArray]['discountType'] = trim($billArrayData['inventory'][$inventoryArray]['discountType']);
					$tempArray[$inventoryArray]['price'] = trim($billArrayData['inventory'][$inventoryArray]['price']);
					$tempArray[$inventoryArray]['qty'] = trim($billArrayData['inventory'][$inventoryArray]['qty']);
					$tempArray[$inventoryArray]['color'] = trim($billArrayData['inventory'][$inventoryArray]['color']);
					$tempArray[$inventoryArray]['frameNo'] = trim($billArrayData['inventory'][$inventoryArray]['frameNo']);
					$tempArray[$inventoryArray]['size'] = trim($billArrayData['inventory'][$inventoryArray]['size']);
					foreach ($enumDiscountTypeArray as $key => $value)
					{
						if(strcmp($value,$tempArray[$inventoryArray]['discountType'])==0)
						{
							$discountTypeFlag=1;
							break;
						}
						else
						{
							$discountTypeFlag=0;
						}
					}
					if($discountTypeFlag==0)
					{
						return $exceptionArray['content'];
					}
				}
			}
			else
			{
				$dataFlag=1;
				$key = array_keys($billArrayData)[$inputArrayData];
				$value = $billArrayData[$key];
				for($asciiChar=0;$asciiChar<strlen($key);$asciiChar++)
				{
					if(ord($key[$asciiChar])<=90 && ord($key[$asciiChar])>=65) 
					{
						$convertedValue1 = "_".chr(ord($key[$asciiChar])+32);
						$convertedValue=$convertedValue.$convertedValue1;
					}
					else
					{
						$convertedValue=$convertedValue.$key[$asciiChar];
					}
				}
				//check is_display and payment-mode
				//check enum type of payment-mode
				if(strcmp('payment_mode',$convertedValue)==0)
				{
					$paymentModeArray = array();
					$paymentModeEnum = new PaymentModeEnum();
					$paymentModeArray = $paymentModeEnum->enumArrays();
					
					$tBillArray[$convertedValue]=trim($value);
					foreach ($paymentModeArray as $key => $value)
					{
						if(strcmp($value,$tBillArray[$convertedValue])==0)
						{
							$paymentModeFlag=1;
							break;
						}
					}
					if($paymentModeFlag==0)
					{
						return $exceptionArray['content'];
					}
				}
				else if(strcmp('is_display',$convertedValue)==0)
				{
					$isDisplayEnum = new IsDisplayEnum();
					$isDisplayArray = $isDisplayEnum->enumArrays();
					
					$tBillArray[$convertedValue]=trim($value);
					foreach ($isDisplayArray as $key => $value)
					{
						if(strcmp($value,$tBillArray[$convertedValue])==0)
						{
							$isDisplayFlag=1;
							break;
						}
					}
					if($isDisplayFlag==0)
					{
						return $exceptionArray['content'];
					}
				}
				else
				{
					$tBillArray[$convertedValue]=trim($value);
				}
				$convertedValue="";
			}
		}
		if($tempArrayFlag==1 && $dataFlag==1)
		{
			$tBillArray['inventory'] = $tempArray;
		}
		else if($tempArrayFlag==1)
		{
			$tBillArray['inventory'] = $tempArray;
		}
		return $tBillArray;
	}
	
	 /**
	 *  trim data -> conversion date -> make an array of transform data
	 * @param request header
     * @return array/error message
     */
	public function trimFromToDateData($headerData)
	{
		//get date from header and trim data
		$fromDate = trim($headerData['fromdate'][0]);
		$toDate = trim($headerData['todate'][0]);
		
		if(strcmp($fromDate,'00-00-0000')==0)
		{
			$transformFromDate = '0000-00-00';
		}
		else
		{
			//from-date conversion
			$splitedFromDate = explode("-",$fromDate);
			$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
		}
		if(strcmp($toDate,'00-00-0000')==0)
		{
			$transformToDate = '0000-00-00';
		}
		else
		{
			//to-date conversion
			$splitedToDate = explode("-",$toDate);
			$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
		}
		$trimArray = array();	
		$trimArray['fromDate'] = $transformFromDate;	
		$trimArray['toDate'] = $transformToDate;	
		return $trimArray;
	}
}