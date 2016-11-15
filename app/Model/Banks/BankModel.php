<?php
namespace ERP\Model\Banks;

use Illuminate\Database\Eloquent\Model;
use DB;
use ERP\Exceptions\ExceptionMessage;
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
		from bank_mst where deleted_at='0000-00-00 00:00:00'");
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
	public function getData($bankId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		bank_id,
		bank_name
		from bank_mst where bank_id = ".$bankId." and deleted_at='0000-00-00 00:00:00'");
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
