<?php
namespace ERP\Api\V1_0\Crm\JobForm\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Core\Crm\JobForm\Entities\ServiceTypeEnum;
use ERP\Core\Accounting\Bills\Entities\PaymentModeEnum;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JobFormTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$serviceTypeFlag=0;
		$paymentModeFlag=0;
		
		// data get from body and trim an input
		$tClientName = trim($request->input('clientName'));
		$tAddress = trim($request->input('address'));
		$tContactNo = trim($request->input('contactNo'));
		$tEmailId = trim($request->input('emailId'));
		$tJobCardNo = trim($request->input('jobCardNo'));
		$tProductInformation = trim($request->input('productInformation'));
		$tQty = trim($request->input('qty'));
		$tTax = trim($request->input('tax'));
		$tDiscountType = trim($request->input('discountType'));
		$tDiscount = trim($request->input('discount'));
		$tAdditionalTax = trim($request->input('additionalTax'));
		$tPrice = trim($request->input('price'));
		$tLabourCharge = trim($request->input('labourCharge'));
		$tServiceType = trim($request->input('serviceType'));
		$tEntryDate = trim($request->input('entryDate'));
		$tDeliveryDate = trim($request->input('deliveryDate'));
		$tAdvance = trim($request->input('advance'));
		$tTotal = trim($request->input('total'));
		$tPaymentMode = trim($request->input('paymentMode'));
		$tStateAbb = trim($request->input('stateAbb'));
		$tCityId = trim($request->input('cityId'));
		$tProductId = trim($request->input('productId'));
		$tCompanyId = trim($request->input('companyId'));
		
		$enumServiceTypeArray = array();
		$serviceTypeEnum = new ServiceTypeEnum();
		$enumServiceTypeArray = $serviceTypeEnum->enumArrays();
		
		foreach ($enumServiceTypeArray as $key => $value)
		{
			if(strcmp($value,$tServiceType)==0)
			{
				$serviceTypeFlag=1;
				break;
			}
			else
			{
				$serviceTypeFlag=2;
			}
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
			else
			{
				$paymentModeFlag=2;
			}
		}
		
		if($serviceTypeFlag==2 || $paymentModeFlag==2)
		{
			return "1";
		}
		else
		{
			// make an array
			$data = array();
			$data['client_name'] = $tClientName;
			$data['address'] = $tAddress;
			$data['contact_no'] = $tContactNo;
			$data['email_id'] = $tEmailId;
			$data['job_card_no'] = $tJobCardNo;
			$data['product_information'] = $tProductInformation;
			$data['qty'] = $tQty;
			$data['tax'] = $tTax;
			$data['discount_type'] = $tDiscountType;
			$data['discount'] = $tDiscount;
			$data['additional_tax'] = $tAdditionalTax;
			$data['price'] = $tPrice;
			$data['labour_charge'] = $tLabourCharge;
			$data['service_type'] = $tServiceType;
			$data['entry_date'] = $tEntryDate;
			$data['delivery_date'] = $tDeliveryDate;
			$data['advance'] = $tAdvance;
			$data['total'] = $tTotal;
			$data['payment_mode'] = $tPaymentMode;
			$data['state_abb'] = $tStateAbb;
			$data['city_id'] = $tCityId;
			$data['product_id'] = $tProductId;
			$data['company_id'] = $tCompanyId;
			return $data;
		}
	}
	
	public function trimUpdateData()
	{
		$isDisplayFlag=0;
		$isDefaultFlag=0;
		$tCompanyArray = array();
		$companyEnumArray = array();
		$companyValue;
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
		$companyValue = func_get_arg(1);
		for($data=0;$data<count($companyValue);$data++)
		{
			$tCompanyArray[$data]= array($convertedValue=> trim($companyValue));
			$companyEnumArray = array_keys($tCompanyArray[$data])[0];
		}
		
		$enumIsDefArray = array();
		$isDefEnum = new IsDefaultEnum();
		$enumIsDefArray = $isDefEnum->enumArrays();
		if(strcmp($companyEnumArray,'is_default')==0)
		{
			foreach ($enumIsDefArray as $key => $value)
			{
				if(strcmp($tCompanyArray[0]['is_default'],$value)==0)
				{
					$isDefaultFlag=1;
					break;
				}
				else
				{
					$isDefaultFlag=2;
				}
			}
		}
		
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if(strcmp($companyEnumArray,'is_display')==0)
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($tCompanyArray[0]['is_display'],$value)==0)
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
		if($isDisplayFlag==2 || $isDefaultFlag==2)
		{
			return "1";
		}
		else
		{
			return $tCompanyArray;
		}
	}
}