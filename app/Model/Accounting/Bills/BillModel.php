<?php
namespace ERP\Model\Accounting\Bills;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillModel extends Model
{
	protected $table = 'retail_sales_dtl';
	
	/**
	 * insert data with document
	 * @param  array
	 * returns the status
	*/
	public function insertAllData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$documentArray)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into retail_sales_dtl(
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
		client_id) 
		values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."')");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$saleId = DB::select("SELECT 
			max(sale_id) sale_id
			FROM retail_sales_dtl where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(is_array($saleId))
			{
				for($docArray=0;$docArray<count($documentArray);$docArray++)
				{
					DB::beginTransaction();
					$documentResult = DB::statement("insert into retail_sales_doc_dtl(
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
					$billResult = DB::select("select
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
					company_id,
					created_at,
					updated_at 
					from retail_sales_dtl where sale_id=(select MAX(sale_id) as sale_id from retail_sales_dtl) and deleted_at='0000-00-00 00:00:00'"); 
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
	public function insertData($productArray,$paymentMode,$invoiceNumber,$bankName,$checkNumber,$total,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into retail_sales_dtl(
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
		client_id) 
		values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$bankName."','".$checkNumber."','".$total."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."')");
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
