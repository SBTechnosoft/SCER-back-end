<?php
namespace ERP\Model\Accounting\Ledgers;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Ledgers\Entities\EncodeTrnAllData;
// use ERP\Core\Accounting\Ledgers\Entities\EncodeProductTrnAllData;
use ERP\Core\Accounting\Journals\Entities\EncodeAllData;
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
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$ledgerId = DB::select("SELECT  MAX(ledger_id) AS ledger_id from ledger_mst where deleted_at='0000-00-00 00:00:00'");
			$result = DB::statement("CREATE TABLE ".$ledgerId[0]->ledger_id."_ledger_dtl (
			 `".$ledgerId[0]->ledger_id."_id` int(11) NOT NULL AUTO_INCREMENT,
			 `amount` decimal(10,2) NOT NULL,
			 `amount_type` enum('credit','debit') NOT NULL DEFAULT '',
			 `entry_date` date NOT NULL,
			 `jf_id` int(11) NOT NULL,
			 `balance_flag` enum('','opening','closing') NOT NULL,
			 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			 `updated_at` datetime NOT NULL,
			 `deleted_at` datetime NOT NULL,
			 `ledger_id` int(11) NOT NULL,
			 PRIMARY KEY (`".$ledgerId[0]->ledger_id."_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf16");
			
			if($result==1)
			{
				DB::beginTransaction();
				$ledgerData = DB::select("select 
				ledger_id,
				ledger_name,
				alias,
				inventory_affected,
				address1,
				address2,
				contact_no,
				email_id,
				pan,
				tin,
				gst,
				created_at,
				updated_at,
				deleted_at,
				state_abb,
				city_id,
				ledger_group_id,
				company_id
				from ledger_mst 
				where ledger_id = (select max(ledger_id) from ledger_mst) and deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				return json_encode($ledgerData);
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
	
	/**
	 * insert all data (ledger data & amount data)
	 * @param  array
	 * returns the status
	*/
	public function insertAllData()
	{
		$getLedgerData = array();
		$getLedgerKey = array();
		$getLedgerData = func_get_arg(0);
		$getLedgerKey = func_get_arg(1);
		$getLedgerBalanceData = array();
		$getLedgerBalanceKey = array();
		$getLedgerBalanceData = func_get_arg(2);
		$getLedgerBalanceKey = func_get_arg(3);
		$ledgerData="";
		$ledgerBalanceData="";
		$keyName = "";
		$balanceKeyName = "";
		//make keys and values for query of ledger data
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
		//make keys and values for query of balance data
		for($balanceData=0;$balanceData<count($getLedgerBalanceData);$balanceData++)
		{
			if($balanceData == (count($getLedgerBalanceData)-1))
			{
				$ledgerBalanceData = $ledgerBalanceData."'".$getLedgerBalanceData[$balanceData]."'";
				$balanceKeyName =$balanceKeyName.$getLedgerBalanceKey[$balanceData];
			}
			else
			{
				$ledgerBalanceData = $ledgerBalanceData."'".$getLedgerBalanceData[$balanceData]."',";
				$balanceKeyName =$balanceKeyName.$getLedgerBalanceKey[$balanceData].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::statement("insert into ledger_mst(".$keyName.") 
		values(".$ledgerData.")");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$ledgerId = DB::select("SELECT  MAX(ledger_id) AS ledger_id from ledger_mst where deleted_at='0000-00-00 00:00:00'");
			$result = DB::statement("CREATE TABLE ".$ledgerId[0]->ledger_id."_ledger_dtl (
			 `".$ledgerId[0]->ledger_id."_id` int(11) NOT NULL AUTO_INCREMENT,
			 `amount` decimal(10,2) NOT NULL,
			 `amount_type` enum('credit','debit') NOT NULL,
			 `entry_date` date NOT NULL,
			 `jf_id` int(11) NOT NULL,
			 `balance_flag` enum('opening','closing') NOT NULL,
			 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			 `updated_at` datetime NOT NULL,
			 `deleted_at` datetime NOT NULL,
			 `ledger_id` int(11) NOT NULL,
			 PRIMARY KEY (`".$ledgerId[0]->ledger_id."_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf16");
			if($result==1)
			{
				//insertion of balance data in ledger table
				$ledgerInsertionResult = DB::statement("insert into ".$ledgerId[0]->ledger_id."_ledger_dtl(".$balanceKeyName.",ledger_id) 
				values(".$ledgerBalanceData.",'".$ledgerId[0]->ledger_id."')");
				if($ledgerInsertionResult==1)
				{
					DB::beginTransaction();
					$ledgerData = DB::select("select 
					ledger_id,
					ledger_name,
					alias,
					inventory_affected,
					address1,
					address2,
					contact_no,
					email_id,
					pan,
					tin,
					gst,
					created_at,
					updated_at,
					deleted_at,
					state_abb,
					city_id,
					ledger_group_id,
					company_id
					from ledger_mst 
					where ledger_id = (select max(ledger_id) from ledger_mst) and deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					return json_encode($ledgerData);
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
		else
		{
			return $exceptionArray['500'];
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
		where ledger_id = '".$ledgerId."' and deleted_at='0000-00-00 00:00:00'");
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
		ledger_id,
		ledger_name,
		alias,
		inventory_affected,
		address1,
		address2,
		contact_no,
		email_id,
		pan,
		tin,
		gst,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_group_id,
		company_id
		from ledger_mst where deleted_at='0000-00-00 00:00:00'");
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
		contact_no,
		email_id,
		pan,
		tin,
		gst,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_group_id,
		company_id
		from ledger_mst where ledger_id = ".$ledgerId." and deleted_at='0000-00-00 00:00:00'");
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
		contact_no,
		email_id,
		pan,
		tin,
		gst,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_group_id,
		company_id
		from ledger_mst where ledger_grp_id ='".$ledgerGrpId."' and  deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getLedgerDetail($companyId)
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		ledger_id,
		ledger_name,
		alias,
		inventory_affected,
		address1,
		address2,
		contact_no,
		email_id,
		pan,
		tin,
		gst,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id,
		ledger_group_id,
		company_id
		from ledger_mst where company_id ='".$companyId."' and  deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getLedgerTransactionDetail($ledgerId)
	{	
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();		
		$ledgerData = DB::select("select 
		ledger_id
		from ledger_mst where ledger_id='".$ledgerId."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($ledgerData)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			DB::beginTransaction();		
			$raw = DB::select("select 
			".$ledgerId."_id,
			amount,
			amount_type,
			entry_date,
			jf_id,
			created_at,
			updated_at,
			ledger_id
			from ".$ledgerId."_ledger_dtl where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
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
	
	/**
	 * get data 
	 * @param  from-date and to-date
	 * get data between given date
	 * returns the error-message/data
	*/
	public function getLedgerData($fromDate,$toDate,$companyId,$ledgerType)
	{
		DB::beginTransaction();
		$raw = DB::select("SELECT 
		ledger_id
		FROM ledger_mst
		WHERE ledger_name='".$ledgerType."' and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
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
			DB::beginTransaction();
			$ledgerData = DB::select("SELECT 
			".$raw[0]->ledger_id."_id,
			amount,
			amount_type,
			entry_date,
			jf_id,
			created_at,
			updated_at,
			ledger_id
			FROM ".$raw[0]->ledger_id."_ledger_dtl
			WHERE (entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($ledgerData)!=0)
			{
				$enocodedData = json_encode($ledgerData);
				return $enocodedData;
			}
			else
			{
				return $exceptionArray['404'];
			}
		}
	}
	
	public function getLedgerId($companyId,$type)
	{
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::select("SELECT 
		ledger_id
		FROM ledger_mst
		WHERE ledger_name='".$type."' and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data 
	 * get current year data
	 * returns the error-message/data
	*/
	public function getCurrentYearData($companyId,$ledgerType)
	{
		DB::beginTransaction();
		$raw = DB::select("SELECT 
		ledger_id
		FROM ledger_mst
		WHERE ledger_name='".$ledgerType."' and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
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
			DB::beginTransaction();
			$ledgerData = DB::select("SELECT 
			".$raw[0]->ledger_id."_id,
			amount,
			amount_type,
			entry_date,
			jf_id,
			created_at,
			updated_at,
			ledger_id
			FROM ".$raw[0]->ledger_id."_ledger_dtl
			WHERE YEAR(entry_date)= YEAR(CURDATE()) and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($ledgerData)!=0)
			{
				$enocodedData = json_encode($ledgerData);
				$encoded = new EncodeTrnAllData();
				$encodeAllData = $encoded->getEncodedAllData($enocodedData,$raw[0]->ledger_id);
				return $encodeAllData;
			}
			else
			{
				return $exceptionArray['404'];
			}
		}
	}
	
	/**
	 * delete the data
	 * @param: ledgerId
	 * returns the error-message/status
	*/
	public function deleteData($ledgerId)
	{
		$mytime = Carbon\Carbon::now();
		DB::beginTransaction();
		$raw = DB::statement("update ledger_mst 
		set deleted_at='".$mytime."' 
		where ledger_id=".$ledgerId);
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
}
