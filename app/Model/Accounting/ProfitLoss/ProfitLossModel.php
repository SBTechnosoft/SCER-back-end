<?php
namespace ERP\Model\Accounting\ProfitLoss;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProfitLossModel extends Model
{
	protected $table = 'profit_loss_dtl';
	
	/**
	 * get data as per given companyId 
	 * returns the array-data/exception message
	*/
	public function getProfitLossData($companyId)
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
		$truncateTable = DB::connection($databaseName)->statement("truncate table profit_loss_dtl"); 
		DB::commit();
		
		$mytime = Carbon\Carbon::now();
		//get ledgerId from ledger 
		DB::beginTransaction();	
		$ledgerResult = DB::connection($databaseName)->select("select
		ledger_id
		from ledger_mst
		where company_id='".$companyId."' and
		(ledger_name = 'cash' OR ledger_name = 'bank') and 
		deleted_at='0000-00-00 00:00:00'"); 
		DB::commit();
		
		$currentDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mytime)->format('Y-m-d');
		$dateTime = $mytime->toDateTimeString();
		$yearStartDate = $mytime->year.'-04-01 00:00:00';
		if($dateTime >= $yearStartDate)
		{
			$toYear = $mytime->year+1;
			$fromDate = $mytime->year.'-04-01 00:00:00';
			$toDate = $toYear.'-03-31 00:00:00';
		}
		else
		{
			$fromYear = $mytime->year-1;
			$fromDate = $fromYear.'-04-01 00:00:00';
			$toDate = $mytime->year.'-03-31 00:00:00';
		}
		if($dateTime > $toDate)
		{
			$toDate = $dateTime;
		}
		
		for($ledgerData=0;$ledgerData<count($ledgerResult);$ledgerData++)
		{
			$flag=0;
			$balanceType="";
			// get amount,amount_type from particular ledgerId_ledger_dtl
			DB::beginTransaction();	
			$ledgerAmountResult = DB::connection($databaseName)->select("select
			amount,
			amount_type,
			entry_date
			from ".$ledgerResult[$ledgerData]->ledger_id."_ledger_dtl
			where deleted_at='0000-00-00 00:00:00' and
			entry_date BETWEEN '".$fromDate."' AND '".$toDate."'
			"); 
			DB::commit();
			for($arrayData=0;$arrayData<count($ledgerAmountResult);$arrayData++)
			{
				// insert amount,amount_type in trial-balance
				DB::beginTransaction();	
				$profitLossResult = DB::connection($databaseName)->statement("insert into profit_loss_dtl(
				amount,
				amount_type,
				entry_date,
				ledger_id)
				values('".$ledgerAmountResult[$arrayData]->amount."','".$ledgerAmountResult[$arrayData]->amount_type."','".$ledgerAmountResult[$arrayData]->entry_date."','".$ledgerResult[$ledgerData]->ledger_id."')");
				DB::commit();
			}
		}
		//get trial-balance data
		DB::beginTransaction();	
		$profitLossResult = DB::connection($databaseName)->select("select 
		profit_loss_id,
		amount,
		amount_type,
		entry_date,
		ledger_id,
		created_at,
		updated_at
		from profit_loss_dtl
		where deleted_at='0000-00-00 00:00:00'
		order by entry_date");
		DB::commit();
		
		if(count($profitLossResult)==0)
		{
			$exceptionArray['404'];
		}
		else
		{
			$encodedData = json_encode($profitLossResult);
			return $encodedData;
		}
	}
}
