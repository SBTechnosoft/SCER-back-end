<?php
namespace ERP\Model\Settings\Templates;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateModel extends Model
{
	protected $table = 'template_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getTemplateData = array();
		$getTemplateKey = array();
		$getTemplateData = func_get_arg(0);
		$getTemplateKey = func_get_arg(1);
		$templateData="";
		$keyName = "";
		for($data=0;$data<count($getTemplateData);$data++)
		{
			if($data == (count($getTemplateData)-1))
			{
				$templateData = $templateData."'".$getTemplateData[$data]."'";
				$keyName =$keyName.$getTemplateKey[$data];
			}
			else
			{
				$templateData = $templateData."'".$getTemplateData[$data]."',";
				$keyName =$keyName.$getTemplateKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into template_mst(".$keyName.") 
		values(".$templateData.")");
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
	 * update data 
	 * @param  template-data,key of template-data,template-id
	 * returns the status
	*/
	public function updateData($templateData,$key,$templateId)
	{
		date_default_timezone_set("Asia/Calcutta");
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($templateData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$templateData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update template_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where template_id = '".$templateId."'");
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
		template_id,
		template_name,
		template_body,
		template_type,
		updated_at,
		created_at,
		company_id
		from template_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given Template Id
	 * @param $templateId
	 * returns the status
	*/
	public function getData($templateId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		template_id,
		template_name,
		template_body,
		template_type,
		updated_at,
		created_at,
		company_id
		from template_mst where template_id ='".$templateId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given Company Id
	 * @param $companyId
	 * returns the status
	*/
	public function getAllTemplateData($companyId,$templateType)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		template_id,
		template_body
		from template_mst where template_type ='".$templateType."' and company_id='".$companyId."' and deleted_at='0000-00-00 00:00:00'");
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
}
