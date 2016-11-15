<?php
namespace ERP\Model\Documents;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
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
	
	//delete
	public function deleteData($stateAbb)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update state_mst 
		set deleted_at='".$mytime."'
		where state_abb = '".$stateAbb."'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($raw==1)
		{
			$city = DB::statement("update city_mst 
			set deleted_at='".$mytime."'
			where state_abb = '".$stateAbb."'");
			if($city==1)
			{
				return $fileSizeArray['200'];
			}
			else
			{
				return $fileSizeArray['500'];
			}
		}
		else
		{
			return $fileSizeArray['500'];
		}
	}
}
