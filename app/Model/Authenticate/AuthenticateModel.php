<?php
namespace ERP\Model\Authenticate;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class AuthenticateModel extends Model
{
	protected $table = 'active_session';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData($userId,$token)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into active_session
		(user_id,
		token)
		values(
		'".$userId."',
		'".$token."'
		)");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * update date 
	 * @param user-id
	 * returns the status
	*/
	public function updateDate($userId)
	{
		$mytime = Carbon\Carbon::now();
		
		DB::beginTransaction();
		$raw = DB::statement("update active_session 
		set updated_at='".$mytime."' where deleted_at='0000-00-00 00:00:00' and user_id='".$userId."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * get data
	 * returns the exception-message/arraydata
	*/
	public function getAllData()
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::select("select
		session_id,
		token,
		created_at,
		updated_at,
		user_id
		from active_session");
		DB::commit();
		
		if(count($raw)!=0)
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data 
	 * @param user-id
	 * returns the exception-message/arraydata
	*/
	public function getData($userId)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::select("select
		session_id,
		token,
		created_at,
		updated_at,
		user_id
		from active_session where user_id='".$userId."' and deleted_at='0000-00-00 00:00:00 	'");
		DB::commit();
		
		if(count($raw)!=0)
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
		else
		{
			return $exceptionArray['404'];
		}
	}
	
}
