<?php
namespace ERP\Model\Clients;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Entities\Constants\ConstantClass;
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$getClientData = array();
		$getClientKey = array();
		$getClientData = func_get_arg(0);
		$getClientKey = func_get_arg(1);
		$clientData="";
		$keyName = "";
		for($index=0;$index<count($getClientKey);$index++)
		{
			if(strcmp($getClientKey[$index],'is_display')==0)
			{
				$dataIndex = $index;
				break;
			}
		}
		
		//set is_display yes(by_default)
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if(strcmp($getClientData[$dataIndex],"")==0)
		{
			$getClientData[$dataIndex]=$enumIsDispArray['display'];
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
		$raw = DB::connection($databaseName)->statement("insert into client_mst(".$keyName.") 
		values(".$clientData.")");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			DB::beginTransaction();
			$clientData = DB::connection($databaseName)->select("select 
			client_id,
			client_name,
			company_name,
			contact_no,
			email_id,
			address1,
			is_display,
			created_at,
			updated_at,
			deleted_at,
			state_abb,
			city_id
			from client_mst where client_id = (select max(client_id) from client_mst) and deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			return json_encode($clientData);
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		client_id,
		client_name,
		company_name,
		contact_no,
		email_id,
		address1,
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
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		client_id,
		client_name,
		company_name,
		contact_no,
		email_id,
		address1,
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
	
	/**
	 * get client data 
	 * @param contact-no
	 * returns the status
	*/
	public function getClientData($contactNo)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		client_id
		from client_mst 
		where deleted_at='0000-00-00 00:00:00' and 
		contact_no='".$contactNo."'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['200'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get client data 
	 * @param client_name
	 * returns the status/error-message
	*/
	public function getClientName($clientName)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		client_id
		from client_mst 
		where deleted_at='0000-00-00 00:00:00' and 
		client_name='".$clientName."'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			return $raw;
		}
	}
	
	/**
	 * update client data 
	 * @param client-data,client key(field-name) and client-id
	 * returns the status/error-message
	*/
	public function updateData($clientData,$key,$clientId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		for($data=0;$data<count($clientData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$clientData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update client_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where client_id = '".$clientId."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
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
