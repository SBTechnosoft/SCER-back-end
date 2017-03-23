<?php
namespace ERP\Entities\Constants;
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
		$constantArray['barcodeWidth'] = 1;
		$constantArray['barcodeHeight'] = 60;
		$constantArray['barcodeSetting'] = "barcode";
		$constantArray['productBarcode'] = "Storage/Barcode/";
		$constantArray['documentUrl'] = "Storage/Document/";
		$constantArray['billDocumentUrl'] = "Storage/Bill/Document/";
		$constantArray['journalDocumentUrl'] = "Storage/Journal/";
		$constantArray['billUrl']="Storage/Bill/";
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
		$constantArray['clientUrl']="http://www.scerp1.com/clients";
		$constantArray['documentGenerateUrl']="http://www.scerp1.com/documents/bill";
		$constantArray['ledgerUrl']="http://www.scerp1.com/accounting/ledgers";
		$constantArray['journalUrl']="http://www.scerp1.com/accounting/journals";
		$constantArray['invoiceUrl']="http://www.scerp1.com/settings/invoice-numbers";
		$constantArray['productUrl']="http://www.scerp1.com/accounting/products";
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
		
		//from header data
		$constantArray['sales']="sales";
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
		$constantArray['invoice']="invoice";
		$constantArray['paymentType']="payment";
		$constantArray['refundType']="refund";
		$constantArray['receiptType']="receipt";
		$constantArray['specialJournalType']="special_journal";
		$constantArray['fromDate']="fromdate";
		$constantArray['toDate']="todate";
		return $constantArray;
	}
	
	/**
	 * check the incoming request url and give them respected database name
	 * @param (no parameter)
	*/
	public function constantDatabase()
	{
		// if(strcmp("www.scerp1.com",$_SERVER['HTTP_HOST'])==0)
		// {
			$database = "mysql";
			return $database;
		// }
		// else
		// {
			// $database = "mysql_silicon";
			// return $database;
		// }
	}
}
