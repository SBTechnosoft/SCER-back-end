<?php
namespace ERP\Api\V1_0\Banks\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Banks\Services\BankService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Core\Banks\Persistables\BankPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BankController extends BaseController implements ContainerInterface
{
	/**
     * @var bankService
     * @var request
     * @var bankPersistable
     */
	private $bankService;
	private $request;
	private $bankPersistable;	
	
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
     * @param  int  $bankId
     */
    public function getData($bankId=null)
    {
		$bankService= new BankService();
		if($bankId==null)
		{	
			$status = $bankService->getAllBankData();
			return $status;
		}
		else
		{	
			$status = $bankService->getBankData($bankId);
			return $status;
		}        
    }
}
