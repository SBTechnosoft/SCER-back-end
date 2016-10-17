<?php
namespace ERP\Model\Quotations;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationModel extends Model
{
	protected $table = 'quotation_dtl';
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		date_default_timezone_set("Asia/Calcutta");
		$getQuotationData = array();
		$getQuotationKey = array();
		$getQuotationData = func_get_arg(0);
		$getQuotationKey = func_get_arg(1);
		$quotationData="";
		$keyName = "";
		for($data=0;$data<count($getQuotationData);$data++)
		{
			if($data == (count($getQuotationData)-1))
			{
				$quotationData = $quotationData."'".$getQuotationData[$data]."'";
				$keyName =$keyName.$getQuotationKey[$data];
			}
			else
			{
				$quotationData = $quotationData."'".$getQuotationData[$data]."',";
				$keyName =$keyName.$getQuotationKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into quotation_dtl(".$keyName.") 
		values(".$quotationData.")");
		DB::commit();
		
		if($raw==1)
		{
			return "200:Data Inserted Successfully";
		}
		else
		{
			return "500:Internal Server Error";
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
		quotation_id,
		quotation_label,
		quotation_type,
		start_at,
		end_at,
		created_at,
		company_id			
		from quotation_dtl");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "204: No Content";
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data as per given quotation Id
	 * @param $quotationId
	 * returns the status
	*/
	public function getData($quotationId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		quotation_id,
		quotation_label,
		quotation_type,
		start_at,
		end_at,
		created_at,
		company_id
		from quotation_dtl where quotation_id = ".$quotationId);
		DB::commit();
		
		if(count($raw)==0)
		{
			return "404:Id Not Found";
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
	public function getAllQuotationData($companyId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		quotation_id,
		quotation_label,
		quotation_type,
		start_at,
		end_at,
		created_at,
		company_id
		from quotation_dtl where company_id ='".$companyId);
		DB::commit();
		
		if(count($raw)==0)
		{
			return "204: No Content";
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
}
