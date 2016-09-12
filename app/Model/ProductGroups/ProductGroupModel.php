<?php
namespace ERP\Model\ProductGroups;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupModel extends Model
{
	protected $table = 'product_group_mst';
	
	/**
	 * insert data 
	 * @param  state_name,is_display and state_abb
	 * returns the status
	*/
	public function insertData($productParentGrpId,$productGrpDesc,$isDisplay,$productGrpName)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert 
		into product_group_mst(product_group_parent_id,product_group_desc,is_display,product_group_name)
		values('".$productParentGrpId."', '".$productGrpDesc."','".$isDisplay."','".$productGrpName."')");
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
	 * @param state_abb,state_nameand is_display
	 * returns the status
	*/
	public function updateData($productParentGrpId,$productGrpDesc,$isDisplay,$productGrpName,$productGrpId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_group_mst 
		set product_group_name='".$productGrpName."',product_group_desc='".$productGrpDesc."',product_group_parent_id='".$productParentGrpId."',is_display='".$isDisplay."',updated_at='".$mytime."'
		where product_group_id = '".$productGrpId."'");
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
		product_group_id,
		product_group_name,
		product_group_desc,
		is_display,
		product_group_parent_id,
		created_at,
		updated_at
		from product_group_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given product_Cat_Id
	 * @param $productCategoryId
	 * returns the status
	*/
	public function getData($productGrpId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		product_group_id,
		product_group_name,
		product_group_desc,
		is_display,
		product_group_parent_id,
		created_at,
		updated_at
		from product_group_mst where product_group_id = '".$productGrpId."' and deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($productGrpId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_group_mst 
		set deleted_at='".$mytime."'
		where product_group_id = '".$productGrpId."'");
		DB::commit();
		if($raw==1)
		{
			$productGrp = DB::statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_group_id = '".$productGrpId."'");
			if($productGrp==1)
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
