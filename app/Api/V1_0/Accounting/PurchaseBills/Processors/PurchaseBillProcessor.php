<?php
namespace ERP\Api\V1_0\Accounting\PurchaseBills\Processors;
	
use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\PurchaseBills\Persistables\PurchaseBillPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\PurchaseBills\Validations\PurchaseBillValidate;
use ERP\Api\V1_0\Accounting\PurchaseBills\Transformers\PurchaseBillTransformer;
use ERP\Model\Accounting\Ledgers\LedgerModel;
use ERP\Model\Clients\ClientModel;
use ERP\Api\V1_0\Accounting\Journals\Controllers\JournalController;
use Illuminate\Container\Container;
// use ERP\Api\V1_0\Clients\Controllers\ClientController;
// use ERP\Api\V1_0\Accounting\Ledgers\Controllers\LedgerController;
use ERP\Api\V1_0\Documents\Controllers\DocumentController;
use ERP\Core\Accounting\Journals\Entities\AmountTypeEnum;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
// use ERP\Core\Accounting\PurchaseBills\Entities\SalesTypeEnum;
use Carbon;
use ERP\Model\Accounting\PurchaseBills\PurchaseBillModel;
// use ERP\Core\Clients\Entities\ClientArray;
// use ERP\Core\Accounting\Ledgers\Entities\LedgerArray;
// use ERP\Model\Accounting\Journals\JournalModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
	
class PurchaseBillProcessor extends BaseProcessor
{	/**
     * @var purchaseBillPersistable
	 * @var request
	*/
	private $purchaseBillPersistable;
	private $request;    
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return PurchaseBill Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();	
		$file = $request->file();
		$docFlag=0;
		if(in_array(true,$file)  || array_key_exists('scanFile',$request->input()))
		{
			$documentController =new DocumentController(new Container());
			$processedData = $documentController->insertUpdate($request,$constantArray['purchaseBillDocUrl']);
			if(is_array($processedData))
			{
				$docFlag=1;
			}
			else
			{
				return $processedData;
			}
		}
		//trim an input 
		$purchaseBillTransformer = new PurchaseBillTransformer();
		$tRequest = $purchaseBillTransformer->trimInsertData($this->request);	
		if(is_array($tRequest))
		{
			//validation 
			$purchaseBillValidate = new PurchaseBillValidate();
			$validationResult = $purchaseBillValidate->validate($tRequest);
			if(strcmp($validationResult,$exceptionArray['200'])==0)
			{
				$value = array();
				$data=0;
				$purchaseId=0;
				//make an journal array 
				$journalResult = $this->makeJournalArray($tRequest,'insert',$purchaseId);
				//seprate inventory from other data
				$inventoryData['inventory'] = $tRequest['inventory'];
				$inventoryData['billNumber'] = $tRequest['billNumber'];
				$inventoryData['transactionType'] = 'purchase_tax';
				$inventoryData['companyId'] = $tRequest['companyId'];
				$requestData = array_except($tRequest,['inventory']);
				if(strcmp($journalResult,$exceptionArray['content'])!=0)
				{
					foreach ($requestData as $key => $value)
					{
						if(!is_numeric($value))
						{
							if (strpos($value, '\'') !== FALSE)
							{
								$purchaseValue[$data]= str_replace("'","\'",$value);
								$keyName[$data] = $key;
							}
							else
							{
								$purchaseValue[$data] = $value;
								$keyName[$data] = $key;
							}
						}
						else
						{
							$purchaseValue[$data]= $value;
							$keyName[$data] = $key;
						}
						$data++;
					}
					$purchaseValue[$data] = json_encode($inventoryData);
					$keyName[$data] = 'productArray';
					$purchaseValue[$data+1] = $journalResult;
					$keyName[$data+1] = 'jfId';
					// set data to the persistable object
					for($data=0;$data<count($purchaseValue);$data++)
					{
						//set the data in persistable object
						$purchaseBillPersistable = new PurchaseBillPersistable();	
						$conversion= preg_replace('/(?<!\ )[A-Z]/', '_$0', $keyName[$data]);
						$lowerCase = strtolower($conversion);
						$str = ucfirst($keyName[$data]);
						//make function name dynamically
						$setFuncName = 'set'.$str;
						$getFuncName[$data] = 'get'.$str;
						
						$purchaseBillPersistable->$setFuncName($purchaseValue[$data]);
						$purchaseBillPersistable->setName($getFuncName[$data]);
						$purchaseBillPersistable->setKey($lowerCase);
						$purchaseArray[$data] = array($purchaseBillPersistable);
						if($data==(count($purchaseValue)-1))
						{
							if($docFlag==1)
							{
								$purchaseArray[$data+1]=$processedData;
							}
						}
					}
					return $purchaseArray;
				}
				else
				{
					return $journalResult;
				}
			}
			else
			{
				return $validationResult;//an array
			}
		}	
		else
		{
			return $tRequest;//exception message
		}
	}
	
	/**
     * make an journal-array
     * $param trim request array
     * @return PurchaseBill Persistable object
     */
	public function makeJournalArray($trimRequest,$stringOperation,$purchaseId)
	{
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(!array_key_exists('companyId',$trimRequest))
		{
			//get jf_id as per given purchaseId
			$purchaseIdArray = array();
			$purchaseIdArray['purchasebillid'][0] = $purchaseId;
			$purchaseModel = new PurchaseBillModel();
			$purchaseArrayData = $purchaseModel->getPurchaseBillData($purchaseIdArray);
			if(strcmp($purchaseArrayData,$exceptionArray['204'])==0)
			{
				return $exceptionArray['204'];
			}
			$purchaseArrayData = json_decode(json_decode($purchaseArrayData)->purchaseBillData);
			$trimRequest['companyId'] = $purchaseArrayData[0]->company_id;
		}
		if(!array_key_exists('paymentMode',$trimRequest) && $purchaseId!='')
		{
			$trimRequest['paymentMode'] = $purchaseArrayData[0]->payment_mode;
		}
		if(!array_key_exists('vendorId',$trimRequest) && $purchaseId!='')
		{
			$trimRequest['vendorId'] = $purchaseArrayData[0]->vendor_id;
		}
		if(!array_key_exists('transactionDate',$trimRequest) && $purchaseId!='')
		{
			$trimRequest['transactionDate'] = $purchaseArrayData[0]->transaction_date;
		}
		if(!array_key_exists('billNumber',$trimRequest) && $purchaseId!='')
		{
			$trimRequest['billNumber'] = $purchaseArrayData[0]->bill_number;
		}
		//get ledger of payment-mode
		$ledgerModel = new LedgerModel();
		$generalLedgerData = $ledgerModel->getLedgerDetail($trimRequest['companyId']);
		$generalLedgerArray = json_decode($generalLedgerData);
		$legderCount = count($generalLedgerArray);
		$paymentLedgerId = '';
		$taxLedgerId = '';
		$discountLedgerId ='';
		$purchaseLedgerId = '';
		for($ledgerArray=0;$ledgerArray<$legderCount;$ledgerArray++)
		{
			if(strcmp($generalLedgerArray[$ledgerArray]->ledger_name,$trimRequest['paymentMode'])==0)
			{$paymentLedgerId = $generalLedgerArray[$ledgerArray]->ledger_id;}
			if(strcmp($generalLedgerArray[$ledgerArray]->ledger_name,'tax(expense)')==0 )
			{$taxLedgerId = $generalLedgerArray[$ledgerArray]->ledger_id;}
			if(strcmp($generalLedgerArray[$ledgerArray]->ledger_name,'discount(income)')==0)
			{$discountLedgerId = $generalLedgerArray[$ledgerArray]->ledger_id;}
			if(strcmp($generalLedgerArray[$ledgerArray]->ledger_name,'purchase_tax')==0)
			{$purchaseLedgerId = $generalLedgerArray[$ledgerArray]->ledger_id;}	
		}
		// total discount calculation
		$discountTotal=0;
		$inventoryCount = count($trimRequest['inventory']);
		for($discountArray=0;$discountArray<$inventoryCount;$discountArray++)
		{
			$discount = strcmp($trimRequest['inventory'][$discountArray]['discountType'],$constantArray['Flatdiscount'])==0
						? $trimRequest['inventory'][$discountArray]['discount'] 
						: ($trimRequest['inventory'][$discountArray]['discount']/100)*
						($trimRequest['inventory'][$discountArray]['price']*$trimRequest['inventory'][$discountArray]['qty']);
			$discountTotal = $discount+$discountTotal;
		}
		$grandTotal = $trimRequest['total']+$trimRequest['extraCharge'];
		$totalDiscount = strcmp($trimRequest['totalDiscounttype'],'flat')==0 
						? $trimRequest['totalDiscount'] : (($trimRequest['totalDiscount']/100)*$grandTotal);
		$finalTotalDiscount = $totalDiscount+$discountTotal;										
		$ledgerId = $trimRequest['vendorId'];
		$actualTotal  = $trimRequest['total'] - $trimRequest['tax'];
		$finalTotal = $actualTotal+$trimRequest['extraCharge'];
		$totalWithTaxAmount = $trimRequest['tax']+$actualTotal;
		$total = $totalWithTaxAmount+$trimRequest['extraCharge']-$finalTotalDiscount;
		$mAmount = $actualTotal+$trimRequest['extraCharge'];
		// calling function for display debit-credit
		$amountTypeEnum = new AmountTypeEnum();
		$amountTypeArray = $amountTypeEnum->enumArrays();
		$dataArray = array();
		if($finalTotalDiscount==0)
		{
			$compareData = $finalTotal+$trimRequest['tax']-$trimRequest['advance'];
			// make data array for journal entry
			if($trimRequest['tax']!=0)
			{
				if($trimRequest['advance']!="" && $trimRequest['advance']!=0)
				{
					if($compareData==0)
					{
						$dataArray[0]=array(
							"amount"=>$finalTotal+$trimRequest['tax'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$taxLedgerId,
						);
						$dataArray[2]=array(
							"amount"=>$finalTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$finalTotal+$trimRequest['tax']-$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$taxLedgerId,
						);
						$dataArray[3]=array(
							"amount"=>$finalTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$finalTotal+$trimRequest['tax'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$trimRequest['tax'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$taxLedgerId,
					);
					$dataArray[2]=array(
						"amount"=>$finalTotal,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$purchaseLedgerId,
					);
				}
			}
			else
			{
				if($trimRequest['advance']!="" && $trimRequest['advance']!=0)
				{
					if($compareData==0)
					{
						$dataArray[0]=array(
							"amount"=>$finalTotal,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$finalTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$finalTotal-$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$finalTotal,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$finalTotal,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$finalTotal,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$purchaseLedgerId,
					);
				}
			}
		}
		else
		{
			// make data array for journal entry
			if($trimRequest['tax']!=0)
			{
				if($trimRequest['advance']!="" && $trimRequest['advance']!=0)
				{
					if($trimRequest['total']==$trimRequest['advance'])
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$finalTotalDiscount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$discountLedgerId,
						);
						$dataArray[2]=array(
							"amount"=>$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$taxLedgerId,
						);
						$dataArray[3]=array(
							"amount"=>$finalTotalDiscount+$trimRequest['advance']-$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$trimRequest['total']-$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$finalTotalDiscount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$discountLedgerId,
						);
						$dataArray[3]=array(
							"amount"=>$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$taxLedgerId,
						);
						$dataArray[4]=array(
							"amount"=>($trimRequest['advance']+$finalTotalDiscount+($trimRequest['total']-$trimRequest['advance']))-$trimRequest['tax'],
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$trimRequest['total'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(						
						"amount"=>$finalTotalDiscount,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$discountLedgerId,
					);
					$dataArray[2]=array(
						"amount"=>$trimRequest['tax'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$taxLedgerId,
					);
					$dataArray[3]=array(
						"amount"=>($trimRequest['total']+$finalTotalDiscount)-$trimRequest['tax'],
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$purchaseLedgerId,
					);
				}
			}
			else
			{
				if($trimRequest['advance']!="" && $trimRequest['advance']!=0)
				{
					if($trimRequest['total']==$trimRequest['advance'])
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$finalTotalDiscount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$discountLedgerId,
						);
						$dataArray[2]=array(
							"amount"=>$trimRequest['advance']+$finalTotalDiscount,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
					else
					{
						$dataArray[0]=array(
							"amount"=>$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$paymentLedgerId,
						);
						$dataArray[1]=array(
							"amount"=>$trimRequest['total']-$trimRequest['advance'],
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$ledgerId,
						);
						$dataArray[2]=array(
							"amount"=>$finalTotalDiscount,
							"amountType"=>$amountTypeArray['creditType'],
							"ledgerId"=>$discountLedgerId,
						);
						$dataArray[3]=array(
							"amount"=>$trimRequest['advance']+($trimRequest['total']-$trimRequest['advance'])+$finalTotalDiscount,
							"amountType"=>$amountTypeArray['debitType'],
							"ledgerId"=>$purchaseLedgerId,
						);
					}
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$trimRequest['total'],
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$ledgerId,
					);
					$dataArray[1]=array(
						"amount"=>$finalTotalDiscount,
						"amountType"=>$amountTypeArray['creditType'],
						"ledgerId"=>$discountLedgerId,
					);
					$dataArray[2]=array(
						"amount"=>$trimRequest['total']+$finalTotalDiscount,
						"amountType"=>$amountTypeArray['debitType'],
						"ledgerId"=>$purchaseLedgerId,
					);
				}
			}
		}
		$journalController = new JournalController(new Container());
		if(strcmp($stringOperation,'insert')==0)
		{
			// get jf_id
			$journalMethod=$constantArray['getMethod'];
			$journalPath=$constantArray['journalUrl'];
			$journalDataArray = array();
			$journalJfIdRequest = Request::create($journalPath,$journalMethod,$journalDataArray);
			$jfId = $journalController->getData($journalJfIdRequest);
			$jsonDecodedJfId = json_decode($jfId)->nextValue;
		}
		else
		{
			$jsonDecodedJfId = $purchaseArrayData[0]->jf_id;
		}
		// conversion of transaction-date
		$splitedDate = explode("-",trim($trimRequest['transactionDate']));
		$transactionDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
		$inventoryCount = count($trimRequest['inventory']);
		$journalInventory = array();
		for($inventoryArray=0;$inventoryArray<$inventoryCount;$inventoryArray++)
		{
			$journalInventory[$inventoryArray]['productId']=$trimRequest['inventory'][$inventoryArray]['productId'];
			$journalInventory[$inventoryArray]['discount']=$trimRequest['inventory'][$inventoryArray]['discount'];
			$journalInventory[$inventoryArray]['price']=$trimRequest['inventory'][$inventoryArray]['price'];
			$journalInventory[$inventoryArray]['qty']=$trimRequest['inventory'][$inventoryArray]['qty'];
			$journalInventory[$inventoryArray]['discountType']=$trimRequest['inventory'][$inventoryArray]['discountType'];
		}
		// make data array for journal sale entry
		$journalArray = array();
		$journalArray= array(
			'jfId' => $jsonDecodedJfId,
			'data' => array(
			),
			'entryDate' => $transactionDate,
			'companyId' => $trimRequest['companyId'],
			'inventory' => array(
			),
			'transactionDate'=> $transactionDate,
			'tax'=> $trimRequest['tax'],
			'billNumber'=>$trimRequest['billNumber']
		);
		$journalArray['data']=$dataArray;
		$journalArray['inventory']=$journalInventory;
		$method=$constantArray['postMethod'];
		if(strcmp($stringOperation,'insert')==0)
		{
			$journalArray['vendorId'] = $trimRequest['vendorId'];
			$path=$constantArray['journalUrl'];
			$journalRequest = Request::create($path,$method,$journalArray);
			$journalRequest->headers->set('type',$constantArray['purchase']);
			$processedData = $journalController->store($journalRequest);
			if(strcmp($processedData,$exceptionArray['200'])!=0)
			{
				return $exceptionArray['500'];
			}
		}
		else
		{
			$path=$constantArray['journalUrl'].'/'.$jsonDecodedJfId;
			$journalRequest = Request::create($path,$method,$journalArray);
			$journalRequest->headers->set('type',$constantArray['purchase']);
			$processedData = $journalController->update($journalRequest,$jsonDecodedJfId);
			if(strcmp($processedData,$exceptionArray['200'])!=0)
			{
				return $exceptionArray['500'];
			}
		}
		return $jsonDecodedJfId;
	}
	
	/**
     * get request data & purchase-id and set into the persistable object
     * $param Request object [Request $request] and purchase-id
     * @return PurchaseBill Persistable object/error message
     */
	public function createPersistableChange(Request $request,$purchaseId)
	{
		$docFlag=0;
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		$file = $request->file();
		if(in_array(true,$file)  || array_key_exists('scanFile',$request->input()))
		{
			$documentController =new DocumentController(new Container());
			$processedData = $documentController->insertUpdate($request,$constantArray['purchaseBillDocUrl']);
			if(is_array($processedData))
			{
				$docFlag=1;
			}
			else
			{
				return $processedData;
			}
		}
		if(count($request->input())!=0)
		{
			//trim bill data
			$purchaseBillTransformer = new PurchaseBillTransformer();
			$tRequest = $purchaseBillTransformer->trimUpdateData($request);
			$value = array();
			$data=0;
			$inventoryFlag=0;
			//make an journal array 
			if(array_key_exists('inventory',$request->input()))
			{
				$inventoryFlag=1;
				$journalResult = $this->makeJournalArray($tRequest,'update',$purchaseId);
				
				if(strcmp($journalResult,$exceptionArray['content'])!=0 && strcmp($journalResult,$exceptionArray['204'])!=0)
				{
					//get jf_id as per given purchaseId
					$purchaseIdArray = array();
					$purchaseIdArray['purchasebillid'][0] = $purchaseId;
					$purchaseModel = new PurchaseBillModel();
					$purchaseArrayData = $purchaseModel->getPurchaseBillData($purchaseIdArray);
					// print_r($purchaseArrayData);
					if(strcmp($purchaseArrayData,$exceptionArray['204'])==0)
					{
						return $exceptionArray['204'];
					}
					$purchaseArrayData = json_decode(json_decode($purchaseArrayData)->purchaseBillData);
					$inventoryData['companyId'] = $purchaseArrayData[0]->company_id;
					$inventoryData['inventory'] = $tRequest['inventory'];
					$inventoryData['billNumber'] =  array_key_exists('billNumber',$tRequest) 
													? $tRequest['billNumber'] : $purchaseArrayData[0]->bill_number;
					
					$inventoryData['transactionType'] =  'purchase_tax';
					$purchaseValue[$data] = json_encode($inventoryData);
					$keyName[$data] = 'productArray';
				}
				else
				{
					return $journalResult;
				}
				//seprate inventory from other data
				$requestData = array_except($tRequest,['inventory']);
			}
			else
			{
				$requestData = $tRequest;
			}
			$data = $inventoryFlag==1 ? $data+1 :0;
			foreach ($requestData as $key => $value)
			{
				if(!is_numeric($value))
				{
					if (strpos($value, '\'') !== FALSE)
					{
						$purchaseValue[$data]= str_replace("'","\'",$value);
						$keyName[$data] = $key;
					}
					else
					{
						$purchaseValue[$data] = $value;
						$keyName[$data] = $key;
					}
				}
				else
				{
					$purchaseValue[$data]= $value;
					$keyName[$data] = $key;
				}
				$data++;
			}
			$purchaseValueCount = count($purchaseValue);
			// set data to the persistable object
			for($data=0;$data<$purchaseValueCount;$data++)
			{
				//set the data in persistable object
				$purchaseBillPersistable = new PurchaseBillPersistable();	
				$conversion= preg_replace('/(?<!\ )[A-Z]/', '_$0', $keyName[$data]);
				$lowerCase = strtolower($conversion);
				$str = ucfirst($keyName[$data]);
				//make function name dynamically
				$setFuncName = 'set'.$str;
				$getFuncName[$data] = 'get'.$str;
				$purchaseBillPersistable->$setFuncName($purchaseValue[$data]);
				$purchaseBillPersistable->setName($getFuncName[$data]);
				$purchaseBillPersistable->setKey($lowerCase);
				$purchaseArray[$data] = array($purchaseBillPersistable);
				if($data==(count($purchaseValue)-1))
				{
					if($docFlag==1)
					{
						$purchaseArray[$data+1]=$processedData;
					}
				}
			}
			return $purchaseArray;
		}
		else if($docFlag==1)
		{
			$purchaseArray[0] = $processedData;
			return $purchaseArray;
		}
	}
	
	/**
     * get the fromDate-toDate data and set into the persistable object
     * $param Request object [Request $request]
     * @return Purchase-Bill Persistable object
     */	
	public function getPersistableData($requestHeader)
	{
		// trim an input 
		$purchaseBillTransformer = new PurchaseBillTransformer();
		$tRequest = $purchaseBillTransformer->trimFromToDateData($requestHeader);
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
			$purchaseBillPersistable = new PurchaseBillPersistable();
			$purchaseBillPersistable->setFromDate($tRequest['fromDate']);
			$purchaseBillPersistable->setToDate($tRequest['toDate']);
			return $purchaseBillPersistable;
		}
		else
		{
			return $tRequest;
		}
	}
}