<?php
namespace ERP\Api\V1_0\Clients\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Clients\Services\ClientService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Clients\Processors\ClientProcessor;
use ERP\Core\Clients\Persistables\ClientPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Model\Clients\ClientModel;
use ERP\Core\Clients\Entities\EncodeData;
use ERP\Model\Accounting\Ledgers\LedgerModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientController extends BaseController implements ContainerInterface
{
	/**
     * @var clientService
     * @var processor
     * @var request
     * @var clientPersistable
     */
	private $clientService;
	private $processor;
	private $request;
	private $clientPersistable;	
	
	/**
	 * get and invoke method is of ContainerInterface method
	 */		
    public function get($id,$name)
	{
		// echo "get";
	}
	public function invoke(callable $method)
	{
		// echo "invoke";
	}
	
	/**
	 * insert the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
    public function store(Request $request)
    {
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		// print_r($_SERVER['REQUEST_URI']);
		$requestUri = explode('/',$_SERVER['REQUEST_URI']);
		if(strcmp($requestUri[1],"accounting")==0 && strcmp($requestUri[2],"bills")==0 || strcmp($_SERVER['REQUEST_URI'],"/accounting/quotations")==0 
			|| strcmp($_SERVER['REQUEST_URI'],"/crm/job-form")==0)
		{
			$this->request = $request;
			// check the requested Http method
			$requestMethod = $_SERVER['REQUEST_METHOD'];
			// insert
			if($requestMethod == 'POST')
			{
				if(array_key_exists('contactNo',$request->input()))
				{
					// check contact_no exists or not?
					$clientModel = new ClientModel();
					$clientData = $clientModel->getClientData($request->input('contactNo'));
					if(strcmp($clientData,$exceptionArray['200'])==0)
					{
						$processor = new ClientProcessor();
						$clientPersistable = new ClientPersistable();		
						$clientService= new ClientService();			
						$clientPersistable = $processor->createPersistable($this->request);
						if($clientPersistable[0][0]=='[')
						{
							return $clientPersistable;
						}
						else if(is_array($clientPersistable))
						{
							$status = $clientService->insert($clientPersistable);
							return $status;
						}
						else
						{
							return $clientPersistable;
						}
					}
					else
					{
						$encodedData = new EncodeData();
						$encodedClientData = $encodedData->getEncodedData($clientData);
						return $encodedClientData;
					}
				}
				else
				{
					return $exceptionArray['content'];
				}
			}
			
		}
		else
		{
			//Authentication
			$tokenAuthentication = new TokenAuthentication();
			$authenticationResult = $tokenAuthentication->authenticate($request->header());
			//get constant array
			$constantClass = new ConstantClass();
			$constantArray = $constantClass->constantVariable();
			if(strcmp($constantArray['success'],$authenticationResult)==0)
			{
				$this->request = $request;
				// check the requested Http method
				$requestMethod = $_SERVER['REQUEST_METHOD'];
				// insert
				if($requestMethod == 'POST')
				{
					if(array_key_exists('contactNo',$request->input()))
					{
						// check contact_no exists or not?
						$clientModel = new ClientModel();
						$clientData = $clientModel->getClientData($request->input('contactNo'));
						if(strcmp($clientData,$exceptionArray['200'])==0)
						{
							$processor = new ClientProcessor();
							$clientPersistable = new ClientPersistable();		
							$clientService= new ClientService();			
							$clientPersistable = $processor->createPersistable($this->request);
							if($clientPersistable[0][0]=='[')
							{
								return $clientPersistable;
							}
							else if(is_array($clientPersistable))
							{
								$status = $clientService->insert($clientPersistable);
								return $status;
							}
							else
							{
								return $clientPersistable;
							}
						}
						else
						{
							$encodedData = new EncodeData();
							$encodedClientData = $encodedData->getEncodedData($clientData);
							return $encodedClientData;
						}
					}
					else
					{
						return $exceptionArray['content'];
					}
				}
			}
			else
			{
				return $authenticationResult;
			}
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $branchId
     */
    public function getData(Request $request,$clientId=null)
    {
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$processedData='';
		$clientService= new ClientService();
		$identifyFlag=0;
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			if($clientId==null)
			{	
				if(array_key_exists('invoicefromdate',$request->header()) && array_key_exists('invoicetodate',$request->header())
					|| array_key_exists('jobcardfromdate',$request->header()) && array_key_exists('jobcardtodate',$request->header()))
				{
					$identifyFlag=1;
					$processor = new ClientProcessor();
					$processedData = $processor->dateConversion($request->header());
					if(!is_object($processedData))
					{
						return $processedData;
					}
				}
				$status = $clientService->getAllClientData($request->header(),$processedData,$identifyFlag);
				return $status;
			}
			else
			{	
				$status = $clientService->getClientData($clientId);
				return $status;
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
     * update the specified resource
     * @param  Request $request (request object contains data)
	 * @return status/exception-message
     */
	public function updateData(Request $request,$clientId)
	{
		$requestUri = explode('/',$_SERVER['REQUEST_URI']);
		if(strcmp($requestUri[1],"accounting")==0 && strcmp($requestUri[2],"bills")==0 || strcmp($_SERVER['REQUEST_URI'],"/accounting/quotations")==0 
			|| strcmp($_SERVER['REQUEST_URI'],"/crm/job-form")==0)
		{
			
			$processor = new ClientProcessor();
			$clientPersistable = new ClientPersistable();		
			$clientService= new ClientService();			
			$clientPersistable = $processor->createPersistableChange($request,$clientId);
			
			if(is_array($clientPersistable))
			{
				$status = $clientService->update($clientPersistable);
				return $status;
			}
			else
			{
				return $clientPersistable;
			}
			
		}
		else
		{
			//Authentication
			$tokenAuthentication = new TokenAuthentication();
			$authenticationResult = $tokenAuthentication->authenticate($request->header());
			
			// get exception message
			$exception = new ExceptionMessage();
			$exceptionArray = $exception->messageArrays();
			
			//get constant array
			$constantClass = new ConstantClass();
			$constantArray = $constantClass->constantVariable();
			if(strcmp($constantArray['success'],$authenticationResult)==0)
			{
				$processor = new ClientProcessor();
				$clientPersistable = new ClientPersistable();		
				$clientService= new ClientService();			
				$clientPersistable = $processor->createPersistableChange($request,$clientId);
				
				if(is_array($clientPersistable))
				{
					$status = $clientService->update($clientPersistable);
					if(strcmp($exceptionArray['200'],$status)==0)
					{
						//get data from client-model as per given client-id
						// $clientModel = new ClientModel();
						// $clientData = $clientModel->getData($clientId);
						// if(strcmp($clientData,$exceptionArray['404'])==0)
						// {
							// return $exceptionArray['404'];
						// }
						// $decodedClientData = json_decode($clientData);
						// $companydId='';
						// get ledgerId for update ledegerData 
						// $ledgerModel = new LedgerModel();
						// $getLedgerData = $ledgerModel->getDataAsPerContactNo($companydId,$decodedClientData->clientData[0]->contact_no);
						// if(strcmp($getLedgerData,$exceptionArray['500'])==0)
						// {
							// return $exceptionArray['500'];
						// }
						// $decodedLedgerData = json_decode($getLedgerData);
						// $ledgerId = $decodedLedgerData[0]->ledger_id;
						//update ledger data
						// $ledgerArray=array();
						// $ledgerArray['ledgerName']=$decodedClientData->clientData[0]->client_name;
						// $ledgerArray['address1']=$decodedClientData->clientData[0]->address1;
						// $ledgerArray['address2']='';
						// $ledgerArray['contactNo']=$decodedClientData->clientData[0]->contact_no;
						// $ledgerArray['emailId']=$decodedClientData->clientData[0]->email_id;
						// $ledgerArray['invoiceNumber']='';
						// $ledgerArray['stateAbb']=$decodedClientData->clientData[0]->state_abb;
						// $ledgerArray['cityId']=$decodedClientData->clientData[0]->city_id;
						// $ledgerArray['companyId']='';
						// $ledgerArray['balanceFlag']=$constantArray['openingBalance'];
						// $ledgerArray['amount']=0;
						// $ledgerArray['amountType']=$constantArray['credit'];
						// $ledgerArray['ledgerGroupId']=$constantArray['ledgerGroupSundryDebitors'];
						// $ledgerController = new LedgerController(new Container());
						// $method=$constantArray['postMethod'];
						// $path=$constantArray['ledgerUrl'].'/'.$ledgerId;
						// $ledgerRequest = Request::create($path,$method,$ledgerArray);
						// $processedData = $ledgerController->update($ledgerRequest,$ledgerId);
						// if(strcmp($processedData,$exceptionArray['200'])!=0)
						// {
							// return $processedData;
						// }
					}
					else
					{
						return $status;
					}
				}
				else
				{
					return $clientPersistable;
				}
			}
		}
		
	}
}
