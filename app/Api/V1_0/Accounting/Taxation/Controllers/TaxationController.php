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
use ERP\Core\Accounting\Taxation\Entities\EncodeTaxationData;
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
    public function getSaleTaxData(Request $request,$companyId)
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
			$resultData = $taxationService->getSaleTaxData($request,$companyId);
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
    public function getGstReturnExcel(Request $request,$companyId)
    {
		$saleTaxResult = $this->getSaleTaxData($request,$companyId);
		$purchaseTaxResult = $this->getPurchaseTaxData($request,$companyId);
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($saleTaxResult,$exceptionArray['204'])==0 || strcmp($purchaseTaxResult,$exceptionArray['204'])==0)
		{
			return $saleTaxResult;
		}
		else
		{
			$encodeTaxationData = new EncodeTaxationData();
			$resultData = $encodeTaxationData->getGstReturnExcelPath($saleTaxResult,$purchaseTaxResult);
			return $resultData;
		}	
	}
	
	/**
	 * get the specified resource 
	 * @param  Request $request
	 * method calls the model and get the data
	*/
    public function getStockDetailData(Request $request,$companyId)
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
			$resultData = $taxationService->getStockDetailData($request,$companyId);
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
    public function getPurchaseTaxData(Request $request,$companyId)
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
			$resultData = $taxationService->getPurchaseTaxData($request,$companyId);
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
    public function getPurchaseData(Request $request,$companyId)
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
			$resultData = $taxationService->getPurchaseData($request,$companyId);
			return $resultData;
		}
		else
		{
			return $authenticationResult;
		}
	}
}
