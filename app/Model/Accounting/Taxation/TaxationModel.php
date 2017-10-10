<?php
namespace ERP\Model\Accounting\Taxation;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use stdClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TaxationModel extends Model
{
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getSaleTaxData($companyId,$headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$dateString = '';
		$mytime = Carbon\Carbon::now();
		if(array_key_exists('fromdate',$headerData) && array_key_exists('todate',$headerData))
		{
			//date conversion
			//from-date conversion
			$splitedFromDate = explode("-",$headerData['fromdate'][0]);
			$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
			//to-date conversion
			$splitedToDate = explode("-",$headerData['todate'][0]);
			$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
			$dateString = "(entry_date BETWEEN '".$transformFromDate."' AND '".$transformToDate."') and";
		}
		//get saleTax from sales bill 
		DB::beginTransaction();	
		$saleTaxResult = DB::connection($databaseName)->select("select
		sale_id,
		product_array,
		invoice_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		sales_type,
		refund,
		entry_date,
		client_id,
		company_id,
		jf_id
		from sales_bill
		where deleted_at='0000-00-00 00:00:00' and 
		sales_type='whole_sales' and ".$dateString."
		company_id='".$companyId."' and is_draft='no' and is_salesorder='not'"); 
		DB::commit();
		if(count($saleTaxResult)!=0)
		{
			return json_encode($saleTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getPurchaseTaxData($companyId,$headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		$dateString='';
		if(array_key_exists('fromdate',$headerData) && array_key_exists('todate',$headerData))
		{
			//date conversion
			//from-date conversion
			$splitedFromDate = explode("-",$headerData['fromdate'][0]);
			$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
			//to-date conversion
			$splitedToDate = explode("-",$headerData['todate'][0]);
			$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
			$dateString = "(entry_date BETWEEN '".$transformFromDate."' AND '".$transformToDate."') and";
		}
		//get purchaseTax from purchase bill 
		DB::beginTransaction();	
		$purchaseTaxResult = DB::connection($databaseName)->select("select
		purchase_id,
		vendor_id,
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		total_discounttype,
		total_discount,
		advance,
		bill_type,
		extra_charge,
		balance,
		transaction_type,
		transaction_date,
		entry_date,
		company_id,
		jf_id
		from purchase_bill
		where bill_type='purchase_bill' and ".$dateString."
		company_id='".$companyId."' and
		deleted_at='0000-00-00 00:00:00'"); 
		DB::commit();
		if(count($purchaseTaxResult)!=0)
		{
			return json_encode($purchaseTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getPurchaseData($companyId,$headerData)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		//date conversion
		//from-date conversion
		$splitedFromDate = explode("-",$headerData['fromdate'][0]);
		$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
		//from-date conversion
		$splitedToDate = explode("-",$headerData['todate'][0]);
		$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
		
		//get saleTax from purchase bill 
		DB::beginTransaction();	
		$purchaseTaxResult = DB::connection($databaseName)->select("select
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		transaction_type,
		transaction_date,
		client_name,
		company_id,
		jf_id
		from purchase_bill
		where deleted_at='0000-00-00 00:00:00' 
		and company_id='".$companyId."' and
		(transaction_date BETWEEN '".$transformFromDate."' AND '".$transformToDate."')"); 
		DB::commit();
		
		if(count($purchaseTaxResult)!=0)
		{
			return json_encode($purchaseTaxResult);
		}
		else
		{
			return $exceptionArray['204'];
		}
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getStockDetailData($companyId,$headerData)
	{
		echo "model";
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$databaseName = $constantDatabase->constantDatabase();
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$mytime = Carbon\Carbon::now();
		$dateString='';
		$transformFromDate='';
		if(array_key_exists('fromdate',$headerData) && array_key_exists('todate',$headerData))
		{
			//date conversion
			//from-date conversion
			$splitedFromDate = explode("-",$headerData['fromdate'][0]);
			$transformFromDate = $splitedFromDate[2]."-".$splitedFromDate[1]."-".$splitedFromDate[0];
			//to-date conversion
			$splitedToDate = explode("-",$headerData['todate'][0]);
			$transformToDate = $splitedToDate[2]."-".$splitedToDate[1]."-".$splitedToDate[0];
			$dateString = "(entry_date BETWEEN '".$transformFromDate."' AND '".$transformToDate."') and";
		}
		echo "iiii";
		//get opening balance
		$openingBalance = $this->getOpeningBalance($transformFromDate,$companyId);
		print_r($openingBalance);
		exit;
		//get purchase data from purchase bill 
		DB::beginTransaction();	
		$purchaseResult = DB::connection($databaseName)->select("select
		purchase_id,
		vendor_id,
		product_array,
		bill_number,
		total,
		tax,
		grand_total,
		total_discounttype,
		total_discount,
		advance,
		bill_type,
		extra_charge,
		balance,
		transaction_type,
		transaction_date,
		entry_date,
		company_id,
		jf_id
		from purchase_bill
		where bill_type='purchase_bill' and ".$dateString."
		company_id='".$companyId."' and
		deleted_at='0000-00-00 00:00:00'"); 
		DB::commit();
		
		//get sales data from sale bill 
		DB::beginTransaction();	
		$saleResult = DB::connection($databaseName)->select("select
		sale_id,
		product_array,
		invoice_number,
		total,
		total_discounttype,
		total_discount,
		extra_charge,
		tax,
		grand_total,
		advance,
		balance,
		sales_type,
		refund,
		entry_date,
		client_id,
		company_id,
		jf_id
		from sales_bill
		where deleted_at='0000-00-00 00:00:00' and 
		sales_type='whole_sales' and ".$dateString."
		company_id='".$companyId."' and is_draft='no' and is_salesorder='not'"); 
		DB::commit();
		if(count($purchaseResult)!=0)
		{
			$purchaseDataCount = count($purchaseResult);
			for($purchaseArray=0;$purchaseArray<$purchaseDataCount;$purchaseArray++)
			{
				
			}
			print_r($purchaseResult);
			// return json_encode($purchaseTaxResult);
		}
		if(count($saleResult)!=0)
		{
			print_r($saleResult);
		}
		// else
		// {
			// return $exceptionArray['204'];
		// }
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function getOpeningBalance($transformFromDate,$companyId)
	{
		//database selection
		$database = "";
		$constantDatabase = new ConstantClass();
		$finantialDate = $constantDatabase->constantAccountingDate();
		$fromDate = $finantialDate['fromDate'];
		$toDate = $transformFromDate;
		
		$databaseName = $constantDatabase->constantDatabase();
		//get purchase data from purchase-bill 
		DB::beginTransaction();	
		$purchaseResult = DB::connection($databaseName)->select("select
		purchase_id,
		product_array,
		company_id,
		jf_id
		from purchase_bill
		where bill_type='purchase_bill' and 
		entry_date>='".$fromDate."' and entry_date<='".$toDate."' and
		company_id='".$companyId."' and
		deleted_at='0000-00-00 00:00:00'"); 
		DB::commit();
		
		//get sales data from sale-bill 
		DB::beginTransaction();	
		$saleResult = DB::connection($databaseName)->select("select
		sale_id,
		product_array,
		invoice_number,
		company_id,
		jf_id
		from sales_bill
		where deleted_at='0000-00-00 00:00:00' and 
		sales_type='whole_sales' and
		entry_date>='".$fromDate."' and entry_date<='".$toDate."' and
		company_id='".$companyId."' and is_draft='no' and is_salesorder='not'"); 
		DB::commit();
		if(count($purchaseResult)!=0)
		{
			$calculationPurchaseResult = $this->calculationOfQty($purchaseResult);
		}
		if(count($saleResult)!=0)
		{
			$calculationSaleResult = $this->calculationOfQty($saleResult);
		}
		$calculationSaleResult[0]['productId'] = 1242;
		if(count($purchaseResult)!=0 && count($saleResult)!=0)
		{
			$intersectArray = array();
			$data=0;
			foreach($calculationPurchaseResult as $key=>$value)
			{
				$result = array_search($calculationPurchaseResult[$key]['productId'], array_column($calculationSaleResult, 'productId'));
				if(count($intersectArray)==0)
				{
					if($result=='')
					{
						$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
						$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty'];
						$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price'];
						// unset($calculationPurchaseResult[$key]);
						$data++;
					}
					else
					{
						$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
						$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty']-$calculationSaleResult[$result]['qty'];
						$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price']-$calculationSaleResult[$result]['price'];
						// unset($calculationPurchaseResult[$key]);
						array_splice($calculationSaleResult,$result,1);
						$data++;
					}
				}
				else if($result=='')
				{
					if(array_key_exists('0',$calculationSaleResult))
					{
						if($calculationPurchaseResult[$key]['productId']!=$calculationSaleResult[0]['productId'])
						{
							$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
							$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty'];
							$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price'];
							// unset($calculationPurchaseResult[$key]);
							$data++;
						}
						else
						{
							$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
							$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty']-$calculationSaleResult[$result]['qty'];
							$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price']-$calculationSaleResult[$result]['price'];
							// unset($calculationPurchaseResult[$key]);
							array_splice($calculationSaleResult,$result,1);
							$data++;
						}
					}
					else
					{
						$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
						$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty'];
						$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price'];
						// unset($calculationPurchaseResult[$key]);
						$data++;
					}
				}
				else
				{
					$intersectArray[$data]['productId']=$calculationPurchaseResult[$key]['productId'];
					$intersectArray[$data]['qty']=$calculationPurchaseResult[$key]['qty']-$calculationSaleResult[$result]['qty'];
					$intersectArray[$data]['price']=$calculationPurchaseResult[$key]['price']-$calculationSaleResult[$result]['price'];
					// unset($calculationPurchaseResult[$key]);
					array_splice($calculationSaleResult,$result,1);
					$data++;
				}
			}
			
			$saleCount = count($calculationSaleResult);
			if($saleCount!=0)
			{
				foreach($calculationSaleResult as $key=>$value)
				{
					$intersectArray[$data]['productId']=$calculationSaleResult[$key]['productId'];
					$intersectArray[$data]['qty']=$calculationSaleResult[$key]['qty'];
					$intersectArray[$data]['price']=$calculationSaleResult[$key]['price'];
					$data++;
				}
			}
		}
		return $intersectArray;
	}
	
	/**
	 * get data
	 * returns the array-data/exception message
	*/
	public function calculationOfQty($result)
	{
		$mainArray = array();
		$outerCount = count($result);
		$data=0;
		for($dataArray=0;$dataArray<$outerCount;$dataArray++)
		{
			$inventoryArray = json_decode($result[$dataArray]->product_array)->inventory;
			$inventoryCount = count(json_decode($result[$dataArray]->product_array)->inventory);
			for($inventoryDataArray=0;$inventoryDataArray<$inventoryCount;$inventoryDataArray++)
			{
				if(count($mainArray)==0)
				{
					$mainArray[$data]['productId'] = $inventoryArray[$inventoryDataArray]->productId;
					$mainArray[$data]['qty'] = $inventoryArray[$inventoryDataArray]->qty;
					$mainArray[$data]['price'] = $inventoryArray[$inventoryDataArray]->price*$inventoryArray[$inventoryDataArray]->qty;
					$data++;
				}
				else
				{
					$key = array_search($inventoryArray[$inventoryDataArray]->productId, array_column($mainArray, 'productId'));
					if($key=='' && $inventoryArray[$inventoryDataArray]->productId!=$mainArray[0]['productId'])
					{
						$mainArray[$data]['productId'] = $inventoryArray[$inventoryDataArray]->productId;
						$mainArray[$data]['qty'] = $inventoryArray[$inventoryDataArray]->qty;
						$mainArray[$data]['price'] = $inventoryArray[$inventoryDataArray]->price*$inventoryArray[$inventoryDataArray]->qty;
						$data++;
					}
					else
					{
						$mainArray[$key]['qty'] = $mainArray[$key]['qty']+$inventoryArray[$inventoryDataArray]->qty;
						$mainArray[$key]['price'] = ($mainArray[$key]['qty']*$mainArray[$key]['price'])+($inventoryArray[$inventoryDataArray]->qty*$inventoryArray[$inventoryDataArray]->price);
					}
				}
			}
		}
		return $mainArray;
	}
}
