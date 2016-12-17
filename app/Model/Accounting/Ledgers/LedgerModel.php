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
			 `amount_type` enum('credit','debit') NOT NULL,
			 `entry_date` date NOT NULL,
			 `jf_id` int(11) NOT NULL,
			 `balance_flag` enum('','opening','closing') NOT NULL DEFAULT '',
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
			 `balance_flag` enum('','opening','closing') NOT NULL  DEFAULT '',
			 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
		$ledgerAllData = DB::select("select 
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
		// print_r($ledgerAllData);
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($ledgerAllData)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$ledgerIdArray = array();
			$mergeArray = array();
			for($ledgerDataArray=0;$ledgerDataArray<count($ledgerAllData);$ledgerDataArray++)
			{
				$ledgerIdArray[$ledgerDataArray] = $ledgerAllData[$ledgerDataArray]->ledger_id;
				$currentBalanceType="";
				
				//get opening balance
				DB::beginTransaction();
				$raw = DB::select("SELECT 
				".$ledgerIdArray[$ledgerDataArray]."_id,
				amount,
				amount_type
				from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
				WHERE balance_flag='opening' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($raw)!=0)
				{
					//get current balance
					DB::beginTransaction();
					$ledgerResult = DB::select("SELECT 
					".$ledgerIdArray[$ledgerDataArray]."_id,
					amount,
					amount_type
					from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
					WHERE deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					$creditAmountArray =0;
					$debitAmountArray = 0;
					for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
					{
						if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						}
					}
					if(count($ledgerResult)==0)
					{
						return $exceptionArray['404'];
					}
				}
				else
				{
					return $exceptionArray['404'];
				}
				//calculate opening balance
				if($creditAmountArray>$debitAmountArray)
				{
					$amountData = $creditAmountArray-$debitAmountArray;
					$currentBalanceType = "credit";
				}
				else
				{
					$amountData = $debitAmountArray-$creditAmountArray;
					$currentBalanceType = "debit";
				}
				$balanceAmountArray = array();
				$balanceAmountArray['openingBalance'] = $raw[0]->amount;
				$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
				$balanceAmountArray['currentBalance'] = $amountData;
				$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
				$mergeArray[$ledgerDataArray] = (Object)array_merge((array)$ledgerAllData[$ledgerDataArray],(array)((Object)$balanceAmountArray));
			}
			$enocodedData = json_encode($mergeArray);
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
		from ledger_mst where ledger_id = ".$ledgerId." and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($ledgerData)==0)
		{
			
			return $fileSizeArray['404'];
		}
		else
		{
			$ledgerId = $ledgerData[0]->ledger_id;
			$currentBalanceType="";
			
			//get opening balance
			DB::beginTransaction();
			$raw = DB::select("SELECT 
			".$ledgerId."_id,
			amount,
			amount_type
			from ".$ledgerId."_ledger_dtl
			WHERE balance_flag='opening' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($raw)!=0)
			{
				//get current balance
				DB::beginTransaction();
				$ledgerResult = DB::select("SELECT 
				".$ledgerId."_id,
				amount,
				amount_type
				from ".$ledgerId."_ledger_dtl
				WHERE deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				
				$creditAmountArray =0;
				$debitAmountArray = 0;
				for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
				{
					if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
					{
						$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						
					}
					else
					{
						$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
					}
				}
				if(count($ledgerResult)==0)
				{
					return $exceptionArray['404'];
				}
			}
			else
			{
				return $exceptionArray['404'];
			}
			//calculate opening balance
			if($creditAmountArray>$debitAmountArray)
			{
				$amountData = $creditAmountArray-$debitAmountArray;
				$currentBalanceType = "credit";
			}
			else
			{
				$amountData = $debitAmountArray-$creditAmountArray;
				$currentBalanceType = "debit";
			}
			$balanceAmountArray = array();
			$balanceAmountArray['openingBalance'] = $raw[0]->amount;
			$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
			$balanceAmountArray['currentBalance'] = $amountData;
			$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
			$mergeArray = (Object)array_merge((array)$ledgerData[0],(array)((Object)$balanceAmountArray));
			$enocodedData = json_encode($mergeArray,true); 	
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
		$ledgerAllData = DB::select("select 
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
		from ledger_mst where ledger_group_id ='".$ledgerGrpId."' and  deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($ledgerAllData)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$ledgerIdArray = array();
			$mergeArray = array();
			for($ledgerDataArray=0;$ledgerDataArray<count($ledgerAllData);$ledgerDataArray++)
			{
				$ledgerIdArray[$ledgerDataArray] = $ledgerAllData[$ledgerDataArray]->ledger_id;
				$currentBalanceType="";
				
				//get opening balance
				DB::beginTransaction();
				$raw = DB::select("SELECT 
				".$ledgerIdArray[$ledgerDataArray]."_id,
				amount,
				amount_type
				from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
				WHERE balance_flag='opening' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($raw)!=0)
				{
					//get current balance
					DB::beginTransaction();
					$ledgerResult = DB::select("SELECT 
					".$ledgerIdArray[$ledgerDataArray]."_id,
					amount,
					amount_type
					from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
					WHERE deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					$creditAmountArray =0;
					$debitAmountArray = 0;
					for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
					{
						if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						}
					}
					if(count($ledgerResult)==0)
					{
						return $exceptionArray['404'];
					}
				}
				else
				{
					return $exceptionArray['404'];
				}
				//calculate opening balance
				if($creditAmountArray>$debitAmountArray)
				{
					$amountData = $creditAmountArray-$debitAmountArray;
					$currentBalanceType = "credit";
				}
				else
				{
					$amountData = $debitAmountArray-$creditAmountArray;
					$currentBalanceType = "debit";
				}
				$balanceAmountArray = array();
				$balanceAmountArray['openingBalance'] = $raw[0]->amount;
				$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
				$balanceAmountArray['currentBalance'] = $amountData;
				$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
				$mergeArray[$ledgerDataArray] = (Object)array_merge((array)$ledgerAllData[$ledgerDataArray],(array)((Object)$balanceAmountArray));
			}
			$enocodedData = json_encode($mergeArray);
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
		$ledgerAllData = DB::select("select 
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
		$exceptionArray = $exception->messageArrays();
		if(count($ledgerAllData)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$ledgerIdArray = array();
			$mergeArray = array();
			for($ledgerDataArray=0;$ledgerDataArray<count($ledgerAllData);$ledgerDataArray++)
			{
				$ledgerIdArray[$ledgerDataArray] = $ledgerAllData[$ledgerDataArray]->ledger_id;
				$currentBalanceType="";
				
				//get opening balance
				DB::beginTransaction();
				$raw = DB::select("SELECT 
				".$ledgerIdArray[$ledgerDataArray]."_id,
				amount,
				amount_type
				from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
				WHERE balance_flag='opening' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($raw)!=0)
				{
					//get current balance
					DB::beginTransaction();
					$ledgerResult = DB::select("SELECT 
					".$ledgerIdArray[$ledgerDataArray]."_id,
					amount,
					amount_type
					from ".$ledgerIdArray[$ledgerDataArray]."_ledger_dtl
					WHERE deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					$creditAmountArray =0;
					$debitAmountArray = 0;
					for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
					{
						if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						}
					}
					if(count($ledgerResult)==0)
					{
						return $exceptionArray['404'];
					}
				}
				else
				{
					return $exceptionArray['404'];
				}
				//calculate opening balance
				if($creditAmountArray>$debitAmountArray)
				{
					$amountData = $creditAmountArray-$debitAmountArray;
					$currentBalanceType = "credit";
				}
				else
				{
					$amountData = $debitAmountArray-$creditAmountArray;
					$currentBalanceType = "debit";
				}
				$balanceAmountArray = array();
				$balanceAmountArray['openingBalance'] = $raw[0]->amount;
				$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
				$balanceAmountArray['currentBalance'] = $amountData;
				$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
				$mergeArray[$ledgerDataArray] = (Object)array_merge((array)$ledgerAllData[$ledgerDataArray],(array)((Object)$balanceAmountArray));
			}
			$enocodedData = json_encode($mergeArray);
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
		$data = DB::select("select 
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
		if(count($data)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$ledgerId = $data[0]->ledger_id;
			$currentBalanceType="";
			
			//get opening balance
			DB::beginTransaction();
			$raw = DB::select("SELECT 
			".$ledgerId."_id,
			amount,
			amount_type
			from ".$ledgerId."_ledger_dtl
			WHERE balance_flag='opening' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($raw)!=0)
			{
				//get current balance
				DB::beginTransaction();
				$ledgerResult = DB::select("SELECT 
				".$ledgerId."_id,
				amount,
				amount_type
				from ".$ledgerId."_ledger_dtl
				WHERE deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				
				$creditAmountArray =0;
				$debitAmountArray = 0;
				for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
				{
					if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
					{
						$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						
					}
					else
					{
						$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
					}
				}
				if(count($ledgerResult)==0)
				{
					return $exceptionArray['404'];
				}
			}
			else
			{
				return $exceptionArray['404'];
			}
			//calculate opening balance
			if($creditAmountArray>$debitAmountArray)
			{
				$amountData = $creditAmountArray-$debitAmountArray;
				$currentBalanceType = "credit";
			}
			else
			{
				$amountData = $debitAmountArray-$creditAmountArray;
				$currentBalanceType = "debit";
			}
			
			$balanceAmountArray = array();
			$balanceAmountArray['openingBalance'] = $raw[0]->amount;
			$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
			$balanceAmountArray['currentBalance'] = $amountData;
			$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
			$mergeArray = array();
			for($arrayData=0;$arrayData<count($data);$arrayData++)
			{
				$mergeArray[$arrayData] = (Object)array_merge((array)$data[$arrayData],(array)((Object)$balanceAmountArray));
			}
			
			$enocodedData = json_encode($mergeArray);
			return $enocodedData;
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
		$data = DB::select("SELECT 
		ledger_id
		FROM ledger_mst
		WHERE ledger_name='".$ledgerType."' and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(count($data)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			DB::beginTransaction();
			$ledgerAllData = DB::select("SELECT 
			".$data[0]->ledger_id."_id,
			amount,
			amount_type,
			entry_date,
			jf_id,
			created_at,
			updated_at,
			ledger_id
			FROM ".$data[0]->ledger_id."_ledger_dtl
			WHERE (entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			if(count($ledgerAllData)!=0)
			{
				$ledgerId = $data[0]->ledger_id;
				$currentBalanceType="";
				
				//get opening balance
				DB::beginTransaction();
				$raw = DB::select("SELECT 
				".$ledgerId."_id,
				amount,
				amount_type
				from ".$ledgerId."_ledger_dtl
				WHERE balance_flag='opening' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($raw)!=0)
				{
					//get current balance
					DB::beginTransaction();
					$ledgerResult = DB::select("SELECT 
					".$ledgerId."_id,
					amount,
					amount_type
					from ".$ledgerId."_ledger_dtl
					WHERE deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					$creditAmountArray =0;
					$debitAmountArray = 0;
					for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
					{
						if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						}
					}
					if(count($ledgerResult)==0)
					{
						return $exceptionArray['404'];
					}
				}
				else
				{
					return $exceptionArray['404'];
				}
				//calculate opening balance
				if($creditAmountArray>$debitAmountArray)
				{
					$amountData = $creditAmountArray-$debitAmountArray;
					$currentBalanceType = "credit";
				}
				else
				{
					$amountData = $debitAmountArray-$creditAmountArray;
					$currentBalanceType = "debit";
				}
				
				$balanceAmountArray = array();
				$balanceAmountArray['openingBalance'] = $raw[0]->amount;
				$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
				$balanceAmountArray['currentBalance'] = $amountData;
				$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
				$mergeArray = array();
				for($arrayData=0;$arrayData<count($ledgerAllData);$arrayData++)
				{
					$mergeArray[$arrayData] = (Object)array_merge((array)$ledgerAllData[$arrayData],(array)((Object)$balanceAmountArray));
				}
				$enocodedData = json_encode($mergeArray);
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
		$data = DB::select("SELECT 
		ledger_id
		FROM ledger_mst
		WHERE ledger_name='".$ledgerType."' and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(count($data)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			DB::beginTransaction();
			$ledgerAllData = DB::select("SELECT 
			".$data[0]->ledger_id."_id,
			amount,
			amount_type,
			entry_date,
			jf_id,
			created_at,
			updated_at,
			ledger_id
			FROM ".$data[0]->ledger_id."_ledger_dtl
			WHERE YEAR(entry_date)= YEAR(CURDATE()) and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($ledgerAllData)!=0)
			{
				$ledgerId = $data[0]->ledger_id;
				$currentBalanceType="";
				
				//get opening balance
				DB::beginTransaction();
				$raw = DB::select("SELECT 
				".$ledgerId."_id,
				amount,
				amount_type
				from ".$ledgerId."_ledger_dtl
				WHERE balance_flag='opening' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($raw)!=0)
				{
					//get current balance
					DB::beginTransaction();
					$ledgerResult = DB::select("SELECT 
					".$ledgerId."_id,
					amount,
					amount_type
					from ".$ledgerId."_ledger_dtl
					WHERE deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					$creditAmountArray =0;
					$debitAmountArray = 0;
					for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
					{
						if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
						{
							$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							
						}
						else
						{
							$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						}
					}
					if(count($ledgerResult)==0)
					{
						return $exceptionArray['404'];
					}
				}
				else
				{
					return $exceptionArray['404'];
				}
				//calculate opening balance
				if($creditAmountArray>$debitAmountArray)
				{
					$amountData = $creditAmountArray-$debitAmountArray;
					$currentBalanceType = "credit";
				}
				else
				{
					$amountData = $debitAmountArray-$creditAmountArray;
					$currentBalanceType = "debit";
				}
				
				$balanceAmountArray = array();
				$balanceAmountArray['openingBalance'] = $raw[0]->amount;
				$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
				$balanceAmountArray['currentBalance'] = $amountData;
				$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
				$mergeArray = array();
				for($arrayData=0;$arrayData<count($ledgerAllData);$arrayData++)
				{
					$mergeArray[$arrayData] = (Object)array_merge((array)$ledgerAllData[$arrayData],(array)((Object)$balanceAmountArray));
				}
				
				$enocodedData = json_encode($mergeArray);
				$encoded = new EncodeTrnAllData();
				$encodeAllData = $encoded->getEncodedAllData($enocodedData,$data[0]->ledger_id);
				return $encodeAllData;
			}
			else
			{
				return $exceptionArray['404'];
			}
		}
	}
	
	/**
	 * get data as per company_id and ledger_group id
	 * returns the error-message/data
	*/
	public function getDataAsPerLedgerGrp($ledgerGrpArray,$companyId)
	{
		$ledgerGrpArray = func_get_arg(0);
		$companyId = func_get_arg(1);
		$ledgerArray = array();
		$mainResult = array();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//get data as per companyId and ledger-group-id
		for($ledgerGrpArrayData=0;$ledgerGrpArrayData<count($ledgerGrpArray);$ledgerGrpArrayData++)
		{
			DB::beginTransaction();
			$data = DB::select("SELECT 
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
			FROM ledger_mst
			WHERE ledger_group_id='".$ledgerGrpArray[$ledgerGrpArrayData]."' and 
			company_id='".$companyId."' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($data)!=0)
			{
				$mainResult[$ledgerGrpArrayData] = $data; 
				for($arrayData=0;$arrayData<count($data);$arrayData++)
				{
					$ledgerArray[$ledgerGrpArrayData][$arrayData] = $data[$arrayData]->ledger_id;
				}
			}
		}
		//add balance in ledger data
		if(count($ledgerArray)!=0)
		{
			$ledgerIdArray = array();
			$mergeArray = array();
			for($ledgerDataArray=0;$ledgerDataArray<count($ledgerArray);$ledgerDataArray++)
			{
				for($innerArray=0;$innerArray<count($ledgerArray[$ledgerDataArray]);$innerArray++)
				{
					$currentBalanceType="";
					
					//get opening balance
					DB::beginTransaction();
					$raw = DB::select("SELECT 
					".$ledgerArray[$ledgerDataArray][$innerArray]."_id,
					amount,
					amount_type
					from ".$ledgerArray[$ledgerDataArray][$innerArray]."_ledger_dtl
					WHERE balance_flag='opening' and 
					deleted_at='0000-00-00 00:00:00'");
					DB::commit();
					
					if(count($raw)!=0)
					{
						//get current balance
						DB::beginTransaction();
						$ledgerResult = DB::select("SELECT 
						".$ledgerArray[$ledgerDataArray][$innerArray]."_id,
						amount,
						amount_type
						from ".$ledgerArray[$ledgerDataArray][$innerArray]."_ledger_dtl
						WHERE deleted_at='0000-00-00 00:00:00'");
						DB::commit();
						
						$creditAmountArray =0;
						$debitAmountArray = 0;
						for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
						{
							if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
							{
								$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
								
							}
							else
							{
								$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
							}
						}
						if(count($ledgerResult)==0)
						{
							return $exceptionArray['404'];
						}
					}
					else
					{
						return $exceptionArray['404'];
					}
					//calculate opening balance
					if($creditAmountArray>$debitAmountArray)
					{
						$amountData = $creditAmountArray-$debitAmountArray;
						$currentBalanceType = "credit";
					}
					else
					{
						$amountData = $debitAmountArray-$creditAmountArray;
						$currentBalanceType = "debit";
					}
					$balanceAmountArray = array();
					$balanceAmountArray['openingBalance'] = $raw[0]->amount;
					$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
					$balanceAmountArray['currentBalance'] = $amountData;
					$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
					
					$mergeArray[$ledgerDataArray][$innerArray] = (Object)array_merge((array)$mainResult[$ledgerDataArray][$innerArray],(array)((Object)$balanceAmountArray));
				}
			}
			return $mergeArray;
		}
		else
		{
			return $exceptionArray['404'];
		}
	}
	
	/**
	 * get data as per company_id and ledger_name
	 * returns the error-message/data
	*/
	public function getDataAsPerLedgerName($ledgerName,$companyId)
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
		from ledger_mst where company_id ='".$companyId."' and ledger_name='".$ledgerName."' and  deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($ledgerData)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			$ledgerId = $ledgerData[0]->ledger_id;
			$currentBalanceType="";
			
			//get opening balance
			DB::beginTransaction();
			$raw = DB::select("SELECT 
			".$ledgerId."_id,
			amount,
			amount_type
			from ".$ledgerId."_ledger_dtl
			WHERE balance_flag='opening' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($raw)!=0)
			{
				//get current balance
				DB::beginTransaction();
				$ledgerResult = DB::select("SELECT 
				".$ledgerId."_id,
				amount,
				amount_type
				from ".$ledgerId."_ledger_dtl
				WHERE deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				
				$creditAmountArray =0;
				$debitAmountArray = 0;
				for($ledgerArrayData=0;$ledgerArrayData<count($ledgerResult);$ledgerArrayData++)
				{
					if(strcmp($ledgerResult[$ledgerArrayData]->amount_type,"credit")==0)
					{
						$creditAmountArray = $creditAmountArray+$ledgerResult[$ledgerArrayData]->amount;
						
					}
					else
					{
						$debitAmountArray = $debitAmountArray+$ledgerResult[$ledgerArrayData]->amount;
					}
				}
				if(count($ledgerResult)==0)
				{
					return $exceptionArray['404'];
				}
			}
			else
			{
				return $exceptionArray['404'];
			}
			//calculate opening balance
			if($creditAmountArray>$debitAmountArray)
			{
				$amountData = $creditAmountArray-$debitAmountArray;
				$currentBalanceType = "credit";
			}
			else
			{
				$amountData = $debitAmountArray-$creditAmountArray;
				$currentBalanceType = "debit";
			}
			$balanceAmountArray = array();
			$balanceAmountArray['openingBalance'] = $raw[0]->amount;
			$balanceAmountArray['openingBalanceType'] = $raw[0]->amount_type;
			$balanceAmountArray['currentBalance'] = $amountData;
			$balanceAmountArray['currentBalanceType'] = $currentBalanceType;
			$mergeArray = (Object)array_merge((array)$ledgerData[0],(array)((Object)$balanceAmountArray));
			$enocodedData = json_encode($mergeArray);
			return $enocodedData;
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
