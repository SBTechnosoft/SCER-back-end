<?php
namespace ERP\Model\ProductGroups;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
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
		where product_group_id = '".$productGrpId."' and deleted_at='0000-00-00 00:00:00'");
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
		product_group_id,
		product_group_name,
		product_group_description,
		is_display,
		product_group_parent_id,
		created_at,
		updated_at
		from product_group_mst where deleted_at='0000-00-00 00:00:00'");
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
	public function getData($productGrpId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		product_group_id,
		product_group_name,
		product_group_description,
		is_display,
		product_group_parent_id,
		created_at,
		updated_at
		from product_group_mst where product_group_id = '".$productGrpId."' and deleted_at='0000-00-00 00:00:00'");
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
	public function deleteData($productGrpId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update product_group_mst 
		set deleted_at='".$mytime."'
		where product_group_id = '".$productGrpId."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$productGrp = DB::statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_group_id = '".$productGrpId."'");
			if($productGrp==1)
			{
				DB::beginTransaction();
				$mytime = Carbon\Carbon::now();
				$productGrpRaw = DB::statement("update product_group_mst 
				set deleted_at='".$mytime."'
				where product_group_parent_id='".$productGrpId."'");
				DB::commit();
				if($productGrpRaw==1)
				{
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
		else
		{
			return $fileSizeArray['500'];
		}
	}
}
