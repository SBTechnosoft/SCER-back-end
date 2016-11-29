<?php
namespace ERP\Model\Accounting\Journals;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Journals\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalModel extends Model
{
	protected $table = 'journal_dtl';
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertData()
	{
		$amountArray = array();
		$amountTypeArray = array();
		$ledgerIdArray = array();
		$jfIdArray = array();
		$entryDateArray = array();
		$companyIdArray = array();
		
		$amountArray = func_get_arg(0);
		$amountTypeArray = func_get_arg(1);
		$jfIdArray = func_get_arg(2);
		$ledgerIdArray = func_get_arg(3);
		$entryDateArray = func_get_arg(4);
		$companyIdArray = func_get_arg(5);
		$debitAmount = array();
		$debitLedger = array();
		$creditAmount = array();
		$creditLedger = array();
		$debitArray=0;
		$creditArray=0;
		
		for($data=0;$data<count($jfIdArray);$data++)
		{
			DB::beginTransaction();
			$raw = DB::statement("insert into 
			journal_dtl(
			jf_id,
			amount,
			amount_type,
			entry_date,
			ledger_id,
			company_id) 
			values('".$jfIdArray[$data]."','".$amountArray[$data]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$ledgerIdArray[$data]."','".$companyIdArray[$data]."')");
			DB::commit();
			if($raw==1)
			{
				if($amountTypeArray[$data]=="credit")
				{
					$creditAmount[$creditArray] = $amountArray[$data];
					$creditLedger[$creditArray] = $ledgerIdArray[$data];
					$creditArray++;
				}
				else
				{
					$debitAmount[$debitArray] = $amountArray[$data];
					$debitLedger[$debitArray] = $ledgerIdArray[$data];
					$debitArray++;
				}
			}
		}
		
		//ledger entry
		for($data=0;$data<count($jfIdArray);$data++)
		{
			if($amountTypeArray[$data]=="debit")
			{
				//purchase case
				if(count($creditLedger)>1)
				{
					for($creditLoop=0;$creditLoop<count($creditLedger);$creditLoop++)
					{
						DB::beginTransaction();
						$ledgerEntryResult = DB::statement("insert into 
						".$ledgerIdArray[$data]."_ledger_dtl(
						jf_id,
						amount,
						amount_type,
						entry_date,
						ledger_id) 
						values('".$jfIdArray[$data]."','".$creditAmount[$creditLoop]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$creditLedger[$creditLoop]."')");
						DB::commit();
					}
				}
				//sale case
				else
				{
					DB::beginTransaction();
					$ledgerEntryResult = DB::statement("insert into 
					".$ledgerIdArray[$data]."_ledger_dtl(
					jf_id,
					amount,
					amount_type,
					entry_date,
					ledger_id) 
					values('".$jfIdArray[$data]."','".$amountArray[$data]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$creditLedger[0]."')");
					DB::commit();
				}
			}
			else
			{
				//sale case
				if(count($debitLedger)>1)
				{
					for($debitLoop=0;$debitLoop<count($debitLedger);$debitLoop++)
					{
						DB::beginTransaction();
						$ledgerEntryResult = DB::statement("insert into 
						".$ledgerIdArray[$data]."_ledger_dtl(
						jf_id,
						amount,
						amount_type,
						entry_date,
						ledger_id) 
						values('".$jfIdArray[$data]."','".$debitAmount[$debitLoop]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$debitLedger[$debitLoop]."')");
						DB::commit();
					}
				}
				//purchase case
				else
				{
					DB::beginTransaction();
					$ledgerEntryResult = DB::statement("insert into 
					".$ledgerIdArray[$data]."_ledger_dtl(
					jf_id,
					amount,
					amount_type,
					entry_date,
					ledger_id) 
					values('".$jfIdArray[$data]."','".$amountArray[$data]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$debitLedger[0]."')");
					DB::commit();
				}
			}
			if($ledgerEntryResult==0)
			{
				return $fileSizeArray['500'];
			}
		}
		// get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if($ledgerEntryResult==1)
		{
			return $fileSizeArray['200'];
		}
	}
	/**
	 * get data 
	 * get next journal id
	 * returns the error-message/data
	*/
	public function getJournalData()
	{
		DB::beginTransaction();
		$raw = DB::select('SELECT  MAX(journal_id) AS journal_id from journal_dtl');
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
			$enocodedData = json_encode($raw);
			$decodedJson = json_decode($enocodedData,true);
			$nextValue = $decodedJson[0]['journal_id']+1;
			$data = array();
			$data['nextValue']=$nextValue;
			return json_encode($data);
		}
	}
	
	/**
	 * get data 
	 * @param  from-date and to-date
	 * get data between given date
	 * returns the error-message/data
	*/
	public function getData($fromDate,$toDate,$companyId)
	{
		DB::beginTransaction();
		$raw = DB::select("SELECT 
		journal_id,
		jf_id,
		amount,
		amount_type,
		entry_date,
		created_at,
		updated_at,
		ledger_id,
		company_id
		FROM journal_dtl
		WHERE (entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
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
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	/**
	 * get data 
	 * get current year data
	 * returns the error-message/data
	*/
	public function getCurrentYearData($companyId)
	{
		$raw = DB::select("SELECT 
		journal_id,
		jf_id,
		amount,
		amount_type,
		entry_date,
		created_at,
		updated_at,
		ledger_id,
		company_id
		FROM journal_dtl  
		WHERE YEAR(entry_date)= YEAR(CURDATE()) and 
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
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($enocodedData);
			return $encodeAllData;
		}
	}
}
