<?php
namespace ERP\Model\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Entities\ProductArray;
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
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
		$raw = DB::connection($databaseName)->statement("insert into product_mst(".$keyName.") 
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$discountArray = array();
		$discountValueArray = array();
		$discountTypeArray = array();
		$qtyArray = array();
		$priceArray = array();
		$transactionDateArray = array();
		$companyIdArray = array();
		$productIdArray = array();
		$transactionTypeArray = array();
		
		$discountArray = func_get_arg(0);
		$discountValueArray = func_get_arg(1);
		$discountTypeArray = func_get_arg(2);
		$productIdArray = func_get_arg(3);
		$qtyArray = func_get_arg(4);
		$priceArray = func_get_arg(5);
		$transactionDateArray = func_get_arg(6);
		$companyIdArray = func_get_arg(7);
		$transactionTypeArray = func_get_arg(8);
		$billNumberArray = func_get_arg(9);
		$invoiceNumberArray = func_get_arg(10);
		$jfId = func_get_arg(11);
		$taxArray = func_get_arg(12);
		
		DB::beginTransaction();
		for($data=0;$data<count($productIdArray);$data++)
		{
			$raw = DB::connection($databaseName)->statement("insert into 
			product_trn(transaction_date,
			transaction_type,
			qty,price,
			discount,
			discount_value,
			discount_type,
			product_id,
			company_id,
			branch_id,
			invoice_number,
			bill_number,
			jf_id,
			tax) 
			values('".$transactionDateArray[$data]."',
			'".$transactionTypeArray[$data]."',
			'".$qtyArray[$data]."',
			'".$priceArray[$data]."',
			'".$discountArray[$data]."',
			'".$discountValueArray[$data]."',
			'".$discountTypeArray[$data]."',
			'".$productIdArray[$data]."',
			'".$companyIdArray[$data]."',
			6,
			'".$invoiceNumberArray[$data]."',
			'".$billNumberArray[$data]."',
			'".$jfId."',
			'".$taxArray[$data]."')");
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$productData[$data]."',";
		}
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update product_mst 
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$multipleArary = func_get_arg(0);
		$singleArray = func_get_arg(1);
		$jfId = func_get_arg(2);
		$productData="";
		$keyName = "";
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get transaction data from jf_id
		DB::beginTransaction();
		$transactionData = DB::connection($databaseName)->select("select 
		transaction_date,
		invoice_number,
		tax,
		is_display,
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
		
		if(!array_key_exists($constantArray['transactionDate'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->transaction_date."',";
			$keyName =$keyName."transaction_date,";
		}
		if(!array_key_exists($constantArray['company_id'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->company_id."',";
			$keyName =$keyName."company_id,";
		}
		if(!array_key_exists($constantArray['bill_number'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->bill_number."',";
			$keyName =$keyName."bill_number,";
		}
		if(!array_key_exists($constantArray['invoice_number'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->invoice_number."',";
			$keyName =$keyName."invoice_number,";
		}
		if(!array_key_exists($constantArray['branch_id'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->branch_id."',";
			$keyName =$keyName."branch_id,";
		}
		if(!array_key_exists('tax',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->tax."',";
			$keyName =$keyName."tax,";
		}
		if(!array_key_exists('is_display',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->is_display."',";
			$keyName =$keyName."is_display,";
		}
		for($data=0;$data<count($singleArray);$data++)
		{
			$productData = $productData."'".$singleArray[array_keys($singleArray)[$data]]."',";
			$keyName =$keyName.array_keys($singleArray)[$data].",";
		}
	
		//delete existing data and then insert new data
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update product_trn 
		set deleted_at='".$mytime."'
		where jf_id='".$jfId."'");
		DB::commit();
		if($raw==1)
		{
			//insert data
			for($arrayData=0;$arrayData<count($multipleArary);$arrayData++)
			{
				DB::beginTransaction();
				$transactionResult = DB::connection($databaseName)->statement("insert into product_trn
				(".$keyName."
				discount,
				discount_value,
				discount_type,
				price,
				qty,
				product_id,
				updated_at,
				jf_id) 
				values(
				".$productData."
				'".$multipleArary[$arrayData]['discount']."',
				'".$multipleArary[$arrayData]['discount_value']."',
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
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
			$transactionData = DB::connection($databaseName)->select("select 
			transaction_date,
			invoice_number,
			bill_number,
			company_id,
			tax,
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
			$raw = DB::connection($databaseName)->statement("update product_trn 
			set deleted_at='".$mytime."'
			where jf_id='".$jfId."'");
			DB::commit();
			if($raw==1)
			{
				for($arrayData=0;$arrayData<count($productTransactionData);$arrayData++)
				{
					DB::beginTransaction();
					$transactionResult = DB::connection($databaseName)->statement("insert into product_trn
					(transaction_date,
					transaction_type,
					invoice_number,
					bill_number,
					company_id,
					branch_id,
					tax,
					discount,
					discount_value,
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
					'".$transactionData[0]->tax."',
					'".$productTransactionData[$arrayData]['discount']."',
					'".$productTransactionData[$arrayData]['discount_value']."',
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
			$transactionResult = DB::connection($databaseName)->statement("update product_trn
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
	 * returns error-message/data
	*/
	public function getAllData()
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		margin,
		margin_flat,
		mrp,
		color,
		size,
		product_description,
		additional_tax,
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
	 * get data as per given header data and company-id
	 * returns error-message/data
	*/
	public function getTransactionData($fromDate,$toDate,$headerData,$companyId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$raw = array();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(array_key_exists('productid',$headerData))
		{
			DB::beginTransaction();
			$raw1 = DB::connection($databaseName)->select("select 
			product_trn_id,
			transaction_date,
			transaction_type,
			qty,
			price,
			discount,
			discount_value,
			discount_type,
			is_display,
			invoice_number,
			bill_number,
			tax,
			updated_at,
			created_at,
			company_id,
			branch_id,
			product_id,			
			jf_id	
			from product_trn 
			where (transaction_date BETWEEN '".$fromDate."' AND '".$toDate."') and company_id='".$companyId."' and 
			product_id='".$headerData['productid'][0]."' and 
			deleted_at='0000-00-00 00:00:00' ORDER BY transaction_date,product_trn_id");
			DB::commit();
			$raw = array();
			$raw[0] = $raw1;
			if(count($raw[0])==0)
			{
				return $exceptionArray['204'];
			}
		}
		else
		{
			$keyValueString = "";
			if(array_key_exists("productcategoryid",$headerData))
			{
				$keyValueString = $keyValueString.'product_category_id='.$headerData['productcategoryid'][0].' and ';
			}
			if(array_key_exists("productgroupid",$headerData))
			{
				$keyValueString = $keyValueString.'product_group_id='.$headerData['productgroupid'][0].' and ';
			}
			DB::beginTransaction();
			$productData = DB::connection($databaseName)->select("select 
			product_id from product_mst
			where ".$keyValueString."
			deleted_at='0000-00-00 00:00:00'");
			for($arrayData=0;$arrayData<count($productData);$arrayData++)
			{
				DB::beginTransaction();
				$raw[$arrayData] = DB::connection($databaseName)->select("select 
				product_trn_id,
				transaction_date,
				transaction_type,
				qty,
				price,
				discount,
				discount_value,
				discount_type,
				is_display,
				invoice_number,
				bill_number,
				tax,
				updated_at,
				created_at,
				company_id,
				branch_id,
				product_id,			
				jf_id	
				from product_trn 
				where (transaction_date BETWEEN '".$fromDate."' AND '".$toDate."') and company_id='".$companyId."' and 
				product_id='".$productData[$arrayData]->product_id."' and 
				deleted_at='0000-00-00 00:00:00' ORDER BY transaction_date,product_trn_id");
				DB::commit();
				// if(count($raw[$arrayData])==0)
				// {
					// return $exceptionArray['204'];
				// }
			}
		}
		$enocodedData = json_encode($raw);
		return $enocodedData;
	}
	
	/**
	 * get jfId data from product transaction table
	 * returns error-message/data
	*/
	public function getJfIdProductData($jfId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();		
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_trn_id,
		transaction_date,
		transaction_type,
		qty,
		price,
		discount,
		discount_value,
		discount_type,
		is_display,
		invoice_number,
		bill_number,
		tax,
		updated_at,
		created_at,
		company_id,
		branch_id,
		product_id,			
		jf_id			
		from product_trn where deleted_at='0000-00-00 00:00:00' and jf_id='".$jfId."'");
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
	 * returns error-message/data
	*/
	public function getData($productId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		margin,
		margin_flat,
		mrp,
		color,
		size,
		product_description,
		additional_tax,
		created_at,
		updated_at,
		deleted_at,
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where product_id = '".$productId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * returns error-message/data
	*/
	public function getBCProductData($companyId,$branchId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		margin,
		margin_flat,
		mrp,
		color,
		size,
		product_description,
		additional_tax,
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
	 * returns error-message/data
	*/
	public function getCProductData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		margin,
		margin_flat,
		mrp,
		color,
		size,
		product_description,
		additional_tax,
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
	 * returns error-message/data
	*/
	public function getBProductData($branchId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		margin,
		margin_flat,
		mrp,
		color,
		size,
		product_description,
		additional_tax,
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
	
	/**
	 * get data as per given headerData and companyId 
	 * returns error-message/data
	*/
	public function getProductData($headerData,$companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$productArray = new ProductArray();
		$arrayData = $productArray->productDataArray();
		$arrayValue = $productArray->productValueArray();
		$querySet = "";
		for($data=0;$data<count($arrayData);$data++)
		{
			if(array_key_exists($arrayData[$data],$headerData))
			{
				$key[$data] = $arrayValue[$data];
				$value[$data] = $headerData[$arrayData[$data]][0];
				$querySet = $querySet.$key[$data]." = '".$value[$data]."' and ";
			}
		}
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id,
		product_name,
		measurement_unit,
		is_display,
		purchase_price,
		wholesale_margin,
		wholesale_margin_flat,
		semi_wholesale_margin,
		vat,
		mrp,
		color,
		size,
		margin,
		margin_flat,
		product_description,
		additional_tax,
		created_at,
		updated_at,
		deleted_at,
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id='".$companyId."' and ".$querySet."deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get product_id as per given companyId and productName
	 * returns error-message/data
	*/
	// public function getProductName($productName,$companyId)
	// {
		// database selection
		// $database = "";
		// $constantDatabase = new ConstantClass();
		// $databaseName = $constantDatabase->constantDatabase();
		
		// DB::beginTransaction();		
		// $raw = DB::connection($databaseName)->select("select 
		// product_id
		// from product_mst 
		// where company_id='".$companyId."' and
		// product_name = '".$productName."' and
		// deleted_at='0000-00-00 00:00:00'");
		// DB::commit();
		
		// get exception message
		// $exception = new ExceptionMessage();
		// $exceptionArray = $exception->messageArrays();
		// if(count($raw)==0)
		// {
			// return $exceptionArray['404'];
		// }
		// else
		// {
			// return $raw;
		// }
	// }
	
	/**
	 * get product_id as per given companyId and productCode
	 * returns error-message/status
	*/
	public function getProductCode($companyId,$productCode)
	{
		// database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_id
		from product_mst 
		where company_id='".$companyId."' and
		product_code = '".$productCode."' and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $raw;
		}
	}
	
	/**
	 * delete data
	 * returns error-message/status
	*/
	public function deleteData($productId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::connection($databaseName)->statement("update product_mst 
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
