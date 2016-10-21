<?php
namespace ERP\Model\Accounting\LedgerGroups;

use Illuminate\Database\Eloquent\Model;
use DB;
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
		ledger_grp_id,
		ledger_grp_name,
		under_what
		from ledger_grp_mst");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "204: No Content";
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
		ledger_grp_id,
		ledger_grp_name,
		under_what
		from ledger_grp_mst where ledger_grp_id = ".$ledgerGrpId);
		DB::commit();
		
		if(count($raw)==0)
		{
			return "404:Id Not Found";
		}
		else
		{
			return json_encode($raw);
		}
	}
}
