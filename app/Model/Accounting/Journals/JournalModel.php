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
		
		// get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
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
		if($ledgerEntryResult==1)
		{
			echo "if";
			exit;
			return $fileSizeArray['200'];
		}
	}
	/**
	 * get data 
	 * get next jf id
	 * returns the error-message/data
	*/
	public function getJournalData()
	{
		DB::beginTransaction();
		$raw = DB::select("SELECT  MAX(jf_id) AS jf_id 
		from journal_dtl
		where deleted_at='0000-00-00 00:00:00'");
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
			$nextValue = $decodedJson[0]['jf_id']+1;
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
	
	/**
	 * get data 
	 * get data from jf_id(jf_id is get from journal_id)
	 * returns the error-message/data
	*/
	public function getJournalArrayData($journalId)
	{
		DB::beginTransaction();
		$jfIdResult = DB::select("SELECT 
		jf_id
		FROM journal_dtl  
		WHERE journal_id='".$journalId."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		if(count($jfIdResult)==0)
		{
			return $exceptionArray['404'];
		}
		else
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
			WHERE jf_id='".$jfIdResult[0]->jf_id."' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data 
	 * get jf_id as per journal id
	 * returns the error-message/data
	*/
	// public function getSpecificJournalData($journalId)
	// {
		// $raw = DB::select("SELECT 
		// jf_id
		// from journal_dtl
		// WHERE journal_id='".$journalId."' and 
		// deleted_at='0000-00-00 00:00:00'");
		// DB::commit();
		// if(count($raw)==0)
		// {
			// return $exceptionArray['404'];
		// }
		// else
		// {
			// $enocodedData = json_encode($raw);
			// return $enocodedData;
		// }
	// }
	
	/**
	 * get data 
	 * get journal data as per jf id
	 * returns the error-message/data
	*/
	public function getJfIdArrayData($jfId)
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
		from journal_dtl
		WHERE jf_id='".$jfId."' and 
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
	 * update data 
	 * @param array
	 * returns the error-message/status
	*/
	public function updateData()
	{
		echo "journal model update = ";	
		$arrayDataFlag=0;
		$keyValueString="";
		$creditAmount = array();
		$creditLedger = array();
		$debitLedger = array();
		$debitAmount = array();
		$journalArray = func_get_arg(0);
		$jfId = func_get_arg(1);
		$flag=0;
		$mytime = Carbon\Carbon::now();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$journalModel = new JournalModel();
		$jfIdArrayData = $journalModel->getJfIdArrayData($jfId);
		$jfIdData = json_decode($jfIdArrayData);
		if(array_key_exists(0,$journalArray))
		{
			$arrayDataFlag=1;
		}
		
		//array exists
		if($arrayDataFlag==1)
		{
			$creditArray=0;
			$debitArray=0;
			//compare dataabse data with input array data
			for($arrayData=0;$arrayData<count($journalArray);$arrayData++)
			{
				$ledgerFlag=0;
				$journalFlag=0;
				for($jfIdArray=0;$jfIdArray<count(json_decode($jfIdArrayData));$jfIdArray++)
				{
					//if journal_id and ledger match then store ledger_id
					if(strcmp($journalArray[$arrayData]['journal_id'],$jfIdData[$jfIdArray]->journal_id)==0 && strcmp($journalArray[$arrayData]['ledger_id'],$jfIdData[$jfIdArray]->ledger_id)==0)
					{
						$ledgerFlag=1;
						$storeLedgerId[$arrayData] = $journalArray[$arrayData]['ledger_id'];
						
						//update the data
						DB::beginTransaction();
						$ledgerDeleteResult = DB::statement("update journal_dtl
						set amount='".$journalArray[$arrayData]['amount']."',amount_type='".$journalArray[$arrayData]['amount_type']."',updated_at='".$mytime."' where journal_id='".$journalArray[$arrayData]['journal_id']."' and deleted_at='0000-00-00 00:00:00'");
						DB::commit();
						break;
					}
					if($ledgerFlag==0 && strcmp($journalArray[$arrayData]['journal_id'],$jfIdData[$jfIdArray]->journal_id)==0)
					{
						$journalFlag=1;
						$dbLedgerId = $jfIdData[$jfIdArray]->ledger_id;
						$dbEntryDate = $jfIdData[$jfIdArray]->entry_date;
						$dbCompanyId = $jfIdData[$jfIdArray]->company_id;
						break;
					}
				}
				if($ledgerFlag==0 && $journalFlag==1)
				{
					// delete not matched ledger_id data from journal_dtl
					DB::beginTransaction();
					$ledgerDeleteResult = DB::statement("update journal_dtl
					set deleted_at='".$mytime."' where journal_id='".$journalArray[$arrayData]['journal_id']."'");
					DB::commit();
					
					// delete not matched ledger_id data from ledgerId_ledger_dtl
					DB::beginTransaction();
					$ledgerDeleteResult = DB::statement("update ".$dbLedgerId."_ledger_dtl
					set deleted_at='".$mytime."' where jf_id='".$jfId."'");
					DB::commit();
					
					// insert not matched ledger_id in journal_dtl
					DB::beginTransaction();
					$ledgerDeleteResult = DB::statement("insert into journal_dtl(jf_id,amount,amount_type,entry_date,ledger_id,company_id,updated_at) values('".$jfId."','".$journalArray[$arrayData]['amount']."','".$journalArray[$arrayData]['amount_type']."','".$dbEntryDate."','".$journalArray[$arrayData]['ledger_id']."','".$dbCompanyId."','".$mytime."')");
					DB::commit();
				}
				// make credit and debit array
				if($journalArray[$arrayData]['amount_type']=="credit")
				{
					$creditAmount[$creditArray] = $journalArray[$arrayData]['amount'];
					$creditLedger[$creditArray] = $journalArray[$arrayData]['ledger_id'];
					$creditArray++;
				}
				else
				{
					$debitAmount[$debitArray] = $journalArray[$arrayData]['amount'];
					$debitLedger[$debitArray] = $journalArray[$arrayData]['ledger_id'];
					$debitArray++;
				}
			}
			exit;
			//update/insert data into ledgerId_ledger_dtl table
			for($arrayData=0;$arrayData<count($journalArray);$arrayData++)
			{
				if($journalArray[$arrayData]['amount_type']=="debit")
				{
					//purchase case
					if(count($creditLedger)>1)
					{
						echo "purchase_if";
						//delete(update data as per jf_id)
						for($creditLoop=0;$creditLoop<count($creditLedger);$creditLoop++)
						{
							// DB::beginTransaction();
							// "insert into 
							// journal_dtl(
							// jf_id,
							// amount,
							// amount_type,
							// entry_date,
							// ledger_id,
							// company_id) 
							// values('".$jfIdArray[$data]."','".$amountArray[$data]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$ledgerIdArray[$data]."','".$companyIdArray[$data]."')"
							
							// $ledgerEntryResult = DB::statement("insert into
							// ".$journalArray[$arrayData]['ledger_id']."_ledger_dtl(amount,amount_type,ledger_id)
							// values('".$creditAmount[$creditLoop]."','".$journalArray[$arrayData]['amount_type']."','".$creditLedger[$creditLoop]."')");
							// DB::commit();
						}
					}
					//sale case
					else
					{
						echo "sale";
						// DB::beginTransaction();
						// $ledgerEntryResult = DB::statement("update  
						// ".$journalArray[$arrayData]['ledger_id']."_ledger_dtl 
						// set amount='".$journalArray[$arrayData]['amount']."',amount_type='".$journalArray[$arrayData]['amount_type']."',ledger_id='".$creditLedger[0]."',updated_at='".$mytime."' where jf_id='".$jfId."'");
						// DB::commit();
					}
				}
				else
				{
					//sale case
					if(count($debitLedger)>1)
					{
						echo "sale_if";
						// for($debitLoop=0;$debitLoop<count($debitLedger);$debitLoop++)
						// {
							// DB::beginTransaction();
							// $ledgerEntryResult = DB::statement("update 
							// ".$journalArray[$arrayData]['ledger_id']."_ledger_dtl
							// set amount='".$debitAmount[$debitLoop]."',amount_type='".$journalArray[$arrayData]['amount_type']."',ledger_id='".$debitLedger[$debitLoop]."',updated_at='".$mytime."' where jf_id='".$jfId."' and ledger_id='".$debitLedger[$debitLoop]."'");
							// DB::commit();
						// }
					}
					//purchase case
					else
					{
						echo "purchase";
						// DB::beginTransaction();
						// $ledgerEntryResult = DB::statement("update 
						// ".$journalArray[$arrayData]['ledger_id']."_ledger_dtl
						// set amount='".$journalArray[$arrayData]['amount']."',amount_type='".$journalArray[$arrayData]['amount_type']."',ledger_id='".$debitLedger[0]."',updated_at='".$mytime."' where jf_id='".$jfId."' ");
						// DB::commit();
					}
				}
			}
			exit;
		}
		else
		{
			//update company_id from journal
			if(array_key_exists("company_id",$journalArray))
			{
				//update the company_id from journal
				DB::beginTransaction();
				$journalResult = DB::statement("update journal_dtl
				set company_id='".$journalArray['company_id']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
			//update entryDate from joural and ledgerId_ledger_dtl
			if(array_key_exists("entry_date",$journalArray))
			{
				//update entry_date from journal 
				DB::beginTransaction();
				$journalResult = DB::statement("update journal_dtl
				set entry_date='".$journalArray['entry_date']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				
				//update entry_date from ledgerId_ledger_dtl
				for($data=0;$data<count($jfIdData);$data++)
				{
					DB::beginTransaction();
					$journalResult = DB::statement("update ".$jfIdData[$data]->ledger_id."_ledger_dtl
					set entry_date='".$journalArray['entry_date']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
					DB::commit();
				}
			}
			if($journalResult==1)
			{
				return $exceptionArray['200'];
			}
			else
			{
				return $exceptionArray['500'];
			}
		}
	}
	
	/**
	 * update array with data 
	 * @param array,data,jf_id
	 * returns the error-message/status
	*/
	public function updateArrayData()
	{
		//update array with data
		$journalArray = func_get_arg(0);
		$journalData = func_get_arg(1);
		$jfId = func_get_arg(2);
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		$journalModel = new JournalModel();
		$jfIdArrayData = $journalModel->getJfIdArrayData($jfId);
		$jfIdData = json_decode($jfIdArrayData);
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//update company_id from journal
		if(array_key_exists("company_id",$journalData))
		{
			//update the company_id from journal
			DB::beginTransaction();
			$journalResult = DB::statement("update journal_dtl
			set company_id='".$journalData['company_id']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
			DB::commit();
		}
		
		//update entryDate from joural and ledgerId_ledger_dtl
		if(array_key_exists("entry_date",$journalData))
		{
			//update entry_date from journal 
			DB::beginTransaction();
			$journalResult = DB::statement("update journal_dtl
			set entry_date='".$journalData['entry_date']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			
			//update entry_date from ledgerId_ledger_dtl
			for($data=0;$data<count($jfIdData);$data++)
			{
				DB::beginTransaction();
				$journalResult = DB::statement("update ".$jfIdData[$data]->ledger_id."_ledger_dtl
				set entry_date='".$journalData['entry_date']."',updated_at='".$mytime."' where jf_id='".$jfId."' and deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		
	}
}
