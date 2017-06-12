<?php
namespace ERP\Api\V1_0\Accounting\Quotations\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use  ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
use ERP\Exceptions\ExceptionMessage;
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
		$tGrandTotal = trim($quotationArrayData['grandTotal']);

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
}