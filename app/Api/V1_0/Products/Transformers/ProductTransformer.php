<?php
namespace ERP\Api\V1_0\Products\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
use ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Core\Products\Entities\EnumClasses\measurementUnitEnum;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductTransformer
{
    /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$isDisplayFlag=0;
		$measurementUnitFlag=0;
		
		//data get from body
		$productName = $request->input('productName'); 
		$measurementUnit = $request->input('measurementUnit'); 
		$color = $request->input('color'); 
		$size = $request->input('size'); 
		$isDisplay = $request->input('isDisplay'); 			
		$purchasePrice = $request->input('purchasePrice'); 			
		$wholeSaleMargin = $request->input('wholesaleMargin'); 			
		$wholeSaleMarginFlat = $request->input('wholesaleMarginFlat'); 			
		$semiWholeSaleMargin = $request->input('semiWholesaleMargin'); 			
		$vat = $request->input('vat'); 			
		$mrp = $request->input('mrp'); 			
		$margin = $request->input('margin'); 			
		$marginFlat = $request->input('marginFlat'); 			
		$productDescription = $request->input('productDescription'); 			
		$additionalTax = $request->input('additionalTax'); 			
		$minimumStockLevel = $request->input('minimumStockLevel'); 			
		$companyId = $request->input('companyId'); 			
		$productCatId = $request->input('productCategoryId'); 			
		$productGrpId = $request->input('productGroupId'); 			
		$branchId = $request->input('branchId'); 	 
		
		//trim an input
		$tProductName = trim($productName);
		$tMeasUnit = trim($measurementUnit);
		$tColor = trim($color);
		$tSize = trim($size);
		$tIsDisplay = trim($isDisplay);
		$tPurchasePrice = trim($purchasePrice);
		$tWholeSaleMargin = trim($wholeSaleMargin);
		$tWholeSaleMarginFlat = trim($wholeSaleMarginFlat);
		$tSemiWholeSaleMargin = trim($semiWholeSaleMargin);
		$tVat = trim($vat);
		$tMrp = trim($mrp);
		$tMargin = trim($margin);
		$tMarginFlat = trim($marginFlat);
		$tProductDescription = trim($productDescription);
		$tAdditionalTax = trim($additionalTax);
		$tMinimumStockLevel = trim($minimumStockLevel);
		$tCompanyId = trim($companyId);
		$tProductCatId = trim($productCatId);
		$tProductGrpId = trim($productGrpId);
		$tBranchId = trim($branchId);
		
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if($tIsDisplay=="")
		{
			$tIsDisplay=$enumIsDispArray['display'];
		}
		else
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($value,$tIsDisplay)==0)
				{
					$isDisplayFlag=1;
					break;
				}
				else
				{
					$isDisplayFlag=2;
				}
			}
		}
		
		$enumMeasurementUnitArray = array();
		$measurementUnitEnum = new measurementUnitEnum();
		$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
		if($tMeasUnit!="")
		{
			foreach ($enumMeasurementUnitArray as $key => $value)
			{
				if(strcmp($value,$tMeasUnit)==0)
				{
					$measurementUnitFlag=1;
					break;
				}
				else
				{
					$measurementUnitFlag=2;
				}
			}
		}
		if($isDisplayFlag==2 || $measurementUnitFlag==2)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['product_name'] = $tProductName;
			$data['measurement_unit'] = $tMeasUnit;
			$data['color'] = $tColor;
			$data['size'] = $tSize;
			$data['is_display'] = $tIsDisplay;
			$data['purchase_price'] = $tPurchasePrice;
			$data['wholesale_margin'] = $tWholeSaleMargin;
			$data['wholesale_margin_flat'] = $tWholeSaleMarginFlat;
			$data['vat'] = $tVat;
			$data['mrp'] = $tMrp;
			$data['margin'] = $tMargin;
			$data['margin_flat'] = $tMarginFlat;
			$data['product_description'] = $tProductDescription;
			$data['additional_tax'] = $tAdditionalTax;
			$data['minimum_stock_level'] = $tMinimumStockLevel;
			$data['semi_wholesale_margin'] = $tSemiWholeSaleMargin;
			$data['company_id'] = $tCompanyId;
			$data['product_category_id'] = $tProductCatId;
			$data['product_group_id'] = $tProductGrpId;
			$data['branch_id'] = $tBranchId;
			return $data;
		}
	}
	
	/**
     * @param Request $request
     * @return array
     */
    public function trimInsertBatchData(Request $request)
    {
		$inputRequestData = $request->input();
		$isDisplayFlag=0;
		$measurementUnitFlag=0;
	
		for($arrayData=0;$arrayData<count($inputRequestData);$arrayData++)
		{
			//trim an input
			$tProductName = trim($inputRequestData[$arrayData]['productName']);
			$tMeasUnit = trim($inputRequestData[$arrayData]['measurementUnit']);
			$tColor = trim($inputRequestData[$arrayData]['color']);
			$tSize = trim($inputRequestData[$arrayData]['size']);
			$tIsDisplay = trim($inputRequestData[$arrayData]['isDisplay']);
			$tPurchasePrice = trim($inputRequestData[$arrayData]['purchasePrice']);
			$tWholeSaleMargin = trim($inputRequestData[$arrayData]['wholesaleMargin']);
			$tWholeSaleMarginFlat = trim($inputRequestData[$arrayData]['wholesaleMarginFlat']);
			$tSemiWholeSaleMargin = trim($inputRequestData[$arrayData]['semiWholesaleMargin']);
			$tVat = trim($inputRequestData[$arrayData]['vat']);
			$tMrp = trim($inputRequestData[$arrayData]['mrp']);
			$tMargin = trim($inputRequestData[$arrayData]['margin']);
			$tMarginFlat = trim($inputRequestData[$arrayData]['marginFlat']);
			$tProductDescription = trim($inputRequestData[$arrayData]['productDescription']);
			$tAdditionalTax = trim($inputRequestData[$arrayData]['additionalTax']);
			$tMinimumStockLevel = trim($inputRequestData[$arrayData]['minimumStockLevel']);
			$tCompanyId = trim($inputRequestData[$arrayData]['companyId']);
			$tProductCatId = trim($inputRequestData[$arrayData]['productCategoryId']);
			$tProductGrpId = trim($inputRequestData[$arrayData]['productGroupId']);
			$tBranchId = trim($inputRequestData[$arrayData]['branchId']);
			
			$enumIsDispArray = array();
			$isDispEnum = new IsDisplayEnum();
			$enumIsDispArray = $isDispEnum->enumArrays();
			if($tIsDisplay=="")
			{
				$tIsDisplay=$enumIsDispArray['display'];
			}
			else
			{
				foreach ($enumIsDispArray as $key => $value)
				{
					if(strcmp($value,$tIsDisplay)==0)
					{
						$isDisplayFlag=1;
						break;
					}
					else
					{
						$isDisplayFlag=2;
					}
				}
			}
			
			$enumMeasurementUnitArray = array();
			$measurementUnitEnum = new measurementUnitEnum();
			$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
			if($tMeasUnit!="")
			{
				foreach ($enumMeasurementUnitArray as $key => $value)
				{
					if(strcmp($value,$tMeasUnit)==0)
					{
						$measurementUnitFlag=1;
						break;
					}
					else
					{
						$measurementUnitFlag=2;
					}
				}
			}
			if($isDisplayFlag==2 || $measurementUnitFlag==2)
			{
				return "1";
			}
			else
			{
				//make an array
				$data[$arrayData] = array();
				$data[$arrayData]['product_name'] = $tProductName;
				$data[$arrayData]['measurement_unit'] = $tMeasUnit;
				$data[$arrayData]['color'] = $tColor;
				$data[$arrayData]['size'] = $tSize;
				$data[$arrayData]['is_display'] = $tIsDisplay;
				$data[$arrayData]['purchase_price'] = $tPurchasePrice;
				$data[$arrayData]['wholesale_margin'] = $tWholeSaleMargin;
				$data[$arrayData]['wholesale_margin_flat'] = $tWholeSaleMarginFlat;
				$data[$arrayData]['vat'] = $tVat;
				$data[$arrayData]['mrp'] = $tMrp;
				$data[$arrayData]['margin'] = $tMargin;
				$data[$arrayData]['margin_flat'] = $tMarginFlat;
				$data[$arrayData]['product_description'] = $tProductDescription;
				$data[$arrayData]['additional_tax'] = $tAdditionalTax;
				$data[$arrayData]['minimum_stock_level'] = $tMinimumStockLevel;
				$data[$arrayData]['semi_wholesale_margin'] = $tSemiWholeSaleMargin;
				$data[$arrayData]['company_id'] = $tCompanyId;
				$data[$arrayData]['product_category_id'] = $tProductCatId;
				$data[$arrayData]['product_group_id'] = $tProductGrpId;
				$data[$arrayData]['branch_id'] = $tBranchId;
			}
		}
		return $data;
	}
	
	/**
     * @param 
     * @return array
     */
    public function trimInsertInOutwardData(Request $request,$inOutWard)
    {
		$discountTypeFlag=0;
		$requestArray = array();
		$exceptionArray = array();
		$numberOfArray = count($request->input()['inventory']);
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//data get from body and trim an input
		$companyId = trim($request->input()['companyId']); 
		$transactionDate = trim($request->input()['transactionDate']); 
		$tax = trim($request->input()['tax']); 
		if(array_key_exists($constantArray['invoiceNumber'],$request->input()))
		{
			$invoiceNumber = trim($request->input()['invoiceNumber']);
			$billNumber="";
		}
		else
		{
			$billNumber = trim($request->input()['billNumber']); 
			$invoiceNumber="";
		}
		
		//transaction date conversion
		$splitedDate = explode("-",$transactionDate);
		$transformTransactionDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
		// $transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $transactionDate)->format('Y-m-d');
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$enumDiscountTypeArray = array();
		$discountTypeEnum = new DiscountTypeEnum();
		$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
		
		for($arrayData=0;$arrayData<$numberOfArray;$arrayData++)
		{
			$tempArray[$arrayData] = array();
			$tempArray[$arrayData][0] = trim($request->input()['inventory'][$arrayData]['productId']);
			$tempArray[$arrayData][1] = trim($request->input()['inventory'][$arrayData]['discount']);
			$tempArray[$arrayData][2] = trim($request->input()['inventory'][$arrayData]['discountType']);
			$tempArray[$arrayData][3] = trim($request->input()['inventory'][$arrayData]['price']);
			$tempArray[$arrayData][4] = trim($request->input()['inventory'][$arrayData]['qty']);
			
			if($tempArray[$arrayData][1]!=0 && $tempArray[$arrayData][1]!="")
			{
				if(strcmp($tempArray[$arrayData][2],$constantArray['percentage'])==0)
				{
					$tempArray[$arrayData][5]=($tempArray[$arrayData][1]/100)*$tempArray[$arrayData][3];
				}
				else
				{
					$tempArray[$arrayData][5]=$tempArray[$arrayData][1];
				}
			}
			else
		    {
				$tempArray[$arrayData][5] = 0;
				$tempArray[$arrayData][1] = 0;
		    }
			foreach ($enumDiscountTypeArray as $key => $value)
			{
				if(strcmp($value,$tempArray[$arrayData][2])==0)
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
				$discountTypeFlag=0;
				break;
			}
		}
		
		if($discountTypeFlag==0)
		{
			return "1";
		}
		else
		{
			// make an array
			$simpleArray = array();
			$simpleArray['transactionDate'] = $transformTransactionDate;
			$simpleArray['companyId'] = $companyId;
			$simpleArray['transactionType'] = $inOutWard;
			$simpleArray['invoiceNumber'] = $invoiceNumber;
			$simpleArray['billNumber'] = $billNumber;
			$simpleArray['tax'] = $tax;
			
			$trimArray = array();
			for($data=0;$data<$numberOfArray;$data++)
			{
				$trimArray[$data]= array(
					'productId' => $tempArray[$data][0],
					'discount' => $tempArray[$data][1],
					'discountType' => $tempArray[$data][2],
					'price' => $tempArray[$data][3],
					'qty' => $tempArray[$data][4],
					'discountValue' => $tempArray[$data][5]
				);
			}
			array_push($simpleArray,$trimArray);
			return $simpleArray;
		}
	}
	
	/**
     * @param key and value
     * @return array
     */
	public function trimUpdateData($arrayData)
	{
		$productEnumArray = array();
		$isDisplayFlag=0;
		$measurementUnitFlag=0;
		$tProductArray = array();
		$productValue;
		// $keyValue = func_get_arg(0);
		$convertedValue="";
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$index=0;
		foreach($arrayData as $keyValue => $value)
		{
			$convertedValue = "";
			for($asciiChar=0;$asciiChar<strlen($keyValue);$asciiChar++)
			{
				if(ord($keyValue[$asciiChar])<=90 && ord($keyValue[$asciiChar])>=65) 
				{
					$convertedValue1 = "_".chr(ord($keyValue[$asciiChar])+32);
					$convertedValue=$convertedValue.$convertedValue1;
				}
				else
				{
					$convertedValue=$convertedValue.$keyValue[$asciiChar];
				}
			}
			$productValue[$index] = $value;
			$tProductArray[$index]= array($convertedValue=> trim($productValue[$index]));
			$productEnumArray = array_keys($tProductArray[$index])[0];
			
			//check enum data
			$enumMeasurementUnitArray = array();
			$measurementUnitEnum = new measurementUnitEnum();
			$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
			
			if(strcmp($constantArray['measurementUnit'],$productEnumArray)==0)
			{
				foreach ($enumMeasurementUnitArray as $innerKey => $innerValue)
				{
					if(strcmp($tProductArray[$index]['measurement_unit'],$innerValue)==0)
					{
						$measurementUnitFlag=1;
						break;
					}
					else
					{
						$measurementUnitFlag=2;
					}
				}
			}
			// echo $measurementUnitFlag;
			$enumIsDispArray = array();
			$isDispEnum = new IsDisplayEnum();
			$enumIsDispArray = $isDispEnum->enumArrays();
			
			if(strcmp($constantArray['isDisplay'],$productEnumArray)==0)
			{
				foreach ($enumIsDispArray as $innerKey => $innerValue)
				{
					if(strcmp($tProductArray[$index]['is_display'],$innerValue)==0)
					{
						$isDisplayFlag=1;
						break;
					}
					else
					{
						$isDisplayFlag=2;
					}
				}
			}
			if($isDisplayFlag==2 || $measurementUnitFlag==2)
			{
				return "1";
			}
			$index++;
		}
		return $tProductArray;
	}
	
	/**
	 * trim request data for update
     * @param object
     * @return array
     */
	public function trimUpdateProductData($productArray,$inOutWard)
	{
		$discountTypeFlag=0;
		$requestArray = array();
		$exceptionArray = array();
		$tProductArray = array();
		$convertedValue="";
		$arraySample = array();
		$tempArrayFlag=0;
		$productArrayFlag=0;
		$tempFlag=0;
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		for($requestArray=0;$requestArray<count($productArray);$requestArray++)
		{
			//check if array is exists
			if(strcmp(array_keys($productArray)[$requestArray],$constantArray['inventory'])==0)
			{
				//number of array elements
				for($arrayElement=0;$arrayElement<count($productArray['inventory']);$arrayElement++)
				{
					$tempArrayFlag=1;
					$tempArray[$arrayElement] = array();
					$tempArray[$arrayElement]['product_id'] = trim($productArray['inventory'][$arrayElement]['productId']);
					$tempArray[$arrayElement]['discount'] = trim($productArray['inventory'][$arrayElement]['discount']);
					$tempArray[$arrayElement]['discount_type'] = trim($productArray['inventory'][$arrayElement]['discountType']);
					$tempArray[$arrayElement]['price'] = trim($productArray['inventory'][$arrayElement]['price']);
					$tempArray[$arrayElement]['qty'] = trim($productArray['inventory'][$arrayElement]['qty']);
					
					if($tempArray[$arrayElement]['discount']!=0 && $tempArray[$arrayElement]['discount']!="")
					{
						if(strcmp($tempArray[$arrayElement]['discount_type'],$constantArray['percentage'])==0)
						{
							$tempArray[$arrayElement]['discount_value']=($tempArray[$arrayElement]['discount']/100)* $tempArray[$arrayElement]['price'];
						}
						else
						{
							$tempArray[$arrayElement]['discount_value'] = $tempArray[$arrayElement]['discount'];
						}
					}
					else
					{
						$tempArray[$arrayElement]['discount_value']=0;
					}
					//check enum type[amount-type]
					$enumDiscountTypeArray = array();
					$discountTypeEnum = new DiscountTypeEnum();
					$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
					foreach ($enumDiscountTypeArray as $key => $value)
					{
						if(strcmp($value,$tempArray[$arrayElement]['discount_type'])==0)
						{
							$discountTypeFlag=1;
							break;
						}
						else
						{
							$discountTypeFlag=0;
						}
					}
				}
				if($discountTypeFlag==0)
				{
					return "1";
				}
			}
			else
			{
				$key = array_keys($productArray)[$requestArray];
				$value = $productArray[$key];
				$productArrayFlag=1;
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
				if(strcmp($convertedValue,$constantArray['transactionDate'])==0)
				{
					$transformTransactionDate=trim($value);
					$splitedDate = explode("-",$transformTransactionDate);
					$tProductArray[$convertedValue] = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
					// $transformTransactionDate = Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
					// $tProductArray[$convertedValue]=trim($transformTransactionDate);
					$convertedValue="";
				}
				else
				{
					$tProductArray[$convertedValue]=trim($value);
					$convertedValue="";
				}
				$tempFlag=1;
			}
			if($tempFlag==1)
			{
				if($requestArray==count($productArray)-1)
				{
					$tProductArray['transaction_type']=$inOutWard;
					$tProductArray['flag']="1";
				}
			}
		}
		if($productArrayFlag==1 && $tempArrayFlag==1)
		{
			array_push($tProductArray,$tempArray);
			return $tProductArray;
		}
		else if($productArrayFlag==1)
		{
			return $tProductArray;
		}
		else
		{
			return $tempArray;
		}
	}
}