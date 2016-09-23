<?php
namespace ERP\Model\States;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateModel extends Model
{
	protected $table = 'state_mst';
	
	/**
	 * insert data 
	 * @param  state_name,is_display and state_abb
	 * returns the status
	*/
	public function insertData($stateName,$isDisplay,$stateAbb)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert 
		into state_mst(state_abb,state_name,is_display)
		values('".$stateAbb."', '".$stateName."','".$isDisplay."')");
		DB::commit();
		
		if($raw==1)
		{
			return "200:Data Inserted Successfully";
		}
		else
		{
			return "500:Internal Server Error";
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
		where state_abb = '".$stateAbb."'");
		DB::commit();
		
		if($raw==1)
		{
			return "200: Data Updated Successfully";
		}
		else
		{
			return "500: Internal Server Error";
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
		updated_at,
		deleted_at
		from state_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "204: No Content";
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
		updated_at,
		deleted_at
		from state_mst where state_abb = '".$stateAbb."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "404:Id Not Found";
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
				return "200 :Data Deleted Successfully";
			}
			else
			{
				return "500 : Internal Server Error";
			}
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}
