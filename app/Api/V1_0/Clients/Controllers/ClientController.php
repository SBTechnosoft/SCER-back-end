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
		if(strcmp($_SERVER['REQUEST_URI'],"/accounting/bills")==0)
		{
			$this->request = $request;
			// check the requested Http method
			$requestMethod = $_SERVER['REQUEST_METHOD'];
			// insert
			if($requestMethod == 'POST')
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
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			if($clientId==null)
			{	
				$clientService= new ClientService();
				$status = $clientService->getAllClientData();
				return $status;
			}
			else
			{	
				$clientService= new ClientService();
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
}
