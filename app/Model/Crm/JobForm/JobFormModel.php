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
		tax,
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
		'".$dataArray['tax']."',
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
		tax='".$dataArray['tax']."',
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
}
