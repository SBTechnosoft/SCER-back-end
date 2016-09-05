<?php
namespace ERP\Core\Cities\Services;

use ERP\Core\Cities\Persistables\CityPersistable;
use ERP\Core\Cities\Entities\City;
use ERP\Model\Cities\CityModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Cities\Entities\EncodeData;
use ERP\Core\Cities\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CityService extends AbstractService
{
    /**
     * @var cityService
	 * $var cityModel
     */
    private $cityService;
    private $companyModel;
	
    /**
     * @param CityService $cityService
     */
    public function initialize(CityService $cityService)
    {		
		echo "init";
    }
	
    /**
     * @param CityPersistable $persistable
     */
    public function create(CityPersistable $persistable)
    {
		return "create method of CityService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param CityPersistable $persistable
     * @return status
     */
	public function insert(CityPersistable $persistable)
	{
		$cityName = $persistable->getName();
		$isDisplay = $persistable->getIsDisplay();
		$stateAbb = $persistable->getStateAbb();
		$cityModel = new CityModel();
		$status = $cityModel->insertData($cityName,$isDisplay,$stateAbb);
		return $status;
	}
	
	/**
     * get all the data as per given id and call the model for database selection opertation
     * @return status
     */
	public function getAllCityData()
	{
		$cityModel = new CityModel();
		$status = $cityModel->getAllData();
		if($status=="204: No Content")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data as per given state_abb and call the model for database selection opertation
     * @return status
     */
	public function getAllData($stateAbb)
	{
		$cityModel = new CityModel();
		$status = $cityModel->getAllCityData($stateAbb);
		
		if($status=="204: No Content")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data from the table and call the model for database selection opertation
     * @return status
     */
	public function getCityData($cityId)
	{
		$cityModel = new CityModel();
		$status = $cityModel->getData($cityId);
		if($status=="404:Id Not Found")
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeData();
			$encodeData = $encoded->getEncodedData($status);
			return $encodeData;
		}
	}
	
    /**
     * get the data from persistable object and call the model for database update opertation
     * @param CityPersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update(CityPersistable $persistable, UpdateOptions $options = null)
    {
	    $cityName = $persistable->getName();		
		$stateAbb = $persistable->getStateAbb();
		$isDisplay = $persistable->getIsDisplay();
		$cityId = $persistable->getId();
		$cityModel = new CityModel();
		$status = $cityModel->updateData($cityName,$stateAbb,$isDisplay,$cityId);
		return $status;		
    }

    /**
     * get and invoke method is of Container Interface method
     * @param int $id,$name
     */
    public function get($id,$name)
    {
		echo "get";		
    }   
	public function invoke(callable $method)
	{
		echo "invoke";
	}
    /**
     * @param int $cityId
     */
    public function delete(CityPersistable $persistable)
    {      
		$cityId = $persistable->getId();
        $cityModel = new CityModel();
		$status = $cityModel->deleteData($cityId);
		return $status;
    }   
}