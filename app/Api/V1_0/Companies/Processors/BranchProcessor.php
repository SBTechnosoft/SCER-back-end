<?php
namespace ERP\Api\V1_0\Sample\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Sample\Persistables\BranchPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class BranchProcessor extends BaseProcessor
{
	/**
     * @var branchPersistable
	 * @var name
	 * @var id
	 * @var request
     */
	private $branchPersistable;
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
			$name = $request->input('txtname'); 
			$age = $request->input('txtphone'); 			
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setName($name);		 
			$branchPersistable->setAge($age);					
			return $branchPersistable;		
		}		
		// update
		// else if($requestMethod === 'PATCH')
		// {
			// echo "patch";
			
			// return $this->populate(new BranchPersistable());
			// echo "PATCH<br/>";
			
			// echo "hello";
			// print_r($request->input());			
			
			// echo $name = $request->input('name'); 
			// echo $age = $request->input('age'); 
			// echo $id = $request->input('id'); 
			// $branchPersistable = new BranchPersistable();		
			// $branchPersistable->setName($name);		 
			// $branchPersistable->setAge($age);		 
			// $branchPersistable->setId($id);		 
			// return $branchPersistable;
		// }
		//delete
		// else if($requestMethod == 'DELETE')
		// {
			// echo "delete";
			// print_r($request->input());	
			// $id = $request->input('id');			
			// $branchPersistable = new BranchPersistable();		
			// $branchPersistable->setId($id);			
			// return $branchPersistable;	
		// }
		//other method
		else{	
		}		
    }
public function createPersistableUpdate(Request $request,$data)
{
	$requestMethod = $_SERVER['REQUEST_METHOD'];
	
	// update
	if($requestMethod == 'PATCH')
	{
		$name = $data->name;
		$age = $data->age;
		$id = $data->id;
		$branchPersistable = new BranchPersistable();		
		$branchPersistable->setName($name);		 
		$branchPersistable->setAge($age);		 
		$branchPersistable->setId($id);		 
		return $branchPersistable;
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