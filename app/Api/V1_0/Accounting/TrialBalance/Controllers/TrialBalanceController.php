<?php
namespace ERP\Api\V1_0\Accounting\TrialBalance\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\TrialBalance\Services\TrialBalanceService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
// use ERP\Api\V1_0\Accounting\TrialBalance\Processors\LedgerProcessor;
// use ERP\Core\Accounting\TrialBalance\Persistables\LedgerPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\TrialBalance\TrialBalanceModel;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TrialBalanceController extends BaseController implements ContainerInterface
{
	/**
     * @var ledgerService
     * @var processor
     * @var request
     * @var ledgerPersistable
     */
	// private $ledgerService;
	// private $processor;
	// private $request;
	// private $ledgerPersistable;	
	
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
    public function getTrialBalanceData($companyId)
    {
		
		$trialBalance = new TrialBalanceService();
		$result = $trialBalance->getData($companyId);
		return $result;
	}
}
