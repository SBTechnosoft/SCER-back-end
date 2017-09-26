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
use ERP\Api\V1_0\Accounting\Ledgers\Controllers\LedgerController;
use Illuminate\Container\Container;
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
				$ledgerModel = new LedgerModel();
				$inputRequest = $request->input();
				$clientModel = new ClientModel();
				//get contact_no from database
				$clientIdData = $clientModel->getData($clientId);
				//check client by contact_no
				if(array_key_exists('contactNo',$inputRequest))
				{
					$contactNo = trim($inputRequest['contactNo']);
				}
				else
				{
					$decodedClientData = (json_decode($clientIdData));
					$contactNo = $decodedClientData->clientData[0]->contact_no;
				}
				//get client-data as per contact-no
				$clientData = $clientModel->getClientContactNoData($contactNo,$clientId);
				if(!is_array($clientData) && !is_object($clientData))
				{
					$ledgerData = $ledgerModel->getDataAsPerClientId($clientId);
					if(strcmp($ledgerData,$exceptionArray['404'])!=0)
					{
						$jsonDecodedLedgerData = json_decode($ledgerData);
						
						// contact-no not exists(update client-data)
						$processor = new ClientProcessor();
						$clientPersistable = new ClientPersistable();		
						$clientService= new ClientService();			
						$clientPersistable = $processor->createPersistableChange($request,$clientId);
						if(is_array($clientPersistable))
						{
							$status = $clientService->update($clientPersistable);
							if(strcmp($status,$exceptionArray['500'])==0)
							{
								return $status;
							}
							else
							{
								// ledger update
								$ledgerUpdateResult = $this->ledgerUpdate($inputRequest,$jsonDecodedLedgerData[0]->ledger_id,$clientId);
								if(strcmp($ledgerUpdateResult,$exceptionArray['200'])!=0)
								{
									return $ledgerUpdateResult;
								}	
							}
							return $exceptionArray['200'];
						}
						else
						{
							return $clientPersistable;
						}
					}
					else
					{
						return $exceptionArray['content'];
					}
				}
				else
				{
					//contact-no exists
					return $exceptionArray['contact'];
				}
			}
		}
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
