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
		
		$tableName = strcmp('sale-bill',$headerData['type'][0])==0 ? "sales_bill_doc_dtl" : "purchase_doc_dtl";
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update ".$tableName." 
		set deleted_at='".$mytime."'
		where document_id='".$documentId."'");
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
