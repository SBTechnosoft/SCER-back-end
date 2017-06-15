<?php
namespace ERP\Api\V1_0\Accounting\Quotations\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use  ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationTransformer
{
    /**
     * @param Request Object
     * @return array/error message
     */
    public function trimInsertData(Request $request)
    {
		$isDisplayFlag=0;
		$quotationArrayData=array();
		//data get from body
		$quotationArrayData = $request->input(); 
		
		//trim an input
		$tCompanyId = trim($quotationArrayData['companyId']);
		$tEntryDate = trim($quotationArrayData['entryDate']);
		if(!array_key_exists('contactNo',$request->input()))
		{
			$tContactNo = "";
		}
		else
		{
			$tContactNo = trim($quotationArrayData['contactNo']);
		}
		if(!array_key_exists('emailId',$request->input()))
		{
			$tEmailId = "";
		}
		else
		{
			$tEmailId = trim($quotationArrayData['emailId']);
		}
		if(!array_key_exists('companyName',$request->input()))
		{
			$tCompanyName = "";
		}
		else
		{
			$tCompanyName = trim($quotationArrayData['companyName']);
		}
		$tClientName = trim($quotationArrayData['clientName']);
		$tQuotationNumber = trim($quotationArrayData['quotationNumber']);
		if(!array_key_exists('address1',$request->input()))
		{
			$tAddress1 = "";
		}
		else
		{
			$tAddress1 = trim($quotationArrayData['address1']);
		}
		$tStateAbb = trim($quotationArrayData['stateAbb']);
		$tCityId = trim($quotationArrayData['cityId']);
		$tTotal = trim($quotationArrayData['total']);
		if(!array_key_exists('extraCharge',$request->input()))
		{
			$tExtraCharge = 0;
		}
		else
		{
			$tExtraCharge = trim($quotationArrayData['extraCharge']);
		}
		$tTax = trim($quotationArrayData['tax']);
		
		if(array_key_exists("grandTotal",$quotationArrayData))
		{
			$tGrandTotal = trim($quotationArrayData['grandTotal']);
		}
		else
		{
			$tGrandTotal =0;
		}
	
		if(array_key_exists("remark",$quotationArrayData))
		{
			$tRemark = trim($quotationArrayData['remark']);
		}
		else
		{
			$tRemark ="";
		}
		if(array_key_exists('isDisplay',$request->input()))
		{
			$tIsDisplay = trim($quotationArrayData['isDisplay']);
		}
		else
		{
			$tIsDisplay="yes";
		}
		$isDisplayEnum = new IsDisplayEnum();
		$isDisplayArray = $isDisplayEnum->enumArrays();
		if($tIsDisplay=="")
		{
			$tIsDisplay=$isDisplayArray['display'];
		}
		else
		{
			//check is-display enum type
			foreach ($isDisplayArray as $key => $value)
			{
				if(strcmp($value,$tIsDisplay)==0)
				{
					$isDisplayFlag=1;
					break;
				}
			}
			if($isDisplayFlag==0)
			{
				return "1";
			}
		}
		$discountFlag=0;
		$discountTypeEnum = new DiscountTypeEnum();
		for($trimInventory=0;$trimInventory<count($quotationArrayData['inventory']);$trimInventory++)
		{
			$discountTypeArray = array();
			$discountTypeArray = $discountTypeEnum->enumArrays();
			$discountTypeFlag=0;
			//check discount-type enum
			foreach ($discountTypeArray as $key => $value)
			{
				if(strcmp($value,$quotationArrayData['inventory'][$trimInventory]['discountType'])==0)
				{
					$discountTypeFlag=1;
					break;
				}
			}
			if($discountTypeFlag==0)
			{
				$discountFlag=2;
				break;
			}
			$tInventoryArray[$trimInventory] = array();
			$tInventoryArray[$trimInventory][0] = trim($quotationArrayData['inventory'][$trimInventory]['productId']);
			$tInventoryArray[$trimInventory][1] = trim($quotationArrayData['inventory'][$trimInventory]['discount']);
			$tInventoryArray[$trimInventory][2] = trim($quotationArrayData['inventory'][$trimInventory]['discountType']);
			$tInventoryArray[$trimInventory][3] = trim($quotationArrayData['inventory'][$trimInventory]['price']);
			$tInventoryArray[$trimInventory][4] = trim($quotationArrayData['inventory'][$trimInventory]['qty']);
		}
		
		if($discountFlag==2)
		{
			return "1";
		}
		else
		{
			// make an array
			$data = array();
			$data['company_id'] = $tCompanyId;
			$data['entry_date'] = $tEntryDate;
			$data['contact_no'] = $tContactNo;
			$data['email_id'] = $tEmailId;
			$data['is_display'] = $tIsDisplay;
			$data['company_name'] = $tCompanyName;
			$data['client_name'] = $tClientName;
			$data['quotation_number'] = $tQuotationNumber;
			$data['address1'] = $tAddress1;
			$data['state_abb'] = $tStateAbb;
			$data['city_id'] = $tCityId;
			$data['total'] = $tTotal;
			$data['extra_charge'] = $tExtraCharge;
			$data['tax'] = $tTax;
			$data['grand_total'] = $tGrandTotal;
			$data['remark'] = $tRemark;
			$trimArray=array();
			for($inventoryArray=0;$inventoryArray<count($quotationArrayData['inventory']);$inventoryArray++)
			{
				$trimArray[$inventoryArray]=array(
					'productId' => $tInventoryArray[$inventoryArray][0],
					'discount' => $tInventoryArray[$inventoryArray][1],
					'discountType' => $tInventoryArray[$inventoryArray][2],
					'price' => $tInventoryArray[$inventoryArray][3],
					'qty' => $tInventoryArray[$inventoryArray][4]
				);
			}
			array_push($data,$trimArray);
			return $data;
		}
	}
	
	/**
     * trim quotation update data and check enum data type
	 * @param request data 
     * @return array/error message
     */
	public function trimQuotationUpdateData(Request $request)
	{
		$convertedValue="";
		$dataFlag=0;
		$discountTypeFlag=0;
		$isDisplayFlag = 0;
		$tempArrayFlag=0;
		$tempArray = array();
		$tQuotationArray = array();
		$quotationArrayData = $request->input();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();		
		for($inputArrayData=0;$inputArrayData<count($quotationArrayData);$inputArrayData++)
		{
			if(strcmp(array_keys($quotationArrayData)[$inputArrayData],'inventory')==0)
			{
				$enumDiscountTypeArray = array();
				$discountTypeEnum = new DiscountTypeEnum();
				$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
				for($inventoryArray=0;$inventoryArray<count($quotationArrayData['inventory']);$inventoryArray++)
				{
					$tempArrayFlag=1;
					$tempArray[$inventoryArray] = array();
					$tempArray[$inventoryArray]['productId'] = trim($quotationArrayData['inventory'][$inventoryArray]['productId']);
					$tempArray[$inventoryArray]['discount'] = trim($quotationArrayData['inventory'][$inventoryArray]['discount']);
					$tempArray[$inventoryArray]['discountType'] = trim($quotationArrayData['inventory'][$inventoryArray]['discountType']);
					$tempArray[$inventoryArray]['price'] = trim($quotationArrayData['inventory'][$inventoryArray]['price']);
					$tempArray[$inventoryArray]['qty'] = trim($quotationArrayData['inventory'][$inventoryArray]['qty']);
					$tempArray[$inventoryArray]['color'] = trim($quotationArrayData['inventory'][$inventoryArray]['color']);
					$tempArray[$inventoryArray]['frameNo'] = trim($quotationArrayData['inventory'][$inventoryArray]['frameNo']);
					$tempArray[$inventoryArray]['size'] = trim($quotationArrayData['inventory'][$inventoryArray]['size']);
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
				$key = array_keys($quotationArrayData)[$inputArrayData];
				$value = $quotationArrayData[$key];
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
				if(strcmp('is_display',$convertedValue)==0)
				{
					$isDisplayEnum = new IsDisplayEnum();
					$isDisplayArray = $isDisplayEnum->enumArrays();
					$tQuotationArray[$convertedValue]=trim($value);
					foreach ($isDisplayArray as $key => $value)
					{
						if(strcmp($value,$tQuotationArray[$convertedValue])==0)
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
					if(strcmp($convertedValue,'entry_date')==0)
					{
						//entry date conversion
						$value = Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
					}
					$tQuotationArray[$convertedValue]=trim($value);
				}
				$convertedValue="";
			}
		}
		if($tempArrayFlag==1 && $dataFlag==1)
		{
			$tQuotationArray['inventory'] = $tempArray;
		}
		else if($tempArrayFlag==1)
		{
			$tQuotationArray['inventory'] = $tempArray;
		}
		return $tQuotationArray;
	}
}