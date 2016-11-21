<?php
namespace ERP\Api\V1_0\Accounting\Bills\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
// use ERP\Core\Accounting\Bills\Persistables\BillPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
// use ERP\Core\Accounting\Bills\Validations\BillValidate;
// use ERP\Api\V1_0\Accounting\Bills\Transformers\BillTransformer;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Core\Clients\Services\ClientService;
use ERP\Api\V1_0\Accounting\Journals\Controllers\JournalController;
use Illuminate\Container\Container;
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
		echo "hi";
		$contactFlag=0;
		$emailIdFlag=0;
		$clientContactFlag=0;
		$arrayKeyContactFlag=0;
		$arrayKeyEmailFlag=0;
		$ledgerAllData = array();
		$arrayKeyArray = array_keys($request->input()[0]['billData'][0]);
		$arrayKeyCount = count(array_keys($request->input()[0]['billData'][0]));
		$contactNo = trim($request->input()[0]['billData'][0]['contactNo']);
		
		//check array contains contact-number & email-id 
		for($arrayKey=0;$arrayKey<$arrayKeyCount;$arrayKey++)
		{
			if(strcmp($arrayKeyArray[$arrayKey],"contactNo")==0)
			{
				$arrayKeyContactFlag=1;
			}
			if(strcmp($arrayKeyArray[$arrayKey],"emailId")==0)
			{
				$arrayKeyEmailFlag=1;
			}
		}
		//check client exists by contact-number
		if($arrayKeyContactFlag==1)
		{
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
		}
		else
		{
			echo "contact number is compulsary..";
		}
		if($clientContactFlag==0)
		{
			echo "add client and get client id";
		}
		else
		{
			echo "get client id";
		}
		
		//get ledger data for checking client is exist in ledger or not by contact-number & email-id
		$ledgerService = new LedgerService();
		$ledgerAllData = $ledgerService->getAllLedgerData();
		$encodedLedgerData = json_decode($ledgerAllData);
		
		//if contact-number exists in array
		if($arrayKeyContactFlag==1)
		{
			//check contact-number of client with ledger contacts
			for($contactData=0;$contactData<count($encodedLedgerData);$contactData++)
			{
				if(strcmp($encodedLedgerData[$contactData]->contactNo,$contactNo)==0)
				{
					$contactFlag=1;
					break;
				}
			}
		}
		//if email-id exists in array
		if($arrayKeyEmailFlag==1)
		{
			$emailId = trim($request->input()[0]['billData'][0]['emailId']);
			//check email-id of client with ledger email-id
			for($emailData=0;$emailData<count($encodedLedgerData);$emailData++)
			{
				if(strcmp($encodedLedgerData[$emailData]->emailId,$emailId)==0)
				{
					$emailIdFlag=1;
					break;
				}
			}
		}
		//check which one is exists(contact-number,email-id)
		if($contactFlag==1)
		{
			if($emailIdFlag==1)
			{
				$journalArray = array();
				$journalArray[0]= array(
					'stateAbb' => "abc",
					'branch' => "sas1"
				);
				$journalController = new JournalController(new Container());
				$method="post";
				$path="http://www.scerp1.com/accounting/bills";
				$journalRequest = Request::create($path,$method,$journalArray);
				$processedData = $journalController->store($journalRequest);
			}
			else
			{
				echo "contact match";
			}
		}
		else if($emailIdFlag==1)
		{
			echo "email match";
		}
		else
		{
			echo "not match";
		}	
	}
}