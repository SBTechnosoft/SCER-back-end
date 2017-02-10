<?php
namespace ERP\Entities\Constants;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConstantClass 
{
    public function constantVariable()
	{
		$constantArray = array();
		$constantArray['token'] = "authenticationToken";
		$constantArray['success'] = "success";
		$constantArray['documentUrl'] = "Storage/Document/";
		$constantArray['billDocumentUrl'] = "Storage/Bill/Document/";
		$constantArray['billUrl']="Storage/Bill/";
		$constantArray['trialBalanceUrl']="Storage/TrialBalance/";
		$constantArray['stockUrl']="Storage/StockRegister/";
		$constantArray['priceList']="Storage/PriceList/";
		$constantArray['clientUrl']="http://www.scerp1.com/clients";
		$constantArray['documentGenerateUrl']="http://www.scerp1.com/documents/bill";
		$constantArray['ledgerUrl']="http://www.scerp1.com/accounting/ledgers";
		$constantArray['journalUrl']="http://www.scerp1.com/accounting/journals";
		$constantArray['invoiceUrl']="http://www.scerp1.com/settings/invoice-numbers";
		$constantArray['productUrl']="http://www.scerp1.com/accounting/products";
		// $constantArray['postMethod']="post";
		$constantArray['postMethod']="POST";
		$constantArray['getMethod']="get";
		$constantArray['deleteMethod']="DELETE";
		$constantArray['journalInward']="Inward";
		$constantArray['journalOutward']="Outward";
		$constantArray['credit']="credit";
		$constantArray['debit']="debit";
		$constantArray['percentage']="percentage";
		
		//from header data
		$constantArray['sales']="sales";
		$constantArray['purchase']="purchase";
		$constantArray['jfId']="jfid";
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
		$constantArray['transactionDate']="transaction_date";
		
		$constantArray['entry_date']="entry_date";
		$constantArray['company_id']="company_id";
		$constantArray['bill_number']="bill_number";
		$constantArray['invoice_number']="invoice_number";
		$constantArray['branch_id']="branch_id";
		
		//for journal-type
		$constantArray['saleType']="sale";
		$constantArray['purchaseType']="purchase";
		$constantArray['paymentType']="payment";
		$constantArray['refundType']="refund";
		$constantArray['receiptType']="receipt";
		$constantArray['specialJournalType']="special_journal";
		$constantArray['fromDate']="fromdate";
		$constantArray['toDate']="todate";
		return $constantArray;
	}
	
	public function constantDatabase()
	{
		if(strcmp("www.scerp1.com",$_SERVER['HTTP_HOST'])==0)
		{
			$database = "mysql";
			return $database;
		}
		else
		{
			$database = "mysql_silicon";
			return $database;
		}
	}
}
