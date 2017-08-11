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
use ERP\Core\Clients\Entities\ClientArray;
use ERP\Core\Accounting\Ledgers\Entities\LedgerArray;
use ERP\Model\Accounting\Journals\JournalModel;
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
			if($status==$constantArray['success'])
			{
				//get contact-number from input data
				if(!array_key_exists($constantArray['contactNo'],$tRequest))
				{
					$contactNo="";
				}
				else
				{
					$contactNo = $tRequest['contact_no'];
				}
				if($contactNo=="" || $contactNo==0)
				{
					$clientArray = array();
					$clientArray['clientName']=$tRequest['client_name'];
					$clientArray['companyName']=$tRequest['company_name'];
					$clientArray['emailId']=$tRequest['email_id'];
					$clientArray['contactNo']=$tRequest['contact_no'];
					$clientArray['address1']=$tRequest['address1'];
					$clientArray['isDisplay']=$tRequest['is_display'];
					$clientArray['stateAbb']=$tRequest['state_abb'];
					$clientArray['cityId']=$tRequest['city_id'];
					$clientController = new ClientController(new Container());
					$method=$constantArray['postMethod'];
					$path=$constantArray['clientUrl'];
					$clientRequest = Request::create($path,$method,$clientArray);
					$processedData = $clientController->store($clientRequest);
					if(strcmp($processedData,$msgArray['content'])==0)
					{
						return $processedData;
					}
					$clientId = json_decode($processedData)[0]->client_id;
				}
				else
				{
					//check client is exists by contact-number
					$clientModel = new ClientModel();
					$clientData = $clientModel->getClientData($contactNo);
					
					if(is_array(json_decode($clientData)))
					{
						$encodedClientData = json_decode($clientData);
						$clientId = $encodedClientData[0]->client_id;
						//update client data
						$clientArray = array();
						$clientArray['clientName']=$tRequest['client_name'];
						$clientArray['companyName']=$tRequest['company_name'];
						$clientArray['emailId']=$tRequest['email_id'];
						$clientArray['contactNo']=$tRequest['contact_no'];
						$clientArray['address1']=$tRequest['address1'];
						$clientArray['isDisplay']=$tRequest['is_display'];
						$clientArray['stateAbb']=$tRequest['state_abb'];
						$clientArray['cityId']=$tRequest['city_id'];
						$clientController = new ClientController(new Container());
						$method=$constantArray['postMethod'];
						$path=$constantArray['clientUrl'].'/'.$clientId;
						$clientRequest = Request::create($path,$method,$clientArray);
						$processedData = $clientController->updateData($clientRequest,$clientId);
						if(strcmp($processedData,$msgArray['200'])!=0)
						{
							return $processedData;
						}
					}
					else
					{
						$clientArray = array();
						$clientArray['clientName']=$tRequest['client_name'];
						$clientArray['companyName']=$tRequest['company_name'];
						$clientArray['contactNo']=$tRequest['contact_no'];
						$clientArray['emailId']=$tRequest['email_id'];
						$clientArray['address1']=$tRequest['address1'];
						$clientArray['isDisplay']=$tRequest['is_display'];
						$clientArray['stateAbb']=$tRequest['state_abb'];
						$clientArray['cityId']=$tRequest['city_id'];
						$clientController = new ClientController(new Container());
						$method=$constantArray['postMethod'];
						$path=$constantArray['clientUrl'];
						$clientRequest = Request::create($path,$method,$clientArray);
						$processedData = $clientController->store($clientRequest);
						if(strcmp($processedData,$msgArray['content'])==0)
						{
							return $processedData;
						}
						$clientId = json_decode($processedData)[0]->client_id;
					}
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
		// if($tRequest['balance']!="" && $tRequest['balance']!=0)
		// {
			if($tRequest['contact_no']=="" || $tRequest['contact_no']==0)
			{
				$contactFlag=2;
			}
			else
			{
				// get ledger data for checking client is exist in ledger or not by contact-number
				$ledgerData = $ledgerModel->getDataAsPerContactNo($tRequest['company_id'],$tRequest['contact_no']);
				
				if(is_array(json_decode($ledgerData)))
				{
					$contactFlag=1;
					$ledgerId = json_decode($ledgerData)[0]->ledger_id;
					
					//update ledger data
					$ledgerArray=array();
					$ledgerArray['ledgerName']=$tRequest['client_name'];
					$ledgerArray['address1']=$tRequest['address1'];
					$ledgerArray['address2']='';
					$ledgerArray['contactNo']=$tRequest['contact_no'];
					$ledgerArray['emailId']=$tRequest['email_id'];
					$ledgerArray['invoiceNumber']=$tRequest['invoice_number'];
					$ledgerArray['stateAbb']=$tRequest['state_abb'];
					$ledgerArray['cityId']=$tRequest['city_id'];
					$ledgerArray['companyId']=$tRequest['company_id'];
					$ledgerArray['balanceFlag']=$constantArray['openingBalance'];
					$ledgerArray['amount']=0;
					$ledgerArray['amountType']=$constantArray['credit'];
					$ledgerArray['ledgerGroupId']=$constantArray['ledgerGroupSundryDebitors'];
					$ledgerController = new LedgerController(new Container());
					$method=$constantArray['postMethod'];
					$path=$constantArray['ledgerUrl'].'/'.$ledgerId;
					$ledgerRequest = Request::create($path,$method,$ledgerArray);
					$processedData = $ledgerController->update($ledgerRequest,$ledgerId);
					if(strcmp($processedData,$msgArray['200'])!=0)
					{
						return $processedData;
					}
				}
				else
				{
					$contactFlag=2;
				}
			}
		// }
		if($contactFlag==2)
		{
			$ledgerArray=array();
			$ledgerArray['ledgerName']=$tRequest['client_name'];
			$ledgerArray['address1']=$tRequest['address1'];
			$ledgerArray['address2']='';
			$ledgerArray['contactNo']=$tRequest['contact_no'];
			$ledgerArray['emailId']=$tRequest['email_id'];
			$ledgerArray['invoiceNumber']=$tRequest['invoice_number'];
			$ledgerArray['stateAbb']=$tRequest['state_abb'];
			$ledgerArray['cityId']=$tRequest['city_id'];
			$ledgerArray['companyId']=$tRequest['company_id'];
			$ledgerArray['balanceFlag']=$constantArray['openingBalance'];
			$ledgerArray['amount']=0;
			$ledgerArray['amountType']=$constantArray['credit'];
			$ledgerArray['ledgerGroupId']=$constantArray['ledgerGroupSundryDebitors'];
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
		else if(strcmp($request->header()['salestype'][0],$salesTypeEnumArray['wholesales'])==0)
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
			if(strcmp($tRequest[0][$discountArray]['discountType'],$constantArray['Flatdiscount'])==0)
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
		// echo "emd";
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
				$tInventoryArray[$trimData][7] = trim($request->input()['inventory'][$trimData]['size']);
				array_push($request->input()['inventory'][$trimData],$tInventoryArray[$trimData]);
			}
			$productArray['inventory'] = $request->input()['inventory'];
			$documentPath = $constantArray['billDocumentUrl'];
			if(in_array(true,$request->file()) || array_key_exists('scanFile',$request->input()))
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
			$billPersistable->setJobCardNumber($tRequest['job_card_number']);
			$billPersistable->setCheckNumber($tRequest['check_number']);
			$billPersistable->setTotal($tRequest['total']);
			$billPersistable->setExtraCharge($tRequest['extra_charge']);
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
			if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$tRequest['fromDate']))
			{
				return "from-date is not valid";
			}
			if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$tRequest['toDate']))
			{
				return "to-date is not valid";
			}
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
			//validate entry-date
			if(!preg_match("/^[0-9]{4}-([1-9]|1[0-2]|0[1-9])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/",$tRequest['entry_date']))
			{
				return "entry-date is not valid";
			}
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

				$ledgerModel = new LedgerModel();
				$ledgerData = $ledgerModel->getLedgerId($companyId,$tRequest['payment_mode']);		
				$decodedLedgerId = json_decode($ledgerData)[0]->ledger_id;				
				if(strcmp($ledgerData,$msgArray['404'])==0)
				{					
					return $msgArray['404'];
				}			

				if(strcmp($jfIdData,$msgArray['404'])!=0)
				{
					$nextJfId = json_decode($jfIdData)->nextValue;

					//process of making a journal entry 
					if(strcmp($tRequest['payment_transaction'],$constantArray['paymentType'])==0)
					{
						//get personal a/c ledgerId
						$ledgerPersonalIdData = $ledgerModel->getPersonalAccLedgerId($companyId,$decodedBillData[0]->jf_id);
						if(strcmp($ledgerPersonalIdData,$msgArray['404'])==0)
						{
							return $msgArray['404'];
						}
						if($decodedBillData[0]->balance<$tRequest['amount'])
						{
							return $msgArray['content'];
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
							'entryDate' => $request->input()['entryDate'],
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
						$billArray['balance'] = $decodedBillData[0]->balance-$tRequest['amount'];
						$billArray['refund'] = 0;
						$billArray['entry_date'] = $tRequest['entry_date'];
						$billArray['payment_transaction'] = $tRequest['payment_transaction'];	

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
						//get salesReturn ledgerId
						$salesLedgerData = $ledgerModel->getLedgerId($companyId,$constantArray['salesReturnType']);
						$decodedSalesLedgerId = json_decode($salesLedgerData)[0]->ledger_id;
						if(strcmp($salesLedgerData,$msgArray['404'])==0)
						{
							return $msgArray['404'];
						}
						if($decodedBillData[0]->advance<$tRequest['amount'])
						{
							return $msgArray['content'];
						}
						$dataArray = array();
						$journalArray = array();
						$dataArray[0]=array(
							"amount"=>$tRequest['amount'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$decodedSalesLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$tRequest['amount'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$decodedLedgerId,
						);						
						$journalArray= array(
							'jfId' => $nextJfId,
							'data' => array(
							),
							'entryDate' => $request->input()['entryDate'],
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
						$billArray['refund'] = $tRequest['amount']+$decodedBillData[0]->refund;
						$billArray['advance'] = $decodedBillData[0]->advance;
						$billArray['balance'] = $decodedBillData[0]->balance+$tRequest['amount'];
						$billArray['entry_date'] = $tRequest['entry_date'];
						$billArray['payment_transaction'] = $tRequest['payment_transaction'];
						
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
					else
					{
						return $msgArray['content'];
					}
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
	
	/**
     * get request data & sale-id and set into the persistable object
     * $param Request object [Request $request] and sale-id and billdata
     * @return Bill Persistable object/error message
     */
	public function createPersistableChange(Request $request,$saleId,$billData)
	{
		$balanceFlag=0;
		
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();

		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//trim bill data
		$billTransformer = new BillTransformer();
		$billTrimData = $billTransformer->trimBillUpdateData($request);
	
		$ledgerModel = new LedgerModel();
		$clientArray = new ClientArray();
		$clientArrayData = $clientArray->getClientArrayData();
		$clientData = array();
		foreach($clientArrayData as $key => $value)
		{
			if(array_key_exists($key,$billTrimData))
			{
				$clientData[$value] = $billTrimData[$key];
			}
		}		
		//get clientId as per given saleId
		$billData = json_decode($billData);	

		//get client-data as per given client-id for getting client contact_no
		$clientModel = new ClientModel();
		$clientIdData = $clientModel->getData($billData[0]->client_id);
		$decodedClientData = json_decode($clientIdData);

		//get ledgerId for update ledegerData 
		$getLedgerData = $ledgerModel->getDataAsPerInvoiceNumber($billData[0]->company_id,$billData[0]->invoice_number);
		$decodedLedgerData = json_decode($getLedgerData);
		$journalController = new JournalController(new Container());
		if(count($clientData)!=0)
		{
			//call controller of client for updating of client data
			$clientController = new ClientController(new Container());
			$clientMethod=$constantArray['postMethod'];
			$clientPath=$constantArray['clientUrl'].'/'.$billData[0]->client_id;
			$clientRequest = Request::create($clientPath,$clientMethod,$clientData);
			$clientData = $clientController->updateData($clientRequest,$billData[0]->client_id);			
			if(strcmp($clientData,$msgArray['200'])==0)
			{
				$ledgerArray = new LedgerArray();
				$ledgerArrayData = $ledgerArray->getLedgerArrayData();
				
				foreach($ledgerArrayData as $key => $value)
				{
					if(array_key_exists($value,$billTrimData))
					{
						$ledgerData[$key] = $billTrimData[$value];
					}
				}
				if(!empty($decodedLedgerData))
				{
					//Now, we can update ledger data
					$ledgerController = new LedgerController(new Container());
					$ledgerMethod=$constantArray['postMethod'];
					$ledgerPath=$constantArray['ledgerUrl'].'/'.$decodedLedgerData[0]->ledger_id;
					$ledgerRequest = Request::create($ledgerPath,$ledgerMethod,$ledgerData);
					$ledgerStatus = $ledgerController->update($ledgerRequest,$decodedLedgerData[0]->ledger_id);
					if(strcmp($ledgerStatus,$msgArray['200'])!=0)
					{
						return $ledgerStatus;
					}
				}
			}
			else
			{
				return $clientData;
			}
		}
		if(array_key_exists('inventory',$billTrimData))
		{
			if(array_key_exists('payment_mode',$billTrimData))
			{
				$paymentMode = $billTrimData['payment_mode'];
			}
			else
			{
				$paymentMode = $billData[0]->payment_mode;
			}		

			$ledgerId = $decodedLedgerData[0]->ledger_id;
			$ledgerResult = $ledgerModel->getLedgerId($billData[0]->company_id,$paymentMode);
			if(is_array(json_decode($ledgerResult)))
			{
				$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
			}			
			//get jf_id
			$journalMethod=$constantArray['getMethod'];
			$journalPath=$constantArray['journalUrl'];
			$journalDataArray = array();
			$journalJfIdRequest = Request::create($journalPath,$journalMethod,$journalDataArray);
			$jfId = $journalController->getData($journalJfIdRequest);
			$jsonDecodedJfId = json_decode($jfId)->nextValue;
			
			//get general ledger array data
			$generalLedgerData = $ledgerModel->getLedger($billData[0]->company_id);
			$generalLedgerArray = json_decode($generalLedgerData);
			$salesTypeEnum = new SalesTypeEnum();
						
			$salesTypeEnumArray = $salesTypeEnum->enumArrays();		
			if(strcmp($billData[0]->sales_type,$salesTypeEnumArray['retailSales'])==0)
			{
				//get ledger-id of retail_sales as per given company_id
				$ledgerIdData = $ledgerModel->getLedgerId($billData[0]->company_id,$salesTypeEnumArray['retailSales']);
				$decodedLedgerId = json_decode($ledgerIdData);
			}
			else if(strcmp($billData[0]->sales_type,$salesTypeEnumArray['wholesales'])==0)
			{
				//get ledger-id of whole sales as per given company_id
				$ledgerIdData = $ledgerModel->getLedgerId($billData[0]->company_id,$salesTypeEnumArray['wholesales']);
				$decodedLedgerId = json_decode($ledgerIdData);
			}
			$ledgerTaxAcId = $generalLedgerArray[0][0]->ledger_id;
			$ledgerSaleAcId = $decodedLedgerId[0]->ledger_id;
			$ledgerDiscountAcId = $generalLedgerArray[1][0]->ledger_id;
						
			$amountTypeEnum = new AmountTypeEnum();
			$amountTypeArray = $amountTypeEnum->enumArrays();
			$ledgerAmount = $billTrimData['total']-$billTrimData['advance'];		
			$discountTotal=0;
			$inventoryArray = $billTrimData['inventory'];			
			for($discountArray=0;$discountArray<count($inventoryArray);$discountArray++)
			{
				if(strcmp($inventoryArray[$discountArray]['discountType'],"flat")==0)
				{
					$discount = $inventoryArray[$discountArray]['discount'];
				}
				else
				{
					$discount = ($inventoryArray[$discountArray]['discount']/100)*$inventoryArray[$discountArray]['price'];
				}	
				$discountTotal = $discount+$discountTotal;
			}
			$totalSaleAmount = $discountTotal+$billTrimData['total'];
			$totalDebitAmount = $billTrimData['tax']+$billTrimData['total'];
			
			if($discountTotal==0)
			{
				//make data array for journal entry
				if($billTrimData['tax']!=0)
				{
					if($request->input()['advance']!="" && $billTrimData['advance']!=0)
					{
						if($ledgerAmount+$billTrimData['tax']==0)
						{
							$dataArray[0]=array(
								"amount"=>$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$paymentLedgerId,
							);
							$dataArray[1]=array(
								"amount"=>$billTrimData['tax'],
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
								"amount"=>$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$paymentLedgerId,
							);
							$dataArray[1]=array(
								"amount"=>$ledgerAmount+$billTrimData['tax'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerId,
							);
							$dataArray[2]=array(
								"amount"=>$billTrimData['tax'],
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
							"amount"=>$billTrimData['tax'],
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
					if($billTrimData['advance']!="" && $billTrimData['advance']!=0)
					{
						if($ledgerAmount==0)
						{
							$dataArray[0]=array(
							"amount"=>$billTrimData['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$paymentLedgerId,
							);
							$dataArray[1]=array(
								"amount"=>$billTrimData['total'],
								"amountType"=>$amountTypeArray['creditType'],
								"ledgerId"=>$ledgerSaleAcId,
							);
						}
						else
						{
							$dataArray[0]=array(
							"amount"=>$billTrimData['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$paymentLedgerId,
							);
							$dataArray[1]=array(
								"amount"=>$ledgerAmount,
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerId,
							);
							$dataArray[2]=array(
								"amount"=>$billTrimData['total'],
								"amountType"=>$amountTypeArray['creditType'],
								"ledgerId"=>$ledgerSaleAcId,
							);
						}
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$billTrimData['total'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[1]=array(
							"amount"=>$billTrimData['total'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
			}
			else
			{					
				//make data array for journal entry
				if($billTrimData['tax']!=0)
				{	
					if($billTrimData['advance']!="" && $billTrimData['advance']!=0)
					{
						if($ledgerAmount+$billTrimData['tax']==0)
						{
							$dataArray[0]=array(
								"amount"=>$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$paymentLedgerId,
							);
							$dataArray[1]=array(
								"amount"=>$discountTotal,
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerDiscountAcId,
							);
							$dataArray[2]=array(
								"amount"=>$billTrimData['tax'],
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
								"amount"=>$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$paymentLedgerId,
							);
							
							$dataArray[1]=array(
								"amount"=>$ledgerAmount+$billTrimData['tax'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerId,
							);
							$dataArray[2]=array(
								"amount"=>$discountTotal,
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerDiscountAcId,
							);
							$dataArray[3]=array(
								"amount"=>$billTrimData['tax'],
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
							"amount"=>$billTrimData['total']+$billTrimData['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[1]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[2]=array(
							"amount"=>$billTrimData['tax'],
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
					if($billTrimData['advance']!="" && $billTrimData['advance']!=0)
					{
						if($ledgerAmount==0)
						{
							$dataArray[0]=array(
							"amount"=>$billTrimData['advance'],
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
							"amount"=>$billTrimData['advance'],
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
							"amount"=>$billTrimData['total'],
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
				'data' => array(
				),
				'inventory' => array(
				),
				'tax'=> $billTrimData['tax']
			);
			if(array_key_exists('entry_date',$billTrimData))
			{
				$journalArray['entryDate'] = $billTrimData['entry_date'];
			}
			if(array_key_exists('transaction_date',$billTrimData))
			{
				$journalArray['transactionDate'] = $billTrimData['transaction_date'];
			}
			
			if(array_key_exists('invoiceNumber',$billTrimData))
			{
				$journalArray['invoiceNumber'] = $billTrimData['invoice_number'];
			}
			$journalArray['data']=$dataArray;
			$journalArray['inventory']=$billTrimData['inventory'];
			$method=$constantArray['postMethod'];
			$path=$constantArray['journalUrl'].'/'.$billData[0]->jf_id;
			$journalRequest = Request::create($path,$method,$journalArray);
			$journalRequest->headers->set('type',$constantArray['sales']);
			$processedData = $journalController->update($journalRequest,$billData[0]->jf_id);
			if(strcmp($processedData,$msgArray['200'])!=0)
			{
				return $processedData;
			}
		}
		else if(array_key_exists('payment_mode',$billTrimData))
		{
			//update journal data
			if(strcmp($billTrimData['payment_mode'],$billData[0]->payment_mode)!=0)
			{
				//get jf_id journal-data
				$journalModel = new JournalModel();
				$journalData = $journalModel->getJfIdArrayData($billData[0]->jf_id);
				$decodedJournalData = json_decode($journalData);
				
				//get payment-id of previous payment-mode
				$previousLedgerResult = $ledgerModel->getLedgerId($billData[0]->company_id,$billData[0]->payment_mode);
				if(is_array(json_decode($previousLedgerResult)))
				{
					$previousPaymentLedgerId = json_decode($previousLedgerResult)[0]->ledger_id;
				}
				//get payment-id
				$ledgerResult = $ledgerModel->getLedgerId($billData[0]->company_id,$billTrimData['payment_mode']);
				if(is_array(json_decode($ledgerResult)))
				{
					$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
				}
				// $journalArrayData = array();
				for($arrayData=0;$arrayData<count($decodedJournalData);$arrayData++)
				{
					if(strcmp($decodedJournalData[$arrayData]->ledger_id,$previousPaymentLedgerId)==0)
					{
						$decodedJournalData[$arrayData]->ledger_id = $paymentLedgerId;
					}
					$journalArrayData[$arrayData]=array(
						'amount'=>$decodedJournalData[$arrayData]->amount,
						'amountType'=>$decodedJournalData[$arrayData]->amount_type,
						'ledgerId'=>$decodedJournalData[$arrayData]->ledger_id,
					);
				}
				//make data array for journal sale entry
				$journalArray = array();
				$journalArray= array(
					'data' => array(
					)
				);
				$journalArray['data']=$journalArrayData;
				$method=$constantArray['postMethod'];
				$path=$constantArray['journalUrl'].'/'.$billData[0]->jf_id;
				$journalRequest = Request::create($path,$method,$journalArray);
				$journalRequest->headers->set('type',$constantArray['sales']);
				$processedData = $journalController->update($journalRequest,$billData[0]->jf_id);
				if(strcmp($processedData,$msgArray['200'])!=0)
				{
					return $processedData;
				}
			}
		}
		
		$dateFlag=0;
		if(count($billTrimData)==1 && array_key_exists('entry_date',$billTrimData))
		{
			$dateFlag=1;
		}
	
		//validate bill data
		//........pending
		$invFlag=0;
		//set bill data into persistable object
		$billPersistable = array();
		$clientBillArrayData = $clientArray->getBillClientArrayData();
		//splice data from trim array
		for($index=0;$index<count($clientBillArrayData);$index++)
		{
			for($innerIndex=0;$innerIndex<count($billTrimData);$innerIndex++)
			{
				if(strcmp('inventory',array_keys($billTrimData)[$innerIndex])!=0)
				{
					if(strcmp(array_keys($billTrimData)[$innerIndex],array_keys($clientBillArrayData)[$index])==0)
					{
						array_splice($billTrimData,$innerIndex,1);
						break;
					}
				}
			}
		}
		for($billArrayData=0;$billArrayData<count($billTrimData);$billArrayData++)
		{
			// making an object of persistable
			$billPersistable[$billArrayData] = new BillPersistable();
			if(strcmp('inventory',array_keys($billTrimData)[$billArrayData])!=0)
			{
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', array_keys($billTrimData)[$billArrayData])));	
				$setFuncName = "set".$str;
				$getFuncName = "get".$str;
				$billPersistable[$billArrayData]->$setFuncName($billTrimData[array_keys($billTrimData)[$billArrayData]]);
				$billPersistable[$billArrayData]->setName($getFuncName);
				$billPersistable[$billArrayData]->setKey(array_keys($billTrimData)[$billArrayData]);
				$billPersistable[$billArrayData]->setSaleId($saleId);
			}
			else
			{
				$invFlag=1;
				$decodedProductArrayData = json_decode($billData[0]->product_array);
				$productArray = array();
				$productArray['invoiceNumber'] = $decodedProductArrayData->invoiceNumber;
				$productArray['transactionType'] = $decodedProductArrayData->transactionType;
				$productArray['companyId'] = $decodedProductArrayData->companyId;
				$productArray['inventory'] = $billTrimData['inventory'];
				$billPersistable[$billArrayData]->setProductArray(json_encode($productArray));
				$billPersistable[$billArrayData]->setSaleId($saleId);
			}
		}
		if($invFlag==1)
		{
			$billPersistable[count($billPersistable)] = 'flag';
		}
		$documentPath = $constantArray['billDocumentUrl'];
		$docFlag=0;
		if(in_array(true,$request->file()))
		{
			$documentController = new DocumentController(new Container());
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
		if($dateFlag==1)
		{
			$billPersistable = new BillPersistable();
			$billPersistable->setEntryDate($billTrimData['entry_date']);
			$billPersistable->setSaleId($saleId);
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
}