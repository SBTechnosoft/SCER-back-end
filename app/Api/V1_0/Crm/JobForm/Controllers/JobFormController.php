<?php
namespace ERP\Api\V1_0\Crm\JobForm\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Crm\JobForm\Services\JobFormService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Crm\JobForm\Processors\JobFormProcessor;
use ERP\Core\Crm\JobForm\Persistables\JobFormPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JobFormController extends BaseController implements ContainerInterface
{
	/**
     * @var jobFormService
     * @var processor
     * @var request
     * @var jobFormPersistable
     */
	private $jobFormService;
	private $processor;
	private $request;
	private $jobFormPersistable;	
	
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
				$processor = new JobFormProcessor();
				$jobFormPersistable = new JobFormPersistable();
				$jobFormService= new JobFormService();		
				$jobFormPersistable = $processor->createPersistable($this->request);
				
				if(is_array($jobFormPersistable))
				{
					$status = $jobFormService->insert($jobFormPersistable,$this->request->header());
					return $status;
				}
				else
				{
					return $jobFormPersistable;
				}
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * get all the data
	 * @param  Request object[Request $request]
	*/
	public function getAllData(Request $request)
	{
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$jobFormService= new JobFormService();
			$status = $jobFormService->getAllData();
			return $status;
		}
		else
		{
			return $authenticationResult;
		}
	}
}
