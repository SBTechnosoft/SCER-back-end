<?php
namespace ERP\Model\Documents;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Http\Requests;
use Illuminate\Http\Request;
// use ERP\Core\Documents\Entities\UserArray;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class DocumentModel extends Model
{
	/**
	 * insert data 
	 * @param  array
	 * returns the status
	*/
	public function deleteDocumentData($headerData,$documentId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$mytime = Carbon\Carbon::now();
		$tableName='';
		if(strcmp('sale-bill',$headerData['type'][0])==0)
		{
			$tableName = "sales_bill_doc_dtl";
			DB::beginTransaction();
			$clientDocumentName = DB::connection($databaseName)->select("select document_name 
			from client_doc_dtl where document_id='".$documentId."'");
			DB::commit();
			if(count($clientDocumentName)!=0)
			{
				DB::beginTransaction();
				$updateBillDocument = DB::connection($databaseName)->statement("update ".$tableName." 
				set deleted_at='".$mytime."'
				where document_name='".$clientDocumentName[0]->document_name."'");
				DB::commit();
			}
			DB::beginTransaction();
			$updateClientDocument = DB::connection($databaseName)->statement("update client_doc_dtl 
			set deleted_at='".$mytime."'
			where document_id='".$documentId."'");
			DB::commit();
				
				
		}
		else
		{
			$tableName = "purchase_doc_dtl";
			DB::beginTransaction();
			$updateBillDocument = DB::connection($databaseName)->statement("update ".$tableName." 
			set deleted_at='".$mytime."'
			where document_id='".$documentId."'");
			DB::commit();
		}
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($updateBillDocument==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
