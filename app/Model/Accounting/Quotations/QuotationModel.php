<?php
namespace ERP\Model\Accounting\Quotations;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Settings\QuotationNumbers\Services\QuotationService;
use ERP\Api\V1_0\Settings\QuotationNumbers\Controllers\QuotationController;
use Illuminate\Container\Container;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationModel extends Model
{
	protected $table = 'quotation_bill_dtl';
	
	/**
	 * insert only data 
	 * @param  array
	 * returns the status
	*/
	public function insertData($productArray,$quotationNumber,$total,$extraCharge,$tax,$grandTotal,$remark,$entryDate,$companyId,$ClientId,$jfId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//insert bill data
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into quotation_bill_dtl(
		product_array,
		quotation_number,
		total,
		extra_charge,
		tax,
		grand_total,
		remark,
		entry_date,
		company_id,
		client_id,
		jf_id) 
		values('".$productArray."','".$quotationNumber."','".$total."','".$extraCharge."','".$tax."','".$grandTotal."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$jfId."')");
		DB::commit();
		
		//update quotation-number
		$quotationResult = $this->updateQuotationNumber($companyId);
		if(strcmp($quotationResult,$exceptionArray['200'])!=0)
		{
			return $quotationResult;
		}
		if($raw==1)
		{
			DB::beginTransaction();
			$quotationId = DB::connection($databaseName)->select("SELECT 
			max(quotation_bill_id) quotation_bill_id
			FROM quotation_bill_dtl where deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			//insertion in quotation bill archives
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into quotation_bill_archives(
			product_array,
			quotation_number,
			total,
			extra_charge,
			tax,
			grand_total,
			remark,
			entry_date,
			company_id,
			client_id,
			quotation_bill_id,
			jf_id) 
			values('".$productArray."','".$quotationNumber."','".$total."','".$extraCharge."','".$tax."','".$grandTotal."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$quotationId[0]->quotation_bill_id."','".$jfId."')");
			DB::commit();
			//get latest inserted quotation bill data
			DB::beginTransaction();
			$quotationResult = DB::connection($databaseName)->select("select
			quotation_bill_id,
			product_array,
			quotation_number,
			total,
			extra_charge,
			tax,
			grand_total,
			remark,
			entry_date,
			client_id,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from quotation_bill_dtl where quotation_bill_id=(select MAX(quotation_bill_id) as quotation_bill_id from quotation_bill_dtl) and deleted_at='0000-00-00 00:00:00'"); 
			DB::commit();
			if(count($quotationResult)==1)
			{
				return json_encode($quotationResult);
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
	 * after insertion quotation-bill data update quotation-number
	 * @param  company-id
	 * returns the exception-message
	*/
	public function updateQuotationNumber($companyId)
	{
		//get constants from constant class
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$quotationService = new QuotationService();	
		$quotationData = $quotationService->getLatestQuotationData($companyId);
		if(strcmp($exceptionArray['204'],$quotationData)==0)
		{
			return $quotationData;
		}
		$endAt = json_decode($quotationData)[0]->endAt;
		$quotationController = new QuotationController(new Container());
		$quotationMethod=$constantArray['postMethod'];
		$quotationPath=$constantArray['quotationUrl'];
		$quotationDataArray = array();
		$quotationDataArray['endAt'] = $endAt+1;
		$quotationRequest = Request::create($quotationPath,$quotationMethod,$quotationDataArray);
		$updateResult = $quotationController->update($quotationRequest,json_decode($quotationData)[0]->quotationId);
		return $updateResult;
	}
	
	/**
	 * insert document data
	 * @param  quotation-id,document-name,document-format,document-type
	 * returns the exception-message
	*/
	public function quotationDocumentData($quotationBillId,$documentName,$documentFormat,$documentType)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$raw = DB::connection($databaseName)->statement("insert into quotation_bill_doc_dtl(
		quotation_bill_id,
		document_name,
		document_format,
		document_type)
		values('".$quotationBillId."','".$documentName."','".$documentFormat."','".$documentType."')");
		DB::commit();
		
		//get exception message
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
