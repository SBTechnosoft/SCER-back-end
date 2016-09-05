<?php
namespace ERP\Api\V1_0\ProductCategories\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\ProductCategories\Persistables\ProductCategoryPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductCategoryProcessor extends BaseProcessor
{
	/**
     * @var productCatPersistable
	 * @var stateName
	 * @var stateAbb
	 * @var isDisplay
	 * @var request
     */
	private $productCatPersistable;
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
			$productCatName = $request->input('product_cat_name'); 
			$productCatDesc = $request->input('product_cat_desc'); 
			$isDisplay = $request->input('is_display'); 
			$productParentCatId = $request->input('product_parent_cat_id'); 
			$productCatPersistable = new ProductCategoryPersistable();		
			$productCatPersistable->setName($productCatName);		 
			$productCatPersistable->setProductCatDesc($productCatDesc);		 
			$productCatPersistable->setProductParentCatId($productParentCatId);		 
			$productCatPersistable->setIsDisplay($isDisplay);		 
			return $productCatPersistable;	
		}		
		else{	
		}		
    }
	public function createPersistableChange(Request $request,$productCatId)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'POST')
		{
			$productCatName = $request->input('product_cat_name'); 
			$productCatDesc = $request->input('product_cat_desc'); 
			$isDisplay = $request->input('is_display'); 
			$productParentCatId = $request->input('product_parent_cat_id');
			$productCatPersistable = new ProductCategoryPersistable();		
			$productCatPersistable->setName($productCatName);		 
			$productCatPersistable->setProductCatDesc($productCatDesc);		 
			$productCatPersistable->setProductParentCatId($productParentCatId);		 
			$productCatPersistable->setIsDisplay($isDisplay);			 
			$productCatPersistable->setId($productCatId);		 
			return $productCatPersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$productCatPersistable = new ProductCategoryPersistable();		
			$productCatPersistable->setId($productCatId);			
			return $productCatPersistable;
		}
	}	
}