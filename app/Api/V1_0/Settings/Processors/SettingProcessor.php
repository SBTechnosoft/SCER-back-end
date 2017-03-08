<?php
namespace ERP\Api\V1_0\Settings\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Settings\Persistables\SettingPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Settings\Validations\SettingValidate;
use ERP\Api\V1_0\Settings\Transformers\SettingTransformer;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingProcessor extends BaseProcessor
{
	/**
     * @var settingPersistable
	 * @var request
     */
	private $settingPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return setting Array / Error Message Array / Exception Message
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$settingArray = array();
		$settingValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		if(count($_POST)==0)
		{
			return $msgArray['204'];
		}
		else
		{
			//trim an input 
			$settingTransformer = new SettingTransformer();
			$tRequest = $settingTransformer->trimInsertData($this->request);
			
			// validation
			$settingValidate = new SettingValidate();
			$status = $settingValidate->validate($tRequest);
			
			if($status=="Success")
			{
				foreach ($tRequest as $key => $value)
				{
					if(!is_numeric($value))
					{
						if (strpos($value, '\'') !== FALSE)
						{
							$settingValue[$data]= str_replace("'","\'",$value);
							$keyName[$data] = $key;
						}
						else
						{
							$settingValue[$data] = $value;
							$keyName[$data] = $key;
						}
					}
					else
					{
						$settingValue[$data]= $value;
						$keyName[$data] = $key;
					}
					$data++;
				}
				
				// set data to the persistable object
				for($data=0;$data<count($settingValue);$data++)
				{
					// set the data in persistable object
					$settingPersistable = new SettingPersistable();	
					$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
					// make function name dynamically
					$setFuncName = 'set'.$str;
					$getFuncName[$data] = 'get'.$str;
					$settingPersistable->$setFuncName($settingValue[$data]);
					$settingPersistable->setName($getFuncName[$data]);
					$settingPersistable->setKey($keyName[$data]);
					$settingArray[$data] = array($settingPersistable);
				}
				return $settingArray;
			}
			else
			{
				return $settingArray;
			}
			
		}
	}
}