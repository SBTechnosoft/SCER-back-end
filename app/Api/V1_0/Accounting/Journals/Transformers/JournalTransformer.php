<?php
namespace ERP\Api\V1_0\Accounting\Journals\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalTransformer extends LedgerModel
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		$requestArray = array();
		$exceptionArray = array();
		// $numberOfArray = count($request->input()[0]['data']);
		print_r($request->input()[0]['journal'][0]);
		exit;
		//data get from body and trim an input
		$jfId = trim($request->input()[0]['jfId']); 
		$entryDate = trim($request->input()[0]['entryDate']); 
		$companyId = trim($request->input()[0]['companyId']); 
		
		//entry date conversion
		$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $entryDate)->format('Y-m-d');
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		for($arrayData=0;$arrayData<$numberOfArray;$arrayData++)
		{
			$tempArray[$arrayData] = array();
			$tempArray[$arrayData][0] = trim($request->input()[0]['data'][$arrayData]['amount']);
			$tempArray[$arrayData][1] = trim($request->input()[0]['data'][$arrayData]['amountType']);
			$tempArray[$arrayData][2] = trim($request->input()[0]['data'][$arrayData]['ledgerId']);
			
			//check ledger exists
			$journalObject = new JournalTransformer();
			$ledgerIdResult = $journalObject->getData($tempArray[$arrayData][2]);
			if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
			{
				return $exceptionArray['404'];
			}
			else
			{
				//check credit-debit amount
				if(strcmp($tempArray[$arrayData][1],"credit")==0)
				{
					$creditAmountArray = $creditAmountArray+$tempArray[$arrayData][0];
				}
				else
				{
					$debitAmountArray = $debitAmountArray+$tempArray[$arrayData][0];
				}
			}
		}
		
		if($creditAmountArray==$debitAmountArray)
		{
			// make an array
			$simpleArray = array();
			$simpleArray['jfId'] = $jfId;
			$simpleArray['entryDate'] = $transformEntryDate;
			$simpleArray['companyId'] = $companyId;
			
			$trimArray = array();
			for($data=0;$data<$numberOfArray;$data++)
			{
				$trimArray[$data]= array(
					'amount' => $tempArray[$data][0],
					'amountType' => $tempArray[$data][1],
					'ledgerId' => $tempArray[$data][2]
				);
			}
			array_push($simpleArray,$trimArray);
			return $simpleArray;
		}
		else
		{
			return $exceptionArray['equal'];
		}
	}
}