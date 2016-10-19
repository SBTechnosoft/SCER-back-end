<?php
namespace ERP\Model\Settings\Templates;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateModel extends Model
{
	protected $table = 'template_mst';
	
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
		template_id,
		template_name,
		template_body,
		template_type,
		updated_at
		from template_mst");
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
		updated_at
		from template_mst where template_id = ".$templateId);
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
}
