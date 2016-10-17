<?php
namespace ERP\Model\Banks;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BankModel extends Model
{
	protected $table = 'bank_mst';
	
	/**
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		bank_id,
		bank_name
		from bank_mst");
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
	public function getData($bankId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		bank_id,
		bank_name
		from bank_mst where bank_id = ".$bankId);
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
