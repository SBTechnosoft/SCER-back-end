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
		$getJournalData = array();
		$getJournalKey = array();
		$getJournalData = func_get_arg(0);
		$getJournalKey = func_get_arg(1);
		$journalData="";
		$keyName = "";
		for($data=0;$data<count($getJournalData);$data++)
		{
			if($data == (count($getJournalData)-1))
			{
				$journalData = $journalData."'".$getJournalData[$data]."'";
				$keyName =$keyName.$getJournalKey[$data];
			}
			else
			{
				$journalData = $journalData."'".$getJournalData[$data]."',";
				$keyName =$keyName.$getJournalKey[$data].",";
			}
		}
		
		DB::beginTransaction();
		$raw = DB::statement("insert into journal_dtl(".$keyName.") 
		values(journalData)");
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
