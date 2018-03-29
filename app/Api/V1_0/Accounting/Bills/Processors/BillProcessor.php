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
use ERP\Core\Accounting\Journals\Validations\BuisnessLogic;
use ERP\Core\Entities\CompanyDetail;
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
			$ledgerModel = new LedgerModel();
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
					//client insertion and ledger validation
					//ledger validation
					$result = $this->ledgerValidationOfInsertion($tRequest['company_id'],$tRequest['client_name'],$tRequest['contact_no']);
					if(is_array($result))
					{
						//client insertion
						$clientResult = $this->clientInsertion($tRequest);
						if(strcmp($clientResult,$msgArray['content'])==0)
						{
							return $clientResult;
						}
						$clientId = json_decode($clientResult)->clientId;
						$ledgerInsertionResult = $this->ledgerInsertion($tRequest,$clientId,$tRequest['invoice_number'],$tRequest['company_id']);
						//ledger insertion (|| $processedData[0][0]=='[' error while validation error occur)
						if(strcmp($msgArray['500'],$ledgerInsertionResult)==0 || strcmp($msgArray['content'],$ledgerInsertionResult)==0)
						{
							return $ledgerInsertionResult;
						}
						$ledgerId = json_decode($ledgerInsertionResult)[0]->ledger_id;
					}
					else
					{
						return $result;
					}
				}
				else
				{
					//check client is exists by contact-number
					$clientModel = new ClientModel();
					$clientArrayData = $clientModel->getClientData($contactNo);
					$clientData = (json_decode($clientArrayData));
					if(is_array($clientData) || is_object($clientData))
					{
						if(is_object($clientData))
						{
							$clientObjectData = $clientData->clientData;
						}
						else if(is_array($clientData))
						{
							$clientObjectData = $clientData['clientData'];
						}
						//update client-data and check ledger
						$ledgerData = $ledgerModel->getDataAsPerContactNo($tRequest['company_id'],$tRequest['contact_no']);
						if(is_array(json_decode($ledgerData)))
						{
							$ledgerId = json_decode($ledgerData)[0]->ledger_id;
							$inputArray = array();
							$inputArray['contactNo'] = $tRequest['contact_no'];
							//update client-data
							$encodedClientData = $clientObjectData;
							$clientId = $encodedClientData[0]->client_id;
							$clientUpdateResult = $this->clientUpdate($tRequest,$clientId);
							if(strcmp($clientUpdateResult,$msgArray['200'])!=0)
							{
								return $clientUpdateResult;
							}
							//update ledger-data
							$ledgerValidationResult = $this->ledgerUpdate($tRequest,$ledgerId,$clientId);
							if(strcmp($ledgerValidationResult,$msgArray['200'])!=0)
							{
								return $ledgerValidationResult;
							}
						}
						else
						{
							//insert ledger and update client
							//ledger validation
							$result = $this->ledgerValidationOfInsertion($tRequest['company_id'],$tRequest['client_name'],$tRequest['contact_no']);
							if(is_array($result))
							{
								//update client
								//update client-data
								$encodedClientData = $clientObjectData;
								$clientId = $encodedClientData[0]->client_id;
								$clientUpdateResult = $this->clientUpdate($tRequest,$clientId);
								if(strcmp($clientUpdateResult,$msgArray['200'])!=0)
								{
									return $clientUpdateResult;
								}
								//insert ledger
								$ledgerInsertionResult = $this->ledgerInsertion($tRequest,$clientId,$tRequest['invoice_number'],$tRequest['company_id']);
								//ledger insertion (|| $processedData[0][0]=='[' error while validation error occur)
								if(strcmp($msgArray['500'],$ledgerInsertionResult)==0 || strcmp($msgArray['content'],$ledgerInsertionResult)==0)
								{
									return $ledgerInsertionResult;
								}
								$ledgerId = json_decode($ledgerInsertionResult)[0]->ledger_id;
							}
							else
							{
								return $result;
							}
						}
					}
					else
					{
						//client insert and ledger validation
						$ledgerData = $ledgerModel->getDataAsPerContactNo($tRequest['company_id'],$tRequest['contact_no']);
						if(is_array(json_decode($ledgerData)))
						{
							//client insertion
							$clientResult = $this->clientInsertion($tRequest);
							if(strcmp($clientResult,$msgArray['content'])==0)
							{
								return $clientResult;
							}
							$clientId = json_decode($clientResult)->clientId;
							$ledgerId = json_decode($ledgerData)[0]->ledger_id;
							//update ledger-data
							$ledgerValidationResult = $this->ledgerUpdate($tRequest,$ledgerId,$clientId);
							if(strcmp($ledgerValidationResult,$msgArray['200'])!=0)
							{
								return $ledgerValidationResult;
							}
						}
						else
						{
							//client insert and ledger insert
							$result = $this->ledgerValidationOfInsertion($tRequest['company_id'],$tRequest['client_name'],$tRequest['contact_no']);
							if(is_array($result))
							{
								//client insertion
								$clientResult = $this->clientInsertion($tRequest);
								if(strcmp($clientResult,$msgArray['content'])==0)
								{
									return $clientResult;
								}
								$clientId = json_decode($clientResult)->clientId;
								$ledgerInsertionResult = $this->ledgerInsertion($tRequest,$clientId,$tRequest['invoice_number'],$tRequest['company_id']);
								// ledger insertion (|| $processedData[0][0]=='[' error while validation error occur)
								if(strcmp($msgArray['500'],$ledgerInsertionResult)==0 || strcmp($msgArray['content'],$ledgerInsertionResult)==0)
								{
									return $ledgerInsertionResult;
								}
								$ledgerId = json_decode($ledgerInsertionResult)[0]->ledger_id;
							}
							else
							{
								return $result;
							}
						}
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
		if(strcmp($paymentMode,$constantArray['credit'])==0)
		{
			if($tRequest['total']!=$tRequest['advance'])
			{
				$ledgerResult = $ledgerModel->getLedgerId($tRequest['company_id'],$constantArray['cashLedger']);
				if(is_array(json_decode($ledgerResult)))
				{
					$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
				}
			}
			else
			{
				return $msgArray['paymentMode'];
			}
		}
		else
		{
			$ledgerResult = $ledgerModel->getLedgerId($tRequest['company_id'],$paymentMode);
			if(is_array(json_decode($ledgerResult)))
			{
				$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
			}
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
		// if(strcmp($request->header()['salestype'][0],$salesTypeEnumArray['retailSales'])==0)
		// {
			// get ledger-id of retail_sales as per given company_id
			// $ledgerIdData = $ledgerModel->getLedgerId($tRequest['company_id'],$request->header()['salestype'][0]);
			// $decodedLedgerId = json_decode($ledgerIdData);
		// }
		// else
		// {
			//get ledger-id of whole sales as per given company_id
			$ledgerIdData = $ledgerModel->getLedgerId($tRequest['company_id'],$salesTypeEnumArray['wholesales']);
			$decodedLedgerId = json_decode($ledgerIdData);
		// }
		//get the company details from database
		$companyDetail = new CompanyDetail();
		$companyDetails = $companyDetail->getCompanyDetails($tRequest['company_id']);
		//convert total to no-of decimal point
		$tRequest['total'] = number_format($tRequest['total'],$companyDetails['noOfDecimalPoints'],'.','');	
		$tRequest['advance'] = number_format($tRequest['advance'],$companyDetails['noOfDecimalPoints'],'.','');	

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
		
		// if(strcmp($tRequest['total_discounttype'],'flat')==0)
		// {
			// $totalDiscount = $tRequest['total_discount'];
		// }
		// else
		// {
			// $totalDiscount = ($tRequest['total_discount']/100)*$tRequest['total'];
		// }
		// $discountTotal = $discountTotal+$totalDiscount;
		$totalSaleAmount = $discountTotal+$tRequest['total'];
		$totalDebitAmount = $tRequest['tax']+$tRequest['total'];
		if($discountTotal==0)
		{
			//make data array for journal entry
			if($tRequest['tax']!=0)
			{
				if($request->input()['advance']!="" && $tRequest['advance']!=0)
				{
					if($tRequest['total']==$tRequest['advance'])
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
							"amount"=>$tRequest['total']-$tRequest['tax'],
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
							"amount"=>$tRequest['total']-$tRequest['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$tRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$tRequest['total']-$tRequest['tax'],
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
						"amount"=>$tRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[2]=array(
						"amount"=>$tRequest['total']-$tRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
			else
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($tRequest['total']==$tRequest['advance'])
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
							"amount"=>$tRequest['total']-$tRequest['advance'],
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
					if($tRequest['total']==$tRequest['advance'])
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
							"amount"=>($tRequest['advance']+$discountTotal)-$tRequest['tax'],
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
							"amount"=>$tRequest['total']-$tRequest['advance'],
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
							"amount"=>$tRequest['total']-$tRequest['tax']+$discountTotal,
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
						"amount"=>$tRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[3]=array(
						"amount"=>$tRequest['total']-$tRequest['tax']+$discountTotal,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
			else
			{
				if($tRequest['advance']!="" && $tRequest['advance']!=0)
				{
					if($tRequest['total']==$tRequest['advance'])
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
							"amount"=>$tRequest['advance']+$discountTotal,
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
							"amount"=>$tRequest['total']-$tRequest['advance'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$discountTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$ledgerDiscountAcId,
						);
						$dataArray[3]=array(
							"amount"=>$tRequest['total']+$discountTotal,
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
						"amount"=>$tRequest['total']+$discountTotal,
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

				$tInventoryArray[$trimData][5] = array_key_exists('color', $request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['color']) : "XX";
				$tInventoryArray[$trimData][6] = array_key_exists('frameNo', $request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['frameNo']) : "";
				$tInventoryArray[$trimData][7] = array_key_exists('size', $request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['size']) : "ZZ";
				$tInventoryArray[$trimData][8] = array_key_exists("cgstPercentage",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['cgstPercentage']):0;
				$tInventoryArray[$trimData][9] = array_key_exists("cgstAmount",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['cgstAmount']):0;
				$tInventoryArray[$trimData][10] = array_key_exists("sgstPercentage",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['sgstPercentage']):0;
				$tInventoryArray[$trimData][11] = array_key_exists("sgstAmount",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['sgstAmount']):0;
				$tInventoryArray[$trimData][12] = array_key_exists("igstPercentage",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['igstPercentage']):0;
				$tInventoryArray[$trimData][13] = array_key_exists("igstAmount",$request->input()['inventory'][$trimData]) ? trim($request->input()['inventory'][$trimData]['igstAmount']):0;
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
			//entry date/service date conversion
			$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $tRequest['entry_date'])->format('Y-m-d');
			$transformServiceDate = $tRequest['service_date']=="" ? "0000-00-00":
													Carbon\Carbon::createFromFormat('d-m-Y', $tRequest['service_date'])->format('Y-m-d');
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
			$billPersistable->setServiceDate($transformServiceDate);
			$billPersistable->setClientId($clientId);
			$billPersistable->setCompanyId($tRequest['company_id']);
			$billPersistable->setTotalDiscounttype($tRequest['total_discounttype']);
			$billPersistable->setTotalDiscount($tRequest['total_discount']);
			$billPersistable->setPoNumber($tRequest['po_number']);
			$billPersistable->setExpense($tRequest['expense']);
			$billPersistable->setJfId($jsonDecodedJfId);
			// if(strcmp($request->header()['salestype'][0],$salesTypeEnumArray['retailSales'])==0 || strcmp($request->header()['salestype'][0],$salesTypeEnumArray['wholesales'])==0)
			// {
				$billPersistable->setSalesType($salesTypeEnumArray['wholesales']);
			// }
			// else
			// {
				// return $msgArray['content'];
			// }
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
		$ledgerId='';
		$balanceFlag=0;
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		//trim bill data
		$billTransformer = new BillTransformer();
		$billTrimData = $billTransformer->trimBillUpdateData($request,$saleId);
		if(!is_array($billTrimData))
		{
			if(strcmp($billTrimData,$msgArray['content'])==0)
			{
				return $msgArray['content'];
			}
		}
		$ledgerModel = new LedgerModel();
		$clientArray = new ClientArray();
		$clientArrayData = $clientArray->getClientArrayDataForBill();
		$clientData = array();
		foreach($clientArrayData as $key => $value)
		{
			if(array_key_exists($key,$billTrimData))
			{
				$clientData[$value] = $billTrimData[$key];
			}
		}	
		$contactFlag=0;
		$clientModel = new ClientModel();
		$ledgerModel = new LedgerModel();
		//get clientId as per given saleId
		$billData = json_decode($billData);
		$journalController = new JournalController(new Container());
		
		//get client-data as per given client-id for getting client contact_no
		$clientIdData = $clientModel->getData($billData[0]->client_id);
		$decodedClientData = (json_decode($clientIdData));
		$contactNo = $decodedClientData->clientData[0]->contact_no;
		
		$ledgerData = $ledgerModel->getDataAsPerContactNo($billData[0]->company_id,$contactNo);
		if(is_array(json_decode($ledgerData)))
		{
			$ledgerId = json_decode($ledgerData)[0]->ledger_id;
		}
		if(count($clientData)!=0)
		{
			//check contact_no exist or not
			if(array_key_exists("contact_no",$clientData))
			{
				$contactNo = $clientData['contact_no'];
			}
			//get client-data as per contact-no
			$clientDataAsPerContactNo = $clientModel->getClientData($contactNo);
			if(strcmp($clientDataAsPerContactNo,$msgArray['200'])!=0)
			{
				$clientDecodedData = json_decode($clientDataAsPerContactNo);
				//contact-no already exist...update client-data ..check ledger
				//update client-data and check ledger
				$ledgerData = $ledgerModel->getDataAsPerContactNo($billData[0]->company_id,$contactNo);
				if(is_array(json_decode($ledgerData)))
				{
					//update client-ledger
					$ledgerId = json_decode($ledgerData)[0]->ledger_id;
					//update client-data
					$encodedClientData = $clientDecodedData->clientData;
					$clientId = $encodedClientData[0]->client_id;
					$clientUpdateResult = $this->clientUpdate($clientData,$clientId);
					if(strcmp($clientUpdateResult,$msgArray['200'])!=0)
					{
						return $clientUpdateResult;
					}
					//update ledger-data
					$ledgerValidationResult = $this->ledgerUpdate($clientData,$ledgerId,$clientId);
					if(strcmp($ledgerValidationResult,$msgArray['200'])!=0)
					{
						return $ledgerValidationResult;
					}
				}
				else
				{
					//ledger validation
					$result = $this->ledgerValidationOfInsertion($billData[0]->company_id,$clientData['client_name'],$contactNo);
					if(is_array($result))
					{
						//update client-data
						$encodedClientData = $clientDecodedData->clientData;
						$clientId = $encodedClientData[0]->client_id;
						$clientUpdateResult = $this->clientUpdate($clientData,$clientId);
						if(strcmp($clientUpdateResult,$msgArray['200'])!=0)
						{
							return $clientUpdateResult;
						}
						//ledger insertion
						$ledgerInsertionResult = $this->ledgerInsertion($clientData,$clientId,$billData[0]->invoice_number,$billData[0]->company_id);
						//ledger insertion (|| $processedData[0][0]=='[' error while validation error occur)
						if(strcmp($msgArray['500'],$ledgerInsertionResult)==0 || strcmp($msgArray['content'],$ledgerInsertionResult)==0)
						{
							return $ledgerInsertionResult;
						}
						$ledgerId = json_decode($ledgerInsertionResult)[0]->ledger_id;
					}
					else
					{
						return $result;
					}
				}
			}
			else
			{
				//client insertion and ledger check
				$ledgerData = $ledgerModel->getDataAsPerContactNo($billData[0]->company_id,$contactNo);
				if(is_array(json_decode($ledgerData)))
				{
					$ledgerId = json_decode($ledgerData)[0]->ledger_id;
					//client insert and ledger update
					//client insertion
					$clientResult = $this->clientInsertion($clientData);
					if(strcmp($clientResult,$msgArray['content'])==0)
					{
						return $clientResult;
					}
					$clientId = json_decode($clientResult)->clientId;
					//update ledger-data
					$ledgerValidationResult = $this->ledgerUpdate($clientData,$ledgerId,$clientId);
					if(strcmp($ledgerValidationResult,$msgArray['200'])!=0)
					{
						return $ledgerValidationResult;
					}
				}
				else
				{
					//client insert and ledger insert
					//ledger validation
					$result = $this->ledgerValidationOfInsertion($billData[0]->company_id,$clientData['client_name'],$contactNo);
					if(is_array($result))
					{
						//client insertion
						$clientResult = $this->clientInsertion($clientData);
						if(strcmp($clientResult,$msgArray['content'])==0)
						{
							return $clientResult;
						}
						$clientId = json_decode($clientResult)->clientId;
						$ledgerInsertionResult = $this->ledgerInsertion($clientData,$clientId,$billData[0]->invoice_number,$billData[0]->company_id);
						
						//ledger insertion (|| $processedData[0][0]=='[' error while validation error occur)
						if(strcmp($msgArray['500'],$ledgerInsertionResult)==0 || strcmp($msgArray['content'],$ledgerInsertionResult)==0)
						{
							return $ledgerInsertionResult;
						}
						$ledgerId = json_decode($ledgerInsertionResult)[0]->ledger_id;
					}
					else
					{
						return $result;
					}
				}
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
			if(strcmp($paymentMode,$constantArray['credit'])==0)
			{
				if($billTrimData['total']!=$billTrimData['advance'])
				{
					$ledgerResult = $ledgerModel->getLedgerId($billData[0]->company_id,$constantArray['cashLedger']);
					if(is_array(json_decode($ledgerResult)))
					{
						$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
					}
				}
				else
				{
					return $msgArray['paymentMode'];
				}
			}
			else
			{
				$ledgerResult = $ledgerModel->getLedgerId($billData[0]->company_id,$paymentMode);
				if(is_array(json_decode($ledgerResult)))
				{
					$paymentLedgerId = json_decode($ledgerResult)[0]->ledger_id;
				}
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
			// if(count($decodedLedgerData)!=0)
			// {
				// $ledgerId = $decodedLedgerData[0]->ledger_id;
			// }
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
			// if(strcmp($billTrimData['total_discounttype'],'flat')==0)
			// {
				// $discountTotal = $billTrimData['total_discount'];
			// }
			// else
			// {
				// $discountTotal = ($billTrimData['total_discount']/100)*$billTrimData['total'];
			// }
			// $discountTotal = $discountTotal+$discountTotal1;
			$totalSaleAmount = $discountTotal+$billTrimData['total'];
			$totalDebitAmount = $billTrimData['tax']+$billTrimData['total'];
			if($discountTotal==0)
			{
				//make data array for journal entry
				if($billTrimData['tax']!=0)
				{
					if($request->input()['advance']!="" && $billTrimData['advance']!=0)
					{
						if($billTrimData['total']==$billTrimData['advance'])
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
								"amount"=>$billTrimData['total']-$billTrimData['tax'],
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
								"amount"=>$billTrimData['total']-$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerId,
							);
							$dataArray[2]=array(
								"amount"=>$billTrimData['tax'],
								"amountType"=>$amountTypeArray['creditType'],
								"ledgerId"=>$ledgerTaxAcId,
							);
							$dataArray[3]=array(
								"amount"=>$billTrimData['total']-$billTrimData['tax'],
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
							"amount"=>$billTrimData['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[2]=array(
							"amount"=>$billTrimData['total']-$billTrimData['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					if($billTrimData['advance']!="" && $billTrimData['advance']!=0)
					{
						if($billTrimData['total']==$billTrimData['advance'])
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
								"amount"=>$billTrimData['total']-$billTrimData['advance'],
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
						if($billTrimData['total']==$billTrimData['advance'])
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
								"amount"=>($billTrimData['advance']+$discountTotal)-$billTrimData['tax'],
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
								"amount"=>$billTrimData['total']-$billTrimData['advance'],
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
								"amount"=>$billTrimData['total']-$billTrimData['tax']+$discountTotal,
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
							"amount"=>$billTrimData['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerTaxAcId,
						);
						$dataArray[3]=array(
							"amount"=>$billTrimData['total']-$billTrimData['tax']+$discountTotal,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerSaleAcId,
						);
					}
				}
				else
				{
					if($billTrimData['advance']!="" && $billTrimData['advance']!=0)
					{
						if($billTrimData['total']==$billTrimData['advance'])
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
								"amount"=>$billTrimData['advance']+$discountTotal,
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
								"amount"=>$billTrimData['total']-$billTrimData['advance'],
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerId,
							);
							$dataArray[2]=array(
								"amount"=>$discountTotal,
								"amountType"=>$amountTypeArray['debitType'],
								"ledgerId"=>$ledgerDiscountAcId,
							);
							$dataArray[3]=array(
								"amount"=>$billTrimData['total']+$discountTotal,
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
							"amount"=>$billTrimData['total']+$discountTotal,
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
				for($inventoryData=0;$inventoryData<count($request->input()['inventory']);$inventoryData++)
				{
					$billTrimData['inventory'][$inventoryData]['amount'] = trim($request->input()['inventory'][$inventoryData]['amount']);
					$billTrimData['inventory'][$inventoryData]['productName'] = trim($request->input()['inventory'][$inventoryData]['productName']);
					$billTrimData['inventory'][$inventoryData]['cgstPercentage'] = array_key_exists("cgstPercentage",$request->input()['inventory'][$inventoryData])?trim($request->input()['inventory'][$inventoryData]['cgstPercentage']):0;
					$billTrimData['inventory'][$inventoryData]['cgstAmount'] = array_key_exists("cgstAmount",$request->input()['inventory'][$inventoryData]) ? trim($request->input()['inventory'][$inventoryData]['cgstAmount']):0;
					$billTrimData['inventory'][$inventoryData]['sgstPercentage'] = array_key_exists("sgstPercentage",$request->input()['inventory'][$inventoryData]) ? trim($request->input()['inventory'][$inventoryData]['sgstPercentage']):0;
					$billTrimData['inventory'][$inventoryData]['sgstAmount'] = array_key_exists("sgstAmount",$request->input()['inventory'][$inventoryData]) ? trim($request->input()['inventory'][$inventoryData]['sgstAmount']):0;
					$billTrimData['inventory'][$inventoryData]['igstPercentage'] = array_key_exists("igstPercentage",$request->input()['inventory'][$inventoryData]) ? trim($request->input()['inventory'][$inventoryData]['igstPercentage']):0;
					$billTrimData['inventory'][$inventoryData]['igstAmount'] = array_key_exists("igstAmount",$request->input()['inventory'][$inventoryData]) ? trim($request->input()['inventory'][$inventoryData]['igstAmount']):0;
				}
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
	
	/**
     * ledger validation for insert ledger-data
     * $param company-id,ledger-name,contact-no
     * @return result array/error-message
     */	
	public function ledgerValidationOfInsertion($companyId,$ledgerName,$contactNo)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$tRequest = array();
		$businessResult = array();
		$buisnessLogic = new BuisnessLogic();
		$businessResult = $buisnessLogic->validateLedgerData($companyId,$ledgerName,$contactNo);
		if(!is_array($businessResult))
		{
			$ledgerName = $ledgerName.$contactNo;
			$innerBusinessResult = $buisnessLogic->validateLedgerData($companyId,$ledgerName,$contactNo);
			if(!is_array($innerBusinessResult))
			{
				return $exceptionArray['content'];
			}
		}
		return $tRequest;
	}
	
	/**
     * ledger validation for update ledger-data
     * $param contact-no,ledger-name,ledger-id,trim request array
     * @return result array/error-message
     */	
	public function ledgerValidationOfUpdate($contactNo,$ledgerName,$ledgerId,$inputArray)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$tRequest = array();
		$buisnessLogic = new BuisnessLogic();
		$businessResult = $buisnessLogic->validateUpdateLedgerData($ledgerName,$ledgerId,$inputArray);
		if(!is_array($businessResult))
		{
			$ledgerName = $ledgerName.$contactNo;
			$innerBusinessResult = $buisnessLogic->validateUpdateLedgerData($ledgerName,$ledgerId,$inputArray);
			if(!is_array($innerBusinessResult))
			{
				return $exceptionArray['content'];
			}
		}
		return $tRequest;
	}
	
	/**
     * client insertion
     * $param trim request array
     * @return result array/error-message
     */	
	public function clientInsertion($tRequest)
	{
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$clientArray = array();
		$clientArray['clientName']=$tRequest['client_name'];
		$clientArray['companyName']=array_key_exists('company_name',$tRequest)?$tRequest['company_name']:'';
		$clientArray['emailId']=array_key_exists('email_id',$tRequest)?$tRequest['email_id']:'';
		$clientArray['gst']=array_key_exists('gst',$tRequest)?$tRequest['gst']:'';
		$clientArray['contactNo']=$tRequest['contact_no'];
		$clientArray['contactNo1']=array_key_exists('contact_no1',$tRequest)?$tRequest['contact_no1']:'';
		$clientArray['address1']=array_key_exists('address1',$tRequest)?$tRequest['address1']:'';
		$clientArray['birthDate']=array_key_exists('birth_date',$tRequest)?$tRequest['birth_date']:'0000-00-00';
		$clientArray['anniversaryDate']=array_key_exists('anniversary_date',$tRequest)?$tRequest['anniversary_date']:'0000-00-00';
		$clientArray['otherDate']=array_key_exists('other_date',$tRequest)?$tRequest['other_date']:'0000-00-00';
		$clientArray['isDisplay']=array_key_exists('is_display',$tRequest)?$tRequest['is_display']:$constantArray['isDisplayYes'];
		$clientArray['stateAbb']=array_key_exists('state_abb',$tRequest)?$tRequest['state_abb']:'';
		$clientArray['cityId']=array_key_exists('city_id',$tRequest)?$tRequest['city_id']:'';
		if(array_key_exists('profession_id',$tRequest))
		{
			$clientArray['professionId']=$tRequest['profession_id'];
		}
		$clientController = new ClientController(new Container());
		$method=$constantArray['postMethod'];
		$path=$constantArray['clientUrl'];
		$clientRequest = Request::create($path,$method,$clientArray);
		$processedData = $clientController->store($clientRequest);
		return $processedData;
	}
	
	/**
     * ledger insertion
     * $param trim request array,client_id
     * @return result array/error-message
     */	
	public function ledgerInsertion($tRequest,$clientId,$invoiceNumber,$companyId)
	{
		$amountTypeEnum = new AmountTypeEnum();
		$enumAmountTypeArray = $amountTypeEnum->enumArrays();
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$ledgerArray=array();
		$ledgerArray['ledgerName']=$tRequest['client_name'];
		$ledgerArray['address1']=array_key_exists('address1',$tRequest)?$tRequest['address1']:'';
		$ledgerArray['address2']='';
		$ledgerArray['contactNo']=$tRequest['contact_no'];
		$ledgerArray['emailId']=array_key_exists('email_id',$tRequest)?$tRequest['email_id']:'';
		$ledgerArray['invoiceNumber']=$invoiceNumber;
		$ledgerArray['stateAbb']=array_key_exists('state_abb',$tRequest) ? $tRequest['state_abb']:'';
		$ledgerArray['cityId']=array_key_exists('city_id',$tRequest) ? $tRequest['city_id']:'';
		$ledgerArray['companyId']=$companyId;
		$ledgerArray['balanceFlag']=$constantArray['openingBalance'];
		$ledgerArray['amount']=0;
		$ledgerArray['amountType']=$constantArray['credit'];
		$ledgerArray['ledgerGroupId']=$constantArray['ledgerGroupSundryDebitors'];
		$ledgerArray['clientName']=$tRequest['client_name'];
		$ledgerArray['outstandingLimit']='0.0000';
		$ledgerArray['outstandingLimit']=$enumAmountTypeArray['creditType'];
		$ledgerArray['clientId']=$clientId;
		$ledgerController = new LedgerController(new Container());
		$method=$constantArray['postMethod'];
		$path=$constantArray['ledgerUrl'];
		$ledgerRequest = Request::create($path,$method,$ledgerArray);
		$processedData = $ledgerController->store($ledgerRequest);
		return $processedData;
	}
	
	/**
     * client update
     * $param trim request array,client_id
     * @return result array/error-message
     */	
	public function clientUpdate($tRequest,$clientId)
	{
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		// update client data
		$clientArray = array();
		if(array_key_exists('client_name',$tRequest))
		{
			$clientArray['clientName']=$tRequest['client_name'];
		}
		if(array_key_exists('company_name',$tRequest))
		{
			$clientArray['companyName']=$tRequest['company_name'];
		}
		if(array_key_exists('email_id',$tRequest))
		{
			$clientArray['emailId']=$tRequest['email_id'];
		}
		if(array_key_exists('gst',$tRequest))
		{
			$clientArray['gst']=$tRequest['gst'];
		}
		if(array_key_exists('contact_no',$tRequest))
		{
			$clientArray['contactNo']=$tRequest['contact_no'];
		}
		if(array_key_exists('contact_no1',$tRequest))
		{
			$clientArray['contactNo1']=$tRequest['contact_no1'];
		}
		if(array_key_exists('address1',$tRequest))
		{
			$clientArray['address1']=$tRequest['address1'];
		}
		if(array_key_exists('is_display',$tRequest))
		{
			$clientArray['isDisplay']=$tRequest['is_display'];
		}
		if(array_key_exists('state_abb',$tRequest))
		{
			$clientArray['stateAbb']=$tRequest['state_abb'];
		}
		if(array_key_exists('profession_id',$tRequest))
		{
			$clientArray['professionId']=$tRequest['profession_id'];
		}
		if(array_key_exists('city_id',$tRequest))
		{
			$clientArray['cityId']=$tRequest['city_id'];
		}
		if(array_key_exists('birth_date',$tRequest))
		{
			$clientArray['birthDate']=$tRequest['birth_date'];
		}
		if(array_key_exists('anniversary_date',$tRequest))
		{
			$clientArray['anniversaryDate']=$tRequest['anniversary_date'];
		}
		if(array_key_exists('other_date',$tRequest))
		{
			$clientArray['otherDate']=$tRequest['other_date'];
		}
		$clientController = new ClientController(new Container());
		$method=$constantArray['postMethod'];
		$path=$constantArray['clientUrl'].'/'.$clientId;
		$clientRequest = Request::create($path,$method,$clientArray);
		$processedData = $clientController->updateData($clientRequest,$clientId);
		return $processedData;
	}
	
	/**
     * ledger update
     * $param trim request array,ledger_id,client_id
     * @return result array/error-message
     */	
	public function ledgerUpdate($tRequest,$ledgerId,$clientId)
	{
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		//update ledger data
		$ledgerArray=array();
		// $ledgerArray['ledgerName']=$tRequest['client_name'];
		if(array_key_exists('address1',$tRequest))
		{
			$ledgerArray['address1']=$tRequest['address1'];
		}
		if(array_key_exists('contact_no',$tRequest))
		{
			$ledgerArray['contactNo']=$tRequest['contact_no'];
		}
		if(array_key_exists('email_id',$tRequest))
		{
			$ledgerArray['emailId']=$tRequest['email_id'];
		}
		if(array_key_exists('invoice_number',$tRequest))
		{
			$ledgerArray['invoiceNumber']=$tRequest['invoice_number'];
		}
		if(array_key_exists('state_abb',$tRequest))
		{
			$ledgerArray['stateAbb']=$tRequest['state_abb'];
		}
		if(array_key_exists('city_id',$tRequest))
		{
			$ledgerArray['cityId']=$tRequest['city_id'];
		}
		if(array_key_exists('company_id',$tRequest))
		{
			$ledgerArray['companyId']=$tRequest['company_id'];
		}
		if(array_key_exists('client_name',$tRequest))
		{
			$ledgerArray['clientName']=$tRequest['client_name'];
		}
		$ledgerArray['clientId']=$clientId;
		$ledgerController = new LedgerController(new Container());
		$method=$constantArray['postMethod'];
		$path=$constantArray['ledgerUrl'].'/'.$ledgerId;
		$ledgerRequest = Request::create($path,$method,$ledgerArray);
		$processedData = $ledgerController->update($ledgerRequest,$ledgerId);
		return $processedData;
	}
}