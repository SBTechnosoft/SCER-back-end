<?php
namespace ERP\Entities\Constants;
use Carbon;
use stdClass;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConstantClass 
{
	/**
	 * making an array contains constant data 
	 * @param (no parameter)
	*/
    public function constantVariable()
	{
		$constantArray = array();
		$constantArray['token'] = "authenticationToken";
		$constantArray['success'] = "Success";
		$constantArray['barcodeWidth'] = 1.5;
		$constantArray['barcodeHeight'] = 60;
		$constantArray['barcodeSetting'] = "barcode";
		$constantArray['chequeNoSetting'] = "chequeno";
		$constantArray['birthDateReminderSetting'] = "birthreminder";
		$constantArray['anniDateReminderSetting'] = "annireminder";
		$constantArray['paymentDateSetting'] = "paymentdate";
		$constantArray['serviceDateSetting'] = "servicedate";
		$constantArray['productSetting'] = "product";
		$constantArray['clientSetting'] = "client";
		$constantArray['billSetting'] = "bill";
		$constantArray['noImage'] = "Storage/No-Image/no-image.jpg";
		$constantArray['productBarcode'] = "Storage/Barcode/";
		$constantArray['documentUrl'] = "Storage/Document/";
		$constantArray['productDocumentUrl'] = "Storage/Product/Document/";
		$constantArray['emailDocumentUrl'] = "Storage/Email/";
		$constantArray['purchaseTaxationUrl'] = "Storage/Taxation/PurchaseDetail/";
		$constantArray['purchaseTaxUrl'] = "Storage/Taxation/PurchaseTax/";
		$constantArray['taxReturnUrl'] = "Storage/Taxation/GstReturn/";
		$constantArray['taxHtmlUrl'] = "Storage/Taxation/GstHtmlFile/";
		$constantArray['saleTaxUrl'] = "Storage/Taxation/SaleTax/";
		$constantArray['mainLogo'] = "Storage/Logo/";
		$constantArray['polishReportUrl'] = "Storage/Reports/Polish-Report/";
		$constantArray['billDocumentUrl'] = "Storage/Bill/Document/";
		$constantArray['journalDocumentUrl'] = "Storage/Journal/";
		$constantArray['billUrl']="Storage/Bill/";
		$constantArray['purchaseBillDocUrl'] = "Storage/PurchaseBill/Document/";
		$constantArray['purchaseBillUrl'] = "Storage/PurchaseBill/";
		$constantArray['quotationDocUrl']="Storage/Quotation/";
		$constantArray['jobFormDocUrl']="Storage/Crm/JobForm";
		$constantArray['profitLossPdf']="Storage/ProfitLoss/Pdf/";
		$constantArray['profitLossExcel']="Storage/ProfitLoss/Excel/";
		$constantArray['cashFlowPdf']="Storage/CashFlow/Pdf/";
		$constantArray['cashFlowExcel']="Storage/CashFlow/Excel/";
		$constantArray['trialBalancePdf']="Storage/TrialBalance/Pdf/";
		$constantArray['trialBalanceExcel']="Storage/TrialBalance/Excel/";
		$constantArray['balanceSheetPdf']="Storage/BalanceSheet/Pdf/";
		$constantArray['balanceSheetExcel']="Storage/BalanceSheet/Excel/";
		$constantArray['stockUrlExcel']="Storage/StockRegister/Excel/";
		$constantArray['stockUrlPdf']="Storage/StockRegister/Pdf/";
		$constantArray['priceListExcel']="Storage/PriceList/Excel/";
		$constantArray['priceListPdf']="Storage/PriceList/Pdf/";
		$constantArray['contactNo']="contact_no";
		$constantArray['openingBalance']="opening";
		$constantArray['postMethod']="POST";
		$constantArray['getMethod']="get";
		$constantArray['deleteMethod']="DELETE";
		$constantArray['journalInward']="Inward";
		$constantArray['journalOutward']="Outward";
		$constantArray['credit']="credit";
		$constantArray['debit']="debit";
		$constantArray['percentage']="percentage";
		$constantArray['ledgerGroupSundryDebitors']="32";
		$constantArray['Flatdiscount']="flat";
		$constantArray['operation']="pdf";
		$constantArray['cashLedger']="cash";
		
		//from header data
		$constantArray['sales']="sales";
		$constantArray['wholeSales']="whole_sales";
		$constantArray['purchase']="purchase";
		$constantArray['jfId']="jfid";
		$constantArray['productCode']="productcode";
		$constantArray['fromDate']="fromdate";
		$constantArray['toDate']="todate";
		$constantArray['data']="data";
		$constantArray['type']="type";
		$constantArray['entryDate']="entryDate";
		$constantArray['companyId']="companyId";
		$constantArray['invoiceNumber']="invoiceNumber";
		$constantArray['billNumber']="billNumber";
		$constantArray['tax']="tax";
		$constantArray['inventory']="inventory";
		$constantArray['flag']="flag";
		$constantArray['productName']="productname";
		$constantArray['measurementUnit']="measurement_unit";
		$constantArray['isDisplay']="is_display";
		$constantArray['isDisplayYes']="yes";
		$constantArray['transactionDate']="transaction_date";
		
		
		$constantArray['entry_date']="entry_date";
		$constantArray['company_id']="company_id";
		$constantArray['bill_number']="bill_number";
		$constantArray['invoice_number']="invoice_number";
		$constantArray['branch_id']="branch_id";
		
		//for journal-type
		$constantArray['saleType']="sale";
		$constantArray['salesReturnType']="sales_return";
		$constantArray['purchaseType']="purchase";
		$constantArray['emailType']="email";
		$constantArray['emailSubject']="Cycle Store";
		$constantArray['smsType']="sms";
		$constantArray['blankType']="blank";
		$constantArray['quotationType']="quotation";
		$constantArray['invoice']="invoice";
		$constantArray['paymentType']="payment";
		$constantArray['refundType']="refund";
		$constantArray['receiptType']="receipt";
		$constantArray['specialJournalType']="special_journal";
		$constantArray['fromDate']="fromdate";
		$constantArray['toDate']="todate";
		
		//crm
		$constantArray['conversationEmailType']="email";
		$constantArray['conversationSmsType']="sms";
		$constantArray['prefixConstant']=")]}',\n";
		
		$constantArray['clientUrl']="http://api.siliconbrain.in/clients";
		$constantArray['documentGenerateQuotationUrl']="http://api.siliconbrain.in/documents/quotation";
		$constantArray['documentGenerateUrl']="http://api.siliconbrain.in/documents/bill";
		$constantArray['documentJobformUrl']="http://api.siliconbrain.in/crm/job-form";
		$constantArray['ledgerUrl']="http://api.siliconbrain.in/accounting/ledgers";
		$constantArray['journalUrl']="http://api.siliconbrain.in/accounting/journals";
		$constantArray['invoiceUrl']="http://api.siliconbrain.in/settings/invoice-numbers";
		$constantArray['quotationUrl']="http://api.siliconbrain.in/settings/quotation-numbers";
		$constantArray['productUrl']="http://api.siliconbrain.in/accounting/products";
		return $constantArray;
	}
	
	/**
	 * making an array contains constant data of template-type
	 * @param (no parameter)
	*/
	public function templateConstants()
	{
		$constantArray = array();
		$constantArray['Invoice'] = "invoice";
		$constantArray['Payment'] = "payment";
		$constantArray['Blank'] = "blank";
		$constantArray['Quotation'] = "quotation";
		$constantArray['Email_NewOrder'] = "email_newOrder";
		$constantArray['Email_DuePayment'] = "email_duePayment";
		$constantArray['Email_BirthDay'] = "email_birthDay";
		$constantArray['Email_AnniversaryDay'] = "email_anniversary";
		$constantArray['Sms_NewOrder'] = "sms_newOrder";
		$constantArray['Sms_DuePayment'] = "sms_duePayment";
		$constantArray['Sms_BirthDay'] = "sms_birthDay";
		$constantArray['Sms_AnniversaryDay'] = "sms_anniversary";
		return $constantArray;
	}

	/**
	 * making an array contains constant data 
	 * @param (no parameter)
	*/
    public function constantAccountingDate()
	{
		$financialArrayDate = array();
		$mytime = Carbon\Carbon::now();
		$currentDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mytime)->format('Y-m-d');
		$dateTime = $mytime->toDateTimeString();
		$yearStartDate = $mytime->year.'-04-01 00:00:00';
		if($dateTime >= $yearStartDate)
		{
			$toYear = $mytime->year+1;
			$financialArrayDate['fromDate'] = $mytime->year.'-04-01 00:00:00';
			$financialArrayDate['toDate'] = $toYear.'-03-31 00:00:00';
		}
		else
		{
			$fromYear = $mytime->year-1;
			$financialArrayDate['fromDate'] = $fromYear.'-04-01 00:00:00';
			$financialArrayDate['toDate'] = $mytime->year.'-03-31 00:00:00';
		}
		return $financialArrayDate;
	}
	
	/**
	 * making an array contains constant data 
	 * @param (no parameter)
	*/
    public function setEmailPassword()
	{
		$mailPasswordArray = array();
		$mailPasswordArray['emailId'] = 'farhan.s@siliconbrain.in';
		$mailPasswordArray['password'] = 'Abcd@1234';

		// $mailPasswordArray['emailId'] = 'nrana1551@gmail.com';
		// $mailPasswordArray['password'] = 'nrana1551';
		return $mailPasswordArray;
	}

	/**
	 * making an array contains constant data 
	 * @param (no parameter)
	*/
    public function setSmsPassword()
	{
		$mailPasswordArray = array();
		$mailPasswordArray['user'] = 'siliconbrain';
		$mailPasswordArray['password'] = 'demo54321';
		$mailPasswordArray['sid'] = 'ERPAKC';
		return $mailPasswordArray;
	}
	
	/**
	 * making an array contains comment data 
	 * @param (no parameter)
	*/
    public function getCommentMessage()
	{
		$commentObject = new stdClass();
		// $commentObject->mailSend = "Your Mail Is Successfully Send";
		$commentObject->billMailSend = "Your Mail Is Successfully Send From Sale-Bill";
		$commentObject->quotationMailSend = "Your Mail Is Successfully Send From Quotation";
		$commentObject->crmMailSend = "Your Mail Is Successfully Send From Crm";
		$commentObject->crmSmsSend = "Your Sms Is Successfully Send From Crm";
		$commentObject->emailIdExists = "Entered Email-id is already exists";
		$commentObject->reminderMailSend = "Your Mail Is Successfully Send From Reminder";
		$commentObject->reminderSmsSend = "Your Sms Is Successfully Send From Reminder";
		$commentObject->alreadyMailSend = "Your Mail Is already Successfully Send From Reminder";
		$commentObject->alreadySmsSend = "Your Sms Is already Successfully Send From Reminder";
		return $commentObject;
	}

	/**
	 * making an array contains comment data 
	 * @param (no parameter)
	*/
    public function getReminderTimeForPayment()
	{
		$commentObject = new stdClass();
		// $commentObject->mailSend = "Your Mail Is Successfully Send";
		$commentObject->billMailSend = "Your Mail Is Successfully Send From Sale-Bill";
		$commentObject->quotationMailSend = "Your Mail Is Successfully Send From Quotation";
		$commentObject->crmMailSend = "Your Mail Is Successfully Send From Crm";
		$commentObject->crmSmsSend = "Your Mail Is Successfully Send From Crm";
		$commentObject->emailIdExists = "Entered Email-id is already exists";
		return $commentObject;
	}
	
	/**
	 * check the incoming request url and give them respected database name
	 * @param (no parameter)
	*/
	public function constantDatabase()
	{
		//if(strcmp("www.scerp1.com",$_SERVER['HTTP_HOST'])==0)
		//{
			// exit;
			$database = "mysql";
			return $database;
		//}
		//else
		//{
			//$database = "mysql_silicon";
			//return $database;
		//}
	}

	/**
	 * check the incoming request url and give them respected database name
	 * @param (no parameter)
	*/
	public function constantDatabaseForCron()
	{
		$database = "mysql";
		return $database;
	}
}
