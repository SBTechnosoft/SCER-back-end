<?php
namespace ERP\Model\ProductGroups;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
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
		$raw = DB::connection($databaseName)->statement("insert into product_group_mst(".$keyName.") 
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productGrpData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productGrpData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update product_group_mst 
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
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
		$mytime = Carbon\Carbon::now();
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::connection($databaseName)->statement("update product_group_mst 
		set deleted_at='".$mytime."'
		where product_group_id = '".$productGrpId."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$productGrp = DB::connection($databaseName)->statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_group_id = '".$productGrpId."'");
			if($productGrp==1)
			{
				$groupId = $this->groupDelete($productGrpId);
				while(strcmp($groupId,'stop')!=0)
				{
					$groupId = $this->groupDelete($groupId);
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
	
	public function groupDelete($groupId)
	{
		$mytime = Carbon\Carbon::now();
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select product_group_id 
		from product_group_mst 
		where product_group_parent_id = '".$groupId."' and
		deleted_at = '0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "stop";
		}
		else
		{
			DB::beginTransaction();
			$productCatRaw = DB::connection($databaseName)->statement("update product_group_mst 
			set deleted_at='".$mytime."'
			where product_group_parent_id='".$groupId."'");
			DB::commit();
			
			DB::beginTransaction();
			$productGrpRaw = DB::connection($databaseName)->statement("update product_mst 
			set deleted_at='".$mytime."'
			where product_group_id='".$raw[0]->product_group_id."'");
			DB::commit();
			return $raw[0]->product_group_id;
		}
	}
}
