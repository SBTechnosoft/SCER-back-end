<?php
namespace ERP\Api\V1_0\Accounting\Journals\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Journals\Services\JournalService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Accounting\Journals\Processors\JournalProcessor;
use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use Illuminate\Support\Collection;
use ERP\Api\V1_0\Products\Processors\ProductProcessor;
use ERP\Core\Products\Services\ProductService;
use ERP\Core\Products\Persistables\ProductPersistable;
use ERP\Model\Accounting\Journals\JournalModel;
use ERP\Entities\Constants\ConstantClass;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalController extends BaseController implements ContainerInterface
{
	/**
     * @var journalService
     * @var processor
     * @var request
     * @var journalPersistable
     */
	private $journalService;
	private $processor;
	private $request;
	private $journalPersistable;	
	
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
		//special journal entry and inventory entry
		$this->request = $request;
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		// insert
		if($requestMethod == 'POST')
		{
			$processor = new JournalProcessor();
			$journalPersistable = new JournalPersistable();
			$journalPersistable = $processor->createPersistable($this->request);
			if(is_array($journalPersistable))
			{
				$journalService= new JournalService();
				$status = $journalService->insert($journalPersistable);
				if(count($request->input())>4)
				{
					$productService= new ProductService();	
					$productPersistable = new ProductPersistable();
					if(strcmp($request->header()['type'][0],"sales")==0)
					{
						$outward = $constantArray['journalOutward'];
						$productProcessor = new ProductProcessor();
						$productPersistable = $productProcessor->createPersistableInOutWard($this->request,$outward);
						
						if(is_array($productPersistable))
						{
							$status = $productService->insertInOutward($productPersistable);
							return $status;
						}
						else
						{
							return $productPersistable;
						}
					}
					else if(strcmp($request->header()['type'],"purchase")==0)
					{
						$inward = $constantArray['journalInward'];
						$productProcessor = new ProductProcessor();
						$productPersistable = $productProcessor->createPersistableInOutWard($this->request,$inward);
						if(is_array($productPersistable))
						{
							$status = $productService->insertInOutward($productPersistable);
							return $status;
						}
						else
						{
							return $productPersistable;
						}
					}
				}
				else
				{
					return $status;
				}
				
			}
			else
			{
				return $journalPersistable;
			}
		}
	}
	
	/**
     * get the next journal folio id
     */
    public function getData()
    {
		$journalService = new JournalService();
		$status = $journalService->getJournalData();
		return $status;
	}
	
	/**
     * get the specific data between given date or current year data
     */
    public function getSpecificData(Request $request,$companyId)
    {
		//get the data between fromDate and toDate
		if(strcmp(array_keys($request->header())[5],"fromdate")==0)
		{
			$this->request = $request;
			$processor = new JournalProcessor();
			$journalPersistable = new JournalPersistable();
			$journalPersistable = $processor->createPersistableData($this->request);
			$journalService= new JournalService();
			$status = $journalService->getJournalDetail($journalPersistable,$companyId);
			return $status;
		}
		//if date is not given..get the data of current year
		else
		{
			$journalModel = new JournalModel();
			$status = $journalModel->getCurrentYearData($companyId);
			return $status;
		}
	}
	
	public function update(Request $request,$journalId)
	{
		echo "enter";
		$this->request = $request;
		$processor = new JournalProcessor();
		$journalPersistable = new JournalPersistable();		
		$journalService= new JournalService();		
		$journalModel = new JournalModel();
		$result = $journalModel->getSpecificJournalData($journalId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($result,$exceptionArray['404'])==0)
		{
			return $result;
		}
		else
		{
			$journalPersistable = $processor->createPersistableChange($this->request,$journalId);
			
			//here two array and string is return at a time
			if(is_array($journalPersistable))
			{
				$status = $journalService->update($journalPersistable);
				return $status;
			}
			else
			{
				return $journalPersistable;
			}
		}
	}
}
