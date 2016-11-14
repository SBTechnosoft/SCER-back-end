<?php
namespace ERP\Api\V1_0\Cities\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Cities\Services\CityService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Cities\Processors\CityProcessor;
use ERP\Core\Cities\Persistables\CityPersistable;
use ERP\Core\Support\Service\ContainerInterface;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityController extends BaseController implements ContainerInterface
{
	/**
     * @var cityService
     * @var Processor
     * @var request
     * @var cityPersistable
     */
	private $cityService;
	private $Processor;
	private $request;
	private $cityPersistable;	
	
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
		$this->request = $request;
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$Processor = new CityProcessor();
			$cityPersistable = new CityPersistable();		
			$cityService= new CityService();			
			$cityPersistable = $Processor->createPersistable($this->request);
			if($cityPersistable[0][0]=='[')
			{
				return $cityPersistable;
			}
			else
			{
				$status = $cityService->insert($cityPersistable);
				return $status;
			}
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get the specified resource.
     * @param  int  $cityId
     */
    public function getData($cityId=null)
    {
		if($cityId==null)
		{			
			$cityService= new CityService();
			$status = $cityService->getAllCityData();
			return $status;
		}
		else
		{	
			$cityService= new CityService();
			$status = $cityService->getCityData($cityId);
			return $status;
		}        
    }
	
	/**
     * get the specified resource.
     * @param  int  $stateAbb
     */
    public function getAllData($stateAbb)
    {
		$cityService= new CityService();
		$status = $cityService->getAllData($stateAbb);
		return $status;
		
	}
	
    /**
     * Update the specified resource in storage.
     * @param  Request object[Request $request]
     * @param  city_id
     * @return status
     */
	public function update(Request $request,$cityId)
    {    
		$this->request = $request;
		$Processor = new CityProcessor();
		$cityPersistable = new CityPersistable();		
		$cityService= new CityService();			
		$cityPersistable = $Processor->createPersistableChange($this->request,$cityId);
		if($cityPersistable=="204: No Content Found For Update")
		{
			return $cityPersistable;
		}
		else if(is_array($cityPersistable))
		{
			$status = $cityService->update($cityPersistable);
			return $status;
		}
		else
		{
			return $cityPersistable;
		}
	}
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     * @param  city_id
     * @return status     
     */
    public function Destroy(Request $request,$cityId)
    {
        $this->request = $request;
		$Processor = new CityProcessor();
		$cityPersistable = new CityPersistable();		
		$cityService= new CityService();			
		$cityPersistable = $Processor->createPersistableChange($this->request,$cityId);
		$status = $cityService->delete($cityPersistable);
		return $status;
    }
}