<?php
namespace ERP\Api\V1_0\Accounting\Ledgers\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//data get from body
		$ledgerName = $request->input('ledgerName'); 
		$alias = $request->input('alias'); 
		$inventoryAffected = $request->input('inventoryAffected'); 
		$address1 = $request->input('address1'); 
		$address2 = $request->input('address2'); 
		$pan = $request->input('pan'); 
		$tin = $request->input('tin'); 
		$gstNo = $request->input('gst'); 		
		$stateAbb = $request->input('stateAbb'); 			
		$cityId = $request->input('cityId'); 			
		$ledgerGrpId = $request->input('ledgerGroupId');  
		$companyId = $request->input('companyId');  
		
		//trim an input
		$tLedgerName = trim($ledgerName);
		$tAlias = trim($alias);
		$tInventoryAffected = trim($inventoryAffected);
		$tAddress1 = trim($address1);
		$tAddress2 = trim($address2);
		$tPan = trim($pan);
		$tTin = trim($tin);
		$tGstNo = trim($gstNo);
		$tStateAbb = trim($stateAbb);
		$tCityId = trim($cityId);
		$tLedgerGrpId = trim($ledgerGrpId);
		$tcompanyId = trim($companyId);
		
		//make an array
		$data = array();
		$data['ledger_name'] = $tLedgerName;
		$data['alias'] = $tAlias;
		$data['inventory_affected'] = $tInventoryAffected;
		$data['address1'] = $tAddress1;
		$data['address2'] = $tAddress2;
		$data['pan'] = $tPan;
		$data['tin'] = $tTin;
		$data['gst'] = $tGstNo;
		$data['state_abb'] = $tStateAbb;
		$data['city_id'] = $tCityId;
		$data['ledger_group_id'] = $tLedgerGrpId;
		$data['company_id'] = $tcompanyId;
		return $data;
	}
	public function trimUpdateData()
	{
		$tLedgerArray = array();
		$LedgerValue;
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
		$LedgerValue = func_get_arg(1);
		for($data=0;$data<count($LedgerValue);$data++)
		{
			$tLedgerArray[$data]= array($convertedValue=> trim($LedgerValue));
			
		}
		return $tLedgerArray;
	}
}