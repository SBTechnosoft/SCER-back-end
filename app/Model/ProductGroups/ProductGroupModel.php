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
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getProductGrpData = array();
		$getProductGrpKey = array();
		$getProductGrpData = func_get_arg(0);
		$getProductGrpKey = func_get_arg(1);
		$productGrpData="";
		$keyName = "";
		for($data=0;$data<count($getProductGrpData);$data++)
		{
			if($data == (count($getProductGrpData)-1))
			{
				$productGrpData = $productGrpData."'".$getProductGrpData[$data]."'";
				$keyName =$keyName.$getProductGrpKey[$data];
			}
			else
			{
				$productGrpData = $productGrpData."'".$getProductGrpData[$data]."',";
				$keyName =$keyName.$getProductGrpKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into product_group_mst(".$keyName.") 
		values(".$productGrpData.")");
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
	public function updateData($productGrpData,$key,$productGrpId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productGrpData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productGrpData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update product_group_mst 
		set ".$keyValueString."updated_at='".$mytime."'
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
