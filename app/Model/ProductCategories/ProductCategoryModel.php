<?php
namespace ERP\Model\ProductCategories;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryModel extends Model
{
	protected $table = 'product_category_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getProductCatData = array();
		$getProductCatKey = array();
		$getProductCatData = func_get_arg(0);
		$getProductCatKey = func_get_arg(1);
		$productCatData="";
		$keyName = "";
		for($data=0;$data<count($getProductCatData);$data++)
		{
			if($data == (count($getProductCatData)-1))
			{
				$productCatData = $productCatData."'".$getProductCatData[$data]."'";
				$keyName =$keyName.$getProductCatKey[$data];
			}
			else
			{
				$productCatData = $productCatData."'".$getProductCatData[$data]."',";
				$keyName =$keyName.$getProductCatKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into product_category_mst(".$keyName.") 
		values(".$productCatData.")");
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
	 * @param productCatData,$key of productCatData,productCatId
	 * returns the status
	*/
	public function updateData($productCatData,$key,$productCatId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productCatData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productCatData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update product_category_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where product_cat_id = '".$productCatId."'");
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
		product_cat_id,
		product_cat_name,
		product_cat_desc,
		is_display,
		product_parent_cat_id,
		created_at,
		updated_at
		from product_category_mst where deleted_at='0000-00-00 00:00:00'");
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
	public function getData($productCategoryId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		product_cat_id,
		product_cat_name,
		product_cat_desc,
		is_display,
		product_parent_cat_id,
		created_at,
		updated_at
		from product_category_mst where product_cat_id = '".$productCategoryId."' and deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($productCatId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_category_mst 
		set deleted_at='".$mytime."'
		where product_cat_id = '".$productCatId."'");
		DB::commit();
		if($raw==1)
		{
			$product = DB::statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_cat_id = '".$productCatId."'");
			if($product==1)
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
