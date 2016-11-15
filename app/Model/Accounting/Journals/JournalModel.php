<?php
namespace ERP\Model\Accounting\Journals;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
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
		
		DB::beginTransaction();
		for($data=0;$data<count($jfIdArray);$data++)
		{
			$raw = DB::statement("insert into 
			journal_dtl(jf_id,amount,amount_type,entry_date,ledger_id,company_id) 
			values('".$jfIdArray[$data]."','".$amountArray[$data]."','".$amountTypeArray[$data]."','".$entryDateArray[$data]."','".$ledgerIdArray[$data]."','".$companyIdArray[$data]."')");
		}
		DB::commit();
		
		// get exception message
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
	
	public function getJournalData()
	{
		DB::beginTransaction();
		$raw = DB::select('SELECT  MAX(journal_id) AS journal_id from journal_dtl');
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $fileSizeArray['404'];
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
}
