<?php
namespace ERP\Model\Settings\InvoiceNumbers;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceModel extends Model
{
	protected $table = 'invoice_dtl';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		date_default_timezone_set("Asia/Calcutta");
		$getInvoiceData = array();
		$getInvoiceKey = array();
		$getInvoiceData = func_get_arg(0);
		$getInvoiceKey = func_get_arg(1);
		$invoiceData="";
		$keyName = "";
		for($data=0;$data<count($getInvoiceData);$data++)
		{
			if($data == (count($getInvoiceData)-1))
			{
				$invoiceData = $invoiceData."'".$getInvoiceData[$data]."'";
				$keyName =$keyName.$getInvoiceKey[$data];
			}
			else
			{
				$invoiceData = $invoiceData."'".$getInvoiceData[$data]."',";
				$keyName =$keyName.$getInvoiceKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into invoice_dtl(".$keyName.") 
		values(".$invoiceData.")");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($raw==1)
		{
			return $fileSizeArray['200'];
		}
		else
		{
			return $fileSizeArray['500'];
		}
	}
	
	/**
	 * update data 
	 * @param  ledger-data,key of ledger-data,ledger-id
	 * returns the status
	*/
	public function updateData($invoiceData,$key,$invoiceId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($invoiceData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$invoiceData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update invoice_dtl 
		set ".$keyValueString."updated_at='".$mytime."'
		where invoice_id = '".$invoiceId."'");
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
		invoice_id,
		invoice_label,
		invoice_type,
		start_at,
		end_at,
		created_at,
		company_id			
		from invoice_dtl");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data as per given Invoice Id
	 * @param $invoiceId
	 * returns the status
	*/
	public function getData($invoiceId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		invoice_id,
		invoice_label,
		invoice_type,
		start_at,
		end_at,
		created_at,
		company_id
		from invoice_dtl where invoice_id = ".$invoiceId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['404'];
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
	public function getAllInvoiceData($companyId)
	{	
		DB::beginTransaction();	
		$raw = DB::select("select 
		invoice_id,
		invoice_label,
		invoice_type,
		start_at,
		end_at,
		created_at,
		company_id
		from invoice_dtl where company_id=".$companyId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get latest data 
	 * returns the status
	*/
	public function getLatestInvoiceData($companyId)
	{	
		DB::beginTransaction();	
		$raw = DB::select("SELECT 
		max(invoice_id) invoice_id,
		invoice_label,
		invoice_type,
		start_at,
		end_at,
		created_at,
		company_id		
		FROM invoice_dtl where company_id='".$companyId."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
}
