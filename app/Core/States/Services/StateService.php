<?php
namespace ERP\Core\States\Services;

use ERP\Core\States\Persistables\StatePersistable;
use ERP\Core\States\Entities\State;
use ERP\Model\States\StateModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\States\Entities\EncodeData;
use ERP\Core\States\Entities\EncodeAllData;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class StateService extends AbstractService
{
    /**
     * @var stateService
	 * $var stateModel
     */
    private $stateService;
    private $stateModel;
	
    /**
     * @param StateService $stateService
     */
    public function initialize(StateService $stateService)
    {		
		echo "init";
    }
	
    /**
     * @param StatePersistable $persistable
     */
    public function create(StatePersistable $persistable)
    {
		return "create method of StateService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param StatePersistable $persistable
     * @return status
     */
	public function insert(StatePersistable $persistable)
	{
		$stateAbb = $persistable->getStateAbb();
		$isDisplay = $persistable->getIsDisplay();
		$stateName = $persistable->getName();
		
		//data pass to the model object for insertion
		$stateModel = new StateModel();
		$status = $stateModel->insertData($stateName,$isDisplay,$stateAbb);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getAllStateData()
	{
		$stateModel = new StateModel();
		$status = $stateModel->getAllData();
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
     * get all the dataas per given id and call the model for database selection opertation
     * @param state_abb
     * @return status
     */
	public function getStateData($stateAbb)
	{
		$stateModel = new StateModel();
		$status = $stateModel->getData($stateAbb);
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
     * @param StatePersistable $persistable
     * @param updateOptions $options [optional]
     * @return status
     */
    public function update(StatePersistable $persistable, UpdateOptions $options = null)
    {
	    $stateAbb = $persistable->getStateAbb();		
		$stateName = $persistable->getName();
		$isDisplay = $persistable->getIsDisplay();
		$stateModel = new StateModel();
		
		//data pass to the model object for update
		$status = $stateModel->updateData($stateAbb,$stateName,$isDisplay);
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
     * delete
     * @param $StatePersistable $persistable 
     */
    public function delete(StatePersistable $persistable)
    {      
		$stateAbb = $persistable->getStateAbb();
        $stateModel = new StateModel();
		$status = $stateModel->deleteData($stateAbb);
		return $status;
    }   
}