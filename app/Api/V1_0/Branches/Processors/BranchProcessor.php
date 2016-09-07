<?php
namespace ERP\Api\V1_0\Branches\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Branches\Persistables\BranchPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchProcessor extends BaseProcessor
{
	/**
     * @var branchPersistable
	 * @var request
     */
	private $branchPersistable;
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
			//data get from body
			$branchName = $request->input('branch_name'); 
			$address1 = $request->input('address1'); 
			$address2 = $request->input('address2'); 
			$pincode = $request->input('pincode'); 
			$isDisplay = $request->input('is_display'); 			
			$isDefault = $request->input('is_default'); 			
			$stateAbb = $request->input('state_abb'); 			
			$cityId = $request->input('city_id'); 			
			$companyId = $request->input('company_id'); 			
			
			//set data to the persistable object
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setName($branchName);		 
			$branchPersistable->setAddress1($address1);		 
			$branchPersistable->setAddress2($address2);		 
			$branchPersistable->setPincode($pincode);		 
			$branchPersistable->setIsDisplay($isDisplay);		 
			$branchPersistable->setIsDefault($isDefault);		 
			$branchPersistable->setStateAbb($stateAbb);		 
			$branchPersistable->setId($cityId);		 
			$branchPersistable->setCompanyId($companyId);		 
			return $branchPersistable;	
		}		
		else{
			
		}	
    }
	public function createPersistableChange(Request $request,$branchId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			//data get from body
			$branchName = $request->input('branch_name'); 
			$address1 = $request->input('address1'); 
			$address2 = $request->input('address2'); 
			$pincode = $request->input('pincode'); 
			$isDisplay = $request->input('is_display'); 			
			$isDefault = $request->input('is_default'); 			
			$stateAbb = $request->input('state_abb'); 			
			$cityId = $request->input('city_id');
			$companyId = $request->input('company_id');
			
			//set data to the persistable object
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setName($branchName);		 
			$branchPersistable->setAddress1($address1);		 
			$branchPersistable->setAddress2($address2);		 
			$branchPersistable->setPincode($pincode);		 
			$branchPersistable->setIsDisplay($isDisplay);		 
			$branchPersistable->setIsDefault($isDefault);		 
			$branchPersistable->setStateAbb($stateAbb);		 
			$branchPersistable->setId($cityId);
			$branchPersistable->setCompanyId($companyId);
			$branchPersistable->setBranchId($branchId);
			return $branchPersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setBranchId($branchId);			
			return $branchPersistable;
		}
	}	
}