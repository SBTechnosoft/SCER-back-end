<?php
namespace ERP\Model\Accounting\Ledgers;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerModel extends Model
{
	protected $table = 'ledger_mst';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$getLedgerData = array();
		$getLedgerKey = array();
		$getLedgerData = func_get_arg(0);
		$getLedgerKey = func_get_arg(1);
		$ledgerData="";
		$keyName = "";
		for($data=0;$data<count($getLedgerData);$data++)
		{
			if($data == (count($getLedgerData)-1))
			{
				$ledgerData = $ledgerData."'".$getLedgerData[$data]."'";
				$keyName =$keyName.$getLedgerKey[$data];
			}
			else
			{
				$ledgerData = $ledgerData."'".$getLedgerData[$data]."',";
				$keyName =$keyName.$getLedgerKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into ledger_mst(".$keyName.") 
		values(".$ledgerData.")");
		DB::commit();
		
		if($raw==1)
		{
			$ledgerId = DB::select('SELECT  MAX(ledger_id) AS ledger_id from ledger_mst');
			
			$result = DB::statement("CREATE TABLE ".$ledgerId[0]->ledger_id."_ledger_dtl (
			 `".$ledgerId[0]->ledger_id."_id` int(11) NOT NULL AUTO_INCREMENT,
			 `amount` decimal(8,2) NOT NULL,
			 `amount_type` enum('credit','debit') NOT NULL,
			 `entry date` datetime NOT NULL,
			 `jf_id` int(11) NOT NULL,
			 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			 `updated_at` datetime NOT NULL,
			 `deleted_at` datetime NOT NULL,
			 `ledger_id` int(11) NOT NULL,
			 PRIMARY KEY (`".$ledgerId[0]->ledger_id."_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			print_r($result);
			exit;
			return "200:Data Inserted Successfully";
		}
		else
		{
			return "500:Internal Server Error";
		}
	}
	/**
	 * update data 
	 * @param  ledger-data,key of ledger-data,ledger-id
	 * returns the status
	*/
	public function updateData($ledgerData,$key,$ledgerId)
	{
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		for($data=0;$data<count($ledgerData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$ledgerData[$data]."',";
		}
		DB::beginTransaction();
		$raw = DB::statement("update ledger_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where ledger_id = '".$ledgerId."'");
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
		ledger_id,
		ledger_name,
		alias,
		inventory_affected,
		address1,
		address2,
		pan,
		tin,
		service_tax_no,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_grp_id			
		from ledger_mst where deleted_at='0000-00-00 00:00:00'");
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
	 * get data as per given Ledger Id
	 * @param $ledgerId
	 * returns the status
	*/
	public function getData($ledgerId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		ledger_id,
		ledger_name,
		alias,
		inventory_affected,
		address1,
		address2,
		pan,
		tin,
		service_tax_no,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_grp_id
		from ledger_mst where ledger_id = ".$ledgerId." and deleted_at='0000-00-00 00:00:00'");
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
	public function getAllLedgerData($ledgerGrpId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		ledger_id,
		ledger_name,
		alias,
		inventory_affected,
		address1,
		address2,
		pan,
		tin,
		service_tax_no,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_grp_id
		from ledger_mst where ledger_grp_id ='".$ledgerGrpId."' and  deleted_at='0000-00-00 00:00:00'");
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
	
	//delete
	public function deleteData($ledgerId)
	{
		$mytime = Carbon\Carbon::now();
		DB::beginTransaction();
		$raw = DB::statement("update ledger_mst 
		set deleted_at='".$mytime."' 
		where ledger_id=".$ledgerId);
		DB::commit();
		if($raw==1)
		{
			return "200 :Data Deleted Successfully";
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}
