<?php
namespace ERP\Api\V1_0\Products\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
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
		$productName = $request->input('productName'); 
		$measurementUnit = $request->input('measurementUnit'); 
		$isDisplay = $request->input('isDisplay'); 			
		$companyId = $request->input('companyId'); 			
		$productCatId = $request->input('productCategoryId'); 			
		$productGrpId = $request->input('productGroupId'); 			
		$branchId = $request->input('branchId'); 	 
		//trim an input
		$tProductName = trim($productName);
		$tMeasUnit = trim($measurementUnit);
		$tIsDisplay = trim($isDisplay);
		$tCompanyId = trim($companyId);
		$tProductCatId = trim($productCatId);
		$tProductGrpId = trim($productGrpId);
		$tBranchId = trim($branchId);
		//make an array
		$data = array();
		$data['product_name'] = $tProductName;
		$data['measurement_unit'] = $tMeasUnit;
		$data['is_display'] = $tIsDisplay;
		$data['company_id'] = $tCompanyId;
		$data['product_category_id'] = $tProductCatId;
		$data['product_group_id'] = $tProductGrpId;
		$data['branch_id'] = $tBranchId;
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
		$numberOfArray = count($request->input()[0]['inventory']);
		
		//data get from body and trim an input
		$companyId = trim($request->input()[0]['companyId']); 
		$transactionDate = trim($request->input()[0]['transactionDate']); 
		
		
		//transaction date conversion
		$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $transactionDate)->format('Y-m-d');
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$enumDiscountTypeArray = array();
		$discountTypeEnum = new DiscountTypeEnum();
		$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
		
		for($arrayData=0;$arrayData<$numberOfArray;$arrayData++)
		{
			$tempArray[$arrayData] = array();
			$tempArray[$arrayData][0] = trim($request->input()[0]['inventory'][$arrayData]['productId']);
			$tempArray[$arrayData][1] = trim($request->input()[0]['inventory'][$arrayData]['discount']);
			$tempArray[$arrayData][2] = trim($request->input()[0]['inventory'][$arrayData]['discountType']);
			$tempArray[$arrayData][3] = trim($request->input()[0]['inventory'][$arrayData]['price']);
			$tempArray[$arrayData][4] = trim($request->input()[0]['inventory'][$arrayData]['qty']);
			
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
			$simpleArray['transactionDate'] = $transformEntryDate;
			$simpleArray['companyId'] = $companyId;
			$simpleArray['transactionType'] = $inOutWard;
			
			$trimArray = array();
			for($data=0;$data<$numberOfArray;$data++)
			{
				$trimArray[$data]= array(
					'productId' => $tempArray[$data][0],
					'discount' => $tempArray[$data][1],
					'discountType' => $tempArray[$data][2],
					'price' => $tempArray[$data][3],
					'qty' => $tempArray[$data][4]
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
	public function trimUpdateData()
	{
		$tProductArray = array();
		$productValue;
		$keyValue = func_get_arg(0);
		$convertedValue="";
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
		$productValue = func_get_arg(1);
		for($data=0;$data<count($productValue);$data++)
		{
			$tProductArray[$data]= array($convertedValue=> trim($productValue));
		}
		return $tProductArray;
	}
}