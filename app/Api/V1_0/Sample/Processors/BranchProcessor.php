<?php
namespace ERP\Api\V1_0\Sample\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Sample\Persistables\BranchPersistable;
use ERP\Core\Sample\Persistables\DocumentPersistable;
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
	 * @var name
	 * @var id
	 * @var request
     */
	private $branchPersistable;
	private $name;
	private $id;   
	private $request;    
	private $persistable;    
	
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
			$path1 = 'storage/';
			$imageName = $file['file'][0]->getClientOriginalName();
			$file['file'][0]->move($path1,$imageName);
			$name = $request->input('txtname'); 
			$age = $request->input('txtphone'); 			
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setName($name);		 
			$branchPersistable->setAge($age);
			$branchPersistable->setImageName($imageName);
			$file = $request->file();
			return $branchPersistable;		
		}		
		else{	
		}		
    }
	public function createPersistableChange(Request $request,$id)
	{
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		
		// update
		if($requestMethod == 'POST')
		{
			$file = $request->file();
			$imageName = $file['file'][0]->getClientOriginalName();
			$path1 = 'storage/';
			$file['file'][0]->move($path1,$imageName);
			
			$name = $request->input('txtname'); 
			$age = $request->input('txtphone'); 
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setName($name);		 
			$branchPersistable->setAge($age);		 
			$branchPersistable->setId($id);		 
			$branchPersistable->setImageName($imageName);		 
			return $branchPersistable;
		}
		//delete
		else if($requestMethod == 'DELETE')
		{
			$branchPersistable = new BranchPersistable();		
			$branchPersistable->setId($id);			
			return $branchPersistable;
		}
	}
}