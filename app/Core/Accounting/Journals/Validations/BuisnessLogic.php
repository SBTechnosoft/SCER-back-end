<?php
namespace ERP\Core\Accounting\Journals\Validations;

use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
/**
  * @author Reema Patel<reema.p@siliconbrain.in>
  */
class BuisnessLogic extends LedgerModel
{
	/**
	 * validate trim-request data for insert
     * @param trim-array
     * @return array/exception message
     */
	public function validateBuisnessLogic($trimRequest)
	{
		$ledgerId = array();
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($journalArray=0;$journalArray<count($trimRequest[0]);$journalArray++)
		{
			$amount[$journalArray][0] = $trimRequest[0][$journalArray]['amount'];
			$amountType[$journalArray][1] = $trimRequest[0][$journalArray]['amountType'];
			$ledgerId[$journalArray][2] = $trimRequest[0][$journalArray]['ledgerId'];
			
			//check ledger exists
			$journalObject = new BuisnessLogic();
			$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
			if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
			{
				return $exceptionArray['404'];
			}
			else
			{
				//check credit-debit amount
				if(strcmp($amountType[$journalArray][1],"credit")==0)
				{
					$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
				}
				else
				{
					$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
				}
			}
		}
		if($creditAmountArray==$debitAmountArray)
		{
			return $trimRequest;
		}
		else
		{
			return $exceptionArray['equal'];
		}
	}
	
	/**
	 * validate trim-request data for update
     * @param trim-array
     * @return array/exception message
     */
	public function validateUpdateBuisnessLogic($trimRequest)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$ledgerId = array();
		$creditAmountArray = 0;
		$debitAmountArray = 0;
		//array exist
		if(array_key_exists('0',$trimRequest))
		{
			if(array_key_exists('flag',$trimRequest))
			{
				for($journalArray=0;$journalArray<count($trimRequest[0]);$journalArray++)
				{
					$amount[$journalArray][0] = $trimRequest[0][$journalArray]['amount'];
					$amountType[$journalArray][1] = $trimRequest[0][$journalArray]['amount_type'];
					$ledgerId[$journalArray][2] = $trimRequest[0][$journalArray]['ledger_id'];
					
					//check ledger exists
					$journalObject = new BuisnessLogic();
					$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
					
					if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
					{
						return $exceptionArray['404'];
					}
					else
					{
						//check credit-debit amount
						if(strcmp($amountType[$journalArray][1],"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
						}
					}
				}
				if($creditAmountArray==$debitAmountArray)
				{
					return $trimRequest;
				}	
				else
				{
					return $exceptionArray['equal'];
				}
			}
			else
			{
				for($journalArray=0;$journalArray<count($trimRequest);$journalArray++)
				{
					$amount[$journalArray][0] = $trimRequest[$journalArray]['amount'];
					$amountType[$journalArray][1] = $trimRequest[$journalArray]['amount_type'];
					$ledgerId[$journalArray][2] = $trimRequest[$journalArray]['ledger_id'];
					
					//check ledger exists
					$journalObject = new BuisnessLogic();
					$ledgerIdResult = $journalObject->getData($ledgerId[$journalArray][2]);
					if(strcmp($ledgerIdResult,$exceptionArray['404'])==0)
					{
						return $exceptionArray['404'];
					}
					else
					{
						//check credit-debit amount
						if(strcmp($amountType[$journalArray][1],"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$amount[$journalArray][0];
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$amount[$journalArray][0];
						}
					}
				}
				if($creditAmountArray==$debitAmountArray)
				{
					return $trimRequest;
				}
				else
				{
					return $exceptionArray['equal'];
				}
			}
		}
		else
		{
			return 0;
		}
	}
	
	/**
	 * validate trim-request data for update
     * @param trim-array of product and journal and header data
	 * check journal and product total-amount,and if tax and discount is available then check that value
     * @return array/exception message
     */
	public function validateUpdateProductBuisnessLogic($headerData,$trimJouranlData,$productData)
	{
		// echo "hi";
		$ledgerIdArray = array();
		
		print_r($trimJouranlData);
		print_r($productData);
		// print_r($headerData['type'][0]);
		exit;
		
		//amount should be equal ,discount amount and tax amount
		if(strcmp("sales",$headerData['type'][0])==0)
		{
			$ledgerService = new LedgerService();
			//check tax and discount is available in journal data
			for($journalArrayData=0;$journalArrayData<count($trimJouranlData[0]);$journalArrayData++)
			{
				$ledgerIdArray[$journalArrayData] = $trimJouranlData[0][$journalArrayData]['ledger_id'];
				$ledgerResult = $ledgerService->getLedgerData($ledgerIdArray[$journalArrayData]);
				// print_r(json_decode($ledgerResult));
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==17)
				{
					//tax  ledger exist
					// if($trimJouranlData[0][$journalArrayData]['amount']==)
				}
				if(json_decode($ledgerResult)->ledgerGroup->ledgerGroupId==16)
				{
					//discount ledger exist
				}
				exit;
			}
			
			
			
			exit;
			// $taxLedgerGrpId=;
			// $discountLedgerGrpId=;
		}
		else
		{
			
		}
		exit;
	}
}