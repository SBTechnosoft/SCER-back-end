<?php
namespace ERP\Api\V1_0\Accounting\ProfitLoss\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\ProfitLoss\Services\ProfitLossService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\ProfitLoss\ProfitLossModel;
// use ERP\Core\Accounting\ProfitLoss\Entities\ProfitLossMpdf;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProfitLossController extends BaseController implements ContainerInterface
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
	 * @param  companyId
	 * method calls the model and get the data
	*/
    public function getProfitLossData(Request $request,$companyId)
    {
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$profitLossService = new ProfitLossService();
			$result = $profitLossService->getData($companyId);
			return $result;
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	/**
	 * get the specified resource 
	 * @param  companyId
	 * method calls the model and get the data
	*/
    // public function getDocumentpath(Request $request,$companyId)
    // {
		// Authentication
		// $tokenAuthentication = new TokenAuthentication();
		// $authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
		// $constantClass = new ConstantClass();
		// $constantArray = $constantClass->constantVariable();
		
		// if(strcmp($constantArray['success'],$authenticationResult)==0)
		// {
			// $trialBalance = new TrialBalanceService();
			// $result = $trialBalance->getData($companyId);
			
			// $trialBalanceMpdf = new TrialBalanceMpdf();
			// $generatedPath = $trialBalanceMpdf->generatePdf($result);
			// return $generatedPath;
		// }
		// else
		// {
			// return $authenticationResult;
		// }
	// }
}