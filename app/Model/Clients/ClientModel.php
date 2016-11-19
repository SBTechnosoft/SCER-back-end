<?php
namespace ERP\Model\Clients;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\EnumClasses\IsDisplayEnum;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientModel extends Model
{
	protected $table = 'client_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getClientData = array();
		$getClientKey = array();
		$getClientData = func_get_arg(0);
		$getClientKey = func_get_arg(1);
		$clientData="";
		$keyName = "";
		
		//set is_display yes(by_default)
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if(strcmp($getClientData[7],"")==0)
		{
			$getClientData[7]=$enumIsDispArray['display'];
		}
		for($data=0;$data<count($getClientData);$data++)
		{
			if($data == (count($getClientData)-1))
			{
				$clientData = $clientData."'".$getClientData[$data]."'";
				$keyName =$keyName.$getClientKey[$data];
			}
			else
			{
				$clientData = $clientData."'".$getClientData[$data]."',";
				$keyName =$keyName.$getClientKey[$data].",";
			}
		}
		
		DB::beginTransaction();
		$raw = DB::statement("insert into client_mst(".$keyName.") 
		values(".$clientData.")");
		DB::commit();
		
		// get exception message
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
	 * get data as per given Client Id
	 * @param $clientId
	 * returns the status
	*/
	public function getData($clientId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		client_id,
		client_name,
		company_name,
		contact_no,
		work_no,
		email_id,
		address1,
		address2,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id
		from client_mst where client_id = ".$clientId." and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
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
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		client_id,
		client_name,
		company_name,
		contact_no,
		work_no,
		email_id,
		address1,
		address2,
		is_display,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id			
		from client_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
}
