<?php
namespace ERP\Model\Accounting\PurchaseBills;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Accounting\Journals\JournalModel;
// use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
// use ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers\InvoiceController;
use Illuminate\Container\Container;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PurchaseBillModel extends Model
{
	protected $table = 'purchase_bill';
	
	/**
	 * insert data with document
	 * @param  array
	 * returns the status
	*/
	public function insertData($getData,$keyName,$documentArray)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$purchaseData="";
		$keyData="";
		for($data=0;$data<count($getData);$data++)
		{
			if($data == (count($getData)-1))
			{
				$purchaseData = $purchaseData."'".$getData[$data]."'";
				$keyData =$keyData.$keyName[$data];
			}
			else
			{
				$purchaseData = $purchaseData."'".$getData[$data]."',";
				$keyData =$keyData.$keyName[$data].",";
			}
		}
		DB::beginTransaction();
		$purchaseBillResult = DB::connection($databaseName)->statement("insert into purchase_bill(".$keyData.") 
		values(".$purchaseData.")");
		DB::commit();
		if(count($documentArray)!=0)
		{
			//get latest purchase-id from database
			DB::beginTransaction();
			$purchaseIdResult = DB::connection($databaseName)->select("SELECT 
			max(purchase_id) purchase_id
			FROM purchase_bill where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			$documentCount = count($documentArray);
			//document insertion
			for($documentData=0;$documentData<$documentCount;$documentData++)
			{
				DB::beginTransaction();
				$purchaseBillResult = DB::connection($databaseName)->statement("insert into purchase_doc_dtl
				(document_name,
				document_size,
				document_format,
				purchase_id) 
				values(
				'".$documentArray[$documentData]['document_name']."',
				".$documentArray[$documentData]['document_size'].",
				'".$documentArray[$documentData]['document_format']."',
				".$purchaseIdResult[0]->purchase_id.")");
				DB::commit();
			}
		}
		if($purchaseBillResult==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * insert data with document
	 * @param  array
	 * returns the status
	*/
	public function udpateData($getData,$keyName,$documentArray,$purchaseId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($getData);$data++)
		{
			$keyValueString=$keyValueString.$keyName[$data]."='".$getData[$data]."',";
		}
		DB::beginTransaction();
		$purchaseBillResult = DB::connection($databaseName)->statement("update purchase_bill
		set ".$keyValueString."updated_at='".$mytime."' where purchase_id='".$purchaseId."'");
		DB::commit();
		$documentCount = count($documentArray);
		if($documentCount!=0)
		{
			//document insertion
			for($documentData=0;$documentData<$documentCount;$documentData++)
			{
				DB::beginTransaction();
				$purchaseBillResultDoc = DB::connection($databaseName)->statement("insert into purchase_doc_dtl
				(document_name,
				document_size,
				document_format,
				purchase_id) 
				values(
				'".$documentArray[$documentData]['document_name']."',
				".$documentArray[$documentData]['document_size'].",
				'".$documentArray[$documentData]['document_format']."',
				".$purchaseId.")");
				DB::commit();
			}
		}
		if($purchaseBillResult==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * get purchase bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getPurchaseBillData($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(array_key_exists('previouspurchaseid',$headerData))
		{
			$previousPurchaseId = $this->getPreviousPurchaseId($headerData);
			return $previousPurchaseId;
		}
		else if(array_key_exists('nextpurchaseid',$headerData))
		{
			$nextPurchaseIdData = $this->getNextPurchaseId($headerData);
			return $nextPurchaseIdData;
			
		}
		else if(array_key_exists('operation',$headerData))
		{
			$firstLastPurchaseData = $this->getFirstLastPurchaseBillData($headerData);
			return $firstLastPurchaseData;
		}
		else if(array_key_exists('purchasebillid',$headerData))
		{
			//get data as per given purchase-id
			DB::beginTransaction();
			$purchaseIdDataResult = DB::connection($databaseName)->select("select 
			purchase_id,
			vendor_id,
			product_array,
			bill_number,
			total,
			tax,
			grand_total,
			payment_mode,
			bank_name,
			check_number,
			total_discounttype,
			total_discount,
			advance,
			extra_charge,
			balance,
			transaction_type,
			transaction_date,
			bill_type,
			remark,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from purchase_bill 
			where bill_type='purchase_bill' and
			purchase_id = '".$headerData['purchasebillid'][0]."' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			if(count($purchaseIdDataResult)==0)
			{
				return $exceptionArray['204'];
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($purchaseIdDataResult);
				return $purchaseDataResult;
			}
		}
		else
		{
			//get all the purchase-bill data
			DB::beginTransaction();
			$purchaseIdDataResult = DB::connection($databaseName)->select("select 
			purchase_id,
			vendor_id,
			product_array,
			bill_number,
			total,
			tax,
			grand_total,
			payment_mode,
			bank_name,
			check_number,
			total_discounttype,
			total_discount,
			advance,
			extra_charge,
			balance,
			transaction_type,
			transaction_date,
			bill_type,
			remark,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from purchase_bill 
			where bill_type='purchase_bill' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			if(count($purchaseIdDataResult)==0)
			{
				return $exceptionArray['204'];
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($purchaseIdDataResult);
				return $purchaseDataResult;
			}
		}
	}
	
	/**
	 * get next purchase-bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getNextPurchaseId($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$purchaseId = $headerData['nextpurchaseid'][0]+1;
		$result = $this->getPurchasePreviousNextData($headerData,$purchaseId);
		if(count($result)==0)
		{
			DB::beginTransaction();
			$nextDescId = DB::connection($databaseName)->select("select 
			purchase_id
			from purchase_bill 
			where bill_type='purchase_bill' and
			company_id = '".$headerData['companyid'][0]."' and
			deleted_at='0000-00-00 00:00:00' 
			order by purchase_id desc limit 1");
			DB::commit();
			if($purchaseId>$nextDescId[0]->purchase_id)
			{
				return $exceptionArray['204'];
			}
			else
			{
				for($arrayData=$purchaseId+1;$arrayData<=$nextDescId[0]->purchase_id;$arrayData++)
				{
					$innerResult = $this->getPurchasePreviousNextData($headerData,$arrayData);
					if(count($innerResult)!=0)
					{
						break;
					}
					if($arrayData==$nextDescId[0]->purchase_id && count($innerResult)==0)
					{
						return $exceptionArray['204'];
					}
					$purchaseId++;
				}
				$saleDataResult = $this->getDocumentData($innerResult);
				return $saleDataResult;
			}
		}
		else
		{
			$saleDataResult = $this->getDocumentData($result);
			return $saleDataResult;
		}
	}
	
	/**
	 * get first-last purchase-bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getFirstLastPurchaseBillData($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($headerData['operation'][0],'first')==0)
		{
			DB::beginTransaction();
			$firstPurchaseDataResult = DB::connection($databaseName)->select("select 
			purchase_id,
			vendor_id,
			product_array,
			bill_number,
			total,
			tax,
			grand_total,
			payment_mode,
			bank_name,
			check_number,
			total_discounttype,
			total_discount,
			advance,
			extra_charge,
			balance,
			transaction_type,
			transaction_date,
			bill_type,
			remark,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from purchase_bill 
			where bill_type='purchase_bill' and
			company_id = '".$headerData['companyid'][0]."' and
			deleted_at='0000-00-00 00:00:00' order by purchase_id asc limit 1");
			DB::commit();
			
			if(count($firstPurchaseDataResult)==0)
			{
				return $exceptionArray['204'];
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($firstPurchaseDataResult);
				return $purchaseDataResult;
			}
		}
		else if(strcmp($headerData['operation'][0],'last')==0)
		{
			DB::beginTransaction();
			$lastPurchaseDataResult = DB::connection($databaseName)->select("select 
			purchase_id,
			vendor_id,
			product_array,
			bill_number,
			total,
			tax,
			grand_total,
			payment_mode,
			bank_name,
			check_number,
			total_discounttype,
			total_discount,
			advance,
			extra_charge,
			balance,
			transaction_type,
			transaction_date,
			bill_type,
			remark,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from purchase_bill 
			where bill_type='purchase_bill' and
			company_id = '".$headerData['companyid'][0]."' and
			deleted_at='0000-00-00 00:00:00' order by purchase_id desc limit 1");
			DB::commit();
			
			if(count($lastPurchaseDataResult)==0)
			{
				return $exceptionArray['204'];
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($lastPurchaseDataResult);
				return $purchaseDataResult;
			}
		}
	}
	
	/**
	 * get previous purchase-bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getPreviousPurchaseId($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($headerData['previouspurchaseid'][0]==0)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->select("select 
			purchase_id,
			vendor_id,
			product_array,
			bill_number,
			total,
			tax,
			grand_total,
			payment_mode,
			bank_name,
			check_number,
			total_discounttype,
			total_discount,
			advance,
			extra_charge,
			balance,
			transaction_type,
			transaction_date,
			bill_type,
			remark,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from sales_bill 
			where bill_type='purchase_bill' and
			company_id = '".$headerData['companyid'][0]."' and
			deleted_at='0000-00-00 00:00:00'
			order by purchase_id desc limit 1");
			DB::commit();
			if(count($raw)==0)
			{
				return $exceptionArray['204'];
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($raw);
				return $purchaseDataResult;
			}
		}
		else
		{
			$purchaseId = $headerData['previouspurchaseid'][0]-1;
			$result = $this->getPurchasePreviousNextData($headerData,$purchaseId);
			if(count($result)==0)
			{
				DB::beginTransaction();
				$previousAscId = DB::connection($databaseName)->select("select 
				purchase_id
				from purchase_bill 
				where bill_type='purchase_bill' and
				company_id = '".$headerData['companyid'][0]."' and
				deleted_at='0000-00-00 00:00:00'
				order by purchase_id asc limit 1");
				DB::commit();
				if($purchaseId<$previousAscId[0]->purchase_id)
				{
					return $exceptionArray['204'];
				}
				else
				{
					for($arrayData=$purchaseId-1;$arrayData>=$previousAscId[0]->purchase_id;$arrayData--)
					{
						$innerResult = $this->getPurchasePreviousNextData($headerData,$arrayData);
						if(count($innerResult)!=0)
						{
							break;
						}
						if($arrayData==$previousAscId[0]->purchase_id && count($innerResult)==0)
						{
							return $exceptionArray['204'];
						}
						$purchaseId++;
					}
					$purchaseDataResult = $this->getDocumentData($innerResult);
					return $purchaseDataResult;
				}
			}
			else
			{
				$purchaseDataResult = $this->getDocumentData($result);
				return $purchaseDataResult;
			}
		}
	}
	
	/**
	 * get previous-next purchase-bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getPurchasePreviousNextData($headerData,$purchaseId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$purchaseData = DB::connection($databaseName)->select("select 
		purchase_id,
		vendor_id,
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		payment_mode,
		bank_name,
		check_number,
		total_discounttype,
		total_discount,
		advance,
		extra_charge,
		balance,
		transaction_type,
		transaction_date,
		bill_type,
		remark,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where bill_type='purchase_bill' and
		company_id = '".$headerData['companyid'][0]."' and
		deleted_at='0000-00-00 00:00:00' and
		sale_id='".$purchaseId."'");
		DB::commit();
		return $purchaseData;
	}
	
	/**
	 * get document purchase-bill data(internal call)
	 * @param  purchase-bill-data
	 * returns the purchase-bill-data
	*/
	public function getDocumentData($purchaseArrayData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$documentResult = array();
		for($purchaseData=0;$purchaseData<count($purchaseArrayData);$purchaseData++)
		{
			DB::beginTransaction();
			$documentResult[$purchaseData] = DB::connection($databaseName)->select("select
			document_id,
			purchase_id,
			document_name,
			document_size,
			document_format,
			created_at,
			updated_at
			from purchase_doc_dtl
			where purchase_id='".$purchaseArrayData[$purchaseData]->purchase_id."' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($documentResult[$purchaseData])==0)
			{
				$documentResult[$purchaseData] = array();
				$documentResult[$purchaseData][0] = new stdClass();
				$documentResult[$purchaseData][0]->document_id = 0;
				$documentResult[$purchaseData][0]->purchase_id = 0;
				$documentResult[$purchaseData][0]->document_name = '';
				$documentResult[$purchaseData][0]->document_size = 0;
				$documentResult[$purchaseData][0]->document_format = '';
				$documentResult[$purchaseData][0]->document_type ='purchase-bill';
				$documentResult[$purchaseData][0]->created_at = '0000-00-00 00:00:00';
				$documentResult[$purchaseData][0]->updated_at = '0000-00-00 00:00:00';
			}
		}
		
		$purchaseDataArray = array();
		$purchaseDataArray['purchaseBillData'] = json_encode($purchaseArrayData);
		$purchaseDataArray['documentData'] = json_encode($documentResult);
		return json_encode($purchaseDataArray);
	}
	
	/**
	 * delete purchase-bill data
	 * @param  purchase-bill-id
	 * returns status/error-message
	*/
	public function deletePurchaseBillData($purchaseId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$mytime = Carbon\Carbon::now();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$purchaseArray = array();
		$purchaseArray['purchasebillid'][0] = $purchaseId;
		$purchaseIdData = $this->getPurchaseBillData($purchaseArray);
		$jsonDecodedPurchaseData = json_decode(json_decode($purchaseIdData)->purchaseBillData);
		
		$productArray = $jsonDecodedPurchaseData[0]->product_array;
		$inventoryCount = count(json_decode($productArray));
		for($productArrayData=0;$productArrayData<$inventoryCount;$productArrayData++)
		{
			$inventoryData = json_decode($productArray);
			DB::beginTransaction();
			$getTransactionSummaryData[$productArrayData] = DB::connection($databaseName)->select("select 
			product_trn_summary_id,
			qty
			from product_trn_summary
			where product_id='".$inventoryData[$productArrayData]->productId."' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($getTransactionSummaryData[$productArrayData])==0)
			{
				$qty = $inventoryData[$productArrayData]->qty*(-1);
				//insert data
				DB::beginTransaction();
				$insertionResult[$productArrayData] = DB::connection($databaseName)->statement("insert into 
				product_trn_summary(qty,company_id,branch_id,product_id)
				values('".$qty."',
					   '".$jsonDecodedPurchaseData[0]->company_id."',
					   0,
					   '".$inventoryData[$productArrayData]->productId."')");
				DB::commit();
			}
			else
			{
				$qty = $getTransactionSummaryData[$productArrayData][0]->qty-$inventoryData[$productArrayData]->qty;
				//update data
				DB::beginTransaction();
				$updateResult = DB::connection($databaseName)->statement("update 
				product_trn_summary set qty='".$qty."'
				where product_trn_summary_id='".$getTransactionSummaryData[$productArrayData][0]->product_trn_summary_id."' and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		//get ledger id from journal
		$journalModel = new JournalModel();
		$journalData = $journalModel->getJfIdArrayData($jsonDecodedPurchaseData[0]->jf_id);
		$jsonDecodedJournalData = json_decode($journalData);
		
		if(strcmp($journalData,$exceptionArray['404'])!=0)
		{
			foreach ($jsonDecodedJournalData as $value)
			{
				//delete ledgerId_ledger_dtl data as per given ledgerId and jf_id
				DB::beginTransaction();
				$deleteLedgerData = DB::connection($databaseName)->statement("update
				".$value->ledger_id."_ledger_dtl set
				deleted_at = '".$mytime."'
				where jf_id = ".$jsonDecodedPurchaseData[0]->jf_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		//delete journal data
		DB::beginTransaction();
		$deleteJournalData = DB::connection($databaseName)->statement("update
		journal_dtl set
		deleted_at = '".$mytime."'
		where jf_id = ".$jsonDecodedPurchaseData[0]->jf_id." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//delete product_trn data
		DB::beginTransaction();
		$deleteProductTrnData = DB::connection($databaseName)->statement("update
		product_trn set
		deleted_at = '".$mytime."'
		where jf_id = ".$jsonDecodedPurchaseData[0]->jf_id." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//delete purchase-bill data 
		DB::beginTransaction();
		$deleteBillData = DB::connection($databaseName)->statement("update
		purchase_bill set
		deleted_at = '".$mytime."'
		where purchase_id = ".$purchaseId." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
	}
}
