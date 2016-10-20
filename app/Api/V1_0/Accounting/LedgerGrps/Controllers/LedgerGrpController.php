<?php
namespace ERP\Api\V1_0\Accounting\LedgerGrps\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\LedgerGrps\Services\LedgerGrpService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Core\Accounting\LedgerGrps\Persistables\LedgerGrpPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerGrpController extends BaseController implements ContainerInterface
{
	/**
     * @var ledgerGrpService
     * @var request
     * @var ledgerGrpPersistable
     */
	private $ledgerGrpService;
	private $request;
	private $ledgerGrpPersistable;	
	
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
     * get the specified resource.
     * @param  int  $ledgerGrpId
     */
    public function getData($ledgerGrpId=null)
    {
		$ledgerGrpService= new LedgerGrpService();
		if($ledgerGrpId==null)
		{	
			$status = $ledgerGrpService->getAllLedgerGrpData();
			return $status;
		}
		else
		{	
			$status = $ledgerGrpService->getLedgerGrpData($ledgerGrpId);
			return $status;
		}        
    }
}
