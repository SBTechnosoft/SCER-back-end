<?php
namespace ERP\Model\Accounting\Bills;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillModel extends Model
{
	protected $table = 'sales_bill';
	
	/**
	 * insert data with document
	 * @param  array
	 * returns the status
	*/
	public function insertAllData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$documentArray,$jfId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into sales_bill(
		product_array,
		payment_mode,
		invoice_number,
		bank_name,
		check_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		remark,
		entry_date,
		company_id,
		sales_type,
		client_id,
		jf_id) 
		values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$salesType."','".$ClientId."','".$jfId."')");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			DB::beginTransaction();
			$saleId = DB::connection($databaseName)->select("SELECT 
			max(sale_id) sale_id
			FROM sales_bill where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			DB::beginTransaction();
			$salesTrnData = DB::connection($databaseName)->statement("insert into sales_bill_trn(
			product_array,
			payment_mode,
			invoice_number,
			bank_name,
			check_number,
			total,
			tax,
			grand_total,
			advance,
			balance,
			remark,
			entry_date,
			company_id,
			sales_type,
			client_id,
			sale_id,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$salesType."','".$ClientId."','".$saleId[0]->sale_id."','".$jfId."')");
			DB::commit();
			
			if(is_array($saleId))
			{
				for($docArray=0;$docArray<count($documentArray);$docArray++)
				{
					DB::beginTransaction();
					$documentResult = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
					sale_id,
					document_name,
					document_size,
					document_format) 
					values('".$saleId[0]->sale_id."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][1]."','".$documentArray[$docArray][2]."')");
					DB::commit();
					if($documentResult==0)
					{
						return $exceptionArray['500'];
					}
				}	
				if($documentResult==1)
				{
					DB::beginTransaction();
					$billResult = DB::connection($databaseName)->select("select
					sale_id,
					product_array,
					payment_mode,
					bank_name,
					invoice_number,
					check_number,
					total,
					tax,
					grand_total,
					advance,
					balance,
					remark,
					entry_date,
					sales_type,
					client_id,
					company_id,
					jf_id,
					created_at,
					updated_at 
					from sales_bill where sale_id=(select MAX(sale_id) as sale_id from sales_bill) and deleted_at='0000-00-00 00:00:00'"); 
					DB::commit();
					if(count($billResult)==1)
					{
						return json_encode($billResult);
					}
					else
					{
						return $exceptionArray['500'];
					}
				}
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
	
	/**
	 * insert only data 
	 * @param  array
	 * returns the status
	*/
	public function insertData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$jfId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into sales_bill(
		product_array,
		payment_mode,
		invoice_number,
		bank_name,
		check_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		remark,
		entry_date,
		company_id,
		client_id,
		sales_type,
		jf_id) 
		values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$salesType."','".$jfId."')");
		DB::commit();
		 
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			
			DB::beginTransaction();
			$saleId = DB::connection($databaseName)->select("SELECT 
			max(sale_id) sale_id
			FROM sales_bill where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into sales_bill_trn(
			product_array,
			payment_mode,
			invoice_number,
			bank_name,
			check_number,
			total,
			tax,
			grand_total,
			advance,
			balance,
			remark,
			entry_date,
			company_id,
			client_id,
			sales_type,
			sale_id,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$salesType."','".$saleId[0]->sale_id."','".$jfId."')");
			DB::commit();
		
			DB::beginTransaction();
			$billResult = DB::connection($databaseName)->select("select
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			check_number,
			total,
			tax,
			grand_total,
			advance,
			balance,
			remark,
			entry_date,
			client_id,
			sales_type,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from sales_bill where sale_id=(select MAX(sale_id) as sale_id from sales_bill) and deleted_at='0000-00-00 00:00:00'"); 
			DB::commit();
			if(count($billResult)==1)
			{
				return json_encode($billResult);
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
	
	/**
	 * insert document data
	 * @param  sale-id,document-name,document-format,document-type
	 * returns the exception-message
	*/
	public function billDocumentData($saleId,$documentName,$documentFormat,$documentType)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
		sale_id,
		document_name,
		document_format,
		document_type)
		values('".$saleId."','".$documentName."','".$documentFormat."','".$documentType."')");
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
	 * get bill-document data
	 * @param  company-id,sales-type,from-date,to-date
	 * returns the exception-message
	*/
	public function getSpecifiedData($companyId,$salesType,$fromDate,$toDate)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		check_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		remark,
		entry_date,
		client_id,
		sales_type,
		jf_id
		company_id,
		created_at,
		updated_at 
		from sales_bill 
		where sales_type='".$salesType."' and
		(entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['404']; 
		}
		else
		{
		
			$documentResult = array();
			for($saleData=0;$saleData<count($raw);$saleData++)
			{
				DB::beginTransaction();
				$documentResult[$saleData] = DB::connection($databaseName)->select("select
				document_id,
				sale_id,
				document_name,
				document_size,
				document_format,
				document_type,
				created_at,
				updated_at
				from sales_bill_doc_dtl
				where sale_id='".$raw[$saleData]->sale_id."' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($documentResult[$saleData])==0)
				{
					// return $exceptionArray['404'];
					$documentResult[$saleData] = array();
					$documentResult[$saleData][0] = new stdClass();
					$documentResult[$saleData][0]->document_id = 0;
					$documentResult[$saleData][0]->sale_id = 0;
					$documentResult[$saleData][0]->document_name = '';
					$documentResult[$saleData][0]->document_size = 0;
					$documentResult[$saleData][0]->document_format = '';
					$documentResult[$saleData][0]->document_type ='bill';
					$documentResult[$saleData][0]->created_at = '0000-00-00 00:00:00';
					$documentResult[$saleData][0]->updated_at = '0000-00-00 00:00:00';
				}
			}
			$salesArrayData = array();
			$salesArrayData['salesData'] = json_encode($raw);
			$salesArrayData['documentData'] = json_encode($documentResult);
			return json_encode($salesArrayData);
		}
	}
	
	/**
	 * get bill data
	 * @param  sale_id
	 * returns the exception-message/sales data
	*/
	public function getSaleIdData($saleId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		check_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		remark,
		entry_date,
		client_id,
		sales_type,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where sale_id='".$saleId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['404']; 
		}
		else
		{
			$encodedData = json_encode($raw);
			return $encodedData;
		}
	}
}
