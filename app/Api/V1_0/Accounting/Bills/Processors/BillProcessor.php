<?php
namespace ERP\Api\V1_0\Accounting\Bills\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\Bills\Validations\BillValidate;
use ERP\Api\V1_0\Accounting\Bills\Transformers\BillTransformer;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Core\Clients\Services\ClientService;
use ERP\Api\V1_0\Accounting\Journals\Controllers\JournalController;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Clients\Controllers\ClientController;
use ERP\Api\V1_0\Accounting\Ledgers\Controllers\LedgerController;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Core\Accounting\Journals\Entities\AmountTypeEnum;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BillProcessor extends BaseProcessor
{
	/**
     * @var ledgerPersistable
	 * @var request
     */
	private $billPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Ledger Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;
		$clientContactFlag=0;
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
				
				//check client is exists by contact-number
				$clientService = new ClientService();
				$clientData = $clientService->getAllClientData();
				$encodedClientData = json_decode($clientData);
				$clientContactNo = array();
				for($contactData=0;$contactData<count($encodedClientData);$contactData++)
				{
					$clientContactNo[$contactData]=$encodedClientData[$contactData]->contactNo;
					if(strcmp($clientContactNo[$contactData],$contactNo)==0)
					{
						$clientContactFlag=1;
						$clientId = $encodedClientData[$contactData]->clientId;
						break;
					}
				}
				
				if($clientContactFlag==0)
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
		//get ledger data for checking client is exist in ledger or not by contact-number
		$ledgerService = new LedgerService();
		$ledgerAllData = $ledgerService->getAllLedgerData();
		$encodedLedgerData = json_decode($ledgerAllData);
		
		//check contact-number of client with ledger contacts
		for($contactData=0;$contactData<count($encodedLedgerData);$contactData++)
		{
			if(strcmp($encodedLedgerData[$contactData]->contactNo,$contactNo)==0)
			{
				$contactFlag=1;
				$ledgerId = $encodedLedgerData[$contactData]->ledgerId;
			}
			if(strcmp($encodedLedgerData[$contactData]->ledgerName,$paymentMode)==0)
			{
				$paymentModeFlag=1;
				$paymentLedgerId = $encodedLedgerData[$contactData]->ledgerId;
			}
		}
		if($contactFlag==0)
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
			$ledgerArray['ledgerGroupId']=9;
			$ledgerController = new LedgerController(new Container());
			$method=$constantArray['postMethod'];
			$path=$constantArray['ledgerUrl'];
			$ledgerRequest = Request::create($path,$method,$ledgerArray);
			$processedData = $ledgerController->store($ledgerRequest);
			
			if(strcmp($msgArray['500'],$processedData)==0 || strcmp($msgArray['content'],$processedData)==0)
			{
				return $processedData;
			}
			$ledgerId = json_decode($processedData)[0]->ledger_id;
		}
		//get jf_id
		$journalController = new JournalController(new Container());
		$journalMethod=$constantArray['getMethod'];
		$journalPath=$constantArray['journalUrl'];
		$journalDataArray = array();
		$journalJfIdRequest = Request::create($journalPath,$journalMethod,$journalDataArray);
		$jfId = $journalController->getData($journalJfIdRequest);
		$ledgerTaxAcId = "86";
		$ledgerSaleAcId = "85";
		$ledgerDiscountAcId = "89";
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
		if($discountTotal==0)
		{
			//make data array for journal entry
			if($paymentModeFlag==1)
			{
				if($tRequest['tax']!=0)
				{
					if($tRequest['advance']!="")
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
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$tRequest['total'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[2]=array(
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					if($tRequest['advance']!="")
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
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$tRequest['total'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
			}
		}
		else
		{
			//make data array for journal entry
			if($paymentModeFlag==1)
			{
				if($tRequest['tax']!=0)
				{
					if($tRequest['advance']!="")
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
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[4]=array(
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$tRequest['total'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[2]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[3]=array(
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					if($tRequest['advance']!="")
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
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
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
							"amount"=>$tRequest['grand_total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
			}
		}
		
		//make data array for journal sale entry
		$journalArray = array();
		$journalArray= array(
			'jfId' => json_decode($jfId)->nextValue,
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
		$journalRequest->headers->set('type',$request->header()['type'][0]);
		$processedData = $journalController->store($journalRequest);
		
		if(strcmp($processedData,$msgArray['200'])==0)
		{
			if(strcmp($request->header()['type'][0],"sales")==0)
			{
				$inwardTrnType = $constantArray['journalOutward'];
			}
			$productArray = array();
			$productArray['invoiceNumber']=$tRequest['invoice_number'];
			$productArray['transactionType']=$inwardTrnType;
			$productArray['companyId']=$tRequest['company_id'];
			$productArray['inventory'] = $tRequest[0];
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
			$billPersistable->setEntryDate($tRequest['entry_date']);
			$billPersistable->setClientId($clientId);
			$billPersistable->setCompanyId($tRequest['company_id']);
			$billPersistable->setSalesType($request->header()['salestype'][0]);
			
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
}