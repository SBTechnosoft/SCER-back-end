<?php
namespace ERP\Model\Users;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class UserModel extends Model
{
	protected $table = 'user_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getUserData = array();
		$getUserKey = array();
		$getUserData = func_get_arg(0);
		$getUserKey = func_get_arg(1);
		$userData="";
		$keyName = "";
		for($data=0;$data<count($getUserData);$data++)
		{
			if($data == (count($getUserData)-1))
			{
				$userData = $userData."'".$getUserData[$data]."'";
				$keyName =$keyName.$getUserKey[$data];
			}
			else
			{
				$userData = $userData."'".$getUserData[$data]."',";
				$keyName =$keyName.$getUserKey[$data].",";
			}
		}
		
		DB::beginTransaction();
		$raw = DB::statement("insert into user_mst(".$keyName.") 
		values(".$userData.")");
		DB::commit();
		
		// get exception message
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
	 * update data 
	 * @param state_abb,state-data and key of state-data
	 * returns the status
	*/
	public function updateData($stateData,$key,$stateAbb)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($stateData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$stateData[$data]."',";
		}
		
		DB::beginTransaction();
		$raw = DB::statement("update state_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where state_abb = '".$stateAbb."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($raw==1)
		{
			return $fileSizeArray['200'];
		}
		else
		{
			return $fileSizeArray['500'];
		}
	}
	
	/**
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		user_id,
		user_name,
		email_id,
		password,
		contact_no,
		address,
		pincode,
		state_abb,
		city_id,
		company_id,
		branch_id,
		created_at,
		updated_at
		from user_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data as per given user_id
	 * @param $userId
	 * returns the status
	*/
	public function getData($userId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		user_id,
		user_name,
		email_id,
		password,
		contact_no,
		address,
		pincode,
		state_abb,
		city_id,
		company_id,
		branch_id,
		created_at,
		updated_at
		from user_mst where user_id = '".$userId."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			$enocodedData = json_encode($raw,true); 
			return $enocodedData;
		}
	}
	
	//delete
	public function deleteData($stateAbb)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update state_mst 
		set deleted_at='".$mytime."'
		where state_abb = '".$stateAbb."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($raw==1)
		{
			$city = DB::statement("update city_mst 
			set deleted_at='".$mytime."'
			where state_abb = '".$stateAbb."'");
			$company = DB::statement("update company_mst 
			set deleted_at='".$mytime."'
			where state_abb = '".$stateAbb."'");
			$branch = DB::statement("update branch_mst 
			set deleted_at='".$mytime."'
			where state_abb = '".$stateAbb."'");
			
			if($city==1 && $company==1 && $branch==1)
			{
				return $fileSizeArray['200'];
			}
			else
			{
				return $fileSizeArray['500'];
			}
		}
		else
		{
			return $fileSizeArray['500'];
		}
	}
}
