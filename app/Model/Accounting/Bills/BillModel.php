<?php
namespace ERP\Model\Accounting\Bills;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Accounting\Journals\JournalModel;
use ERP\Core\Settings\InvoiceNumbers\Services\InvoiceService;
use ERP\Api\V1_0\Settings\InvoiceNumbers\Controllers\InvoiceController;
use Illuminate\Container\Container;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Model\Clients\ClientModel;
use ERP\Core\Clients\Entities\ClientArray;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillModel extends Model
{
	protected $table = 'sales_bill';
	
	/**
	 * insert data with document
	 * @param  array
	 * returns the status
	*/
	public function insertAllData($productArray,$paymentMode,$invoiceNumber,$jobCardNumber,$bankName,$checkNumber,$total,$extraCharge,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$documentArray,$jfId,$totalDiscounttype,$totalDiscount,$poNumber)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if($jobCardNumber!="")
		{
			//get job-card-number for checking job-card-number is exist or not
			DB::beginTransaction();
			$getJobCardNumber = DB::connection($databaseName)->select("select
			job_card_number 
			from sales_bill 
			where job_card_number='".$jobCardNumber."' and 
			deleted_at='0000-00-00 00:00:00' and is_draft='no'");
			DB::commit();
		}
		else
		{
			$getJobCardNumber = array();
		}
			
		//if job-card-number is exists then update bill data otherwise insert bill data
		if(count($getJobCardNumber)==0)
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into sales_bill(
			product_array,
			payment_mode,
			invoice_number,
			job_card_number,
			bank_name,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			company_id,
			sales_type,
			client_id,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$jobCardNumber."','".$bankName."','".$checkNumber."','".$total."','".$totalDiscounttype."','".$totalDiscount."','".$extraCharge."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$poNumber."','".$remark."','".$entryDate."','".$companyId."','".$salesType."','".$ClientId."','".$jfId."')");
			DB::commit();
			
			//update invoice-number
			$invoiceResult = $this->updateInvoiceNumber($companyId);
			if(strcmp($invoiceResult,$exceptionArray['200'])!=0)
			{
				return $invoiceResult;
			}
		}
		else
		{
			$mytime = Carbon\Carbon::now();
			//update bill data
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("update 
			sales_bill set 
			product_array='".$productArray."',
			payment_mode='".$paymentMode."',
			job_card_number='".$jobCardNumber."',
			bank_name='".$bankName."',
			check_number='".$checkNumber."',
			total='".$total."',
			total_discounttype='".$totalDiscounttype."',
			total_discount='".$totalDiscount."',
			extra_total='".$extraCharge."',
			tax='".$tax."',
			grand_total='".$grandTotal."',
			advance='".$advance."',
			balance='".$balance."',
			remark='".$remark."',
			entry_date='".$entryDate."',
			company_id='".$companyId."',
			client_id='".$ClientId."',
			sales_type='".$salesType."',
			po_number='".$poNumber."',
			jf_id='".$jfId."',
			updated_at='".$mytime."' 
			where job_card_number='".$jobCardNumber."' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
		}
		if($raw==1)
		{
			//get latest sale-id from database
			DB::beginTransaction();
			$saleId = DB::connection($databaseName)->select("SELECT 
			max(sale_id) sale_id
			FROM sales_bill where deleted_at='0000-00-00 00:00:00' and is_draft='no'");
			DB::commit();
			
			DB::beginTransaction();
			$salesTrnData = DB::connection($databaseName)->statement("insert into sales_bill_trn(
			product_array,
			payment_mode,
			invoice_number,
			job_card_number,
			bank_name,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			company_id,
			sales_type,
			client_id,
			sale_id,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$jobCardNumber."','".$bankName."','".$checkNumber."','".$total."','".$totalDiscounttype."','".$totalDiscount."','".$extraCharge."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$poNumber."','".$remark."','".$entryDate."','".$companyId."','".$salesType."','".$ClientId."','".$saleId[0]->sale_id."','".$jfId."')");
			DB::commit();
			
			if(is_array($saleId))
			{
				for($docArray=0;$docArray<count($documentArray);$docArray++)
				{
					// add documents in sale-document table
					DB::beginTransaction();
					$saleDocumentResult = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
					sale_id,
					document_name,
					document_size,
					document_format) 
					values('".$saleId[0]->sale_id."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][1]."','".$documentArray[$docArray][2]."')");
					DB::commit();
					
					// add documents in client database
					DB::beginTransaction();
					$clientDocumentResult = DB::connection($databaseName)->statement("insert into client_doc_dtl(
					sale_id,
					document_name,
					document_size,
					document_format,
					client_id) 
					values('".$saleId[0]->sale_id."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][1]."','".$documentArray[$docArray][2]."','".$ClientId."')");
					DB::commit();
					if($saleDocumentResult==0 || $clientDocumentResult==0)
					{
						return $exceptionArray['500'];
					}
				}	
				if($saleDocumentResult==1)
				{
					//get latest sale data from database
					DB::beginTransaction();
					$billResult = DB::connection($databaseName)->select("select
					sale_id,
					product_array,
					payment_mode,
					bank_name,
					invoice_number,
					job_card_number,
					check_number,
					total,
					total_discounttype,
					total_discount,
					extra_charge,
					tax,
					grand_total,
					advance,
					balance,
					po_number,
					remark,
					entry_date,
					sales_type,
					client_id,
					company_id,
					jf_id,
					created_at,
					updated_at 
					from sales_bill where sale_id=(select MAX(sale_id) as sale_id from sales_bill) and deleted_at='0000-00-00 00:00:00' and is_draft='no'"); 
					DB::commit();
					if(count($billResult)==1)
					{
						return json_encode($billResult);
					}
					else
					{
						return $exceptionArray['500'];
					}
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
	 * insert only data 
	 * @param  array
	 * returns the status
	*/
	public function insertData($productArray,$paymentMode,$invoiceNumber,$jobCardNumber,$bankName,$checkNumber,$total,$extraCharge,$tax,$grandTotal,$advance,$balance,$remark,$entryDate,$companyId,$ClientId,$salesType,$jfId,$totalDiscounttype,$totalDiscount,$poNumber)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if($jobCardNumber!="")
		{
			//get job-card-number for checking job-card-number is exist or not
			DB::beginTransaction();
			$getJobCardNumber = DB::connection($databaseName)->select("select
			job_card_number 
			from sales_bill 
			where job_card_number='".$jobCardNumber."' and 
			deleted_at='0000-00-00 00:00:00' and is_draft='no'");
			DB::commit();
		}
		else
		{
			$getJobCardNumber = array();
		}
		//if job-card-number is exists then update bill data otherwise insert bill data
		if(count($getJobCardNumber)==0)
		{
			//insert bill data
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into sales_bill(
			product_array,
			payment_mode,
			invoice_number,
			job_card_number,
			bank_name,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			company_id,
			client_id,
			sales_type,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$jobCardNumber."','".$bankName."','".$checkNumber."','".$total."','".$totalDiscounttype."','".$totalDiscount."','".$extraCharge."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$poNumber."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$salesType."','".$jfId."')");
			DB::commit();
			
			//update invoice-number
			$invoiceResult = $this->updateInvoiceNumber($companyId);
			if(strcmp($invoiceResult,$exceptionArray['200'])!=0)
			{
				return $invoiceResult;
			}
		}
		else
		{
			$mytime = Carbon\Carbon::now();
			//update bill data
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("update 
			sales_bill set 
			product_array='".$productArray."',
			payment_mode='".$paymentMode."',
			job_card_number='".$jobCardNumber."',
			bank_name='".$bankName."',
			check_number='".$checkNumber."',
			total='".$total."',
			total_discounttype='".$totalDiscounttype."',
			total_discount='".$totalDiscount."',
			extra_charge='".$extraCharge."',
			tax='".$tax."',
			grand_total='".$grandTotal."',
			advance='".$advance."',
			balance='".$balance."',
			po_number='".$poNumber."',
			remark='".$remark."',
			entry_date='".$entryDate."',
			company_id='".$companyId."',
			client_id='".$ClientId."',
			sales_type='".$salesType."',
			jf_id='".$jfId."',
			updated_at='".$mytime."' 
			where job_card_number='".$jobCardNumber."' and 
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
		}
		if($raw==1)
		{
			DB::beginTransaction();
			$saleId = DB::connection($databaseName)->select("SELECT 
			max(sale_id) sale_id
			FROM sales_bill where deleted_at='0000-00-00 00:00:00' and is_draft='no'");
			DB::commit();
			
			//insertion in sale bill transaction
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->statement("insert into sales_bill_trn(
			product_array,
			payment_mode,
			invoice_number,
			job_card_number,
			bank_name,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			company_id,
			client_id,
			sales_type,
			sale_id,
			jf_id) 
			values('".$productArray."','".$paymentMode."','".$invoiceNumber."','".$jobCardNumber."','".$bankName."','".$checkNumber."','".$total."','".$totalDiscounttype."','".$totalDiscount."','".$extraCharge."','".$tax."','".$grandTotal."','".$advance."','".$balance."','".$poNumber."','".$remark."','".$entryDate."','".$companyId."','".$ClientId."','".$salesType."','".$saleId[0]->sale_id."','".$jfId."')");
			DB::commit();
			//get latest inserted sale bill data
			DB::beginTransaction();
			$billResult = DB::connection($databaseName)->select("select
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			job_card_number,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			client_id,
			sales_type,
			company_id,
			jf_id,
			created_at,
			updated_at 
			from sales_bill where sale_id=(select MAX(sale_id) as sale_id from sales_bill) and deleted_at='0000-00-00 00:00:00' and is_draft='no'"); 
			DB::commit();
			if(count($billResult)==1)
			{
				return json_encode($billResult);
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
	 * insertion of draft-bill data
	 * @param  request-input array
	 * returns the exception-message/status
	*/
	public function insertBillDraftData()
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$inputData = func_get_arg(0);
		$clientFlag=0;
		$clientId=0;
		if(array_key_exists('contactNo',$inputData))
		{
			if($inputData['contactNo']!=0 && $inputData['contactNo']!='')
			{
				//get client-id of this contact-no
				//check client is exists by contact-number
				$clientModel = new ClientModel();
				$clientArrayData = $clientModel->getClientData($inputData['contactNo']);
				$clientData = (json_decode($clientArrayData));
				if(is_array($clientData) || is_object($clientData))
				{
					$clientFlag=1;
					$clientId = $clientData->clientData[0]->client_id;
				}
			}
		}
		$clientArray = new ClientArray();
		$clientBillArrayData = $clientArray->getBillClientArrayData();
		//splice data from trim array
		for($index=0;$index<count($clientBillArrayData);$index++)
		{
			if(array_key_exists($clientBillArrayData[array_keys($clientBillArrayData)[$index]],$inputData))
			{
				unset($inputData[$clientBillArrayData[array_keys($clientBillArrayData)[$index]]]);
			}
		}
		$inventoryDecodedData = json_encode($inputData['inventory']);
		unset($inputData['inventory']);	
		$inputDataCount = count($inputData);
		$newInputArray = array();
		$keyString='';
		$valueString='';
		for($billData=0;$billData<$inputDataCount;$billData++)
		{
			$conversion= preg_replace('/(?<!\ )[A-Z]/', '_$0', array_keys($inputData)[$billData]);
			$lowerCase = strtolower($conversion);
			// $newInputArray[$lowerCase] = $inputData[array_keys($inputData)[$billData]];
			$keyString = $keyString.$lowerCase.",";
			$valueString = $valueString."'".$inputData[array_keys($inputData)[$billData]]."',";
		}	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		//insert dsale-bill draft data
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into sales_bill
		(".$keyString."product_array,client_id,is_draft)
		values(".$valueString."'".$inventoryDecodedData."','".$clientId."','yes')");
		DB::commit();
		if($raw==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}			
	}
	
	/**
	 * after insertion bill data update invoice-number
	 * @param  compant-id
	 * returns the exception-message
	*/
	public function updateInvoiceNumber($companyId)
	{
		//get constants from constant class
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$invoiceService = new InvoiceService();	
		$invoiceData = $invoiceService->getLatestInvoiceData($companyId);
		if(strcmp($exceptionArray['204'],$invoiceData)==0)
		{
			return $invoiceData;
		}
		$endAt = json_decode($invoiceData)->endAt;
		$invoiceController = new InvoiceController(new Container());
		$invoiceMethod=$constantArray['postMethod'];
		$invoicePath=$constantArray['invoiceUrl'];
		$invoiceDataArray = array();
		
		$invoiceDataArray['endAt'] = $endAt+1;
		$invoiceRequest = Request::create($invoicePath,$invoiceMethod,$invoiceDataArray);
		$updateResult = $invoiceController->update($invoiceRequest,json_decode($invoiceData)->invoiceId);
		return $updateResult;
	}
	
	/**
	 * insert document data
	 * @param  sale-id,document-name,document-format,document-type
	 * returns the exception-message
	*/
	public function billDocumentData($saleId,$documentName,$documentFormat,$documentType)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		//insert document data into sale-bill-document table
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
		sale_id,
		document_name,
		document_format,
		document_type)
		values('".$saleId."','".$documentName."','".$documentFormat."','".$documentType."')");
		DB::commit();
		
		//get client-id from sale-bill
		DB::beginTransaction();
		$saleBillData = DB::connection($databaseName)->select("SELECT 
		sale_id,
		client_id
		FROM sales_bill where sale_id='".$saleId."' and deleted_at='0000-00-00 00:00:00' and is_draft='no'");
		DB::commit();
		
		//insert document data into client-document table
		DB::beginTransaction();
		$clientDocumentInsertion = DB::connection($databaseName)->statement("insert into client_doc_dtl(
		sale_id,
		document_name,
		document_format,
		document_type,
		client_id)
		values('".$saleId."','".$documentName."','".$documentFormat."','".$documentType."','".$saleBillData[0]->client_id."')");
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
	
	/**
	 * get bill-document data
	 * @param  company-id,sales-type,from-date,to-date
	 * returns the exception-message
	*/
	public function getSpecifiedData($companyId,$data)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(is_object($data))
		{
			$salesType = $data->getSalesType();
			$fromDate = $data->getFromDate();
			$toDate = $data->getToDate();
		
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->select("select 
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			job_card_number,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
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
			where sales_type='".$salesType."' and
			(entry_date BETWEEN '".$fromDate."' AND '".$toDate."') and 
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
						// return $exceptionArray['404'];
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
		else if(is_array($data))
		{
			DB::beginTransaction();
			$raw = DB::connection($databaseName)->select("select 
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			job_card_number,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
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
			where sales_type='".$data['salestype'][0]."' and is_draft='no' and 
			company_id='".$companyId."' and 
			deleted_at='0000-00-00 00:00:00' and 
			(invoice_number='".$data['invoicenumber'][0]."' or client_id in ( select client_id from client_mst where contact_no = '".$data['invoicenumber'][0]."') or client_id in ( select client_id from client_mst where email_id = '".$data['invoicenumber'][0]."') or client_id in ( select client_id from client_mst where client_name like '%".$data['invoicenumber'][0]."%')) ");
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
						// return $exceptionArray['404'];
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
	
	/**
	 * get bill-draft data
	 * @param 
	 * returns the exception-message/sales data
	*/
	public function getBillDraftData()
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
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where deleted_at='0000-00-00 00:00:00' and is_draft='yes'");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['404']; 
		}
		else
		{
			$encodedData = json_encode($raw);
			return $encodedData;
		}
	}
	
	/**
	 * get bill data
	 * @param  sale_id
	 * returns the exception-message/sales data
	*/
	public function getSaleIdData($saleId)
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
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where sale_id='".$saleId."' and 
		deleted_at='0000-00-00 00:00:00' and is_draft='no'");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['404']; 
		}
		else
		{
			$encodedData = json_encode($raw);
			return $encodedData;
		}
	}
	
	/**
	 * get previous-next bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getPreviousNextData($headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(array_key_exists('previoussaleid',$headerData))
		{
			if($headerData['previoussaleid'][0]==0)
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->select("select 
				sale_id,
				product_array,
				payment_mode,
				bank_name,
				invoice_number,
				check_number,
				job_card_number,
				total,
				total_discounttype,
				total_discount,
				extra_charge,
				tax,
				grand_total,
				advance,
				balance,
				po_number,
				remark,
				entry_date,
				client_id,
				sales_type,
				refund,
				company_id,
				jf_id,
				created_at,
				updated_at 
				from sales_bill 
				where sales_type='".$headerData['salestype'][0]."' and
				company_id = '".$headerData['companyid'][0]."' and
				deleted_at='0000-00-00 00:00:00' and is_draft='no'
				order by sale_id desc limit 1");
				DB::commit();
				if(count($raw)==0)
				{
					return $exceptionArray['204'];
				}
				else
				{
					$saleDataResult = $this->getDocumentData($raw);
					return $saleDataResult;
				}
			}
			else
			{
				$saleId = $headerData['previoussaleid'][0]-1;
				$result = $this->getSalePreviousNextData($headerData,$saleId);
				if(count($result)==0)
				{
					DB::beginTransaction();
					$previousAscId = DB::connection($databaseName)->select("select 
					sale_id
					from sales_bill 
					where sales_type='".$headerData['salestype'][0]."' and
					company_id = '".$headerData['companyid'][0]."' and
					deleted_at='0000-00-00 00:00:00' and is_draft='no'
					order by sale_id asc limit 1");
					DB::commit();
					if($saleId<$previousAscId[0]->sale_id)
					{
						return $exceptionArray['204'];
					}
					else
					{
						for($arrayData=$saleId-1;$arrayData>=$previousAscId[0]->sale_id;$arrayData--)
						{
							$innerResult = $this->getSalePreviousNextData($headerData,$arrayData);
							if(count($innerResult)!=0)
							{
								break;
							}
							if($arrayData==$previousAscId[0]->sale_id && count($innerResult)==0)
							{
								return $exceptionArray['204'];
							}
							$saleId++;
						}
						$saleDataResult = $this->getDocumentData($innerResult);
						return $saleDataResult;
					}
				}
				else
				{
					$saleDataResult = $this->getDocumentData($result);
					return $saleDataResult;
				}
			}
		}
		else if(array_key_exists('nextsaleid',$headerData))
		{
			$saleId = $headerData['nextsaleid'][0]+1;
			$result = $this->getSalePreviousNextData($headerData,$saleId);
			if(count($result)==0)
			{
				DB::beginTransaction();
				$nextDescId = DB::connection($databaseName)->select("select 
				sale_id
				from sales_bill 
				where sales_type='".$headerData['salestype'][0]."' and
				company_id = '".$headerData['companyid'][0]."' and
				deleted_at='0000-00-00 00:00:00' and is_draft='no'
				order by sale_id desc limit 1");
				DB::commit();
				if($saleId>$nextDescId[0]->sale_id)
				{
					return $exceptionArray['204'];
				}
				else
				{
					for($arrayData=$saleId+1;$arrayData<=$nextDescId[0]->sale_id;$arrayData++)
					{
						$innerResult = $this->getSalePreviousNextData($headerData,$arrayData);
						if(count($innerResult)!=0)
						{
							break;
						}
						if($arrayData==$nextDescId[0]->sale_id && count($innerResult)==0)
						{
							return $exceptionArray['204'];
						}
						$saleId++;
					}
					$saleDataResult = $this->getDocumentData($innerResult);
					return $saleDataResult;
				}
			}
			else
			{
				$saleDataResult = $this->getDocumentData($result);
				return $saleDataResult;
			}
		}
		else if(array_key_exists('operation',$headerData))
		{
			if(strcmp($headerData['operation'][0],'first')==0)
			{
				DB::beginTransaction();
				$fistSaleDataResult = DB::connection($databaseName)->select("select 
				sale_id,
				product_array,
				payment_mode,
				bank_name,
				invoice_number,
				job_card_number,
				check_number,
				total,
				total_discounttype,
				total_discount,
				extra_charge,
				tax,
				grand_total,
				advance,
				balance,
				po_number,
				remark,
				entry_date,
				client_id,
				sales_type,
				refund,
				company_id,
				jf_id,
				created_at,
				updated_at 
				from sales_bill 
				where sales_type='".$headerData['salestype'][0]."' and
				company_id = '".$headerData['companyid'][0]."' and
				deleted_at='0000-00-00 00:00:00' and is_draft='no' order by sale_id asc limit 1");
				DB::commit();
				
				$saleDataResult = $this->getDocumentData($fistSaleDataResult);
				return $saleDataResult;
			}
			else if(strcmp($headerData['operation'][0],'last')==0)
			{
				DB::beginTransaction();
				$lastSaleDataResult = DB::connection($databaseName)->select("select 
				sale_id,
				product_array,
				payment_mode,
				bank_name,
				invoice_number,
				job_card_number,
				check_number,
				total,
				total_discounttype,
				total_discount,
				extra_charge,
				tax,
				grand_total,
				advance,
				balance,
				po_number,
				remark,
				entry_date,
				client_id,
				sales_type,
				refund,
				company_id,
				jf_id,
				created_at,
				updated_at 
				from sales_bill 
				where sales_type='".$headerData['salestype'][0]."' and
				company_id = '".$headerData['companyid'][0]."' and
				deleted_at='0000-00-00 00:00:00' and is_draft='no' order by sale_id desc limit 1");
				DB::commit();
				
				$saleDataResult = $this->getDocumentData($lastSaleDataResult);
				return $saleDataResult;
			}
		}
	}
	
	/**
	 * get previous bill data
	 * @param  header-data
	 * returns the exception-message/sales data
	*/
	public function getSalePreviousNextData($headerData,$saleId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$saleData = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where sales_type='".$headerData['salestype'][0]."' and
		company_id = '".$headerData['companyid'][0]."' and
		deleted_at='0000-00-00 00:00:00' and 
		sale_id='".$saleId."' and is_draft='no'");
		DB::commit();
		return $saleData;
	}
	
	/**
	 * get document bill data(internal call)
	 * @param  bill-data
	 * returns the sales-data
	*/
	public function getDocumentData($saleArrayData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$documentResult = array();
		for($saleData=0;$saleData<count($saleArrayData);$saleData++)
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
			where sale_id='".$saleArrayData[$saleData]->sale_id."' and 
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
		$salesArrayData['salesData'] = json_encode($saleArrayData);
		$salesArrayData['documentData'] = json_encode($documentResult);
		return json_encode($salesArrayData);
	}
	
	/**
	 * update payment bill data
	 * @param  bill array data
	 * returns the exception-message/status
	*/
	public function updatePaymentData($arrayData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$paymentTransaction = $arrayData->payment_transaction;
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(strcmp($arrayData->payment_mode,"bank")==0)
		{
			if(strcmp($paymentTransaction,"payment")==0)
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("update
				sales_bill set
				payment_mode = '".$arrayData->payment_mode."',
				advance = '".$arrayData->advance."',
				balance = '".$arrayData->balance."'	,
				bank_name = '".$arrayData->bank_name."',
				check_number = '".$arrayData->check_number."',
				entry_date = '".$arrayData->entry_date."',
				updated_at = '".$mytime."'
				where sale_id = ".$arrayData->sale_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
			else
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("update
				sales_bill set
				payment_mode = '".$arrayData->payment_mode."',
				advance = '".$arrayData->advance."',
				balance = '".$arrayData->balance."'	,
				bank_name = '".$arrayData->bank_name."',
				refund = '".$arrayData->refund."',
				check_number = '".$arrayData->check_number."',
				entry_date = '".$arrayData->entry_date."',
				updated_at = '".$mytime."'
				where sale_id = ".$arrayData->sale_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
			
		}
		else
		{
			if(strcmp($paymentTransaction,"payment")==0)
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("update
				sales_bill set
				payment_mode = '".$arrayData->payment_mode."',
				advance = '".$arrayData->advance."',
				balance = '".$arrayData->balance."',
				entry_date = '".$arrayData->entry_date."',
				updated_at = '".$mytime."'
				where sale_id = ".$arrayData->sale_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
			else
			{
				DB::beginTransaction();
				$raw = DB::connection($databaseName)->statement("update
				sales_bill set
				payment_mode = '".$arrayData->payment_mode."',
				advance = '".$arrayData->advance."',
				refund = '".$arrayData->refund."',
				balance = '".$arrayData->balance."',
				entry_date = '".$arrayData->entry_date."',
				updated_at = '".$mytime."'
				where sale_id = ".$arrayData->sale_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		if($raw!=1)
		{
			return $exceptionArray['500']; 
		}
		$saleIdData = $this->getSaleIdData($arrayData->sale_id);
		$jsonDecodedSaleData = json_decode($saleIdData);
		
		DB::beginTransaction();
		$saleTrnInsertionResult = DB::connection($databaseName)->statement("insert
		into sales_bill_trn(
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		job_card_number,
		check_number,
		total,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		payment_trn,
		refund,
		entry_date,
		client_id,
		sales_type,
		company_id,
		jf_id,
		created_at,
		updated_at)
		values(
		'".$jsonDecodedSaleData[0]->sale_id."',
		'".$jsonDecodedSaleData[0]->product_array."',
		'".$jsonDecodedSaleData[0]->payment_mode."',
		'".$jsonDecodedSaleData[0]->bank_name."',
		'".$jsonDecodedSaleData[0]->invoice_number."',
		'".$jsonDecodedSaleData[0]->job_card_number."',
		'".$jsonDecodedSaleData[0]->check_number."',
		'".$jsonDecodedSaleData[0]->total."',
		'".$jsonDecodedSaleData[0]->extra_charge."',
		'".$jsonDecodedSaleData[0]->tax."',
		'".$jsonDecodedSaleData[0]->grand_total."',
		'".$jsonDecodedSaleData[0]->advance."',
		'".$jsonDecodedSaleData[0]->balance."',
		'".$jsonDecodedSaleData[0]->po_number."',
		'".$jsonDecodedSaleData[0]->remark."',
		'".$paymentTransaction."',
		'".$jsonDecodedSaleData[0]->refund."',
		'".$jsonDecodedSaleData[0]->entry_date."',
		'".$jsonDecodedSaleData[0]->client_id."',
		'".$jsonDecodedSaleData[0]->sales_type."',
		'".$jsonDecodedSaleData[0]->company_id."',
		'".$jsonDecodedSaleData[0]->jf_id."',
		'".$jsonDecodedSaleData[0]->created_at."',
		'".$jsonDecodedSaleData[0]->updated_at."')");
		DB::commit();
		if($saleTrnInsertionResult!=1)
		{
			return $exceptionArray['500']; 
		}
		else
		{
			return $exceptionArray['200'];
		}
	}
	
	/**
	 * update bill data
	 * @param  sale-id and bill-data array and image Array
	 * returns the exception-message/status
	*/
	public function updateBillData($billArray,$saleId,$documentArray)
	{
		$mytime = Carbon\Carbon::now();
	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$keyValueString = "";
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(isset($documentArray) && !empty($documentArray))
		{
			for($docArray=0;$docArray<count($documentArray);$docArray++)
			{
				DB::beginTransaction();
				$documentResult = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
				sale_id,
				document_name,
				document_size,
				document_format) 
				values('".$saleId."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][1]."','".$documentArray[$docArray][2]."')");
				DB::commit();
				
				//get client-id from sale-bill
				DB::beginTransaction();
				$saleBillData = DB::connection($databaseName)->select("SELECT 
				sale_id,
				client_id
				FROM sales_bill where sale_id='".$saleId."' and deleted_at='0000-00-00 00:00:00' and is_draft='no'");
				DB::commit();
				
				//insert document data into client-document table
				DB::beginTransaction();
				$clientDocumentInsertion = DB::connection($databaseName)->statement("insert into client_doc_dtl(
				sale_id,
				document_name,
				document_format,
				document_size,
				client_id)
				values('".$saleId."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][2]."','".$documentArray[$docArray][1]."','".$saleBillData[0]->client_id."')");
				DB::commit();
				if($documentResult==0)
				{
					return $exceptionArray['500'];
				}
			}	
		}
		
		for($billArrayData=0;$billArrayData<count($billArray);$billArrayData++)
		{
			$keyValueString = $keyValueString.array_keys($billArray)[$billArrayData]." = '".$billArray[array_keys($billArray)[$billArrayData]]."',";
		}
		// update bill-date
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update
		sales_bill set
		".$keyValueString."
		updated_at = '".$mytime."'
		where sale_id = ".$saleId." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		if($raw==1)
		{
			$saleData = $this->getSaleIdData($saleId);
			$jsonDecodedSaleData = json_decode($saleData);
			
			//insert bill data in bill_trn 
			DB::beginTransaction();
			$saleTrnInsertionResult = DB::connection($databaseName)->statement("insert
			into sales_bill_trn(
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			job_card_number,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			client_id,
			sales_type,
			company_id,
			jf_id,
			created_at,
			updated_at)
			values(
			'".$jsonDecodedSaleData[0]->sale_id."',
			'".$jsonDecodedSaleData[0]->product_array."',
			'".$jsonDecodedSaleData[0]->payment_mode."',
			'".$jsonDecodedSaleData[0]->bank_name."',
			'".$jsonDecodedSaleData[0]->invoice_number."',
			'".$jsonDecodedSaleData[0]->job_card_number."',
			'".$jsonDecodedSaleData[0]->check_number."',
			'".$jsonDecodedSaleData[0]->total."',
			'".$jsonDecodedSaleData[0]->total_discounttype."',
			'".$jsonDecodedSaleData[0]->total_discount."',
			'".$jsonDecodedSaleData[0]->extra_charge."',
			'".$jsonDecodedSaleData[0]->tax."',
			'".$jsonDecodedSaleData[0]->grand_total."',
			'".$jsonDecodedSaleData[0]->advance."',
			'".$jsonDecodedSaleData[0]->balance."',
			'".$jsonDecodedSaleData[0]->po_number."',
			'".$jsonDecodedSaleData[0]->remark."',
			'".$jsonDecodedSaleData[0]->entry_date."',
			'".$jsonDecodedSaleData[0]->client_id."',
			'".$jsonDecodedSaleData[0]->sales_type."',
			'".$jsonDecodedSaleData[0]->company_id."',
			'".$jsonDecodedSaleData[0]->jf_id."',
			'".$jsonDecodedSaleData[0]->created_at."',
			'".$jsonDecodedSaleData[0]->updated_at."')");
			DB::commit();
			if($saleTrnInsertionResult!=1)
			{
				return $exceptionArray['500']; 
			}
			else
			{
				return $exceptionArray['200'];
			}
		}
	}
	
	/**
	 * update bill-entry date
	 * @param  sale-id and bill-entryDate
	 * returns the exception-message/status
	*/
	public function updateBillEntryData($entryDate,$saleId)
	{
		$mytime = Carbon\Carbon::now();
	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// update bill-date
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("update
		sales_bill set
		entry_date = '".$entryDate."',
		updated_at = '".$mytime."'
		where sale_id = ".$saleId." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if($raw==1)
		{
			$saleData = $this->getSaleIdData($saleId);
			$jsonDecodedSaleData = json_decode($saleData);
			
			//insert bill data in bill_trn 
			DB::beginTransaction();
			$saleTrnInsertionResult = DB::connection($databaseName)->statement("insert
			into sales_bill_trn(
			sale_id,
			product_array,
			payment_mode,
			bank_name,
			invoice_number,
			job_card_number,
			check_number,
			total,
			total_discounttype,
			total_discount,
			extra_charge,
			tax,
			grand_total,
			advance,
			balance,
			po_number,
			remark,
			entry_date,
			client_id,
			sales_type,
			company_id,
			jf_id,
			created_at,
			updated_at)
			values(
			'".$jsonDecodedSaleData[0]->sale_id."',
			'".$jsonDecodedSaleData[0]->product_array."',
			'".$jsonDecodedSaleData[0]->payment_mode."',
			'".$jsonDecodedSaleData[0]->bank_name."',
			'".$jsonDecodedSaleData[0]->invoice_number."',
			'".$jsonDecodedSaleData[0]->job_card_number."',
			'".$jsonDecodedSaleData[0]->check_number."',
			'".$jsonDecodedSaleData[0]->total."',
			'".$jsonDecodedSaleData[0]->total_discounttype."',
			'".$jsonDecodedSaleData[0]->total_discount."',
			'".$jsonDecodedSaleData[0]->extra_charge."',
			'".$jsonDecodedSaleData[0]->tax."',
			'".$jsonDecodedSaleData[0]->grand_total."',
			'".$jsonDecodedSaleData[0]->advance."',
			'".$jsonDecodedSaleData[0]->balance."',
			'".$jsonDecodedSaleData[0]->po_number."',
			'".$jsonDecodedSaleData[0]->remark."',
			'".$jsonDecodedSaleData[0]->entry_date."',
			'".$jsonDecodedSaleData[0]->client_id."',
			'".$jsonDecodedSaleData[0]->sales_type."',
			'".$jsonDecodedSaleData[0]->company_id."',
			'".$jsonDecodedSaleData[0]->jf_id."',
			'".$jsonDecodedSaleData[0]->created_at."',
			'".$jsonDecodedSaleData[0]->updated_at."')");
			DB::commit();
			if($saleTrnInsertionResult!=1)
			{
				return $exceptionArray['500']; 
			}
			else
			{
				return $exceptionArray['200'];
			}
		}
	}
	
	/**
	 * update image data
	 * @param  image-array and saleId
	 * returns the exception-message/status
	*/
	public function updateImageData($saleId,$documentArray)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(isset($documentArray) && !empty($documentArray))
		{
			for($docArray=0;$docArray<count($documentArray);$docArray++)
			{
				DB::beginTransaction();
				$documentResult = DB::connection($databaseName)->statement("insert into sales_bill_doc_dtl(
				sale_id,
				document_name,
				document_size,
				document_format) 
				values('".$saleId."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][1]."','".$documentArray[$docArray][2]."')");
				DB::commit();
				
				//get client-id from sale-bill
				DB::beginTransaction();
				$saleBillData = DB::connection($databaseName)->select("SELECT 
				sale_id,
				client_id
				FROM sales_bill 
				where sale_id='".$saleId."' and 
				deleted_at='0000-00-00 00:00:00' 
				and is_draft='no'");
				DB::commit();
				
				//insert document data into client-document table
				DB::beginTransaction();
				$clientDocumentInsertion = DB::connection($databaseName)->statement("insert into client_doc_dtl(
				sale_id,
				document_name,
				document_format,
				document_size,
				client_id)
				values('".$saleId."','".$documentArray[$docArray][0]."','".$documentArray[$docArray][2]."','".$documentArray[$docArray][1]."','".$saleBillData[0]->client_id."')");
				DB::commit();
				if($documentResult==0)
				{
					return $exceptionArray['500'];
				}
			}
			return $exceptionArray['200'];		
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * get last 2 records of transaction data
	 * @param  saleId
	 * returns the exception-message/status
	*/
	public function getTransactionData($saleId)
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
		sale_trn_id,
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		company_id,
		jf_id,
		payment_trn,
		refund,
		created_at,
		updated_at
		from sales_bill_trn 
		where sale_id='".$saleId."' and 
		deleted_at='0000-00-00 00:00:00'
		ORDER BY sale_trn_id DESC
		limit 2");
		DB::commit();
		if(count($raw)==0)
		{
			return $exceptionArray['500'];
		}
			
		else
		{
			return $raw;
		}
	}
	
	/**
	 * get bill data
	 * @param  invoice-number
	 * returns the exception-message/data
	*/
	public function getInvoiceNumberData($invoiceNumber)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$billData = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where invoice_number='".$invoiceNumber."' and
		deleted_at='0000-00-00 00:00:00' and 
		is_draft='no'");
		DB::commit();
		if(count($billData)!=0)
		{
			return json_encode($billData);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get bill data
	 * @param  fromdate,todate
	 * returns the exception-message/data
	*/
	public function getFromToDateData($fromDate,$toDate)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$billData = DB::connection($databaseName)->select("select 
		sale_id,
		product_array,
		payment_mode,
		bank_name,
		invoice_number,
		job_card_number,
		check_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		po_number,
		remark,
		entry_date,
		client_id,
		sales_type,
		refund,
		company_id,
		jf_id,
		created_at,
		updated_at 
		from sales_bill 
		where (entry_Date BETWEEN '".$fromDate."' AND '".$toDate."') and
		deleted_at='0000-00-00 00:00:00' and is_draft='no'");
		DB::commit();
		if(count($billData)!=0)
		{
			return json_encode($billData);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * delete bill data
	 * @param  sale-id
	 * returns the exception-message/status
	*/
	public function deleteBillData($saleId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		$mytime = Carbon\Carbon::now();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$saleIdData = $this->getSaleIdData($saleId);
		$jsonDecodedSaleData = json_decode($saleIdData);
		$productArray = $jsonDecodedSaleData[0]->product_array;
		$inventoryCount = count(json_decode($productArray)->inventory);
		for($productArrayData=0;$productArrayData<$inventoryCount;$productArrayData++)
		{
			$inventoryData = json_decode($productArray)->inventory;
			DB::beginTransaction();
			$getTransactionSummaryData[$productArrayData] = DB::connection($databaseName)->select("select 
			product_trn_summary_id,
			qty
			from product_trn_summary
			where product_id='".$inventoryData[$productArrayData]->productId."' and
			deleted_at='0000-00-00 00:00:00'");
			DB::commit();
			if(count($getTransactionSummaryData[$productArrayData])==0)
			{
				//insert data
				DB::beginTransaction();
				$insertionResult[$productArrayData] = DB::connection($databaseName)->statement("insert into 
				product_trn_summary(qty,company_id,branch_id,product_id)
				values('".$inventoryData[$productArrayData]->qty."',
					   '".$jsonDecodedSaleData[0]->company_id."',
					   0,
					   '".$inventoryData[$productArrayData]->productId."')");
				DB::commit();
			}
			else
			{
				$qty = $getTransactionSummaryData[$productArrayData][0]->qty+$inventoryData[$productArrayData]->qty;
				//update data
				DB::beginTransaction();
				$updateResult = DB::connection($databaseName)->statement("update 
				product_trn_summary set qty='".$qty."'
				where product_trn_summary_id='".$getTransactionSummaryData[$productArrayData][0]->product_trn_summary_id."' and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		if(strcmp($saleIdData,$exceptionArray['404'])==0)
		{
			return $exceptionArray['404'];
		}
		//get ledger id from journal
		$journalModel = new JournalModel();
		$journalData = $journalModel->getJfIdArrayData($jsonDecodedSaleData[0]->jf_id);
		$jsonDecodedJournalData = json_decode($journalData);
		
		if(strcmp($journalData,$exceptionArray['404'])!=0)
		{
			foreach ($jsonDecodedJournalData as $value)
			{
				//delete ledgerId_ledger_dtl data as per given ledgerId and jf_id
				DB::beginTransaction();
				$deleteLedgerData = DB::connection($databaseName)->statement("update
				".$value->ledger_id."_ledger_dtl set
				deleted_at = '".$mytime."'
				where jf_id = ".$jsonDecodedSaleData[0]->jf_id." and
				deleted_at='0000-00-00 00:00:00'");
				DB::commit();
			}
		}
		//delete journal data
		DB::beginTransaction();
		$deleteJournalData = DB::connection($databaseName)->statement("update
		journal_dtl set
		deleted_at = '".$mytime."'
		where jf_id = ".$jsonDecodedSaleData[0]->jf_id." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
			
		//delete product_trn data
		DB::beginTransaction();
		$deleteProductTrnData = DB::connection($databaseName)->statement("update
		product_trn set
		deleted_at = '".$mytime."'
		where jf_id = ".$jsonDecodedSaleData[0]->jf_id." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//delete bill data 
		DB::beginTransaction();
		$deleteBillData = DB::connection($databaseName)->statement("update
		sales_bill set
		deleted_at = '".$mytime."'
		where sale_id = ".$saleId." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//delete bill-transaction data 
		DB::beginTransaction();
		$deleteBillTrnData = DB::connection($databaseName)->statement("update
		sales_bill_trn set
		deleted_at = '".$mytime."'
		where sale_id = ".$saleId." and
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		
		if($deleteJournalData==1 && $deleteProductTrnData==1 && $deleteBillData==1 && $deleteBillTrnData==1)
		{
			return $exceptionArray['200'];
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
}
