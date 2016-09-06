<?php
namespace ERP\Model\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductModel extends Model
{
	protected $table = 'product_mst';
	
	/**
	 * insert data 
	 * @param  name and age
	 * returns the status
	*/
	public function insertData($productName,$isDisplay,$companyId,$getMeasureUnit,$productCatId,$branchId,$productGrpId)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into product_mst(product_name,is_display,company_id,measurement_unit,product_cat_id,branch_id,product_group_id) 
		values('".$productName."', 
		'".$isDisplay."',
		'".$companyId."',
		'".$getMeasureUnit."',
		'".$productCatId."',
		'".$branchId."',
		'".$productGrpId."'
		)");
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
	public function updateData($productName,$isDisplay,$companyId,$productId,$productCatId,$getMeasureUnit,$branchId,$productGrpId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_mst 
		set product_name='".$productName."',
		is_display='".$isDisplay."',
		measurement_unit='".$getMeasureUnit."',
		product_cat_id='".$productCatId."',
		product_group_id='".$productGrpId."',
		branch_id='".$branchId."',
		company_id='".$companyId."',
		updated_at='".$mytime."'
		where product_id = ".$productId);
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
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_cat_id,
		product_group_id,
		branch_id,
		company_id			
		from product_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given product Id
	 * @param $productId
	 * returns the status
	*/
	public function getData($productId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_cat_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where product_id = ".$productId." and deleted_at='0000-00-00 00:00:00'");
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
	public function getCBProductData($companyId,$branchId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_cat_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id ='".$companyId."' and branch_id='".$branchId."' and  deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getCProductData($companyId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_cat_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id ='".$companyId."'and deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getBProductData($branchId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_cat_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where branch_id='".$branchId."' and  deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($productId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_mst 
		set deleted_at='".$mytime."' 
		where product_id=".$productId);
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
