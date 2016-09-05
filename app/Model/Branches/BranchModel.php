<?php
namespace ERP\Model\Branches;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchModel extends Model
{
	protected $table = 'branch_mst';
	
	/**
	 * insert data 
	 * @param  name and age
	 * returns the status
	*/
	public function insertData($branchName,$address1,$address2,$pincode,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into branch_mst(branch_name,address1,address2,pincode,is_display,is_default,state_abb,city_id,company_id) 
		values('".$branchName."', 
		'".$address1."',
		'".$address2."',
		'".$pincode."',
		'".$isDisplay."',
		'".$isDefault."',
		'".$stateAbb."',
		'".$cityId."',
		'".$companyId."')");
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
	 * @param  name,age and id
	 * returns the status
	*/
	public function updateData($branchName,$address1,$address2,$pincode,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId,$branchId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update branch_mst 
		set branch_name='".$branchName."',
		address1='".$address1."',
		address2='".$address2."',
		pincode='".$pincode."',
		is_display='".$isDisplay."',
		is_default='".$isDefault."',
		state_abb='".$stateAbb."',
		city_id='".$cityId."',
		company_id='".$companyId."',
		updated_at='".$mytime."'
		where branch_id = ".$branchId);
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
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id			
		from branch_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given Branch Id
	 * @param $branchId
	 * returns the status
	*/
	public function getData($branchId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id	
		from branch_mst where branch_id = ".$branchId." and deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getAllBranchData($companyId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id
		from branch_mst where company_id ='".$companyId."' and  deleted_at='0000-00-00 00:00:00'");
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
	
	//delete
	public function deleteData($branchId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update branch_mst 
		set deleted_at='".$mytime."' 
		where branch_id=".$branchId);
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
