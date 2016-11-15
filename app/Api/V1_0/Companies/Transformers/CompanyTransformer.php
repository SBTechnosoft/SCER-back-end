<?php
namespace ERP\Api\V1_0\Companies\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Entities\EnumClasses\IsDefaultEnum;
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
		$isDisplayFlag=0;
		$isDefaultFlag=0;
		//data get from body
		$companyName = $request->input('companyName'); 
		$companyDispName = $request->input('companyDisplayName'); 
		$address1 = $request->input('address1'); 
		$address2 = $request->input('address2'); 
		$pincode = $request->input('pincode'); 
		$pan = $request->input('pan'); 
		$tin = $request->input('tin'); 
		$vatNo = $request->input('vatNo'); 
		$serviceTaxNo = $request->input('serviceTaxNo'); 
		$basicCurrencySymbol = $request->input('basicCurrencySymbol'); 			
		$formalName = $request->input('formalName'); 			
		$noOfDecimalPoints = $request->input('noOfDecimalPoints'); 			
		$currencySymbol = $request->input('currencySymbol'); 			
		$isDisplay = $request->input('isDisplay'); 			
		$isDefault = $request->input('isDefault'); 			
		$stateAbb = $request->input('stateAbb'); 			
		$cityId = $request->input('cityId');  
		
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
		
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		foreach ($enumIsDispArray as $key => $value)
		{
			if(strcmp($value,$tIsDisplay)==0)
			{
				$isDisplayFlag=1;
				break;
			}
		}
		
		$enumIsDefArray = array();
		$isDefEnum = new IsDefaultEnum();
		$enumIsDefArray = $isDefEnum->enumArrays();
		foreach ($enumIsDefArray as $key => $value)
		{
			if(strcmp($value,$tIsDefault)==0)
			{
				$isDefaultFlag=1;
				break;
			}
		}
		if($isDisplayFlag==0 || $isDefaultFlag==0)
		{
			return "1";
		}
		else
		{
			// make an array
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