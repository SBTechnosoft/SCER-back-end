<?php
namespace ERP\Model\Cities;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityModel extends Model
{
	protected $table = 'city_mst';
	/**
	 * insert data 
	 * @param  city_name,is_display,state_abb
	 * returns the status
	*/
	public function insertData($cityName,$isDisplay,$stateAbb)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert 
		into city_mst(city_name,is_display,state_abb)
		values('".$cityName."','".$isDisplay."','".$stateAbb."')");
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
	 * @param city_name,state_abb and is_display
	 * returns the status
	*/
	public function updateData($cityName,$stateAbb,$isDisplay,$cityId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update city_mst 
		set city_name='".$cityName."',
		is_display='".$isDisplay."',
		state_abb='".$stateAbb."',
		updated_at='".$mytime."'
		where city_id = '".$cityId."'");
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
		city_id,
		city_name,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		state_abb
		from city_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given city_id
	 * @param $cityId
	 * returns the status
	*/
	public function getData($cityId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		city_id,
		city_name,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		state_abb
		from city_mst where city_id='".$cityId."' and deleted_at='0000-00-00 00:00:00'");
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
	
	/**
	 * get data as per given city_id
	 * @param $cityId
	 * returns the status
	*/
	public function getAllCityData($stateAbb)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		city_id,
		city_name,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		state_abb
		from city_mst where state_abb='".$stateAbb."' and deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($cityId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update city_mst 
		set deleted_at='".$mytime."'
		where city_id = '".$cityId."'");
		DB::commit();
		
		if($raw==1)
		{
			return "200 :Data Deleted Successfully";
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}
