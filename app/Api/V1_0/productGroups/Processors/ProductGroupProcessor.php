<?php
namespace ERP\Api\V1_0\ProductGroups\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\ProductGroups\Persistables\ProductGroupPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductGroupProcessor extends BaseProcessor
{
	/**
     * @var productCatPersistable
	 * @var stateName
	 * @var stateAbb
	 * @var isDisplay
	 * @var request
     */
	private $productGroupPersistable;
	private $stateName;
	private $stateAbb;   
	private $isDisplay;   
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return State Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// insert
		if($requestMethod == 'POST')
		{
			$productGroupName = $request->input('product_group_name'); 
			$productGroupDesc = $request->input('product_group_desc'); 
			$isDisplay = $request->input('is_display'); 
			$productParentGrpId = $request->input('product_group_parent_id'); 
			$productGroupPersistable = new ProductGroupPersistable();		
			$productGroupPersistable->setName($productGroupName);		 
			$productGroupPersistable->setProductGrpDesc($productGroupDesc);		 
			$productGroupPersistable->setProductParentGrpId($productParentGrpId);		 
			$productGroupPersistable->setIsDisplay($isDisplay);		 
			return $productGroupPersistable;	
		}		
		else{	
		}		
    }
	public function createPersistableChange(Request $request,$productGrpId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'POST')
		{
			$productGrpName = $request->input('product_group_name'); 
			$productGrpDesc = $request->input('product_group_desc'); 
			$isDisplay = $request->input('is_display'); 
			$productParentGrpId = $request->input('product_group_parent_id');
			$productGroupPersistable = new ProductGroupPersistable();		
			$productGroupPersistable->setName($productGrpName);		 
			$productGroupPersistable->setProductGrpDesc($productGrpDesc);		 
			$productGroupPersistable->setProductParentGrpId($productParentGrpId);		 
			$productGroupPersistable->setIsDisplay($isDisplay);			 
			$productGroupPersistable->setId($productGrpId);		 
			return $productGroupPersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$productGroupPersistable = new ProductGroupPersistable();		
			$productGroupPersistable->setId($productGrpId);			
			return $productGroupPersistable;
		}
	}	
}