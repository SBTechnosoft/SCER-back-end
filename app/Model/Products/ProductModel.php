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
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertInOutwardData()
	{
		$discountArray = array();
		$discountTypeArray = array();
		$qtyArray = array();
		$priceArray = array();
		$transactionDateArray = array();
		$companyIdArray = array();
		$productIdArray = array();
		$transactionTypeArray = array();
		
		$discountArray = func_get_arg(0);
		$discountTypeArray = func_get_arg(1);
		$productIdArray = func_get_arg(2);
		$qtyArray = func_get_arg(3);
		$priceArray = func_get_arg(4);
		$transactionDateArray = func_get_arg(5);
		$companyIdArray = func_get_arg(6);
		$transactionTypeArray = func_get_arg(7);
		$billNumberArray = func_get_arg(8);
		$invoiceNumberArray = func_get_arg(9);
		DB::beginTransaction();
		for($data=0;$data<count($productIdArray);$data++)
		{
			$raw = DB::statement("insert into 
			product_trn(transaction_date,transaction_type,qty,price,discount,
			discount_type,product_id,company_id,branch_id,invoice_number,bill_number) 
			values('".$transactionDateArray[$data]."','".$transactionTypeArray[$data]."','".$qtyArray[$data]."','".$priceArray[$data]."','".$discountArray[$data]."','".$discountTypeArray[$data]."','".$productIdArray[$data]."','".$companyIdArray[$data]."',6,'".$invoiceNumberArray[$data]."','".$billNumberArray[$data]."')");
		}
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
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
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
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
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
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
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
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
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
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
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
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
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
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
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
