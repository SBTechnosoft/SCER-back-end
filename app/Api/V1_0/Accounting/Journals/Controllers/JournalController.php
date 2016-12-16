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
		$jfId = trim($this->request->input()['jfId']);
		
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
							$status = $productService->insertInOutward($productPersistable,$jfId);
							return $status;
						}
						else
						{
							return $productPersistable;
						}
					}
					else if(strcmp($request->header()['type'][0],"purchase")==0)
					{
						$inward = $constantArray['journalInward'];
						$productProcessor = new ProductProcessor();
						$productPersistable = $productProcessor->createPersistableInOutWard($this->request,$inward);
						if(is_array($productPersistable))
						{
							$status = $productService->insertInOutward($productPersistable,$jfId);
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
     * get the journal data
     */
    public function getJournalData($journalId)
    {
		$journalService = new JournalService();
		$status = $journalService->getJournalArrayData($journalId);
		return $status;
	}
	
	/**
     * get the specific data between given date or current year data
     */
    public function getSpecificData(Request $request,$companyId)
    {
		if(array_key_exists("type",$request->header()))
		{
			if(strcmp(trim($request->header()['type'][0]),'sales')==0 || strcmp(trim($request->header()['type'][0]),'purchase')==0)
			{
				//get journal-data as well as transaction-data for update
				if(array_key_exists("jfid",$request->header()))
				{
					$jfId = $request->header()['jfid'];
					$journalModel = new JournalModel();
					$status = $journalModel->getJournalTransactionData($companyId,$request->header()['type'][0],$jfId);
					$result = json_decode($status);
					print_r($result);
					
					// $ledgerId = array();
					// $balanceData = array();
					// for($arrayData=0;$arrayData<count($result->journal);$arrayData++)
					// {
						// echo "for";
						// $ledgerId[$arrayData] = $result->journal[$arrayData]->ledger->ledgerId;
						// $balanceData[$arrayData] = $journalModel->getBalanceData($ledgerId[$arrayData]);
						// echo "hi";
						// print_r(array($result->journal[$arrayData]->ledger));
						// $result->journal[$arrayData]->ledger = (Object)$balanceData[$arrayData];
						// print_r((Object)$balanceData[$arrayData]);
						
						// array_push($result->journal[$arrayData]->ledger,(Object)$balanceData[$arrayData]);
						// $result->journal[$arrayData]->ledger = $balanceData[$arrayData];
					// }
					// print_r($result);
					// print_r($balanceData);
					// exit;
					// return $status;
				}
			}
			else
			{
				return $exceptionArray['content'];
			}
		}
		//get the data between fromDate and toDate
		else if(array_key_exists("fromdate",$request->header()) && array_key_exists("todate",$request->header()))
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
	
	/**
	 * update the specified resource 
	 * @param  Request object[Request $request] and journal-folio id
	 * method calls the processor for creating persistable object & setting the data
	*/
	public function update(Request $request,$jfId)
	{
		$this->request = $request;
		$processor = new JournalProcessor();
		$journalPersistable = new JournalPersistable();		
		$journalService= new JournalService();		
		$journalModel = new JournalModel();
		$jfIdArrayData = $journalModel->getJfIdArrayData($jfId);
		$entryDateFlag=0;
		$companyIdFlag=0;
		$journalArrayFlag=0;
		$invoiceNumberFlag=0;
		$productArrayFlag=0;
		$billNumberFlag=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//check array exists
		if(array_key_exists('data', $this->request->input()))
		{
			$journalData = $this->request->input()['data'];
			$dataCountOfArray = count($this->request->input()['data']);
			for($dataArray=0;$dataArray<$dataCountOfArray-1;$dataArray++)
			{
				if(strcmp($journalData[$dataArray]['ledgerId'],$journalData[$dataArray+1]['ledgerId'])==0)
				{
					return $exceptionArray['content'];
				}
			}
		}
		//check journal-data is available in database as per given jf-id
		if(strcmp($jfIdArrayData,$exceptionArray['404'])==0)
		{
			return $exceptionArray['404'];
		}
		if(array_key_exists('type',$request->header())==1)
		{
			if(strcmp($request->header()['type'][0],'sales')==0 || strcmp($request->header()['type'][0],'purchase')==0)
			{
				$productArray = array();
				$journalArray = array();
				$inputArray = $this->request->input();
				if(array_key_exists('entryDate',$inputArray))
				{
					$entryDateFlag=1;
					$journalArray['entryDate']=$inputArray['entryDate'];
					$productArray['transactionDate']=$inputArray['entryDate'];
				}
				if(array_key_exists('companyId',$inputArray))
				{
					$companyIdFlag=1;
					$journalArray['companyId']=$inputArray['companyId'];
					$productArray['companyId'] = $inputArray['companyId'];
				}
				if(array_key_exists('invoiceNumber',$inputArray))
				{
					$invoiceNumberFlag=1;
					$productArray['invoiceNumber'] = $inputArray['invoiceNumber'];
				}
				if(array_key_exists('billNumber',$inputArray))
				{
					$billNumberFlag=1;
					$productArray['billNumber'] = $inputArray['billNumber'];
				}
				//check array exists in request 
				if(array_key_exists('data',$this->request->input()))
				{
					$journalArrayFlag=1;
					$journalArray['data']=array();
					for($arrayData=0;$arrayData<count($this->request->input()['data']);$arrayData++)
					{
						$journalArray['data'][$arrayData]=array();
						$journalArray['data'][$arrayData]['amount']=$this->request->input()['data'][$arrayData]['amount'];
						$journalArray['data'][$arrayData]['amountType']=$this->request->input()['data'][$arrayData]['amountType'];
						$journalArray['data'][$arrayData]['ledgerId']=$this->request->input()['data'][$arrayData]['ledgerId'];
					}
				}
				//check array is exists in request
				if(array_key_exists('inventory',$inputArray))
				{
					$productArrayFlag=1;
					$productArray['inventory'] = array();
					for($inventoryArray=0;$inventoryArray<count($inputArray['inventory']);$inventoryArray++)
					{
						$productArray['inventory'][$inventoryArray] = array();
						$productArray['inventory'][$inventoryArray]['productId']=$inputArray['inventory'][$inventoryArray]['productId'];
						$productArray['inventory'][$inventoryArray]['discount']=$inputArray['inventory'][$inventoryArray]['discount'];
						$productArray['inventory'][$inventoryArray]['discountType']=$inputArray['inventory'][$inventoryArray]['discountType'];
						$productArray['inventory'][$inventoryArray]['price']=$inputArray['inventory'][$inventoryArray]['price'];
						$productArray['inventory'][$inventoryArray]['qty']=$inputArray['inventory'][$inventoryArray]['qty'];
					}
					
				}
				//journal data is available in sale/purchase for update
				if($entryDateFlag==1 || $companyIdFlag==1 || $journalArrayFlag==1)
				{
					//journal data is processed(trim,validation and set data in object)
					$journalPersistable = $processor->createPersistableChange($journalArray,$jfId);
					if(is_array($journalPersistable))
					{
						$status = $journalService->update($journalPersistable,$jfId);
						//update data in product_transaction
						if(strcmp($status,$exceptionArray['200'])==0)
						{
							//product transaction data is available for update
							if($productArrayFlag==1 || $invoiceNumberFlag==1 || $entryDateFlag==1 || $companyIdFlag==1 || $billNumberFlag==1)
							{
								//sale data update
								if(strcmp($request->header()['type'][0],'sales')==0)
								{
									if($billNumberFlag==1)
									{
										//wrong entry
									}
									else
									{
										$inOutward = $constantArray['journalOutward'];
									}
								}
								else
								{
									if($invoiceNumberFlag==1)
									{
										//wrong entry
									}
									else
									{
										$inOutward = $constantArray['journalInward'];
									}
								}
								$productService= new ProductService();	
								$productPersistable = new ProductPersistable();
								$productProcessor = new ProductProcessor();
								$productPersistable = $productProcessor->createPersistableChangeInOutWard($productArray,$inOutward);
								//here two array and string is return at a time
								if(is_array($productPersistable))
								{
									$status = $productService->updateInOutwardData($productPersistable,$jfId,$inOutward);
									return $status;
								}
								else
								{
									return $productPersistable;
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
				else
				{
					echo "else";
					//sale data update
					if(strcmp($request->header()['type'][0],'sales')==0)
					{
						if($billNumberFlag==1)
						{
							//wrong entry
						}
						else
						{
							$inOutward = $constantArray['journalOutward'];
						}
					}
					else
					{
						if($invoiceNumberFlag==1)
						{
							//wrong entry
						}
						else
						{
							$inOutward = $constantArray['journalInward'];
						}
					}
					$productService= new ProductService();	
					$productPersistable = new ProductPersistable();
					$productProcessor = new ProductProcessor();
					$productPersistable = $productProcessor->createPersistableChangeInOutWard($productArray,$inOutward);
					
					//here two array and string is return at a time
					if(is_array($productPersistable))
					{
						$status = $productService->updateInOutwardData($productPersistable,$jfId,$inOutward);
						return $status;
					}
					else
					{
						return $productPersistable;
					}
				}
				exit;
				
			}
		}
		else
		{
			$journalArray = $this->request->input();
			//journal data is processed(trim,validation and set data in object)
			$journalPersistable = $processor->createPersistableChange($journalArray,$jfId);
			//here two array and string is return at a time
			if(is_array($journalPersistable))
			{
				$status = $journalService->update($journalPersistable,$jfId);
				
				return $status;
			}
			else
			{
				return $journalPersistable;
			}
		}
	}
}
