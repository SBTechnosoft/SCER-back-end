<?php
namespace ERP\Model\Accounting\BalanceSheet;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BalanceSheetModel extends Model
{
	protected $table = 'balance_sheet_dtl';
	
	/**
	 * get data as per given companyId 
	 * returns the array-data/exception message
	*/
	public function getBalanceSheetData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//truncate table trial-balance
		DB::beginTransaction();	
		$truncateTable = DB::connection($databaseName)->statement("truncate table balance_sheet_dtl"); 
		DB::commit();
		
		//get ledgerId from ledger 
		DB::beginTransaction();	
		$ledgerResult = DB::connection($databaseName)->select("select
		ledger_id
		from ledger_mst
		where company_id='".$companyId."' and deleted_at='0000-00-00 00:00:00'"); 
		DB::commit();
		
		
		$mytime = Carbon\Carbon::now();
		
		$fromYear = $mytime->year-1;
		$fromDate = $fromYear.'-04-01';
		$toDate = $mytime->year.'-03-31';
		
		for($ledgerData=0;$ledgerData<count($ledgerResult);$ledgerData++)
		{
			$flag=0;
			$balanceType="";
			
			// get amount,amount_type from particular ledgerId_ledger_dtl
			DB::beginTransaction();	
			$ledgerAmountResult = DB::connection($databaseName)->select("select
			amount,
			amount_type
			from ".$ledgerResult[$ledgerData]->ledger_id."_ledger_dtl
			where deleted_at='0000-00-00 00:00:00' and 
			entry_date BETWEEN '".$fromDate."' AND '".$toDate."'"); 
			DB::commit();
			$creditTotal=0;
			$debitTotal=0;
			for($ledgerAmountData=0;$ledgerAmountData<count($ledgerAmountResult);$ledgerAmountData++)
			{
				if(strcmp($ledgerAmountResult[$ledgerAmountData]->amount_type,"credit")==0)
				{
					$creditTotal = $creditTotal+$ledgerAmountResult[$ledgerAmountData]->amount;
				}
				else
				{
					$debitTotal = $debitTotal+$ledgerAmountResult[$ledgerAmountData]->amount;
				}
			}
			
			if($creditTotal>$debitTotal)
			{
				$totalBalance = $creditTotal-$debitTotal;
				$balanceType = "credit";
			}
			else if($creditTotal<$debitTotal)
			{
				$totalBalance = $debitTotal-$creditTotal;
				$balanceType = "debit";
			}
			else
			{
				$flag=1;
			}
			if($flag==0)
			{
				// insert amount,amount_type in trial-balance
				DB::beginTransaction();	
				$trialBalanceResult = DB::connection($databaseName)->statement("insert into balance_sheet_dtl(
				amount,
				amount_type,
				ledger_id)
				values('".$totalBalance."','".$balanceType."','".$ledgerResult[$ledgerData]->ledger_id."')");
				DB::commit();
			}
		}
		
		//get trial-balance data
		DB::beginTransaction();	
		$balanceSheetResult = DB::connection($databaseName)->select("select 
		balance_sheet_id,
		amount,
		amount_type,
		ledger_id
		from balance_sheet_dtl 
		where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		if(count($balanceSheetResult)==0)
		{
			$exceptionArray['404'];
		}
		else
		{
			$encodedData = json_encode($balanceSheetResult);
			return $encodedData;
		}
	}
}
