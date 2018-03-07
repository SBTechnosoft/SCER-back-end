<?php
namespace ERP\Model\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Entities\ProductArray;
use TCPDFBarcode;
use ERP\Model\Settings\SettingModel;
use stdClass;
use ERP\Core\Products\Validations\ProductValidate;
/**
 * @author reema Patel<reema.p@siliconbrain.in>
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
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$mytime = Carbon\Carbon::now();
		
		$getProductData = array();
		$getproductKey = array();
		$getProductData = func_get_arg(0);
		$getProductKey = func_get_arg(1);
		$documentData = func_get_arg(2);

		$productData="";
		$keyName = "";
		for($data=0;$data<count($getProductData);$data++)
		{
			if(strcmp('product_code',$getProductKey[$data])==0)
			{
				$productCode = $getProductData[$data];
			}
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
		if($raw==1)
		{
			DB::beginTransaction();
			$productId = DB::connection($databaseName)->select("select 
			product_id 
			from product_mst 
			order by product_id desc limit 1");
			DB::commit();
			if(is_array($documentData))
			{
				if(count($documentData)!=0)
				{
					//document-data save to database
					$documentResult = $this->saveProductDocument($documentData,$productId[0]->product_id);
					if(strcmp($documentResult,$exceptionArray['500'])==0)
					{
						return $exceptionArray['500'];
					}
				}
			}
			//get constant array
			$constantArray = $constantDatabase->constantVariable();
			$path = $constantArray['productBarcode'];
			
			//make unique name of barcode svg image
			$dateTime = date("d-m-Y h-i-s");
			$convertedDateTime = str_replace(" ","-",$dateTime);
			$splitDateTime = explode("-",$convertedDateTime);
			$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
			$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".svg";
			$documentPath = $path.$documentName;
			
			//get barcode-size
			$settingType = 'barcode';
			$settingModel = new SettingModel();
			$settingData = $settingModel->getParticularTypeData($settingType);
			$decodedSettingData = json_decode($settingData);
			if(strcmp($settingData,$exceptionArray['204'])==0)
			{
				$width = $constantArray['barcodeWidth'];
				$height = $constantArray['barcodeHeight'];
			}
			else
			{
				$decodedSetting = json_decode($decodedSettingData[0]->setting_data);
				$width = $decodedSetting->barcode_width;
				$height =$decodedSetting->barcode_height;
			}
			//insert barcode image
			$barcodeobj = new TCPDFBarcode($productCode, 'C128','C');
			file_put_contents($documentPath,$barcodeobj->getBarcodeSVGcode($width ,$height, 'black'));
			
			//update document-data into database
			DB::beginTransaction();
			$documentStatus = DB::connection($databaseName)->statement("update
			product_mst set document_name='".$documentName."', document_format='svg',updated_at='".$mytime."'
			where deleted_at='0000-00-00 00:00:00' and product_id='".$productId[0]->product_id."'");
			DB::commit();
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * insert product-document data 
	 * @param  array
	 * returns the status
	*/
	public function saveProductDocument($documentData,$productId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$constantArray = $constantDatabase->constantVariable();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();

		$documentCount = count($documentData);
		for($documentArray=0;$documentArray<$documentCount;$documentArray++)
		{
			//insert documents in product_doc_dtl table
			DB::beginTransaction();
			$documentResult = DB::connection($databaseName)->statement("insert into product_doc_dtl(
			document_name,
			document_size,
			document_format,
			product_id) 
			values('".$documentData[$documentArray][0]."',
			".$documentData[$documentArray][1].",
			'".$documentData[$documentArray][2]."',
			".$productId.")");
			DB::commit();

			if(strcmp($documentData[$documentArray][3],$constantArray['productDocumentUrl']."CoverImage/")==0)
			{
				$mytime = Carbon\Carbon::now();
				//update document-data in product-mst
				DB::beginTransaction();
				$productResult = DB::connection($databaseName)->statement("update
				product_mst set 
				product_cover_id=(select document_id from product_doc_dtl order by document_id desc limit 1) 
				where product_id='".$productId."'");
				DB::commit();
			}
		}
		if($documentResult!=1)
		{
			return $exceptionArray['500'];
		}
		else
		{
			return $exceptionArray['200'];
		}
	}

	/**
	 * insert batch data 
	 * @param  array
	 * returns the status
	*/
	public function insertBatchData()
	{
		//database selection
		$database = "";
		$productDetail = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get constant array
		$constantArray = $constantDatabase->constantVariable();
		$path = $constantArray['productBarcode'];
		
		//get barcode-size
		$settingType = 'barcode';
		$settingModel = new SettingModel();
		$settingData = $settingModel->getParticularTypeData($settingType);
		$decodedSettingData = json_decode($settingData);
		if(strcmp($settingData,$exceptionArray['204'])==0)
		{
			$width = $constantArray['barcodeWidth'];
			$height = $constantArray['barcodeHeight'];
		}
		else
		{
			$decodedSetting = json_decode($decodedSettingData[0]->setting_data);
			$width = $decodedSetting->barcode_width;
			$height =$decodedSetting->barcode_height;
		}
		
		$mytime = Carbon\Carbon::now();
		$getProductData = array();
		$getErrorArray = array();
		$getproductKey = array();
		$getProductData = func_get_arg(0);
		$getProductKey = func_get_arg(1);
		$getErrorArray = func_get_arg(2);
		
		$getErrorCount = count($getErrorArray);
		for($dataArray=0;$dataArray<count($getProductData);$dataArray++)
		{
			$productData="";
			$productCode='';
			$keyName = "";
			for($data=0;$data<count($getProductData[$dataArray]);$data++)
			{
				if(strcmp('product_code',$getProductKey[$dataArray][$data])==0)
				{
					$getProductData[$dataArray][$data];
					$productCode = $getProductData[$dataArray][$data];
					$index = $data;
				}
				if(strcmp('company_id',$getProductKey[$dataArray][$data])==0)
				{
					$companyId = $getProductData[$dataArray][$data];
				}
			}
			$indexNumber=0;
			
			//check product-code
			$productCodeResult = $this->batchRepeatProductCodeValidate($productCode,$companyId,$indexNumber);
			$getProductData[$dataArray][$index] = $productCodeResult;
			
			for($data=0;$data<count($getProductData[$dataArray]);$data++)
			{
				if($data == (count($getProductData[$dataArray])-1))
				{
					$productData = $productData."'".$getProductData[$dataArray][$data]."'";
					$keyName =$keyName.$getProductKey[$dataArray][$data];
				}
				else
				{
					$productData = $productData."'".$getProductData[$dataArray][$data]."',";
					$keyName = $keyName.$getProductKey[$dataArray][$data].",";
				}
			}
			
			//make unique name of barcode svg image
			$dateTime = date("d-m-Y h-i-s");
			$convertedDateTime = str_replace(" ","-",$dateTime);
			$splitDateTime = explode("-",$convertedDateTime);
			$combineDateTime = $splitDateTime[0].$splitDateTime[1].$dataArray.$splitDateTime[2].$splitDateTime[3].$data.$splitDateTime[4].$splitDateTime[5];
			$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".svg";
			$documentPath = $path.$documentName;
			
			//insert barcode image
			$barcodeobj = new TCPDFBarcode($productCodeResult, 'C128','C');
			file_put_contents($documentPath,$barcodeobj->getBarcodeSVGcode($width ,$height, 'black'));
			
			//insert batch of product data	
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into product_mst(".$keyName.",document_name,document_format) 
			values (".$productData.",'".$documentName."','svg')");
			DB::commit();
		}
		
		if($raw==1)
		{
			if(count($getErrorArray)==0)
			{
				return $exceptionArray['200'];
			}
			else
			{
				return json_encode($getErrorArray);
			}
		}
		else
		{
			if(count($getErrorArray)==0)
			{
				return $exceptionArray['500'];
			}
			else
			{
				return json_encode($getErrorArray);
			}
		}
		
	}
	
	/**
     * repeat product-code validation with database 
     * product-code,company-id and index-number
     * @return product-code
     */	
	public function batchRepeatProductCodeValidate($productCode,$companyId,$indexNumber)
	{
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$productValidate = new ProductValidate();
		$validationResult = $productValidate->productCodeValidate($companyId,$productCode);
		if(strcmp($validationResult,$exceptionArray['200'])!=0)
		{
			
			$indexNumber= $indexNumber+1;
			if($indexNumber!=1 && $indexNumber<=10)
			{
				$productCode = substr_replace($productCode,'', -1);
			}
			if($indexNumber>=11)
			{
				$productCode = substr_replace($productCode,'', -2);
			}
			$newProductCode = $productCode.$indexNumber;
			
			$result = $this->batchRepeatProductCodeValidate($newProductCode,$companyId,$indexNumber);
			return $result;
		}
		else
		{
			return $productCode;
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
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
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
		$vendorId = func_get_arg(13);
		
		if(strcmp($transactionTypeArray[0],'Inward')==0)
		{		
			$arrayData = array();
			$arrayData['billNumber'] = $billNumberArray[0];
			$arrayData['transactionType'] = $transactionTypeArray[0];
			$arrayData['companyId'] = $companyIdArray[0];
		}
		$flag=0;
		$totalPrice=0;
		$arrayData['inventory'] = array();
		for($data=0;$data<count($productIdArray);$data++)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into 
			product_trn(transaction_date,
			transaction_type,
			qty,
			price,
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
			DB::commit();
			
			$totalPrice = $totalPrice+$priceArray[$data];
			
			if(strcmp($transactionTypeArray[$data],'Inward')==0)
			{
				$flag=1;
				$arrayData['inventory'][$data] = array();
				$arrayData['inventory'][$data]['productId'] = $productIdArray[$data];
				$arrayData['inventory'][$data]['discount'] = $discountArray[$data];
				$arrayData['inventory'][$data]['discountType'] = $discountTypeArray[$data];
				$arrayData['inventory'][$data]['price'] = $priceArray[$data];
				$arrayData['inventory'][$data]['qty'] = $qtyArray[$data];
			}
		}
		$grandTotal = $totalPrice+$taxArray[0];
		if($flag==1)
		{
			$encodedJsonArray = json_encode($arrayData);
			
			//get purchase type from journal
			DB::beginTransaction();
			$journalData = DB::connection($databaseName)->select("select
			ledger_id,
			journal_type 
			from journal_dtl 
			where deleted_at='0000-00-00 00:00:00' and jf_id='".$jfId."'");
			DB::commit();
			// $purchaseType = $journalData[0]->journal_type;
			
			$ledgerData = array();
			// $clientName="";
			
			for($ledgerIdArray=0;$ledgerIdArray<count($journalData);$ledgerIdArray++)
			{
				//get ledger-group from ledger
				DB::beginTransaction();
				$ledgerData[$ledgerIdArray] = DB::connection($databaseName)->select("select
				ledger_name,
				ledger_group_id
				from ledger_mst
				where deleted_at='0000-00-00 00:00:00' and ledger_id='".$journalData[$ledgerIdArray]->ledger_id."'");
				DB::commit();
				
				// if($ledgerData[$ledgerIdArray][0]->ledger_group_id==31 || $ledgerData[$ledgerIdArray][0]->ledger_group_id==32)
				// {
					// $clientName = $ledgerData[$ledgerIdArray][0]->ledger_name;
				// }
				if($ledgerData[$ledgerIdArray][0]->ledger_group_id==26)
				{
					$purchaseType = $ledgerData[$ledgerIdArray][0]->ledger_name;
				}
			}
			$RequestUri = explode("/", $_SERVER['REQUEST_URI']);
			if(strcmp($RequestUri[1],"accounting")==0 && strcmp($RequestUri[2],"purchase-bills")!=0)
			{
				DB::beginTransaction();
				$purchaseBill = DB::connection($databaseName)->statement("insert into 
				purchase_bill(
				vendor_id,
				product_array,
				bill_number,
				total,
				tax,
				grand_total,
				transaction_type,
				transaction_date,
				company_id,
				jf_id) 
				values(
				'".$vendorId."',
				'".$encodedJsonArray."',
				'".$billNumberArray[0]."',
				'".$totalPrice."',
				'".$taxArray[0]."',
				'".$grandTotal."',
				'".$purchaseType."',
				'".$transactionDateArray[0]."',
				'".$companyIdArray[0]."',
				'".$jfId."')");
				DB::commit();
				if($purchaseBill!=1)
				{
					return $exceptionArray['500'];
				}
			}
		}
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
	public function updateData($productData,$key,$productId,$documentData)
	{
		$productCodeFlag=0;
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($productData);$data++)
		{
			if(strcmp('product_code',$key[$data])==0)
			{
				$productCodeFlag=1;
				$productCode = $productData[$data];
			}
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
			if(is_array($documentData))
			{
				if(count($documentData)!=0)
				{
					//document-data save to database
					$documentResult = $this->saveProductDocument($documentData,$productId);
					if(strcmp($documentResult,$exceptionArray['500'])==0)
					{
						return $exceptionArray['500'];
					}
				}
			}
			if($productCodeFlag==1)
			{
				//get constant array
				$constantArray = $constantDatabase->constantVariable();
				$path = $constantArray['productBarcode'];
				
				//make unique name of barcode svg image
				$dateTime = date("d-m-Y h-i-s");
				$convertedDateTime = str_replace(" ","-",$dateTime);
				$splitDateTime = explode("-",$convertedDateTime);
				$combineDateTime = $splitDateTime[0].$splitDateTime[1].$splitDateTime[2].$splitDateTime[3].$splitDateTime[4].$splitDateTime[5];
				$documentName = $combineDateTime.mt_rand(1,9999).mt_rand(1,9999).".svg";
				$documentPath = $path.$documentName;
				
				//get barcode-size
				$settingType = 'barcode';
				$settingModel = new SettingModel();
				$settingData = $settingModel->getParticularTypeData($settingType);
				$decodedSettingData = json_decode($settingData);
				if(strcmp($settingData,$exceptionArray['204'])==0)
				{
					$width = $constantArray['barcodeWidth'];
					$height = $constantArray['barcodeHeight'];
				}
				else
				{
					$decodedSetting = json_decode($decodedSettingData[0]->setting_data);
					$width = $decodedSetting->barcode_width;
					$height =$decodedSetting->barcode_height;
				}
				//insert barcode image 
				$barcodeobj = new TCPDFBarcode($productCode, 'C128','C');
				file_put_contents($documentPath,$barcodeobj->getBarcodeSVGcode($width ,$height, 'black'));
				
				//update document-data into database
				DB::beginTransaction();
				$documentStatus = DB::connection($databaseName)->statement("update
				product_mst set document_name='".$documentName."', document_format='svg',updated_at='".$mytime."'
				where deleted_at='0000-00-00 00:00:00' and product_id='".$productId."'");
				DB::commit();
			}
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * update product-batch data 
	 * returns the status/exception-message
	*/
	
	public function updateBatchData($productData,$key,$productId)
	{
		$productCodeFlag=0;
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
		for($data=0;$data<count($productId);$data++)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("update product_mst 
			set ".$keyValueString."updated_at='".$mytime."'
			where product_id = '".$productId[$data]."' and deleted_at='0000-00-00 00:00:00'");
			DB::commit();
		}
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
		echo "hhhhhhhh";
		exit;
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$multipleArary = func_get_arg(0);
		$singleArray = func_get_arg(1);
		$jfId = func_get_arg(2);
		$productData="";
		$productBillData="";
		$keyName = "";
		$billKeyName = "";
		
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
			$productBillData = $productBillData."'".$transactionData[0]->transaction_date."',";
			$billKeyName =$billKeyName."transaction_date,";
		}
		if(!array_key_exists($constantArray['company_id'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->company_id."',";
			$keyName =$keyName."company_id,";
			$productBillData = $productBillData."'".$transactionData[0]->company_id."',";
			$billKeyName =$billKeyName."company_id,";
		}
		if(!array_key_exists($constantArray['bill_number'],$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->bill_number."',";
			$keyName =$keyName."bill_number,";
			$productBillData = $productBillData."'".$transactionData[0]->bill_number."',";
			$billKeyName = $billKeyName."bill_number,";
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
			$productBillData = $productBillData."'".$transactionData[0]->tax."',";
			$billKeyName =$billKeyName."tax,";
		}
		if(!array_key_exists('is_display',$singleArray))
		{
			$productData = $productData."'".$transactionData[0]->is_display."',";
			$keyName =$keyName."is_display,";
		}
		for($data=0;$data<count($singleArray);$data++)
		{
			print_r($singleArray);
			exit;
			$productData = $productData."'".$singleArray[array_keys($singleArray)[$data]]."',";
			$keyName =$keyName.array_keys($singleArray)[$data].",";
			
			$productBillData = $productBillData."'".$singleArray[array_keys($singleArray)[$data]]."',";
			$billKeyName =$billKeyName.array_keys($singleArray)[$data].",";
		}
		exit;
		//delete existing data and then insert new data
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update product_trn 
		set deleted_at='".$mytime."'
		where jf_id='".$jfId."'");
		DB::commit();
		
		//delete existing data from purchase bill and then insert new data
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update purchase_bill 
		set deleted_at='".$mytime."'
		where jf_id='".$jfId."'");
		DB::commit();
		
		// if(strcmp($transactionTypeArray[0],'Inward')==0)
		// {		
			// $arrayData = array();
			// $arrayData['billNumber'] = $billNumberArray[0];
			// $arrayData['transactionType'] = $transactionTypeArray[0];
			// $arrayData['companyId'] = $companyIdArray[0];
		// }
		// $flag=0;
		// $arrayData['inventory'] = array();
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
				if(strcmp($transactionTypeArray[$data],'Inward')==0)
				{
					$flag=1;
					$arrayData['inventory'][$data] = array();
					$arrayData['inventory'][$data]['productId'] = $productIdArray[$data];
					$arrayData['inventory'][$data]['discount'] = $discountArray[$data];
					$arrayData['inventory'][$data]['discountType'] = $discountTypeArray[$data];
					$arrayData['inventory'][$data]['price'] = $priceArray[$data];
					$arrayData['inventory'][$data]['qty'] = $qtyArray[$data];
				}
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
			
			$RequestUri = explode("/", $_SERVER['REQUEST_URI']);
			if(strcmp($RequestUri[1],"accounting")==0 && strcmp($RequestUri[2],"purchase-bills")!=0)
			{
				//delete existing data and then insert new data
				DB::beginTransaction();
				$purchaseBillDataResult = DB::connection($databaseName)->statement("update purchase_bill 
				set deleted_at='".$mytime."'
				where jf_id='".$jfId."'");
				DB::commit();
			}
			if(strcmp($inOutWardData,'Inward')==0)
			{		
				$inventoryArrayData = array();
				$inventoryArrayData['billNumber'] = $transactionData[0]->bill_number;
				$inventoryArrayData['transactionType'] = $inOutWardData;
				$inventoryArrayData['companyId'] = $transactionData[0]->company_id;
			}
			$flag=0;
			$inventoryArrayData['inventory'] = array();
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
					
					if(strcmp($inOutWardData,'Inward')==0)
					{
						$flag=1;
						$inventoryArrayData['inventory'][$arrayData] = array();
						$inventoryArrayData['inventory'][$arrayData]['productId'] = $productTransactionData[$arrayData]['product_id'];
						$inventoryArrayData['inventory'][$arrayData]['discount'] = $productTransactionData[$arrayData]['discount'];
						$inventoryArrayData['inventory'][$arrayData]['discountType'] = $productTransactionData[$arrayData]['discount_type'];
						$inventoryArrayData['inventory'][$arrayData]['price'] = $productTransactionData[$arrayData]['price'];
						$inventoryArrayData['inventory'][$arrayData]['qty'] = $productTransactionData[$arrayData]['qty'];
					}
					if($transactionResult==0)
					{
						return $exceptionArray['500'];
					}
				}
				if($flag==1)
				{
					$encodedJsonArray = json_encode($inventoryArrayData);
					
					//get purchase type from journal
					DB::beginTransaction();
					$journalData = DB::connection($databaseName)->select("select
					ledger_id,
					journal_type 
					from journal_dtl 
					where deleted_at='0000-00-00 00:00:00' and jf_id='".$jfId."'");
					DB::commit();
					$purchaseType = $journalData[0]->journal_type;
					
					$ledgerData = array();
					// $clientName="";
					for($ledgerIdArray=0;$ledgerIdArray<count($journalData);$ledgerIdArray++)
					{
						//get ledger-group from ledger
						DB::beginTransaction();
						$ledgerData[$ledgerIdArray] = DB::connection($databaseName)->select("select
						ledger_id,
						ledger_name,
						ledger_group_id
						from ledger_mst
						where deleted_at='0000-00-00 00:00:00' and ledger_id='".$journalData[$ledgerIdArray]->ledger_id."'");
						DB::commit();
						
						if($ledgerData[$ledgerIdArray][0]->ledger_group_id==31)
						{
							$vendorId = $ledgerData[$ledgerIdArray][0]->ledger_id;
							break;
						}
					}
					$RequestUri = explode("/", $_SERVER['REQUEST_URI']);
					if(strcmp($RequestUri[1],"accounting")==0 && strcmp($RequestUri[2],"purchase-bills")!=0)
					{
						DB::beginTransaction();
						$purchaseBill = DB::connection($databaseName)->statement("insert into 
						purchase_bill(
						vendor_id,
						product_array,
						bill_number,
						total,
						tax,
						grand_total,
						transaction_type,
						transaction_date,
						company_id,
						jf_id) 
						values(
						'".$vendorId."',
						'".$encodedJsonArray."',
						'".$transactionData[0]->bill_number."',
						'100',
						'".$transactionData[0]->tax."',
						'200',
						'".$purchaseType."',
						'".$transactionData[0]->transaction_date."',
						'".$transactionData[0]->company_id."',
						'".$jfId."')");
						DB::commit();
						if($purchaseBill!=1)
						{
							return $exceptionArray['500'];
						}
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
			
			$RequestUri = explode("/", $_SERVER['REQUEST_URI']);
			if(strcmp($RequestUri[1],"accounting")==0 && strcmp($RequestUri[2],"purchase-bills")!=0)
			{
				DB::beginTransaction();
				$billTransactionResult = DB::connection($databaseName)->statement("update purchase_bill
				set ".$keyValueString."
				updated_at='".$mytime."'
				where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		margin,
		margin_flat,
		mrp,
		igst,
		hsn,
		color,
		size,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
			return $enocodedData;
		}
	}
	
	/**
	 * get document-data as per given product-id
	 * returns error-message/data
	*/
	public function getProductDocumentData($productData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$productDataCount = count($productData);
		for($productArray=0;$productArray<$productDataCount;$productArray++)
		{
			$documentResult = array();
			DB::beginTransaction();
			$documentResult = DB::connection($databaseName)->select("select 
			document_id,
			document_name,
			document_size,
			document_format,
			created_at,
			updated_at,
			deleted_at,
			product_id 
			from product_doc_dtl 
			where deleted_at='0000-00-00 00:00:00' and 
			product_id='".$productData[$productArray]->product_id."'");
			DB::commit();
			if(count($documentResult)!=0)
			{
				foreach ($documentResult as $key => $value) {
					if($productData[$productArray]->product_cover_id==$value->document_id)
					{
						$documentResult[$key]->document_type = "CoverImage";
					}
					else
					{
						$documentResult[$key]->document_type = "";
					}
				}
			}
			$productData[$productArray]->document = $documentResult;
		}
		return $productData;
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
			deleted_at='0000-00-00 00:00:00' and company_id='".$companyId."'");
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		margin,
		margin_flat,
		mrp,
		igst,
		hsn,
		color,
		size,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		margin,
		margin_flat,
		mrp,
		igst,
		hsn,
		color,
		size,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		margin,
		margin_flat,
		mrp,
		igst,
		hsn,
		color,
		size,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		margin,
		margin_flat,
		mrp,
		igst,
		hsn,
		color,
		size,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		mrp,
		igst,
		hsn,
		color,
		size,
		margin,
		margin_flat,
		product_description,
		minimum_stock_level,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
		created_at,
		updated_at,
		deleted_at,
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst where company_id='".$companyId."' and ".$querySet."deleted_at='0000-00-00 00:00:00' 
		order by product_category_id asc");
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
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
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
	 * update client name
	 * returns error-message/status
	*/
	public function updateClientName($clientNameArray,$jfId)
	{
		// database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
	
		$mytime = Carbon\Carbon::now();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->statement("update 
		purchase_bill
		set client_name='".$clientNameArray['client_name']."',
		updated_at='".$mytime."'
		where jf_id='".$jfId."' and
		deleted_at='0000-00-00 00:00:00'");
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
			return $exceptionArray['500'];;
		}
	}
	
	/**
	 * get product_data as per given productCode
	 * returns error-message/status
	*/
	public function getProductCodeData($productCode)
	{
		// database selection
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
		purchase_cgst,
		purchase_sgst,
		purchase_igst,
		mrp,
		igst,
		hsn,
		color,
		size,
		margin,
		margin_flat,
		product_description,
		additional_tax,
		product_type,
		product_menu,
		product_cover_id,
		not_for_sale,
		max_sale_qty,
		best_before_time,
		best_before_type,
		cess_flat,
		cess_percentage,
		document_name,
		document_format,
		minimum_stock_level,
		product_code,
		created_at,
		updated_at,
		deleted_at,
		product_category_id,
		product_group_id,
		branch_id,
		company_id	
		from product_mst 
		where product_code = '".$productCode['productcode'][0]."' and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)!=0)
		{
			//get product-document data
			$productResult = $this->getProductDocumentData($raw);
			$enocodedData = json_encode($productResult);
			return $enocodedData;
		}
		else
		{
			return $exceptionArray['404'];
		}
	}
	
	/**
	 * get product_data as per given company-id
	 * returns error-message/arrayData
	*/
	public function getStockSummaryData($companyId)
	{
		// database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		product_trn_summary_id,
		qty,
		created_at,
		updated_at,
		branch_id,
		company_id,
		product_id
		from product_trn_summary
		where company_id='".$companyId."' and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)!=0)
		{
			return json_encode($raw);
		}
		else
		{
			return $exceptionArray['404'];
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
			DB::beginTransaction();
			$documentResult = DB::connection($databaseName)->statement("update product_doc_dtl 
			set deleted_at='".$mytime."' 
			where product_id=".$productId);
			DB::commit();
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
