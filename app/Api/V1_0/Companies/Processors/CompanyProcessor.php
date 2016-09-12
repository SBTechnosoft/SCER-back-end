<?php
namespace ERP\Api\V1_0\Companies\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Companies\Persistables\CompanyPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ERP\Core\Sample\Persistables\DocumentPersistable;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyProcessor extends BaseProcessor
{   
	/**
     * @var companyPersistable
	 * @var request
     */
	private $companyPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Branch Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
	
		// insert
		if($requestMethod == 'POST')
		{
			//file upload
			$file = $request->file();
			$documentUrl = 'Storage/Document/';
			$documentName = $file['file'][0]->getClientOriginalName();
			$documentFormat = $file['file'][0]->getClientOriginalExtension();
			$documentSize = $file['file'][0]->getClientSize();
			$file['file'][0]->move($documentUrl,$documentName);	
			
			//data get from body
			$companyName = $request->input('company_name'); 
			$companyDispName = $request->input('company_display_name'); 
			$address1 = $request->input('address1'); 
			$address2 = $request->input('address2'); 
			$pincode = $request->input('pincode'); 
			$pan = $request->input('pan'); 
			$tin = $request->input('tin'); 
			$vatNo = $request->input('vat_no'); 
			$serviceTaxNo = $request->input('service_tax_no'); 
			$basicCurrencySymbol = $request->input('basic_currency_symbol'); 			
			$formalName = $request->input('formal_name'); 			
			$noOfDecimalPoints = $request->input('no_of_decimal_points'); 			
			$currencySymbol = $request->input('currency_symbol'); 			
			$isDisplay = $request->input('is_display'); 			
			$isDefault = $request->input('is_default'); 			
			$stateAbb = $request->input('state_abb'); 			
			$cityId = $request->input('city_id'); 			
			
			//set data to the persistable object
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setName($companyName);		 
			$companyPersistable->setCompanyDispName($companyDispName);		 
			$companyPersistable->setAddress1($address1);		 
			$companyPersistable->setAddress2($address2);		 
			$companyPersistable->setPincode($pincode);		 
			$companyPersistable->setPanNo($pan);		 
			$companyPersistable->setTinNo($tin);		 
			$companyPersistable->setVatNo($vatNo);		 
			$companyPersistable->setServiceTaxNo($serviceTaxNo);		 
			$companyPersistable->setBasicCurrencySymbol($basicCurrencySymbol);		 
			$companyPersistable->setFormalName($formalName);		 
			$companyPersistable->setNoOfDecimalPoints($noOfDecimalPoints);		 
			$companyPersistable->setCurrencySymbol($currencySymbol);		 
			$companyPersistable->setDocumentName($documentName);		 
			$companyPersistable->setDocumentUrl($documentUrl);		 
			$companyPersistable->setDocumentSize($documentSize);		 
			$companyPersistable->setDocumentFormat($documentFormat);		 
			$companyPersistable->setIsDisplay($isDisplay);		 
			$companyPersistable->setIsDefault($isDefault);		 
			$companyPersistable->setStateAbb($stateAbb);		 
			$companyPersistable->setId($cityId);		 
			return $companyPersistable;	
		}		
		else{
			
		}	
    }
	
	/**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * $param company_id
     * @return Company Persistable object
     */	
	public function createPersistableChange(Request $request,$companyId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			//file uploading
			$file = $request->file();
			$documentName = $file['file'][0]->getClientOriginalName();
			$documentFormat = $file['file'][0]->getClientOriginalExtension();
			$documentSize = $file['file'][0]->getClientSize();
			$path = 'Storage/Document/';
			$file['file'][0]->move($path,$documentName);
			
			//get data from body 
			$companyName = $request->input('company_name'); 
			$companyDispName = $request->input('company_display_name'); 
			$address1 = $request->input('address1'); 
			$address2 = $request->input('address2'); 
			$pincode = $request->input('pincode'); 
			$pan = $request->input('pan'); 
			$tin = $request->input('tin'); 
			$vatNo = $request->input('vat_no'); 
			$serviceTaxNo = $request->input('service_tax_no'); 
			$basicCurrencySymbol = $request->input('basic_currency_symbol'); 			
			$formalName = $request->input('formal_name'); 			
			$noOfDecimalPoints = $request->input('no_of_decimal_points'); 			
			$currencySymbol = $request->input('currency_symbol'); 			
			$isDisplay = $request->input('is_display'); 			
			$isDefault = $request->input('is_default'); 			
			$stateAbb = $request->input('state_abb'); 			
			$cityId = $request->input('city_id');
			
			//set the data in persistable object
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setName($companyName);		 
			$companyPersistable->setCompanyDispName($companyDispName);		 
			$companyPersistable->setAddress1($address1);		 
			$companyPersistable->setAddress2($address2);		 
			$companyPersistable->setPincode($pincode);		 
			$companyPersistable->setPanNo($pan);		 
			$companyPersistable->setTinNo($tin);		 
			$companyPersistable->setVatNo($vatNo);		 
			$companyPersistable->setServiceTaxNo($serviceTaxNo);		 
			$companyPersistable->setBasicCurrencySymbol($basicCurrencySymbol);		 
			$companyPersistable->setFormalName($formalName);		 
			$companyPersistable->setNoOfDecimalPoints($noOfDecimalPoints);		 
			$companyPersistable->setCurrencySymbol($currencySymbol);		 
			$companyPersistable->setDocumentName($documentName);		 
			$companyPersistable->setDocumentUrl($path);		 
			$companyPersistable->setDocumentSize($documentSize);		 
			$companyPersistable->setDocumentFormat($documentFormat);		 
			$companyPersistable->setIsDisplay($isDisplay);		 
			$companyPersistable->setIsDefault($isDefault);		 
			$companyPersistable->setStateAbb($stateAbb);		 
			$companyPersistable->setId($cityId);
			$companyPersistable->setCompanyId($companyId);
			return $companyPersistable;
			
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setId($companyId);			
			return $companyPersistable;
		}
	}	
}