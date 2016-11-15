<?php
namespace ERP\Model\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductModel extends Model
{
	protected $table = 'product_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getProductData = array();
		$getproductKey = array();
		$getProductData = func_get_arg(0);
		$getProductKey = func_get_arg(1);
		$productData="";
		$keyName = "";
		for($data=0;$data<count($getProductData);$data++)
		{
			if($data == (count($getProductData)-1))
			{
				$productData = $productData."'".$getProductData[$data]."'";
				$keyName =$keyName.$getProductKey[$data];
			}
			else
			{
				$productData = $productData."'".$getProductData[$data]."',";
				$keyName =$keyName.$getProductKey[$data].",";
			}
		}
		
		DB::beginTransaction();
		$raw = DB::statement("insert into product_mst(".$keyName.") 
		values(".$productData.")");
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
	 * @param  name,age and id
	 * returns the status
	*/
	public function updateData($productData,$key,$productId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productData[$data]."',";
		}
		
		DB::beginTransaction();
		$raw = DB::statement("update product_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where product_id = '".$productId."'");
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
		product_id,
		product_name,
		measurement_unit,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		product_category_id,
		product_group_id,
		branch_id,
		company_id			
		from product_mst where deleted_at='0000-00-00 00:00:00'");
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
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where product_id = ".$productId." and deleted_at='0000-00-00 00:00:00'");
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
	public function getBCProductData($companyId,$branchId)
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
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id ='".$companyId."' and branch_id='".$branchId."' and  deleted_at='0000-00-00 00:00:00'");
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
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id ='".$companyId."'and deleted_at='0000-00-00 00:00:00'");
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
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where branch_id='".$branchId."' and  deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($productId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_mst 
		set deleted_at='".$mytime."' 
		where product_id=".$productId);
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
}
