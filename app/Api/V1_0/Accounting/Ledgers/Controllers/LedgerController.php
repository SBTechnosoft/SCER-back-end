<?php
namespace ERP\Api\V1_0\Accounting\Ledgers\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Accounting\Ledgers\Processors\LedgerProcessor;
use ERP\Core\Accounting\Ledgers\Persistables\LedgerPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerController extends BaseController implements ContainerInterface
{
	/**
     * @var ledgerService
     * @var Processor
     * @var request
     * @var ledgerPersistable
     */
	private $ledgerService;
	private $Processor;
	private $request;
	private $ledgerPersistable;	
	
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
			$processor = new LedgerProcessor();
			$ledgerPersistable = new LedgerPersistable();		
			$ledgerPersistable = $processor->createPersistable($this->request);
			if($ledgerPersistable[0][0]=='[')
			{
				return $ledgerPersistable;
			}
			else
			{
				$ledgerService= new LedgerService();
				$status = $ledgerService->insert($ledgerPersistable);
				return $status;
			}
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $ledgerId
     */
    public function getData($ledgerId=null)
    {
		if($ledgerId==null)
		{	
			$ledgerService= new LedgerService();
			$status = $ledgerService->getAllLedgerData();
			
			return $status;
		}
		else
		{	
			$ledgerService= new LedgerService();
			$status = $ledgerService->getLedgerData($ledgerId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param  int  $ledgerGrpId
     */
    public function getAllData($ledgerGrpId)
    {
		$ledgerService= new LedgerService();
		$status = $ledgerService->getAllData($ledgerGrpId);
		return $status;
	}
	
	/**
     * get the specified resource.
     * @param  int  $companyId
     */
    public function getLedgerData($companyId)
    {
		$ledgerService= new LedgerService();
		$status = $ledgerService->getLedgerDetail($companyId);
		return $status;
	}
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  ledger_id
     */
	public function update(Request $request,$ledgerId)
    {    
		$this->request = $request;
		$Processor = new LedgerProcessor();
		$ledgerPersistable = new LedgerPersistable();		
		$ledgerService= new LedgerService();			
		$ledgerPersistable = $Processor->createPersistableChange($this->request,$ledgerId);
		//here two array and string is return at a time
		if($ledgerPersistable=="204: No Content Found For Update")
		{
			return $ledgerPersistable;
		}
		else if(is_array($ledgerPersistable))
		{
			$status = $ledgerService->update($ledgerPersistable);
			return $status;
		}
		else
		{
			return $ledgerPersistable;
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  ledger_id     
     */
    public function destroy(Request $request,$ledgerId)
    {
        $this->request = $request;
		$Processor = new LedgerProcessor();
		$ledgerPersistable = new LedgerPersistable();		
		$ledgerService= new LedgerService();			
		$ledgerPersistable = $Processor->createPersistableChange($this->request,$ledgerId);
		$ledgerService->create($ledgerPersistable);
		$status = $ledgerService->delete($ledgerPersistable);
		return $status;
    }
}
