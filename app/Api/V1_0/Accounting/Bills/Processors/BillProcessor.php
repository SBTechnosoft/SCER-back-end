<?php
namespace ERP\Api\V1_0\Accounting\Bills\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\Bills\Validations\BillValidate;
use ERP\Api\V1_0\Accounting\Bills\Transformers\BillTransformer;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Model\Clients\ClientModel;
use ERP\Api\V1_0\Accounting\Journals\Controllers\JournalController;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Clients\Controllers\ClientController;
use ERP\Api\V1_0\Accounting\Ledgers\Controllers\LedgerController;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Core\Accounting\Journals\Entities\AmountTypeEnum;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Accounting\Bills\Entities\SalesTypeEnum;
use Carbon;
use ERP\Model\Accounting\Bills\BillModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillProcessor extends BaseProcessor
{	/**
     * @var billPersistable
	 * @var request
     */
	private $billPersistable;
	private $request;    
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Bill Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;
		// $clientContactFlag=0;
		$contactFlag=0;
		$paymentModeFlag=0;
		$taxFlag=0;
		$docFlag=0;
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();

		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//trim an input 
		$billTransformer = new BillTransformer();
		$tRequest = $billTransformer->trimInsertData($this->request);	
		if($tRequest==1)
		{
			return $msgArray['content'];
		}	
		else
		{
			//validation
			$billValidate = new BillValidate();
			$status = $billValidate->validate($tRequest);
			if($status=="Success")
			{
				//get contact-number from input data
				$contactNo = $tRequest['contact_no'];
				
				// if($contactNo=="" || $contactNo==0)
				// {
				   	// return $msgArray['content'];
				// }
				//check client is exists by contact-number
				$clientModel = new ClientModel();
				$clientData = $clientModel->getClientData($contactNo);			
				if(is_array(json_decode($clientData)))
				{
					$encodedClientData = json_decode($clientData);
					$clientId = $encodedClientData[0]->client_id;				
				}
				else
				{
					$clientArray = array();
					$clientArray['clientName']=$tRequest['client_name'];
					$clientArray['companyName']=$tRequest['client_name'];
					$clientArray['contactNo']=$tRequest['contact_no'];
					$clientArray['workNo']=$tRequest['work_no'];
					$clientArray['emailId']=$tRequest['email_id'];
					$clientArray['address1']=$tRequest['address1'];
					$clientArray['address2']=$tRequest['address2'];
					$clientArray['isDisplay']=$tRequest['is_display'];
					$clientArray['stateAbb']=$tRequest['state_abb'];
					$clientArray['cityId']=$tRequest['city_id'];
					$clientController = new ClientController(new Container());
					$method=$constantArray['postMethod'];
					$path=$constantArray['clientUrl'];
					$clientRequest = Request::create($path,$method,$clientArray);
					$processedData = $clientController->store($clientRequest);
					$clientId = json_decode($processedData)[0]->client_id;
				}
			}
			else
			{
				//data is not valid...return validation error message
				return $status;
			}
		}
		$paymentMode = $tRequest['payment_mode'];
		$ledgerModel = new LedgerModel();
		$ledgerResult = $ledgerModel->getLedgerId($tRequest['company_id'],$paymentMode);
		if(is_array(json_decode($ledgerResult)))
		{
			$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
		}
		if($tRequest['balance']!="" || $tRequest['balance']!=0)
		{
		   	// get ledger data for checking client is exist in ledger or not by contact-number
			$ledgerData = $ledgerModel->getDataAsPerContactNo($tRequest['company_id'],$tRequest['contact_no']);
			if(is_array(json_decode($ledgerData)))
			{
				$contactFlag=1;
				$ledgerId = json_decode($ledgerData)[0]->ledger_id;
			}
			else
			{
				$contactFlag=2;
			}
		}
		if($contactFlag==2)
		{
			$ledgerArray=array();
			$ledgerArray['ledgerName']=$tRequest['client_name'];
			$ledgerArray['address1']=$tRequest['address1'];
			$ledgerArray['address2']=$tRequest['address2'];
			$ledgerArray['contactNo']=$tRequest['contact_no'];
			$ledgerArray['emailId']=$tRequest['email_id'];
			$ledgerArray['stateAbb']=$tRequest['state_abb'];
			$ledgerArray['cityId']=$tRequest['city_id'];
			$ledgerArray['companyId']=$tRequest['company_id'];
			$ledgerArray['balanceFlag']="opening";
			$ledgerArray['amount']=0;
			$ledgerArray['amountType']="credit";
			$ledgerArray['ledgerGroupId']=32;
			$ledgerController = new LedgerController(new Container());
			$method=$constantArray['postMethod'];
			$path=$constantArray['ledgerUrl'];
			$ledgerRequest = Request::create($path,$method,$ledgerArray);
			$processedData = $ledgerController->store($ledgerRequest);	
			// print_r($processedData);
			//|| $processedData[0][0]=='[' error while validation error occur
			if(strcmp($msgArray['500'],$processedData)==0 || strcmp($msgArray['content'],$processedData)==0)
			{
				return $processedData;
			}
			$ledgerId = json_decode($processedData)[0]->ledger_id;
		}
		// get jf_id
		$journalController = new JournalController(new Container());
		$journalMethod=$constantArray['getMethod'];
		$journalPath=$constantArray['journalUrl'];
		$journalDataArray = array();
		$journalJfIdRequest = Request::create($journalPath,$journalMethod,$journalDataArray);
		$jfId = $journalController->getData($journalJfIdRequest);
		$jsonDecodedJfId = json_decode($jfId)->nextValue;
		
		//get general ledger array data
		$generalLedgerData = $ledgerModel->getLedger($tRequest['company_id']);
		$generalLedgerArray = json_decode($generalLedgerData);
		$salesTypeEnum = new SalesTypeEnum();
		
		$salesTypeEnumArray = $salesTypeEnum->enumArrays();		
		if(strcmp($request->header()['salestype'][0],$salesTypeEnumArray['retailSales'])==0)
		{
			//get ledger-id of retail_sales as per given company_id
			$ledgerIdData = $ledgerModel->getLedgerId($tRequest['company_id'],$request->header()['salestype'][0]);
			$decodedLedgerId = json_decode($ledgerIdData);
		}
		else
		{
			//get ledger-id of whole sales as per given company_id
			$ledgerIdData = $ledgerModel->getLedgerId($tRequest['company_id'],$request->header()['salestype'][0]);
			$decodedLedgerId = json_decode($ledgerIdData);
		}
		$ledgerTaxAcId = $generalLedgerArray[0][0]->ledger_id;
		$ledgerSaleAcId = $decodedLedgerId[0]->ledger_id;
		$ledgerDiscountAcId = $generalLedgerArray[1][0]->ledger_id;
		
		$amountTypeEnum = new AmountTypeEnum();
		$amountTypeArray = $amountTypeEnum->enumArrays();
		$ledgerAmount = $tRequest['total']-$tRequest['advance'];		
		$discountTotal=0;
		for($discountArray=0;$discountArray<count($tRequest[0]);$discountArray++)
		{
			if(strcmp($tRequest[0][$discountArray]['discountType'],"flat")==0)
			{
				$discount = $tRequest[0][$discountArray]['discount'];
			}
			else
			{
				$discount = ($tRequest[0][$discountArray]['discount']/100)*$tRequest[0][$discountArray]['price'];
			}	
			$discountTotal = $discount+$discountTotal;
		}
		
		$totalSaleAmount = $discountTotal+$tRequest['total'];
		$totalDebitAmount = $tRequest['tax']+$tRequest['total'];
		if($discountTotal==0)
		{
			//make data array for journal entry
			if($tRequest['tax']!=0)
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($ledgerAmount+$tRequest['tax']==0)
					{
						$dataArray[0]=array(
							"amount"=>$tRequest['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[2]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$tRequest['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$ledgerAmount+$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$totalDebitAmount,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$tRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[2]=array(
						"amount"=>$totalSaleAmount,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
			else
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($ledgerAmount==0)
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$ledgerAmount,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$tRequest['total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$tRequest['total'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$tRequest['total'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
		}
		else
		{
			//make data array for journal entry
			if($tRequest['tax']!=0)
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($ledgerAmount+$tRequest['tax']==0)
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[2]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$ledgerAmount+$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[3]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[4]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$tRequest['total']+$tRequest['tax'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$discountTotal,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerDiscountAcId,
					);
					$dataArray[2]=array(
						"amount"=>$tRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[3]=array(
						"amount"=>$totalSaleAmount,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
			else
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($ledgerAmount==0)
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[2]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
						"amount"=>$tRequest['advance'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$ledgerAmount,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[3]=array(
							"amount"=>$totalSaleAmount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$tRequest['total'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$discountTotal,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$ledgerDiscountAcId,
					);
					$dataArray[2]=array(
						"amount"=>$totalSaleAmount,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
		}
		
		//make data array for journal sale entry
		$journalArray = array();
		$journalArray= array(
			'jfId' => $jsonDecodedJfId,
			'data' => array(
			),
			'entryDate' => $tRequest['entry_date'],
			'companyId' => $tRequest['company_id'],
			'inventory' => array(
			),
			'transactionDate'=> $tRequest['entry_date'],
			'tax'=> $tRequest['tax'],
			'invoiceNumber'=>$tRequest['invoice_number']
		);
		$journalArray['data']=$dataArray;
		$journalArray['inventory']=$tRequest[0];
		$method=$constantArray['postMethod'];
		$path=$constantArray['journalUrl'];
		$journalRequest = Request::create($path,$method,$journalArray);
		$journalRequest->headers->set('type',$constantArray['sales']);
		$processedData = $journalController->store($journalRequest);
		if(strcmp($processedData,$msgArray['200'])==0)
		{	
			$productArray = array();
			$productArray['invoiceNumber']=$tRequest['invoice_number'];
			$productArray['transactionType']=$constantArray['journalOutward'];
			$productArray['companyId']=$tRequest['company_id'];
	
			$tInventoryArray = array();
			for($trimData=0;$trimData<count($request->input()['inventory']);$trimData++)
			{
				
				$tInventoryArray[$trimData] = array();
				$tInventoryArray[$trimData][5] = trim($request->input()['inventory'][$trimData]['color']);
				$tInventoryArray[$trimData][6] = trim($request->input()['inventory'][$trimData]['frameNo']);
				array_push($request->input()['inventory'][$trimData],$tInventoryArray[$trimData]);
			}
			
			$productArray['inventory'] = $request->input()['inventory'];
			$documentPath = $constantArray['billDocumentUrl'];
			if(in_array(true,$request->file()))
			{
				$documentController =new DocumentController(new Container());
				$processedData = $documentController->insertUpdate($request,$documentPath);
				if(is_array($processedData))
				{
					$docFlag=1;
				}
				else
				{
					return $processedData;
				}
			}
			//entry date conversion
			$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $tRequest['entry_date'])->format('Y-m-d');
			$billPersistable = new BillPersistable();
			$billPersistable->setProductArray(json_encode($productArray));
			$billPersistable->setPaymentMode($tRequest['payment_mode']);
			$billPersistable->setBankName($tRequest['bank_name']);
			$billPersistable->setInvoiceNumber($tRequest['invoice_number']);
			$billPersistable->setCheckNumber($tRequest['check_number']);
			$billPersistable->setTotal($tRequest['total']);
			$billPersistable->setTax($tRequest['tax']);
			$billPersistable->setGrandTotal($tRequest['grand_total']);
			$billPersistable->setAdvance($tRequest['advance']);
			$billPersistable->setBalance($tRequest['balance']);
			$billPersistable->setRemark($tRequest['remark']);
			$billPersistable->setEntryDate($transformEntryDate);
			$billPersistable->setClientId($clientId);
			$billPersistable->setCompanyId($tRequest['company_id']);
			$billPersistable->setJfId($jsonDecodedJfId);
			if(strcmp($request->header()['salestype'][0],$salesTypeEnumArray['retailSales'])==0 || strcmp($request->header()['salestype'][0],$salesTypeEnumArray['wholesales'])==0)
			{
				$billPersistable->setSalesType($request->header()['salestype'][0]);
			}
			else
			{
				return $msgArray['content'];
			}
			if($docFlag==1)
			{
				$array1 = array();
				array_push($processedData,$billPersistable);
				return $processedData;
			}
			else
			{
				return $billPersistable;
			}
		}
		else
		{
			return $processedData;
		}
	}

	/**
     * get the fromDate-toDate data and set into the persistable object
     * $param Request object [Request $request]
     * @return Bill Persistable object
     */	
	public function getPersistableData($requestHeader)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();

		//trim an input 
		$billTransformer = new BillTransformer();
		$tRequest = $billTransformer->trimFromToDateData($requestHeader);
		if(is_array($tRequest))
		{
			// set data in persistable object
			$billPersistable = new BillPersistable();
			$billPersistable->setSalesType($tRequest['salesType']);
			$billPersistable->setFromDate($tRequest['fromDate']);
			$billPersistable->setToDate($tRequest['toDate']);
			return $billPersistable;
		}
		else
		{
			return $tRequest;
		}
	}
	
	/**
     * get request data and set into the persistable object
     * $param Request object [Request $request] and sale-id
     * @return Bill Persistable object
     */	
	public function getPersistablePaymentData(Request $request,$saleId)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		$amountTypeEnum = new AmountTypeEnum();
		$amountTypeArray = $amountTypeEnum->enumArrays();
		
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//trim an input 
		$billTransformer = new BillTransformer();
		$tRequest = $billTransformer->trimPaymentData($request);
		if(is_array($tRequest))
		{
			//get bill data as per given sale-id(get company id)
			$billModel = new BillModel();
			$saleIdData = $billModel->getSaleIdData($saleId);
			if(strcmp($saleIdData,$msgArray['404'])!=0)
			{
				$decodedBillData = json_decode($saleIdData);
				$companyId = $decodedBillData[0]->company_id;
				
				//get latest jf-id
				$journalController = new JournalController(new Container());
				$journalMethod=$constantArray['getMethod'];
				$journalPath=$constantArray['journalUrl'];
				$journalDataArray = array();
				
				$journalJfIdRequest = Request::create($journalPath,$journalMethod,$journalDataArray);
				$jfIdData = $journalController->getData($journalJfIdRequest);
				if(strcmp($jfIdData,$msgArray['404'])!=0)
				{
					$nextJfId = json_decode($jfIdData)->nextValue;
					
					//process of making a journal entry 
					if(strcmp($tRequest['payment_transaction'],$constantArray['paymentType'])==0)
					{
						// type payment
						$ledgerModel = new LedgerModel();
						$ledgerData = $ledgerModel->getLedgerId($companyId,$tRequest['payment_mode']);
						$decodedLedgerId = json_decode($ledgerData)[0]->ledger_id;
						if(strcmp($ledgerData,$msgArray['404'])==0)
						{
							return $msgArray['404'];
						}
						
						//get personal a/c ledgerId
						$ledgerPersonalIdData = $ledgerModel->getPersonalAccLedgerId($companyId,$decodedBillData[0]->jf_id);
						if(strcmp($ledgerPersonalIdData,$msgArray['404'])==0)
						{
							return $msgArray['404'];
						}
						
						$decodedPersonalAccData = json_decode($ledgerPersonalIdData)[0]->ledger_id;
						$dataArray = array();
						$journalArray = array();
						$dataArray[0]=array(
							"amount"=>$tRequest['amount'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$decodedLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['amount'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$decodedPersonalAccData,
						);
						
						$journalArray= array(
							'jfId' => $nextJfId,
							'data' => array(
							),
							'entryDate' => $tRequest['entry_date'],
							'companyId' => $companyId
						);
						$journalArray['data']=$dataArray;
						$method=$constantArray['postMethod'];
						$path=$constantArray['journalUrl'];
						
						$journalRequest = Request::create($path,$method,$journalArray);
						$journalRequest->headers->set('type',$constantArray['paymentType']);
						$processedData = $journalController->store($journalRequest);
						if(strcmp($processedData,$msgArray['200'])!=0)
						{
							return $processedData;
						}
						$billArray = array();
						$billArray['sale_id'] = $saleId;
						$billArray['payment_mode'] = $tRequest['payment_mode'];
						$billArray['advance'] = $decodedBillData[0]->advance+$tRequest['amount'];
						$billArray['balance'] = $decodedBillData[0]->balance+$tRequest['amount'];
						$billArray['entry_date'] = $tRequest['entry_date'];
						
						if(strcmp($tRequest['payment_mode'],"bank")==0)
						{
							$billArray['bank_name'] = $tRequest['bank_name'];
							$billArray['check_number'] = $tRequest['check_number'];
						}
						// set data in persistable object
						$billPersistable = new BillPersistable();
						$billPersistable->setBillArray(json_encode($billArray));
						return $billPersistable;
					}
					else if(strcmp($tRequest['payment_transaction'],$constantArray['refundType'])==0)
					{
						// type refund
						
					}
					else
					{
						return $msgArray['content'];
					}
					// set data in persistable object
					// $billPersistable = new BillPersistable();
					// $billPersistable->setSalesType($tRequest['salesType']);
					// $billPersistable->setFromDate($tRequest['fromDate']);
					// $billPersistable->setToDate($tRequest['toDate']);
					// return $billPersistable;4
				}
				else
				{
					return $jfIdData;
				}
			}
			else
			{
				return $saleIdData;
			}
		}
		else
		{
			return $tRequest;
		}
	}
}