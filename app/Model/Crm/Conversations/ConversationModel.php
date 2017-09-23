<?php
namespace ERP\Model\Crm\Conversations;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Clients\ClientModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConversationModel extends Model
{
	protected $table = 'conversation_dtl';
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertEmailData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$getDataArray = func_get_arg(0);
		$getKeyData = func_get_arg(1);
		$document = func_get_arg(2);
		$userId = func_get_arg(3);
		$conversationData='';
		$keyName = "";
		$queryArrayCount = count($getDataArray);
		for($insertQueryIndex=0;$insertQueryIndex<$queryArrayCount;$insertQueryIndex++)
		{
			$conversationData="";
			$keyName = "";
			
			$innerArrayCount = count($getDataArray[$insertQueryIndex]);
			for($innerArray=0;$innerArray<$innerArrayCount;$innerArray++)
			{
				$keyName = $keyName.$getKeyData[$insertQueryIndex][$innerArray].',';
				$conversationData = $conversationData."'".$getDataArray[$insertQueryIndex][$innerArray]."',";
			}
			$documentKey='';
			$documentData='';
			if(count($document)!=0)
			{
				$documentKey = "attachment_name,attachment_format,attachment_size,attachment_path";
				$documentData = "'".$document[0][0]."','".$document[0][2]."','".$document[0][1]."','".$document[0][3]."'";
			}
			else
			{
				$keyName = rtrim($keyName,',');
				$conversationData = rtrim($conversationData,',');
			}
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into conversation_dtl(".$keyName."".$documentKey.",user_id)
			values(".$conversationData."".$documentData.",'".$userId."')");
			DB::commit();
		}
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
	}
	
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function insertSmsData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$getDataArray = func_get_arg(0);
		$getKeyData = func_get_arg(1);
		$document = func_get_arg(2);
		$userId = func_get_arg(3);
		$conversationData='';
		$keyName = "";
		$queryArrayCount = count($getDataArray);
		for($insertQueryIndex=0;$insertQueryIndex<$queryArrayCount;$insertQueryIndex++)
		{
			$conversationData="";
			$keyName = "";
			
			$innerArrayCount = count($getDataArray[$insertQueryIndex]);
			for($innerArray=0;$innerArray<$innerArrayCount;$innerArray++)
			{
				$keyName = $keyName.$getKeyData[$insertQueryIndex][$innerArray].',';
				$conversationData = $conversationData."'".$getDataArray[$insertQueryIndex][$innerArray]."',";
			}
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into conversation_dtl(".$keyName."user_id)
			values(".$conversationData."".$userId.")");
			DB::commit();
		}
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
	}
}
