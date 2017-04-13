<?php
namespace ERP\Model\Companies;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\EnumClasses\IsDefaultEnum;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Settings\Templates\Entities\TemplateDesign;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyModel extends Model
{
	/**
	 * insert data with document
	 * returns the status
	*/
	public function insertAllData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$constantArray = $constantDatabase->constantVariable();
		
		$getCompanyData = array();
		$getCompanyKey = array();
		$getCompanyData = func_get_arg(0);
		$getCompanyKey = func_get_arg(1);
		$getDocumentData = func_get_arg(2);
		$companyData="";
		$keyName = "";
		for($data=0;$data<count($getCompanyData);$data++)
		{
			if($data == (count($getCompanyData)-1))
			{
				$companyData = $companyData."'".$getCompanyData[$data]."'";
				$keyName =$keyName.$getCompanyKey[$data];
			}
			else
			{
				$companyData = $companyData."'".$getCompanyData[$data]."',";
				$keyName =$keyName.$getCompanyKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into company_mst(".$keyName.",document_name,document_size,document_format) 
		values(".$companyData.",'".$getDocumentData[0][0]."','".$getDocumentData[0][1]."','".$getDocumentData[0][2]."')");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			//branch insertion
			DB::beginTransaction();
			$companyId = DB::connection($databaseName)->select("SELECT 
			company_id,
			company_name,
			state_abb,
			city_id
			FROM `company_mst` 
			where deleted_at='0000-00-00 00:00:00'
			ORDER by company_id DESC limit 1");
			DB::commit();
			
			DB::beginTransaction();
			$branchInserion = DB::connection($databaseName)->statement("INSERT 
			into branch_mst(
			branch_name,
			address1,
			address2,
			pincode, 
			is_display, 
			is_default,
			updated_at,
			deleted_at,
			state_abb,
			city_id, 
			company_id)
			VALUES(
			'MainBranch',
			'address1',
			'address2', 
			'395000', 
			'yes',
			'not',
			'0000-00-00 00:00:00',
			'0000-00-00 00:00:00',
			'".$companyId[0]->state_abb."',
			'".$companyId[0]->city_id."', 
			'".$companyId[0]->company_id."')
			");
			DB::commit();
			
			//get Template
			$templateDesign = new TemplateDesign();
			$templateArray = $templateDesign->getTemplate();
			
			DB::beginTransaction();
			$invoiceInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$companyId[0]->company_name.' Invoice'."',
			'".$templateArray['invoice']."',
			'".$constantArray['invoice']."',
			'".$companyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$paymentInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$companyId[0]->company_name.' Payment'."',
			'".$templateArray['payment']."',
			'".$constantArray['paymentType']."',
			'".$companyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$emailInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$companyId[0]->company_name.' Email'."',
			'".$templateArray['email']."',
			'".$constantArray['emailType']."',
			'".$companyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$smsInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$companyId[0]->company_name.' Sms'."',
			'".$templateArray['sms']."',
			'".$constantArray['smsType']."',
			'".$companyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$blankInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$companyId[0]->company_name.' Blank'."',
			'".$templateArray['blank']."',
			'".$constantArray['blankType']."',
			'".$companyId[0]->company_id."')");
			DB::commit();
			
			if($invoiceInsertion!=1 && $paymentInsertion!=1 && $emailInsertion!=1 && $smsInsertion!=1 && $blankInsertion!=1)
			{
				return $exceptionArray['500'];
			}
			return $companyId;
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	
	/**
	 * insert only data 
	 * returns the status
	*/
	public function insertData()
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$constantArray = $constantDatabase->constantVariable();
		
		$getCompanyData = array();
		$getCompanyKey = array();
		$getCompanyData = func_get_arg(0);
		$getCompanyKey = func_get_arg(1);
		$companyData="";
		$keyName = "";
		for($data=0;$data<count($getCompanyData);$data++)
		{
			if($data == (count($getCompanyData)-1))
			{
				$companyData = $companyData."'".$getCompanyData[$data]."'";
				$keyName =$keyName.$getCompanyKey[$data];
			}
			else
			{
				$companyData = $companyData."'".$getCompanyData[$data]."',";
				$keyName =$keyName.$getCompanyKey[$data].",";
			}
		}
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->statement("insert into company_mst(".$keyName.") 
		values(".$companyData.")");
		DB::commit();
		
		//get latest company_id
		DB::beginTransaction();
		$latestCompanyId = DB::connection($databaseName)->select("SELECT 
		company_id,
		company_name,
		state_abb,
		city_id
		FROM `company_mst` 
		where deleted_at='0000-00-00 00:00:00'
      	ORDER by company_id DESC limit 1");
		DB::commit();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			DB::beginTransaction();
			$branchInsertion = DB::connection($databaseName)->statement("INSERT 
			into branch_mst(
			branch_name,
			address1,
			address2,
			pincode, 
			is_display, 
			is_default,
			updated_at,
			deleted_at,
			state_abb,
			city_id, 
			company_id)
			VALUES(
			'MainBranch',
			'address1',
			'address2', 
			'395000', 
			'yes',
			'not',
			'0000-00-00 00:00:00',
			'0000-00-00 00:00:00',
			'".$latestCompanyId[0]->state_abb."',
			'".$latestCompanyId[0]->city_id."', 
			'".$latestCompanyId[0]->company_id."')
			");
			DB::commit();
			//get Template
			$templateDesign = new TemplateDesign();
			$templateArray = $templateDesign->getTemplate();
			
			DB::beginTransaction();
			$invoiceInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$latestCompanyId[0]->company_name.' Invoice'."',
			'".$templateArray['invoice']."',
			'".$constantArray['invoice']."',
			'".$latestCompanyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$paymentInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$latestCompanyId[0]->company_name.' Payment'."',
			'".$templateArray['payment']."',
			'".$constantArray['paymentType']."',
			'".$latestCompanyId[0]->company_id."')");
			DB::commit();
		
			DB::beginTransaction();
			$emailInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$latestCompanyId[0]->company_name.' Email'."',
			'".$templateArray['email']."',
			'".$constantArray['emailType']."',
			'".$latestCompanyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$smsInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$latestCompanyId[0]->company_name.' Sms'."',
			'".$templateArray['sms']."',
			'".$constantArray['smsType']."',
			'".$latestCompanyId[0]->company_id."')");
			DB::commit();
			
			DB::beginTransaction();
			$blankInsertion = DB::connection($databaseName)->statement("insert
			into template_mst(
			template_name,
			template_body,
			template_type,
			company_id)
			values(
			'".$latestCompanyId[0]->company_name.' Blank'."',
			'".$templateArray['blank']."',
			'".$constantArray['blankType']."',
			'".$latestCompanyId[0]->company_id."')");
			DB::commit();
			
			if($invoiceInsertion!=1 && $paymentInsertion!=1 && $emailInsertion!=1 && $smsInsertion!=1 && $blankInsertion!=1)
			{
				return $exceptionArray['500'];
			}
			// DB::beginTransaction();
			// $companyId = DB::connection($databaseName)->select("select
			// max(company_id) as company_id 
			// from company_mst where deleted_at='0000-00-00 00:00:00'");
			// DB::commit();
			return $latestCompanyId;
		}
		else
		{
			return $exceptionArray['500'];
		}
	}
	/**
	 * update data 
	 * @param company_id,company-data,key of company-data and document-data
	 * returns the status
	*/
	public function updateData($companyData,$key,$companyId,$documentData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// only one company is checked by default
		$enumIsDefArray = array();
		$isDefEnum = new IsDefaultEnum();
		$enumIsDefArray = $isDefEnum->enumArrays();
		for($keyData=0;$keyData<count($key);$keyData++)
		{
		    if(strcmp($key[array_keys($key)[$keyData]],"is_default")==0)
			{
				if(strcmp($companyData[$keyData],$enumIsDefArray['default'])==0)
				{
					$raw  = DB::connection($databaseName)->statement("update company_mst 
					set is_default='".$enumIsDefArray['notDefault']."',
					updated_at='".$mytime."' 
					where deleted_at = '0000-00-00 00:00:00'");
					if($raw==0)
					{
						return $exceptionArray['500'];
					}
				}
			}	
		}
		
		for($data=0;$data<count($companyData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$companyData[$data]."',";
		}
		$raw  = DB::connection($databaseName)->statement("update company_mst 
		set ".$keyValueString."updated_at='".$mytime."',
		document_name='".$documentData[0][0]."',
		document_size='".$documentData[0][1]."',
		document_format='".$documentData[0][2]."' 
		where company_id = '".$companyId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * update data 
	 * @param company_id,company-data,key of company-data
	 * returns the status
	*/
	public function updateCompanyData($companyData,$key,$companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		// only one company is checked by default
		$enumIsDefArray = array();
		$isDefEnum = new IsDefaultEnum();
		$enumIsDefArray = $isDefEnum->enumArrays();
		for($keyData=0;$keyData<count($key);$keyData++)
		{
		    if(strcmp($key[array_keys($key)[$keyData]],"is_default")==0)
			{
				if(strcmp($companyData[$keyData],$enumIsDefArray['default'])==0)
				{
					$raw  = DB::connection($databaseName)->statement("update company_mst 
					set is_default='".$enumIsDefArray['notDefault']."',updated_at='".$mytime."' 
					where deleted_at = '0000-00-00 00:00:00'");
					if($raw==0)
					{
						return $exceptionArray['500'];
					}
				}
			}	
		}
		for($data=0;$data<count($companyData);$data++)
		{
			$keyValueString=$keyValueString.$key[$data]."='".$companyData[$data]."',";
		}
		$raw  = DB::connection($databaseName)->statement("update company_mst 
		set ".$keyValueString."updated_at='".$mytime."'
		where company_id = '".$companyId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * update data 
	 * @param company_id,company-data,key of company-data
	 * returns the status
	*/
	public function updateDocumentData($companyId,$documentData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		$mytime = Carbon\Carbon::now();
		$keyValueString="";
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$raw  = DB::connection($databaseName)->statement("update company_mst 
		set document_name='".$documentData[0][0]."',
		document_size='".$documentData[0][1]."',
		document_format='".$documentData[0][2]."',
		updated_at='".$mytime."'
		where company_id = '".$companyId."' and deleted_at='0000-00-00 00:00:00'");
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
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();		
		$raw = DB::connection($databaseName)->select("select 
		company_id,
		company_name,
		company_display_name,
		address1,
		address2,
		pincode,
		pan,
		tin,
		vat_no,
		cgst,
		sgst,
		service_tax_no,
		basic_currency_symbol,
		formal_name,
		no_of_decimal_points,
		currency_symbol,
		document_name,
		document_size,
		document_format,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id 
		from company_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(count($raw)==0)
		{
			return $exceptionArray['204'];
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data as per given Company Id
	 * @param $companyId
	 * returns the status
	*/
	public function getData($companyId)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		company_id,
		company_name,
		company_display_name,
		address1,
		address2,
		pincode,
		pan,
		tin,
		vat_no,
		cgst,
		sgst,
		service_tax_no,
		basic_currency_symbol,
		formal_name,
		no_of_decimal_points,
		currency_symbol,
		document_name,
		document_size,
		document_format,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id 
		from company_mst where company_id = '".$companyId."' and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{
			$enocodedData = json_encode($raw,true); 	
			return $enocodedData;
		}
	}
	
	/**
	 * get particular company data
	 * @param $companyName
	 * returns the status
	*/
	public function getCompanyName($companyName)
	{	
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$raw = DB::connection($databaseName)->select("select 
		company_id
		from company_mst 
		where company_name = '".$companyName."' and 
		deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(count($raw)==0)
		{
			return $exceptionArray['404'];
		}
		else
		{	
			return $raw;
		}
	}
	
	//delete
	public function deleteData($companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::connection($databaseName)->statement("update company_mst 
		set deleted_at='".$mytime."' 
		where company_id=".$companyId);
		DB::commit();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if($raw==1)
		{
			$ledgerId = DB::connection($databaseName)->select("select ledger_id 
			from ledger_mst 
			where company_id=".$companyId." and deleted_at='0000-00-00 00:00:00'");
			$userId = DB::connection($databaseName)->select("select user_id 
			from user_mst 
			where company_id=".$companyId." and deleted_at='0000-00-00 00:00:00'");
			$branch = DB::connection($databaseName)->statement("update branch_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$product = DB::connection($databaseName)->statement("update product_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$template = DB::connection($databaseName)->statement("update template_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$invoice = DB::connection($databaseName)->statement("update invoice_dtl 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$quotation = DB::connection($databaseName)->statement("update quotation_dtl 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$journal = DB::connection($databaseName)->statement("update journal_dtl 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$ledger = DB::connection($databaseName)->statement("update ledger_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$productTrn = DB::connection($databaseName)->statement("update product_trn 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$retailsalesDtl = DB::connection($databaseName)->statement("update sales_bill
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$userMst = DB::connection($databaseName)->statement("update user_mst
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			//delete from active_session
			for($userData=0;$userData<count($userId);$userData++)
			{
				DB::beginTransaction();
				$userId = DB::connection($databaseName)->statement("delete
				from active_session
				where user_id='".$userId[$userData]->user_id."'");
				DB::commit();
			}
			//ledegerId_ledger_dtl drop
			for($ledgerArray=0;$ledgerArray<count($ledgerId);$ledgerArray++)
			{
				DB::beginTransaction();
				$dropLedger = DB::connection($databaseName)->statement("drop table
				".$ledgerId[$ledgerArray]->ledger_id."_ledger_dtl");
				DB::commit();
			}
			if($branch==1 && $product==1 && $template==1 && $invoice==1 && $quotation==1 && $journal==1 && $ledger==1 && $productTrn==1 && $retailsalesDtl==1 && $userMst==1) 
			{
				return $exceptionArray['200'];
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
}