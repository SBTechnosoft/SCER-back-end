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
		$jfId = func_get_arg(10);
		
		DB::beginTransaction();
		for($data=0;$data<count($productIdArray);$data++)
		{
			$raw = DB::statement("insert into 
			product_trn(transaction_date,transaction_type,qty,price,discount,
			discount_type,product_id,company_id,branch_id,invoice_number,bill_number,jf_id) 
			values('".$transactionDateArray[$data]."','".$transactionTypeArray[$data]."','".$qtyArray[$data]."','".$priceArray[$data]."','".$discountArray[$data]."','".$discountTypeArray[$data]."','".$productIdArray[$data]."','".$companyIdArray[$data]."',6,'".$invoiceNumberArray[$data]."','".$billNumberArray[$data]."','".$jfId."')");
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
	 * @param  product data,key and product id
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
		where product_id = '".$productId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * update transaction data 
	 * returns the status
	*/
	public function updateArrayData()
	{
		$multipleArary = func_get_arg(0);
		$singleArray = func_get_arg(1);
		$jfId = func_get_arg(2);
		$productData="";
		$keyName = "";
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		//get transaction data from jf_id
		DB::beginTransaction();
		$transactionData = DB::select("select 
		transaction_date,
		invoice_number,
		bill_number,
		company_id,
		branch_id,
		product_id
		from product_trn 
		where deleted_at='0000-00-00 00:00:00'
		and jf_id = '".$jfId."'");
		DB::commit();
		if(count($transactionData)==0)
		{
			return $exceptionArray['404'];
		}
		
		if(!array_key_exists('transaction_date',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->transaction_date."',";
			$keyName =$keyName."transaction_date,";
		}
		if(!array_key_exists('company_id',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->company_id."',";
			$keyName =$keyName."company_id,";
		}
		if(!array_key_exists('bill_number',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->bill_number."',";
			$keyName =$keyName."bill_number,";
		}
		if(!array_key_exists('invoice_number',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->invoice_number."',";
			$keyName =$keyName."invoice_number,";
		}
		if(!array_key_exists('branch_id',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->branch_id."',";
			$keyName =$keyName."branch_id,";
		}
		for($data=0;$data<count($singleArray);$data++)
		{
			$productData = $productData."'".$singleArray[array_keys($singleArray)[$data]]."',";
			$keyName =$keyName.array_keys($singleArray)[$data].",";
		}
	
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//delete existing data and then insert new data
		DB::beginTransaction();
		$raw = DB::statement("update product_trn 
		set deleted_at='".$mytime."'
		where jf_id='".$jfId."'");
		DB::commit();
		
		if($raw==1)
		{
			//insert data
			for($arrayData=0;$arrayData<count($multipleArary);$arrayData++)
			{
				DB::beginTransaction();
				$transactionResult = DB::statement("insert into product_trn
				(".$keyName."
				discount,
				discount_type,
				price,
				qty,
				product_id,
				updated_at,
				jf_id) 
				values(
				".$productData."
				'".$multipleArary[$arrayData]['discount']."',
				'".$multipleArary[$arrayData]['discount_type']."',
				'".$multipleArary[$arrayData]['price']."',
				'".$multipleArary[$arrayData]['qty']."',
				'".$multipleArary[$arrayData]['product_id']."',
				'".$mytime."',
				'".$jfId."'
				)");  
				DB::commit();
				if($transactionResult==0)
				{
					return $exceptionArray['500'];
				}
			}
			if($transactionResult==1)
			{
				return $exceptionArray['200'];
			}
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * update array data/simple data 
	 * @param  
	 * returns the status
	*/
	public function updateTransactionData()
	{
		$productTransactionData = func_get_arg(0);
		$jfId = func_get_arg(1);
		$inOutWardData = func_get_arg(2);
		$arrayDataFlag=0;
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		
		if(array_key_exists(0,$productTransactionData))
		{
			$arrayDataFlag=1;
		}
		//only array exists
		if($arrayDataFlag==1)
		{
			//get transaction data from jf_id
			DB::beginTransaction();
			$transactionData = DB::select("select 
			transaction_date,
			invoice_number,
			bill_number,
			company_id,
			branch_id,
			product_id
			from product_trn 
			where deleted_at='0000-00-00 00:00:00'
			and jf_id = '".$jfId."'");
			DB::commit();
			if(count($transactionData)==0)
			{
				return $exceptionArray['404'];
			}	
			
			//delete existing data and then insert new data
			DB::beginTransaction();
			$raw = DB::statement("update product_trn 
			set deleted_at='".$mytime."'
			where jf_id='".$jfId."'");
			DB::commit();
			if($raw==1)
			{
				for($arrayData=0;$arrayData<count($productTransactionData);$arrayData++)
				{
					DB::beginTransaction();
					$transactionResult = DB::statement("insert into product_trn
					(transaction_date,
					transaction_type,
					invoice_number,
					bill_number,
					company_id,
					branch_id,
					discount,
					discount_type,
					price,
					qty,
					product_id,
					updated_at,
					jf_id) 
					values(
					'".$transactionData[0]->transaction_date."',
					'".$inOutWardData."',
					'".$transactionData[0]->invoice_number."',
					'".$transactionData[0]->bill_number."',
					'".$transactionData[0]->company_id."',
					'".$transactionData[0]->branch_id."',
					'".$productTransactionData[$arrayData]['discount']."',
					'".$productTransactionData[$arrayData]['discount_type']."',
					'".$productTransactionData[$arrayData]['price']."',
					'".$productTransactionData[$arrayData]['qty']."',
					'".$productTransactionData[$arrayData]['product_id']."',
					'".$mytime."',
					'".$jfId."')");  
					DB::commit();
					if($transactionResult==0)
					{
						return $exceptionArray['500'];
					}
				}
				if($transactionResult==1)
				{
					return $exceptionArray['200'];
				}
			}
			else
			{
				return $exceptionArray['500'];
			}
		}
		else
		{
			for($data=0;$data<count($productTransactionData);$data++)
			{
				$keyValueString = $keyValueString.array_keys($productTransactionData)[$data]."='".$productTransactionData[array_keys($productTransactionData)[$data]]."',";
			}
			
			DB::beginTransaction();
			$transactionResult = DB::statement("update product_trn
			set ".$keyValueString."
			updated_at='".$mytime."'
			where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if($transactionResult==0)
			{
				return $exceptionArray['500'];
			}
			else
			{
				return $exceptionArray['200'];
			}
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
