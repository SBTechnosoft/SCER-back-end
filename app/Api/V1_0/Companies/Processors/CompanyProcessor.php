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
	 * @var name
	 * @var id
	 * @var request
     */
	private $companyPersistable;
	private $name;
	private $id;   
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
			$file = $request->file();
			$path = 'Storage/Document/';
			$imageName = $file['file'][0]->getClientOriginalName().'<br>';
			$file['file'][0]->move($path,$imageName);
			
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
			$documentName = $request->input('document_name'); 			
			$documentUrl = $request->input('document_url'); 			
			$documentSize = $request->input('document_size'); 			
			$documentFormat = $request->input('document_format'); 			
			$isDisplay = $request->input('is_display'); 			
			$isDefault = $request->input('is_default'); 			
			$stateAbb = $request->input('state_abb'); 			
			$cityId = $request->input('city_id'); 			
			
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setCompanyName($companyName);		 
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
			$companyPersistable->setCityid($cityId);		 
			
			return $companyPersistable;	
		}		
		else{	
		}		
    }
	public function createPersistableUpdate(Request $request,$id)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'PATCH')
		{
			$file = $request->file();
			$imageName = $file['file'][0]->getClientOriginalName();
			$path1 = 'storage/';
			$file['file'][0]->move($path1,$imageName);
			
			$name = $request->input('txtname'); 
			$age = $request->input('txtphone'); 
			$companyPersistable = new CompanyPersistable();		
			$companyPersistable->setName($name);		 
			$companyPersistable->setAge($age);		 
			$companyPersistable->setId($id);		 
			$companyPersistable->setImageName($imageName);		 
			return $companyPersistable;
			
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$id = $data->id;
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setId($id);			
			return $branchPersistable;
		}
	}	
}