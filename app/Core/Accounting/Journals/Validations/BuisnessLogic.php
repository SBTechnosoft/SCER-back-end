<?php
namespace ERP\Core\Accounting\Journals\Validations;

use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Exceptions\ExceptionMessage;
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
		print_r($trimRequest);
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
			echo "array not exist";
		}
	}
}