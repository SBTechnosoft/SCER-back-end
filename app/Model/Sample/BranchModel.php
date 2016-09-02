<?php
namespace ERP\Model\Sample;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchModel extends Model
{
	protected $table = 'product';
	public $timestamps = false;	
	/**
	 * insert data 
	 * @param  name,age and image_name
	 * returns the status
	*/
	public function insertData($name,$age,$imageName)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert 
		into product (name,age,image_name) 
		values('".$name."','".$age."','".$imageName."')");
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
	 * @param  name,age,id,image_name
	 * returns the status
	*/
	public function updateData($name,$age,$id,$imageName)
	{
		DB::beginTransaction();
		$raw = DB::statement("update product 
		set name='".$name."',age='".$age."',image_name='".$imageName."' 
		where id = ".$id);
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
		name,age,image_name,created_at,updated_at
		from product 
		where deleted_at='0000-00-00 00:00:00'");
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
	 * get the data of given id 
	 * @param id
	 * returns the status
	*/
	public function getData($id)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		name,age,image_name,created_at,updated_at
		from product 
		where id = ".$id." and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		print_r(count($raw));
		if(count($raw)==0)
		{
			print_r("ggggggggg");
			return "404:Id Not Found";
		}
		else
		{
			$enocodedData = json_encode($raw); 
			return $enocodedData;
		}
	}
	
	/**
	 * delete particular data 
	 * @param  id
	 * returns the status
	*/
	public function deleteData($id)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product 
		set deleted_at='".$mytime."' 
		where id = ".$id);
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
