<?php
namespace ERP\Api\V1_0\Accounting\Quotations\Processors;
	
use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\Quotations\Persistables\QuotationPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\Quotations\Validations\QuotationValidate;
use ERP\Api\V1_0\Accounting\Quotations\Transformers\QuotationTransformer;
use ERP\Model\Clients\ClientModel;
use Illuminate\Container\Container;
use ERP\Api\V1_0\Clients\Controllers\ClientController;
use ERP\Exceptions\ExceptionMessage;
use ERP\Entities\Constants\ConstantClass;
use Carbon;
use ERP\Core\Clients\Entities\ClientArray;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
	
class QuotationProcessor extends BaseProcessor
{	/**
     * @var quotationPersistable
	 * @var request
	*/
	private $quotationPersistable;
	private $request;    
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Quotation Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;
		$clientContactFlag=0;
		$contactFlag=0;
		$taxFlag=0;

		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();

		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();	
		
		//trim an input 
		$quotationTransformer = new QuotationTransformer();
		$tRequest = $quotationTransformer->trimInsertData($this->request);	
		if($tRequest==1)
		{
			return $msgArray['content'];
		}	
		else
		{
			//validation
			$quotationValidate = new QuotationValidate();
			$status = $quotationValidate->validate($tRequest);
			if($status==$constantArray['success'])
			{
				//get contact-number from input data
				if(!array_key_exists($constantArray['contactNo'],$tRequest))
				{
					$contactNo="";
				}
				else
				{
					$contactNo = $tRequest['contact_no'];
				}
				if($contactNo=="" || $contactNo==0)
				{
					$clientArray = array();
					$clientArray['clientName']=$tRequest['client_name'];
					$clientArray['companyName']=$tRequest['company_name'];
					$clientArray['emailId']=$tRequest['email_id'];
					$clientArray['contactNo']=$tRequest['contact_no'];
					$clientArray['address1']=$tRequest['address1'];
					$clientArray['isDisplay']=$tRequest['is_display'];
					$clientArray['stateAbb']=$tRequest['state_abb'];
					$clientArray['cityId']=$tRequest['city_id'];
					$clientController = new ClientController(new Container());
					$method=$constantArray['postMethod'];
					$path=$constantArray['clientUrl'];
					$clientRequest = Request::create($path,$method,$clientArray);
					$processedData = $clientController->store($clientRequest);
					
					if(strcmp($processedData,$msgArray['content'])==0)
					{
						return $processedData;
					}
					$clientId = json_decode($processedData)[0]->client_id;
				}
				else
				{
					//check client is exists by contact-number
					$clientModel = new ClientModel();
					$clientData = $clientModel->getClientData($contactNo);
					
					if(is_array(json_decode($clientData)))
					{
						$encodedClientData = json_decode($clientData);
						$clientId = $encodedClientData[0]->client_id;
						//update client data
						$clientArray = array();
						$clientArray['clientName']=$tRequest['client_name'];
						$clientArray['companyName']=$tRequest['company_name'];
						$clientArray['emailId']=$tRequest['email_id'];
						$clientArray['contactNo']=$tRequest['contact_no'];
						$clientArray['address1']=$tRequest['address1'];
						$clientArray['isDisplay']=$tRequest['is_display'];
						$clientArray['stateAbb']=$tRequest['state_abb'];
						$clientArray['cityId']=$tRequest['city_id'];
						$clientController = new ClientController(new Container());
						$method=$constantArray['postMethod'];
						$path=$constantArray['clientUrl'].'/'.$clientId;
						$clientRequest = Request::create($path,$method,$clientArray);
						$processedData = $clientController->updateData($clientRequest,$clientId);
						if(strcmp($processedData,$msgArray['200'])!=0)
						{
							return $processedData;
						}
					}
					else
					{
						$clientArray = array();
						$clientArray['clientName']=$tRequest['client_name'];
						$clientArray['companyName']=$tRequest['company_name'];
						$clientArray['contactNo']=$tRequest['contact_no'];
						$clientArray['emailId']=$tRequest['email_id'];
						$clientArray['address1']=$tRequest['address1'];
						$clientArray['isDisplay']=$tRequest['is_display'];
						$clientArray['stateAbb']=$tRequest['state_abb'];
						$clientArray['cityId']=$tRequest['city_id'];
						$clientController = new ClientController(new Container());
						$method=$constantArray['postMethod'];
						$path=$constantArray['clientUrl'];
						$clientRequest = Request::create($path,$method,$clientArray);
						$processedData = $clientController->store($clientRequest);
						if(strcmp($processedData,$msgArray['content'])==0)
						{
							return $processedData;
						}
						$clientId = json_decode($processedData)[0]->client_id;
					}
				}
			}
			else
			{
				//data is not valid...return validation error message
				return $status;
			}
		}
		$productArray = array();
		$productArray['quotationNumber']=$tRequest['quotation_number'];
		$productArray['transactionType']=$constantArray['journalOutward'];
		$productArray['companyId']=$tRequest['company_id'];	
		
		$tInventoryArray = array();
		for($trimData=0;$trimData<count($request->input()['inventory']);$trimData++)
		{
			$tInventoryArray[$trimData] = array();
			$tInventoryArray[$trimData][5] = trim($request->input()['inventory'][$trimData]['color']);
			$tInventoryArray[$trimData][6] = trim($request->input()['inventory'][$trimData]['frameNo']);
			$tInventoryArray[$trimData][7] = trim($request->input()['inventory'][$trimData]['size']);
			array_push($request->input()['inventory'][$trimData],$tInventoryArray[$trimData]);
		}
		$productArray['inventory'] = $request->input()['inventory'];
		//entry date conversion
		$transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $tRequest['entry_date'])->format('Y-m-d');
		$quotationPersistable = new QuotationPersistable();
		$quotationPersistable->setProductArray(json_encode($productArray));
		$quotationPersistable->setQuotationNumber($tRequest['quotation_number']);		
		$quotationPersistable->setTotal($tRequest['total']);
		$quotationPersistable->setExtraCharge($tRequest['extra_charge']);
		$quotationPersistable->setTax($tRequest['tax']);		
		$quotationPersistable->setGrandTotal($tRequest['grand_total']);
		$quotationPersistable->setRemark($tRequest['remark']);
		$quotationPersistable->setEntryDate($transformEntryDate);
		$quotationPersistable->setClientId($clientId);
		$quotationPersistable->setCompanyId($tRequest['company_id']);		
		$quotationPersistable->setJfId(0);		
		return $quotationPersistable;
	}
	
	/**
     * get request data & quotation-bill-id and set into the persistable object
     * $param Request object [Request $request] and quotation-bill-id and quotationdata
     * @return Quotation Persistable object/error message
     */
	public function createPersistableChange(Request $request,$quotationBillId,$quotationData)
	{
		$balanceFlag=0;
		
		//get constant variables array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();

		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		//trim quotation data
		$quotationTransformer = new QuotationTransformer();
		$quotationTrimData = $quotationTransformer->trimQuotationUpdateData($request);
		
		// $ledgerModel = new LedgerModel();
		$clientArray = new ClientArray();
		$clientArrayData = $clientArray->getClientArrayData();
		$clientData = array();
		foreach($clientArrayData as $key => $value)
		{
			if(array_key_exists($key,$quotationTrimData))
			{
				$clientData[$value] = $quotationTrimData[$key];
			}
		}		
		//get clientId as per given quotationBillId
		$quotationData = json_decode($quotationData);	

		if(count($clientData)!=0)
		{
			//call controller of client for updating of client data
			$clientController = new ClientController(new Container());
			$clientMethod=$constantArray['postMethod'];
			$clientPath=$constantArray['clientUrl'].'/'.$quotationData[0]->client_id;
			$clientRequest = Request::create($clientPath,$clientMethod,$clientData);
			$clientData = $clientController->updateData($clientRequest,$quotationData[0]->client_id);			
			if(strcmp($clientData,$msgArray['200'])!=0)
			{
				return $clientData;
			}
		}
		//validate bill data
		//........pending
		$quoFlag=0;
		//set bill data into persistable object
		$quotationPersistable = array();
		$clientBillArrayData = $clientArray->getBillClientArrayData();
		
		//splice data from trim array
		for($index=0;$index<count($clientBillArrayData);$index++)
		{
			for($innerIndex=0;$innerIndex<count($quotationTrimData);$innerIndex++)
			{
				if(strcmp('inventory',array_keys($quotationTrimData)[$innerIndex])!=0)
				{
					if(strcmp(array_keys($quotationTrimData)[$innerIndex],array_keys($clientBillArrayData)[$index])==0)
					{
						array_splice($quotationTrimData,$innerIndex,1);
						break;
					}
				}
			}
		}
		for($quotationArrayData=0;$quotationArrayData<count($quotationTrimData);$quotationArrayData++)
		{
			// making an object of persistable
			$quotationPersistable[$quotationArrayData] = new QuotationPersistable();
			if(strcmp('inventory',array_keys($quotationTrimData)[$quotationArrayData])!=0)
			{
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', array_keys($quotationTrimData)[$quotationArrayData])));	
				$setFuncName = "set".$str;
				$getFuncName = "get".$str;
				$quotationPersistable[$quotationArrayData]->$setFuncName($quotationTrimData[array_keys($quotationTrimData)[$quotationArrayData]]);
				$quotationPersistable[$quotationArrayData]->setName($getFuncName);
				$quotationPersistable[$quotationArrayData]->setKey(array_keys($quotationTrimData)[$quotationArrayData]);
				$quotationPersistable[$quotationArrayData]->setQuotationId($quotationBillId);
			}
			else
			{
				$quoFlag=1;
				$decodedProductArrayData = json_decode($quotationData[0]->product_array);
				$productArray = array();
				$productArray['quotationNumber'] = $decodedProductArrayData->quotationNumber;
				$productArray['transactionType'] = $decodedProductArrayData->transactionType;
				$productArray['companyId'] = $decodedProductArrayData->companyId;
				$productArray['inventory'] = $quotationTrimData['inventory'];
				$quotationPersistable[$quotationArrayData]->setProductArray(json_encode($productArray));
				$quotationPersistable[$quotationArrayData]->setQuotationId($quotationBillId);
			}
		}
		if($quoFlag==1)
		{
			$quotationPersistable[count($quotationPersistable)] = 'flag';
		}
		return $quotationPersistable;
		
	}
}