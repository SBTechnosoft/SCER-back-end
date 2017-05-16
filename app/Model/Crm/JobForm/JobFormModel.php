<?php
namespace ERP\Model\Crm\JobForm;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JobFormModel extends Model
{
	protected $table = 'job_form_dtl';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$mytime = Carbon\Carbon::now();
		
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$dataArray = func_get_arg(0);
		$productArray = func_get_arg(1);
		$encodedArray = json_encode($productArray);
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into job_card_dtl(
		client_name,
		address,
		contact_no,
		email_id,
		job_card_no,
		labour_charge,
		service_type,
		entry_date,
		delivery_date,
		advance,
		total,
		payment_mode,
		state_abb,
		city_id,
		company_id,
		bank_name,
		cheque_no,
		product_array) 
		values(
		'".$dataArray['clientName']."',
		'".$dataArray['address']."',
		'".$dataArray['contactNo']."',
		'".$dataArray['emailId']."',
		'".$dataArray['jobCardNo']."',
		'".$dataArray['labourCharge']."',
		'".$dataArray['serviceType']."',
		'".$dataArray['entryDate']."',
		'".$dataArray['deliveryDate']."',
		'".$dataArray['advance']."',
		'".$dataArray['total']."',
		'".$dataArray['paymentMode']."',
		'".$dataArray['stateAbb']."',
		'".$dataArray['cityId']."',
		'".$dataArray['companyId']."',
		'".$dataArray['bankName']."',
		'".$dataArray['chequeNo']."',
		'".$encodedArray."') on duplicate key update 
		client_name='".$dataArray['clientName']."',
		address='".$dataArray['address']."',
		contact_no='".$dataArray['contactNo']."',
		email_id='".$dataArray['emailId']."',
		job_card_no='".$dataArray['jobCardNo']."',
		labour_charge='".$dataArray['labourCharge']."',
		service_type='".$dataArray['serviceType']."',
		entry_date='".$dataArray['entryDate']."',
		delivery_date='".$dataArray['deliveryDate']."',
		advance='".$dataArray['advance']."',
		total='".$dataArray['total']."',
		payment_mode='".$dataArray['paymentMode']."',
		state_abb='".$dataArray['stateAbb']."',
		city_id='".$dataArray['cityId']."',
		company_id='".$dataArray['companyId']."',
		bank_name='".$dataArray['bankName']."',
		cheque_no='".$dataArray['chequeNo']."',
		product_array='".$encodedArray."',
		updated_at='".$mytime."'");
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
	 * insert/update job-form data & insert bill data
	 * @param  array
	 * returns the status
	*/
	public function insertBillJobData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$getJobFormData = array();
		$getJobFormKey = array();
		$getJobFormData = func_get_arg(0);
		$getJobFormKey = func_get_arg(1);
		$jobFormData="";
		$keyName = "";
		$updateString="";
		for($data=0;$data<count($getJobFormData);$data++)
		{
			if($data == (count($getJobFormData)-1))
			{
				$jobFormData = $jobFormData."'".$getJobFormData[$data]."'";
				$keyName =$keyName.$getJobFormKey[$data];
			}
			else
			{
				$jobFormData = $jobFormData."'".$getJobFormData[$data]."',";
				$keyName =$keyName.$getJobFormKey[$data].",";
			}
			$updateString = $updateString.$getJobFormKey[$data]."='".$getJobFormData[$data]."',";
		}
		echo "insert into job_card_dtl(".$keyName.") 
		values(".$jobFormData.") ON DUPLICATE KEY UPDATE ".$updateString;
		exit;
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into job_card_dtl(".$keyName.") 
		values(".$jobFormData.") ON DUPLICATE KEY UPDATE ".$updateString);
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
	 * update data 
	 * @param  branch-data,key of branch-data,branch-id
	 * returns the status
	*/
	public function updateData($branchData,$key,$branchId)
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
		
		//only one branch is checked by default
		$enumIsDefArray = array();
		$isDefEnum = new IsDefaultEnum();
		$enumIsDefArray = $isDefEnum->enumArrays();
		for($keyData=0;$keyData<count($key);$keyData++)
		{
		    if(strcmp($key[array_keys($key)[$keyData]],"is_default")==0)
			{
				if(strcmp($branchData[$keyData],$enumIsDefArray['default'])==0)
				{
					$raw  = DB::connection($databaseName)->statement("update branch_mst 
					set is_default='".$enumIsDefArray['notDefault']."',updated_at='".$mytime."' 
					where deleted_at = '0000-00-00 00:00:00'");
					if($raw==0)
					{
						return $exceptionArray['500'];
					}
				}
			}	
		}
		
		for($data=0;$data<count($branchData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$branchData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update branch_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where branch_id = '".$branchId."' and deleted_at='0000-00-00 00:00:00'");
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
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id			
		from branch_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
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
	 * get data as per given Branch Id
	 * @param $branchId
	 * returns the status
	*/
	public function getData($branchId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id	
		from branch_mst where branch_id = ".$branchId." and deleted_at='0000-00-00 00:00:00'");
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
	public function getAllBranchData($companyId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		branch_id,
		branch_name,
		address1,
		address2,
		pincode,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		company_id
		from branch_mst where company_id ='".$companyId."' and  deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
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
	 * get data as per given branch-name
	 * returns the error-message/branchId
	*/
	public function getBranchId($convertedBranchName)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$branchIdResult = DB::connection($databaseName)->select("SELECT 
		branch_id 
		from branch_mst 
		where REGEXP_REPLACE(branch_name,'[^a-zA-Z0-9]','')='".$convertedBranchName."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($branchIdResult)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			return json_encode($branchIdResult);
		}
	}
	
	//delete
	public function deleteData($branchId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::connection($databaseName)->statement("update product_mst 
		set deleted_at='".$mytime."' 
		where branch_id=".$branchId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$product = DB::connection($databaseName)->statement("update branch_mst 
			set deleted_at='".$mytime."' 
			where branch_id=".$branchId);
			if($product==1)
			{
				return $exceptionArray['200'];
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
}
