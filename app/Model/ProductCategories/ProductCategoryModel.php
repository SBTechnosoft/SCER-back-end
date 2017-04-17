<?php
namespace ERP\Model\ProductCategories;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
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
		$raw = DB::connection($databaseName)->statement("insert into product_category_mst(".$keyName.") 
		values(".$productCatData.")");
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
	 * insert batch of data 
	 * @param  array
	 * returns the status
	*/
	public function insertBatchData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$getProductCatData = array();
		$getProductCatKey = array();
		$getProductCatData = func_get_arg(0);
		$getProductCatKey = func_get_arg(1);
		$productCatDetail = "";
		
		for($dataArray=0;$dataArray<count($getProductCatData);$dataArray++)
		{
			$productCatData="";
			$keyName = "";
			for($data=0;$data<count($getProductCatData[$dataArray]);$data++)
			{
				if($data == (count($getProductCatData[$dataArray])-1))
				{
					$productCatData = $productCatData."'".$getProductCatData[$dataArray][$data]."'";
					$keyName =$keyName.$getProductCatKey[$dataArray][$data];
				}
				else
				{
					$productCatData = $productCatData."'".$getProductCatData[$dataArray][$data]."',";
					$keyName =$keyName.$getProductCatKey[$dataArray][$data].",";
				}
			}
			
			$finalProductData = "(".$productCatData.")";
			$keyName = $keyName;
			if($dataArray==count($getProductCatData)-1)
			{
				$productCatDetail = $productCatDetail.$finalProductData;
			}
			else
			{
				$productCatDetail = $productCatDetail.$finalProductData.",";
			}
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into product_category_mst(".$keyName.") 
		values".$productCatDetail);
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
	 * update data 
	 * @param productCatData,$key of productCatData,productCatId
	 * returns the status
	*/
	public function updateData($productCatData,$key,$productCatId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productCatData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productCatData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update product_category_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where product_category_id ='".$productCatId."' and deleted_at='0000-00-00 00:00:00'");
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_category_id,
		product_category_name,
		product_category_description,
		is_display,
		product_parent_category_id,
		created_at,
		updated_at
		from product_category_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given product_Cat_Id
	 * @param $productCategoryId
	 * returns the status
	*/
	public function getData($productCategoryId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		product_category_id,
		product_category_name,
		product_category_description,
		is_display,
		product_parent_category_id,
		created_at,
		updated_at
		from product_category_mst where product_category_id = '".$productCategoryId."' and deleted_at='0000-00-00 00:00:00'");
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
	
	//delete
	public function deleteData($productCatId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::connection($databaseName)->statement("update product_category_mst 
		set deleted_at='".$mytime."'
		where product_category_id = '".$productCatId."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$product = DB::connection($databaseName)->statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_category_id = '".$productCatId."'");
			if($product==1)
			{
				$categoryId = $this->categoryDelete($productCatId);
				while(strcmp($categoryId,'stop')!=0)
				{
					$categoryId = $this->categoryDelete($categoryId);
				}
				return $exceptionArray['200'];
			}
			else
			{
				return $exceptionArray['500'];
			}
			
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	public function categoryDelete($categoryId)
	{
		$mytime = Carbon\Carbon::now();
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select product_category_id 
		from product_category_mst 
		where product_parent_category_id = '".$categoryId."' and
		deleted_at = '0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "stop";
		}
		else
		{
			DB::beginTransaction();
			$productCatRaw = DB::connection($databaseName)->statement("update product_category_mst 
			set deleted_at='".$mytime."'
			where product_parent_category_id='".$categoryId."'");
			DB::commit();
			
			DB::beginTransaction();
			$productCatRaww = DB::connection($databaseName)->statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_category_id='".$raw[0]->product_category_id."'");
			DB::commit();
			return $raw[0]->product_category_id;
		}
	}
}
