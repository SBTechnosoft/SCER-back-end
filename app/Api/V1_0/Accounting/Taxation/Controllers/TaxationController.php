<?php
namespace ERP\Api\V1_0\Accounting\Taxation\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Taxation\Services\TaxationService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\Taxation\TaxationModel;
// use ERP\Core\Accounting\Taxation\Entities\TrialBalanceOperation;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TaxationController extends BaseController implements ContainerInterface
{
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
	 * get the specified resource 
	 * @param  Request $request
	 * method calls the model and get the data
	*/
    public function getSaleTaxData(Request $request)
    {
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$taxationService = new TaxationService();
			$resultData = $taxationService->getSaleTaxData();
			return $resultData;
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * get the specified resource 
	 * @param  Request $request
	 * method calls the model and get the data
	*/
    public function getPurchaseTaxData(Request $request)
    {
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$taxationService = new TaxationService();
			$resultData = $taxationService->getPurchaseTaxData();
			return $resultData;
		}
		else
		{
			return $authenticationResult;
		}
	}
}
