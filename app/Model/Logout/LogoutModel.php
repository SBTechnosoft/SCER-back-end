<?php
namespace ERP\Model\Logout;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LogoutModel extends Model
{
	//delete data
	public function deleteData($userId)
	{
		DB::beginTransaction();
		$raw = DB::statement("delete 
		from active_session 
		where user_id='".$userId."'");
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
}
