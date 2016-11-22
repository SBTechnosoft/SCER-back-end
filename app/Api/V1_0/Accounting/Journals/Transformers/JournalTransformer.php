<?php
namespace ERP\Api\V1_0\Accounting\Journals\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
use ERP\Core\Accounting\Journals\Entities\AmountTypeEnum;
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
		$amountTypeFlag=0;
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		$requestArray = array();
		$exceptionArray = array();
		$numberOfArray = count($request->input()[0]['data']);
		
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
			
			//check enum type[amount-type]
			$enumAmountTypeArray = array();
			$amountTypeEnum = new AmountTypeEnum();
			$enumAmountTypeArray = $amountTypeEnum->enumArrays();
			foreach ($enumAmountTypeArray as $key => $value)
			{
				if(strcmp($value,$tempArray[$arrayData][1])==0)
				{
					$amountTypeFlag=1;
					break;
				}
				else
				{
					$amountTypeFlag=0;
				}
			}
			if($amountTypeFlag==0)
			{
				return "1";
			}
			else
			{
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
		}
		
		// if($creditAmountArray==$debitAmountArray)
		// {
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
		// }
		// else
		// {
			// return $exceptionArray['equal'];
		// }
	}
	public function trimDateData(Request $request)
	{
		//get data from header
		$fromDate =$request->header('fromDate');
		$toDate =$request->header('toDate');
		
		//trim the data
		$tFromDate =  trim($fromDate);
		$tToDate = trim($toDate);
		
		//date format conversion
		$transformFromDate = Carbon\Carbon::createFromFormat('d-m-Y', $tFromDate)->format('Y-m-d');
		$transformToDate = Carbon\Carbon::createFromFormat('d-m-Y', $tToDate)->format('Y-m-d');
		
		//put date into an array
		$trimArray = array();
		$trimArray['fromDate'] = $transformFromDate;
		$trimArray['toDate'] = $transformToDate;
		return $trimArray;
	}
}