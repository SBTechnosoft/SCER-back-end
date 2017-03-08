<?php
namespace ERP\Core\Settings\Services;

// use ERP\Core\Settings\Templates\Persistables\TemplatePersistable;
// use ERP\Core\Settings\Templates\Entities\Branch;
use ERP\Model\Settings\SettingModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
// use ERP\Core\User\Entities\User;
// use ERP\Core\Settings\Templates\Entities\EncodeData;
// use ERP\Core\Settings\Templates\Entities\EncodeAllData;
// use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingService extends AbstractService
{
    /**
     * @var settingService
	 * $var settingModel
     */
    private $settingService;
    private $settingModel;
	
    /**
     * @param SettingService $settingService
     */
    public function initialize(SettingService $settingService)
    {		
		echo "init";
    }
	
    /**
     * @param SettingPersistable $persistable
     */
    public function create(SettingPersistable $persistable)
    {
		return "create method of SettingService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param SettingPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$settingArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$settingArray = func_get_arg(0);
		
		for($data=0;$data<count($settingArray);$data++)
		{
			$funcName[$data] = $settingArray[$data][0]->getName();
			$getData[$data] = $settingArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $settingArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$settingModel = new SettingModel();
		$status = $settingModel->insertData($getData,$keyName);
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
}