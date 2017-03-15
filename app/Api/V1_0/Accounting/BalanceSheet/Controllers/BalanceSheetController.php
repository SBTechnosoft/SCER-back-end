<?php
namespace ERP\Api\V1_0\Accounting\BalanceSheet\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\BalanceSheet\Services\BalanceSheetService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\BalanceSheet\BalanceSheetModel;
// use ERP\Core\Accounting\BalanceSheet\Entities\TrialBalanceMpdf;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BalanceSheetController extends BaseController implements ContainerInterface
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
    public function getBalanceSheetData(Request $request,$companyId)
    {
		//Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		//get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$balanceSheet = new BalanceSheetService();
			$result = $balanceSheet->getData($companyId);
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