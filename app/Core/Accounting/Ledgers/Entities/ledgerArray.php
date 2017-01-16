<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ledgerArray
{
	public function ledgerArrays()
	{
		$ledgerGeneralArray = array();
		$ledgerGeneralArray[0] = "capital";
		$ledgerGeneralArray[1] = "salary";
		$ledgerGeneralArray[2] = "machine";
		$ledgerGeneralArray[3] = "labour";
		$ledgerGeneralArray[4] = "sales";
		$ledgerGeneralArray[5] = "purchase";
		$ledgerGeneralArray[6] = "bank";
		$ledgerGeneralArray[7] = "tax(income)";
		$ledgerGeneralArray[8] = "tax(expense)";
		$ledgerGeneralArray[9] = "discount(income)";
		$ledgerGeneralArray[10] = "discount(expense)";
		$ledgerGeneralArray[11] = "cash";
		$ledgerGeneralArray[12] = "sales_return";
		$ledgerGeneralArray[13] = "purchase_return";
		$ledgerGeneralArray[14] = "retail_sales";
		$ledgerGeneralArray[15] = "whole_sales";
		return $ledgerGeneralArray;
	}
	
	public function billLedgerArray()
	{
		$ledgerGeneralArray = array();
		$ledgerGeneralArray[0] = "sales";
		$ledgerGeneralArray[1] = "tax(income)";
		$ledgerGeneralArray[2] = "discount(expense)";
		return $ledgerGeneralArray;
	}
	
	public function ledgerGrpArray()
	{
		$ledgerGeneralArray = array();
		$ledgerGeneralArray[0] = 11;
		$ledgerGeneralArray[1] = 19;
		$ledgerGeneralArray[2] = 13;
		$ledgerGeneralArray[3] = 19;
		$ledgerGeneralArray[4] = 28;
		$ledgerGeneralArray[5] = 26;
		$ledgerGeneralArray[6] = 9;
		$ledgerGeneralArray[7] = 17;
		$ledgerGeneralArray[8] = 16;
		$ledgerGeneralArray[9] = 17;
		$ledgerGeneralArray[10] = 16;
		$ledgerGeneralArray[11] = 12;
		$ledgerGeneralArray[12] = 28;
		$ledgerGeneralArray[13] = 26;
		$ledgerGeneralArray[14] = 28;
		$ledgerGeneralArray[15] = 28;
		return $ledgerGeneralArray;
	}
}