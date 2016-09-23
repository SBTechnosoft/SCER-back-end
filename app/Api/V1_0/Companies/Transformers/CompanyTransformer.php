<?php
namespace ERP\Api\V1_0\Companies\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//data get from body
		$companyName = $request->input('company_name'); 
		$companyDispName = $request->input('company_display_name'); 
		$address1 = $request->input('address1'); 
		$address2 = $request->input('address2'); 
		$pincode = $request->input('pincode'); 
		$pan = $request->input('pan'); 
		$tin = $request->input('tin'); 
		$vatNo = $request->input('vat_no'); 
		$serviceTaxNo = $request->input('service_tax_no'); 
		$basicCurrencySymbol = $request->input('basic_currency_symbol'); 			
		$formalName = $request->input('formal_name'); 			
		$noOfDecimalPoints = $request->input('no_of_decimal_points'); 			
		$currencySymbol = $request->input('currency_symbol'); 			
		$isDisplay = $request->input('is_display'); 			
		$isDefault = $request->input('is_default'); 			
		$stateAbb = $request->input('state_abb'); 			
		$cityId = $request->input('city_id');  
		
		//trim an input
		$tCompanyName = trim($companyName);
		$tCompanyDispName = trim($companyDispName);
		$tAddress1 = trim($address1);
		$tAddress2 = trim($address2);
		$tPincode = trim($pincode);
		$tPan = trim($pan);
		$tTin = trim($tin);
		$tVatNo = trim($vatNo);
		$tServiceTaxNo = trim($serviceTaxNo);
		$tBasicCurrencySymbol = trim($basicCurrencySymbol);
		$tFormalName = trim($formalName);
		$tNoOfDecimalPoints = trim($noOfDecimalPoints);
		$tCurrencySymbol = trim($currencySymbol);
		$tIsDisplay = trim($isDisplay);
		$tIsDefault = trim($isDefault);
		$tStateAbb = trim($stateAbb);
		$tCityId = trim($cityId);
		
		//make an array
		$data = array();
		$data['company_name'] = $tCompanyName;
		$data['company_display_name'] = $tCompanyDispName;
		$data['address1'] = $tAddress1;
		$data['address2'] = $tAddress2;
		$data['pincode'] = $tPincode;
		$data['pan'] = $tPan;
		$data['tin'] = $tTin;
		$data['vat_no'] = $tVatNo;
		$data['service_tax_no'] = $tServiceTaxNo;
		$data['basic_currency_symbol'] = $tBasicCurrencySymbol;
		$data['formal_name'] = $tFormalName;
		$data['no_of_decimal_points'] = $tNoOfDecimalPoints;
		$data['currency_symbol'] = $tCurrencySymbol;
		$data['is_display'] = $tIsDisplay;
		$data['is_default'] = $tIsDefault;
		$data['state_abb'] = $tStateAbb;
		$data['city_id'] = $tCityId;
		return $data;
	}
	public function trimUpdateData()
	{
		$tCompanyArray = array();
		$companyValue;
		$keyValue = func_get_arg(0);
		$companyValue = func_get_arg(1);
		for($data=0;$data<count($companyValue);$data++)
		{
			$tCompanyArray[$data]= array($keyValue=> trim($companyValue));
			
		}
		return $tCompanyArray;
	}
}