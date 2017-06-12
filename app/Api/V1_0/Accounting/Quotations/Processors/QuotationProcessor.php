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
}