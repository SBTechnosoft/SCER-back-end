<?php
namespace ERP\Model\Accounting\LedgerGroups;

use Illuminate\Database\Eloquent\Model;
use DB;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerGroupModel extends Model
{
	protected $table = 'ledger_grp_mst';
	
	/**
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		ledger_group_id,
		ledger_group_name,
		alias,
		under_what,
		nature_of_group,
		affected_group_profit
		from ledger_grp_mst");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
		}
		else
		{
			return json_encode($raw);
		}
	}
	
	/**
	 * get data as per given Bank Id
	 * @param $bankId
	 * returns the status
	*/
	public function getData($ledgerGrpId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		ledger_group_id,
		ledger_group_name,
		alias,
		under_what,
		nature_of_group,
		affected_group_profit
		from ledger_grp_mst where ledger_group_id = ".$ledgerGrpId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['404'];
		}
		else
		{
			return json_encode($raw);
		}
	}
}
