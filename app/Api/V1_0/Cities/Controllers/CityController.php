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
     * @var cityName
     * @var request
     * @var cityPersistable
     */
	private $companyService;
	private $Processor;
	private $companyName;
	private $request;
	private $cityPersistable;	
	
	/**
	 * get and invoke method is of ContainerInterface method
	 */		
    public function get($cityId,$cityName)
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
		$Processor = new CityProcessor();
		$cityPersistable = new CityPersistable();		
		$cityService= new CityService();			
		$cityPersistable = $Processor->createPersistable($this->request);
		$cityService->create($cityPersistable);
		$status = $cityService->insert($cityPersistable);
		return $status;
	}
	
	/**
     * get the specified resource.
     * @param  int  $companyId
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
     * @param  int  $companyId
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
     */
	public function update(Request $request,$cityId)
    {    
		$this->request = $request;
		$Processor = new CityProcessor();
		$cityPersistable = new CityPersistable();		
		$cityService= new CityService();			
		$cityPersistable = $Processor->createPersistableChange($this->request,$cityId);
		$cityService->create($cityPersistable);
		$status = $cityService->update($cityPersistable);
		return $status;
    }
	
    /**
     * Remove the specified resource from storage.
     * @param  Request object[Request $request]     
     */
    public function Destroy(Request $request,$cityId)
    {
        $this->request = $request;
		$Processor = new CityProcessor();
		$cityPersistable = new CityPersistable();		
		$cityService= new CityService();			
		$cityPersistable = $Processor->createPersistableChange($this->request,$cityId);
		$cityService->create($cityPersistable);
		$status = $cityService->delete($cityPersistable);
		return $status;
    }
}
