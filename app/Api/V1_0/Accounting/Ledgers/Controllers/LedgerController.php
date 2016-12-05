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
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\Ledgers\LedgerModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerController extends BaseController implements ContainerInterface
{
	/**
     * @var ledgerService
     * @var processor
     * @var request
     * @var ledgerPersistable
     */
	private $ledgerService;
	private $processor;
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
			else if(is_array($ledgerPersistable))
			{
				$ledgerService= new LedgerService();
				$status = $ledgerService->insert($ledgerPersistable);
				return $status;
			}
			else
			{
				return $ledgerPersistable;
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
    public function getLedgerData(Request $request,$companyId)
    {
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		if(array_key_exists("type",$request->header()))
		{
			if(strcmp(trim($request->header()['type'][0]),'sales')==0 || strcmp(trim($request->header()['type'][0]),'purchase')==0)
			{
				if(array_key_exists("fromdate",$request->header()) && array_key_exists("todate",$request->header()))
				{
					$this->request = $request;
					$processor = new LedgerProcessor();
					$ledgerPersistable = new LedgerPersistable();
					$ledgerPersistable = $processor->createPersistableData($this->request);
					$ledgerService= new LedgerService();
					$status = $ledgerService->getData($ledgerPersistable,$companyId,$request->header()['type'][0]);
					return $status;
				}
				//get current year data
				else
				{
					$ledgerModel = new LedgerModel();
					$status = $ledgerModel->getCurrentYearData($companyId,$request->header()['type'][0]);
					return $status;
				}
			}
			else
			{
				return $exceptionArray['content'];
			}
		}
		else
		{
			$ledgerService= new LedgerService();
			$status = $ledgerService->getLedgerDetail($companyId);
			return $status;
		}
	}
	
	/**
     * get the transaction resource.
     * @param  int  $ledgerId
     */
    public function getLedgerTransactionData($ledgerId)
    {
		$ledgerService= new LedgerService();
		$status = $ledgerService->getLedgerTransactionDetail($ledgerId);
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
		$processor = new LedgerProcessor();
		$ledgerPersistable = new LedgerPersistable();		
		$ledgerService= new LedgerService();		
		$ledgerModel = new LedgerModel();
		$result = $ledgerModel->getData($ledgerId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($result,$exceptionArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$ledgerPersistable = $processor->createPersistableChange($this->request,$ledgerId);
			//here two array and string is return at a time
			if(is_array($ledgerPersistable))
			{
				$status = $ledgerService->update($ledgerPersistable);
				return $status;
			}
			else
			{
				return $ledgerPersistable;
			}
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
		$ledgerModel = new LedgerModel();
		$result = $ledgerModel->getData($ledgerId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$fileSizeArray = $exception->messageArrays();
		if(strcmp($result,$fileSizeArray['404'])==0)
		{
			return $result;
		}
		else
		{		
			$ledgerPersistable = $Processor->createPersistableChange($this->request,$ledgerId);
			$ledgerService->create($ledgerPersistable);
			$status = $ledgerService->delete($ledgerPersistable);
			return $status;
		}
    }
}
