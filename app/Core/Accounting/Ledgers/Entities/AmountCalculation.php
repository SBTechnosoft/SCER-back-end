<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Exceptions\ExceptionMessage;
use DB;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class AmountCalculation 
{
	public function getCalculatedData($ledgerId,$ledgerAllData)
	{
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		
	}
}