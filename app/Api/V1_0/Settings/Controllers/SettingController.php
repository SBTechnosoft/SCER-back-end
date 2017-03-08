<?php
namespace ERP\Api\V1_0\Settings\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Settings\Services\SettingService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Settings\Processors\SettingProcessor;
use ERP\Core\Settings\Persistables\SettingPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingController extends BaseController implements ContainerInterface
{
	/**
     * @var settingService
     * @var processor
     * @var request
     * @var settingPersistable
     */
	private $settingService;
	private $processor;
	private $request;
	private $settingPersistable;	
	
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
		// Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
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
				$processor = new SettingProcessor();
				$settingPersistable = new SettingPersistable();		
				$settingService= new SettingService();			
				$settingPersistable = $processor->createPersistable($this->request);
				if($settingPersistable[0][0]=='[')
				{
					return $settingPersistable;
				}
				else if(is_array($settingPersistable))
				{
					$status = $settingService->insert($settingPersistable);
					return $status;
				}
				else
				{
					return $settingPersistable;
				}
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
}
