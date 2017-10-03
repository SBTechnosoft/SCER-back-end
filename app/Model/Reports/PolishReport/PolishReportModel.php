<?php
namespace ERP\Model\Reports\PolishReport;

use Illuminate\Database\Eloquent\Model;
use DB;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class PolishReportModel extends Model
{
	/**
	 * get data as per given companyId 
	 * returns the array-data/exception message
	*/
	public function getPolishReportData($companyId,$fromDate,$toDate)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		check_number,
		total,
		tax,
		grand_total,
		advance,
		balance,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		jf_id,
		company_id,
		created_at,
		updated_at 
		from sales_bill 
		where (entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
		company_id='".$companyId."' and 
		deleted_at='0000-00-00 00:00:00' and is_draft='no'");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['404']; 
		}
		else
		{
			$documentResult = array();
			for($saleData=0;$saleData<count($raw);$saleData++)
			{
				DB::beginTransaction();
				$documentResult[$saleData] = DB::connection($databaseName)->select("select
				document_id,
				sale_id,
				document_name,
				document_size,
				document_format,
				document_type,
				created_at,
				updated_at
				from sales_bill_doc_dtl
				where sale_id='".$raw[$saleData]->sale_id."' and 
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
				if(count($documentResult[$saleData])==0)
				{
					$documentResult[$saleData] = array();
					$documentResult[$saleData][0] = new stdClass();
					$documentResult[$saleData][0]->document_id = 0;
					$documentResult[$saleData][0]->sale_id = 0;
					$documentResult[$saleData][0]->document_name = '';
					$documentResult[$saleData][0]->document_size = 0;
					$documentResult[$saleData][0]->document_format = '';
					$documentResult[$saleData][0]->document_type ='bill';
					$documentResult[$saleData][0]->created_at = '0000-00-00 00:00:00';
					$documentResult[$saleData][0]->updated_at = '0000-00-00 00:00:00';
				}
			}
			$salesArrayData = array();
			$salesArrayData['salesData'] = json_encode($raw);
			$salesArrayData['documentData'] = json_encode($documentResult);
			return json_encode($salesArrayData);
		}
	}
}
