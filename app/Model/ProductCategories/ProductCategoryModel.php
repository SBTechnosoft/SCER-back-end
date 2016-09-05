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
	 * @param  state_name,is_display and state_abb
	 * returns the status
	*/
	public function insertData($productParentCatId,$productCatDesc,$isDisplay,$productCatName)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert 
		into product_category_mst(product_parent_cat_id,product_cat_desc,is_display,product_cat_name)
		values('".$productParentCatId."', '".$productCatDesc."','".$isDisplay."','".$productCatName."')");
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
	public function updateData($productParentCatId,$productCatDesc,$isDisplay,$productCatName,$productCatId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_category_mst 
		set product_cat_name='".$productCatName."',product_cat_desc='".$productCatDesc."',product_parent_cat_id='".$productParentCatId."',is_display='".$isDisplay."',updated_at='".$mytime."'
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
			return "200 :Data Deleted Successfully";
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}
