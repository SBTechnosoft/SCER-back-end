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
use ERP\Exceptions\ExceptionMessage;

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
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
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
				$contactNo = trim($request->input()[0]['billData'][0]['contactNo']);
				
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
					$clientArray['workNo']="9876567656";
					$clientArray['emailId']=$tRequest['email_id'];
					$clientArray['address1']=$tRequest['address1'];
					$clientArray['address2']=$tRequest['address2'];
					$clientArray['isDisplay']="yes";
					$clientArray['stateAbb']=$tRequest['state_abb'];
					$clientArray['cityId']=$tRequest['city_id'];
					
					$clientController = new ClientController(new Container());
					$method="post";
					$path="http://www.scerp1.com/clients";
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
		$paymentMode = $request->input()[0]['billData'][0]['paymentMode'];
		if($paymentMode=="")
		{
			$paymentMode="cash";
		}
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
			$ledgerArray['ledgerName']=$request->input()[0]['billData'][0]['clientName'];
			$ledgerArray['address1']=$request->input()[0]['billData'][0]['address1'];
			$ledgerArray['address2']=$request->input()[0]['billData'][0]['address2'];
			$ledgerArray['contactNo']=$request->input()[0]['billData'][0]['contactNo'];
			$ledgerArray['emailId']=$request->input()[0]['billData'][0]['emailId'];
			$ledgerArray['stateAbb']=$request->input()[0]['billData'][0]['stateAbb'];
			$ledgerArray['cityId']=$request->input()[0]['billData'][0]['cityId'];
			$ledgerArray['companyId']=$request->input()[0]['billData'][0]['companyId'];
			$ledgerArray['ledgerGroupId']=9;
			$ledgerController = new LedgerController(new Container());
			$method="post";
			$path="http://www.scerp1.com/accounting/ledgers";
			$ledgerRequest = Request::create($path,$method,$ledgerArray);
			$processedData = $ledgerController->store($ledgerRequest);
			$ledgerId = json_decode($processedData)[0]->ledger_id;
			if(strcmp($msgArray['500'],$processedData)!=0)
			{
				return $processedData;
			}
		}
		//get jf_id
		$journalController = new JournalController(new Container());
		$jfId = $journalController->getData();
		$ledgerTaxAcId = "14";
		$ledgerSaleAcId = "13";
		
		$ledgerAmount = $request->input()[0]['billData'][0]['total']-$request->input()[0]['billData'][0]['advance'];
		//make data array for journal entry
		if($paymentModeFlag==1)
		{
			if($request->input()[0]['billData'][0]['tax']!="")
			{
				if($request->input()[0]['billData'][0]['balance']!="")
				{
					$dataArray[0]=array(
						"amount"=>$request->input()[0]['billData'][0]['advance'],
						"amountType"=>"debit",
						"ledgerId"=>$paymentLedgerId,
					);
					$dataArray[1]=array(
						"amount"=>$ledgerAmount,
						"amountType"=>"debit",
						"ledgerId"=>$ledgerId,
					);
					$dataArray[2]=array(
						"amount"=>$request->input()[0]['billData'][0]['tax'],
						"amountType"=>"debit",
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[3]=array(
						"amount"=>$request->input()[0]['billData'][0]['grandTotal'],
						"amountType"=>"credit",
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$request->input()[0]['billData'][0]['total'],
						"amountType"=>"debit",
						"ledgerId"=>$paymentLedgerId,
					);
					$dataArray[1]=array(
						"amount"=>$request->input()[0]['billData'][0]['tax'],
						"amountType"=>"debit",
						"ledgerId"=>$ledgerTaxAcId,
					);
					$dataArray[2]=array(
						"amount"=>$request->input()[0]['billData'][0]['grandTotal'],
						"amountType"=>"credit",
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
			else
			{
				if($request->input()[0]['billData'][0]['balance']!="")
				{
					$dataArray[0]=array(
						"amount"=>$request->input()[0]['billData'][0]['advance'],
						"amountType"=>"debit",
						"ledgerId"=>$paymentLedgerId,
					);
					$dataArray[1]=array(
						"amount"=>$ledgerAmount,
						"amountType"=>"debit",
						"ledgerId"=>$ledgerId,
					);
					$dataArray[2]=array(
						"amount"=>$request->input()[0]['billData'][0]['grandTotal'],
						"amountType"=>"credit",
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
				else
				{
					$dataArray[0]=array(
						"amount"=>$request->input()[0]['billData'][0]['total'],
						"amountType"=>"debit",
						"ledgerId"=>$paymentLedgerId,
					);
					$dataArray[1]=array(
						"amount"=>$request->input()[0]['billData'][0]['grandTotal'],
						"amountType"=>"credit",
						"ledgerId"=>$ledgerSaleAcId,
					);
				}
			}
		}
		//make data array for journal sale entry
		$journalArray = array();
		$journalArray[0]= array(
			'jfId' => $jfId,
			'data' => array(
			),
			'entryDate' => $request->input()[0]['billData'][0]['entryDate'],
			'companyId' => $request->input()[0]['billData'][0]['companyId'],
			'inventory' => array(
			),
			'transactionDate'=> $request->input()[0]['billData'][0]['entryDate'],
			'billNumber'=> $request->input()[0]['billData'][0]['billNumber'],
			'invoiceNumber'=>$request->input()[0]['billData'][0]['invoiceNumber']
		);
		$journalArray[0]['data']=$dataArray;
		$journalArray[0]['inventory']=$request->input()[0]['billData'][0]['inventory'];
		$method="post";
		$path="http://www.scerp1.com/accounting/journals";
		$journalRequest = Request::create($path,$method,$journalArray);
		$journalRequest->headers->set('type','sales');
		// $processedData = $journalController->store($journalRequest);
		// print_r($processedData); //simple error msg

		$billProductArray = array();
		$billProductArray=array(
			'transactionDate'=> $request->input()[0]['billData'][0]['entryDate'],
			'billNumber'=> $request->input()[0]['billData'][0]['billNumber'],
			'invoiceNumber'=>$request->input()[0]['billData'][0]['invoiceNumber'],
			'inventory' => array(
			),
			'transactionType'=>'inward',
			'companyId' => $request->input()[0]['billData'][0]['companyId']
		);
		$billProductArray['inventory']=$request->input()[0]['billData'][0]['inventory'];
		// $productArray = "[{}]";
		
		ill
		
		//make an array for bill insert data in retail sales
		$billArray = array();
		$billArray = array(
			// 'product_array'=>$billProductArray;
			'payment_mode'=>$request->input()[0]['billData'][0]['paymentMode'],
			'bank_name'=>$request->input()[0]['billData'][0]['bankName'],
			'invoice_number'=>$request->input()[0]['billData'][0]['invoiceNumber'],
			'check_number'=>$request->input()[0]['billData'][0]['checkNumber'],
			'total'=>$request->input()[0]['billData'][0]['total'],
			'tax'=>$request->input()[0]['billData'][0]['tax'],
			'grand_total'=>$request->input()[0]['billData'][0]['grandTotal'],
			'advance'=>$request->input()[0]['billData'][0]['advance'],
			'balance'=>$request->input()[0]['billData'][0]['balance'],
			'remark'=>$request->input()[0]['billData'][0]['remark'],
			'entry_date'=>$request->input()[0]['billData'][0]['entryDate'],
			'client_id'=>$clientId,
			'company_id'=>$request->input()[0]['billData'][0]['companyId']
		);
		print_r($billArray);
	}
}