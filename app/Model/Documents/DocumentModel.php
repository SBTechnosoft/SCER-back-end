<?php
namespace ERP\Model\Documents;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentModel extends Model
{
	/**
	 * insert data 
	 * @param  state_name,is_display and state_abb
	 * returns the status
	*/
	public function insertData($documentName,$documentUrl,$documentSize,$documentFormat,$status)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update company_mst
		set document_name='".$documentName."',
		document_url='".$documentUrl."',
		document_size='".$documentSize."',
		document_format='".$documentFormat."',
		updated_at='".$mytime."' where company_id=".$status);
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
	 * update data 
	 * @param state_abb,state_nameand is_display
	 * returns the status
	*/
	public function updateData($documentName,$documentUrl,$documentSize,$documentFormat,$companyId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update company_mst 
		set document_name='".$documentName."',
		document_url='".$documentUrl."',
		document_size='".$documentSize."',
		document_format='".$documentFormat."',
		updated_at='".$mytime."' where company_id=".$companyId);
		
		DB::commit();
		
		if($raw==1)
		{
			return "200: Data Updated Successfully";
		}
		else
		{
			return "500: Internal Server Error";
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
		document_name,
		document_url,
		document_size,
		document_format
		from company_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given companyId
	 * @param $companyId
	 * returns the status
	*/
	public function getData($companyId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		document_name,
		document_url,
		document_size,
		document_format
		from company_mst where company_id = '".$companyId."' and deleted_at='0000-00-00 00:00:00'");
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
	
	//delete
	public function deleteData($stateAbb)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update state_mst 
		set deleted_at='".$mytime."'
		where state_abb = '".$stateAbb."'");
		DB::commit();
		if($raw==1)
		{
			$city = DB::statement("update city_mst 
			set deleted_at='".$mytime."'
			where state_abb = '".$stateAbb."'");
			if($city==1)
			{
				return "200 :Data Deleted Successfully";
			}
			else
			{
				return "500 : Internal Server Error";
			}
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}
