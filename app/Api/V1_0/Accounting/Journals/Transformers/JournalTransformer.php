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
		$numberOfArray = count($request->input()['data']);
		
		//data get from body and trim an input
		$jfId = trim($request->input()['jfId']); 
		$entryDate = trim($request->input()['entryDate']); 
		$companyId = trim($request->input()['companyId']); 
		
		//entry date conversion
		$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $entryDate)->format('Y-m-d');
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		for($arrayData=0;$arrayData<$numberOfArray;$arrayData++)
		{
			$tempArray[$arrayData] = array();
			$tempArray[$arrayData][0] = trim($request->input()['data'][$arrayData]['amount']);
			$tempArray[$arrayData][1] = trim($request->input()['data'][$arrayData]['amountType']);
			$tempArray[$arrayData][2] = trim($request->input()['data'][$arrayData]['ledgerId']);
			
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
	
	//trim fromdate-todate data
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
	
	//trim update data
	public function trimUpdateData(Request $request)
	{
		$amountTypeFlag=0;
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		$requestArray = array();
		$exceptionArray = array();
		$tJournalArray = array();
		$convertedValue="";
		$arraySample = array();
		$tempArrayFlag=0;
		$journalArrayFlag=0;
		$tempFlag=0;
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($requestArray=0;$requestArray<count($request->input());$requestArray++)
		{
			//check if array is exists
			if(strcmp(array_keys($request->input())[$requestArray],"data")==0)
			{
				//number of array elements
				for($arrayElement=0;$arrayElement<count($request->input()['data']);$arrayElement++)
				{
					$tempArrayFlag=1;
					$tempArray[$arrayElement] = array();
					$tempArray[$arrayElement]['amount'] = trim($request->input()['data'][$arrayElement]['amount']);
					$tempArray[$arrayElement]['amount_type'] = trim($request->input()['data'][$arrayElement]['amountType']);
					$tempArray[$arrayElement]['ledger_id'] = trim($request->input()['data'][$arrayElement]['ledgerId']);
					
					//check enum type[amount-type]
					$enumAmountTypeArray = array();
					$amountTypeEnum = new AmountTypeEnum();
					$enumAmountTypeArray = $amountTypeEnum->enumArrays();
					foreach ($enumAmountTypeArray as $key => $value)
					{
						if(strcmp($value,$tempArray[$arrayElement]['amount_type'])==0)
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
						$ledgerIdResult = $journalObject->getData($tempArray[$arrayElement]['ledger_id']);
						if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
						{
							return $exceptionArray['404'];
						}
						else
						{
							//check credit-debit amount
							if(strcmp($tempArray[$arrayElement]['amount_type'],"credit")==0)
							{
								$creditAmountArray = $creditAmountArray+$tempArray[$arrayElement]['amount'];
							}
							else
							{
								
								$debitAmountArray = $debitAmountArray+$tempArray[$arrayElement]['amount'];
							}
						}
					}
				}
			}
			else
			{
				$key = array_keys($request->input())[$requestArray];
				$value = $request->input()[$key];
				$journalArrayFlag=1;
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
				if(strcmp($convertedValue,"entry_date")==0)
				{
					$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
					$tJournalArray[$convertedValue]=trim($transformEntryDate);
					$convertedValue="";
				}
				else
				{
					$tJournalArray[$convertedValue]=trim($value);
					$convertedValue="";
				}
				$tempFlag=1;
			}
			if($tempFlag==1)
			{
				if($requestArray==count($request->input())-1)
				{
					$tJournalArray['flag']="1";
				}
			}
		}
		if($journalArrayFlag==1 && $tempArrayFlag==1)
		{
			if($creditAmountArray==$debitAmountArray)
			{
				array_push($tJournalArray,$tempArray);
				return $tJournalArray;
			}
			else
			{
				return $exceptionArray['equal'];
			}
		}
		else if($tempArrayFlag==1)
		{
			if($creditAmountArray==$debitAmountArray)
			{
				return $tempArray;
			}
			else
			{
				return $exceptionArray['equal'];
			}
		}
		else
		{
			return $tJournalArray;
		}
	}
}