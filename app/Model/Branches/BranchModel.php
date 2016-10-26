<?php
namespace ERP\Model\Branches;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchModel extends Model
{
	protected $table = 'branch_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getBranchData = array();
		$getBranchKey = array();
		$getBranchData = func_get_arg(0);
		$getBranchKey = func_get_arg(1);
		$branchData="";
		$keyName = "";
		for($data=0;$data<count($getBranchData);$data++)
		{
			if($data == (count($getBranchData)-1))
			{
				$branchData = $branchData."'".$getBranchData[$data]."'";
				$keyName =$keyName.$getBranchKey[$data];
			}
			else
			{
				$branchData = $branchData."'".$getBranchData[$data]."',";
				$keyName =$keyName.$getBranchKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into branch_mst(".$keyName.") 
		values(".$branchData.")");
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
	 * update data 
	 * @param  branch-data,key of branch-data,branch-id
	 * returns the status
	*/
	public function updateData($branchData,$key,$branchId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($branchData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$branchData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update branch_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where branch_id = '".$branchId."'");
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['404'];
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
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
		$raw = DB::statement("update product_mst 
		set deleted_at='".$mytime."' 
		where branch_id=".$branchId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($raw==1)
		{
			$product = DB::statement("update branch_mst 
			set deleted_at='".$mytime."' 
			where branch_id=".$branchId);
			if($product==1)
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
