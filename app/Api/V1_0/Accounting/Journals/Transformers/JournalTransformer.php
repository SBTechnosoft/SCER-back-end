<?php
namespace ERP\Api\V1_0\Accounting\Journals\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		print_r($request->input()[0]);
		$requestArray = array();
		
		
		
		exit;
		//data get from body
		$amount = $request->input()[0]['']; 
		$amount_type = $request->input('amountType'); 
		$entry_date = $request->input('entryDate'); 
		$ledger_id = $request->input('ledgerId'); 
		
		//trim an input
		$tAmount = trim($amount);
		$tAmountType = trim($amount_type);
		$tEntryDate = trim($entry_date);
		$tLedgerId = trim($ledger_id);
		
		//make an array
		$data = array();
		$data['amount'] = $tAmount;
		$data['amount_name'] = $tAmountType;
		$data['entry_date'] = $tEntryDate;
		$data['ledger_id'] = $tLedgerId;
		return $data;
	}
}