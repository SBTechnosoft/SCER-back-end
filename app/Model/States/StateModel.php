<?php
namespace ERP\Model\States;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateModel extends Model
{
	protected $table = 'state_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getStateData = array();
		$getStateKey = array();
		$getStateData = func_get_arg(0);
		$getStateKey = func_get_arg(1);
		$stateData="";
		$keyName = "";
		for($data=0;$data<count($getStateData);$data++)
		{
			if($data == (count($getStateData)-1))
			{
				$stateData = $stateData."'".$getStateData[$data]."'";
				$keyName =$keyName.$getStateKey[$data];
			}
			else
			{
				$stateData = $stateData."'".$getStateData[$data]."',";
				$keyName =$keyName.$getStateKey[$data].",";
			}
		}
		
		DB::beginTransaction();
		$raw = DB::statement("insert into state_mst(".$keyName.") 
		values(".$stateData.")");
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
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		state_abb,
		state_name,
		is_display,
		created_at,
		updated_at
		from state_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given state_abb
	 * @param $stateAbb
	 * returns the status
	*/
	public function getData($stateAbb)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		state_abb,
		state_name,
		is_display,
		created_at,
		updated_at
		from state_mst where state_abb = '".$stateAbb."' and deleted_at='0000-00-00 00:00:00'");
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
		$exceptionArray = $exception->messageArrays();
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
				return $exceptionArray['200'];
			}
			else
			{
				return $exceptionArray['500'];
			}
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
