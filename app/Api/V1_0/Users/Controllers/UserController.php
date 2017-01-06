<?php
namespace ERP\Api\V1_0\Users\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Users\Services\UserService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Users\Processors\UserProcessor;
use ERP\Core\Users\Persistables\UserPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Model\Users\UserModel;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class UserController extends BaseController implements ContainerInterface
{
	/**
     * @var userService
     * @var processor
     * @var request
     * @var userPersistable
     */
	private $userService;
	private $processor;
	private $request;
	private $userPersistable;	
	
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
		$this->request = $request;
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$processor = new Userprocessor();
			$userPersistable = new UserPersistable();	
			$userService= new UserService();
			$userPersistable = $processor->createPersistable($this->request);
			
			if($userPersistable[0][0]=='[')
			{
				return $userPersistable;
			}
			else if(is_array($userPersistable))
			{
				$status = $userService->insert($userPersistable);
				return $status;
			}
			else
			{
				return $userPersistable;
			}
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get the specified resource.
     * @param  state_id
     */
    public function getData($userId=null)
    {
		if($userId==null)
		{		
			$userService= new UserService();
			$status = $userService->getAllUserData();
			return $status;
		}
		else
		{	
			$userService= new UserService();
			$status = $userService->getUserData($userId);
			return $status;
		}        
    }
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  state_abb
     */
	public function update(Request $request,$stateAbb)
    {    
		$this->request = $request;
		$processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();
		$stateModel = new StateModel();	
		$result = $stateModel->getData($stateAbb);
		
		// get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $fileSizeArray['404'];
		}
		else
		{
			$statePersistable = $processor->createPersistableChange($this->request,$stateAbb);
			
			if(is_array($statePersistable))
			{
				$status = $stateService->update($statePersistable);
				return $status;
			}
			else
			{
				return $statePersistable;
			}
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  state_abb     
     */
    public function Destroy(Request $request,$stateAbb)
    {
        $this->request = $request;
		$processor = new StateProcessor();
		$statePersistable = new StatePersistable();		
		$stateService= new StateService();	
		
		$stateModel = new StateModel();	
		$result = $stateModel->getData($stateAbb);
		
		// get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $fileSizeArray['404'];
		}
		else
		{		
			$statePersistable = $processor->createPersistableChange($this->request,$stateAbb);
			$status = $stateService->delete($statePersistable);
			return $status;
		}
    }
}
