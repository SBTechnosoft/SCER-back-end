<?php
namespace ERP\Api\V1_0\Accounting\Bills\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Accounting\Bills\Entities\PaymentModeEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$paymentModeFlag=0;
		$billArrayData=array();
		//data get from body
		$billArrayData = $request->input()[0]['billData'][0]; 
		
		//trim an input
		$tCompanyId = trim($billArrayData['companyId']);
		$tEntryDate = trim($billArrayData['entryDate']);
		$tContactNo = trim($billArrayData['contactNo']);
		$tEmailId = trim($billArrayData['emailId']);
		$tCompanyName = trim($billArrayData['companyName']);
		$tClientName = trim($billArrayData['clientName']);
		$tInvoiceNumber = trim($billArrayData['invoiceNumber']);
		$tAddress1 = trim($billArrayData['address1']);
		$tAddress2 = trim($billArrayData['address2']);
		$tStateAbb = trim($billArrayData['stateAbb']);
		$tCityId = trim($billArrayData['cityId']);
		$tTotal = trim($billArrayData['total']);
		$tTax = trim($billArrayData['tax']);
		$tGrandTotal = trim($billArrayData['grandTotal']);
		$tAdvance = trim($billArrayData['advance']);
		$tBalance = trim($billArrayData['balance']);
		$tPaymentMode = trim($billArrayData['paymentMode']);
		$tCheckNumber = trim($billArrayData['checkNumber']);
		$tRemark = trim($billArrayData['remark']);
		
		for($trimInventory=0;$trimInventory<count($billArrayData['inventory']);$trimInventory++)
		{
			$tInventoryArray[$trimInventory] = array();
			$tInventoryArray[$trimInventory][0] = trim($billArrayData['inventory'][$trimInventory]['productId']);
			$tInventoryArray[$trimInventory][1] = trim($billArrayData['inventory'][$trimInventory]['discount']);
			$tInventoryArray[$trimInventory][2] = trim($billArrayData['inventory'][$trimInventory]['discountType']);
			$tInventoryArray[$trimInventory][3] = trim($billArrayData['inventory'][$trimInventory]['price']);
			$tInventoryArray[$trimInventory][4] = trim($billArrayData['inventory'][$trimInventory]['qty']);
		}
		$paymentModeArray = array();
		$paymentModeEnum = new PaymentModeEnum();
		$paymentModeArray = $paymentModeEnum->enumArrays();
		foreach ($paymentModeArray as $key => $value)
		{
			if(strcmp($value,$tPaymentMode)==0)
			{
				$paymentModeFlag=1;
				break;
			}
		}
		if($paymentModeFlag==0)
		{
			return "1";
		}
		else
		{
			$tCompanyId = trim($billArrayData['companyId']);
			$tEntryDate = trim($billArrayData['entryDate']);
			$tContactNo = trim($billArrayData['contactNo']);
			$tEmailId = trim($billArrayData['emailId']);
			$tCompanyName = trim($billArrayData['companyName']);
			$tClientName = trim($billArrayData['clientName']);
			$tInvoiceNumber = trim($billArrayData['invoiceNumber']);
			$tAddress1 = trim($billArrayData['address1']);
			$tAddress2 = trim($billArrayData['address2']);
			$tStateAbb = trim($billArrayData['stateAbb']);
			$tCityId = trim($billArrayData['cityId']);
			$tTotal = trim($billArrayData['total']);
			$tTax = trim($billArrayData['tax']);
			$tGrandTotal = trim($billArrayData['grandTotal']);
			$tAdvance = trim($billArrayData['advance']);
			$tBalance = trim($billArrayData['balance']);
			$tPaymentMode = trim($billArrayData['paymentMode']);
			$tCheckNumber = trim($billArrayData['checkNumber']);
			$tRemark = trim($billArrayData['remark']);
			
			// make an array
			$data = array();
			$data['company_id'] = $tCompanyId;
			$data['entry_date'] = $tEntryDate;
			$data['contact_no'] = $tContactNo;
			$data['email_id'] = $tEmailId;
			$data['company_name'] = $tCompanyName;
			$data['client_name'] = $tClientName;
			$data['invocie_number'] = $tInvoiceNumber;
			$data['address1'] = $tAddress1;
			$data['address2'] = $tAddress2;
			$data['state_abb'] = $tStateAbb;
			$data['city_id'] = $tCityId;
			$data['total'] = $tTotal;
			$data['tax'] = $tTax;
			$data['grand_total'] = $tGrandTotal;
			$data['advance'] = $tAdvance;
			$data['balance'] = $tBalance;
			$data['payment_mode'] = $tPaymentMode;
			$data['check_number'] = $tCheckNumber;
			
			$trimArray=array();
			for($inventoryArray=0;$inventoryArray<count($billArrayData['inventory']);$inventoryArray++)
			{
				$trimArray[$inventoryArray]=array(
					'product_id' => $tInventoryArray[$inventoryArray][0],
					'discount' => $tInventoryArray[$inventoryArray][1],
					'discount_type' => $tInventoryArray[$inventoryArray][2],
					'price' => $tInventoryArray[$inventoryArray][3],
					'qty' => $tInventoryArray[$inventoryArray][4]
				);
			}
			array_push($data,$trimArray);
			return $data;
		}
	}
}