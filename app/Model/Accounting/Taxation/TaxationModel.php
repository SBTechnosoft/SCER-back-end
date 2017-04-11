<?php
namespace ERP\Model\Accounting\Taxation;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TaxationModel extends Model
{
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getSaleTaxData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		//get saleTax from sales bill 
		DB::beginTransaction();	
		$saleTaxResult = DB::connection($databaseName)->select("select
		product_array,
		invoice_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		sales_type,
		refund,
		entry_date,
		client_id,
		company_id,
		jf_id
		from sales_bill
		where deleted_at='0000-00-00 00:00:00' and sales_type='whole_sales' and company_id='".$companyId."'"); 
		DB::commit();
		
		if(count($saleTaxResult)!=0)
		{
			return json_encode($saleTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getPurchaseTaxData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		
		//get saleTax from purchase bill 
		DB::beginTransaction();	
		$purchaseTaxResult = DB::connection($databaseName)->select("select
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		transaction_type,
		transaction_date,
		client_name,
		company_id,
		jf_id
		from purchase_bill
		where deleted_at='0000-00-00 00:00:00' and transaction_type='tax_purchase' and company_id='".$companyId."'"); 
		DB::commit();
		
		if(count($purchaseTaxResult)!=0)
		{
			return json_encode($purchaseTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getPurchaseData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		
		//get saleTax from purchase bill 
		DB::beginTransaction();	
		$purchaseTaxResult = DB::connection($databaseName)->select("select
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		transaction_type,
		transaction_date,
		client_name,
		company_id,
		jf_id
		from purchase_bill
		where deleted_at='0000-00-00 00:00:00' and company_id='".$companyId."'"); 
		DB::commit();
		
		if(count($purchaseTaxResult)!=0)
		{
			return json_encode($purchaseTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
}
