<?php
namespace ERP\Model\Authenticate;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into active_session
		(user_id,
		token,
		updated_at)
		values(
		'".$userId."',
		'".$token."',
		'".$mytime."'
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update active_session 
		set updated_at='".$mytime."' where user_id='".$userId."'");
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select
		session_id,
		token,
		created_at,
		updated_at,
		user_id
		from active_session where user_id='".$userId."'");
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
	
	/**
	 * get user-type 
	 * @param header-data
	 * returns the exception-message/user-type
	*/
	public function getUserType($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select
		u.user_type
		from active_session a  
		RIGHT JOIN user_mst u
		ON a.user_id=u.user_id
		where token='".$headerData['authenticationtoken'][0]."'");
		DB::commit();
		if(strcmp($raw[0]->user_type,'admin')==0 || strcmp($raw[0]->user_type,'superadmin'))
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['content'];
		}
	}
	
	/**
	 * get user-type 
	 * @param header-data
	 * returns the exception-message/user-type
	*/
	public function checkAuthenticationToken($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();

		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select
		session_id,
		updated_at
		from active_session
		where token='".$headerData."'");

		DB::commit();
		if(count($raw)!=0)
		{
			return $raw;
		}
		else
		{
			return $exceptionArray['token'];
		}
	}

	
	/**
	 * change updated_at date
	 * @param header-data
	 * returns the exception-message/status
	*/
	public function changeDate($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();

		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update active_session
		set updated_at='".$mytime."'
		where token='".$headerData['authenticationtoken'][0]."'");
		DB::commit();
		return $exceptionArray['200'];
	}
}
