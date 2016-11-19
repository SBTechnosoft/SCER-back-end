<?php
namespace ERP\Api\V1_0\Clients\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Clients\Persistables\ClientPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Clients\Validations\ClientValidate;
use ERP\Api\V1_0\Clients\Transformers\ClientTransformer;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ClientProcessor extends BaseProcessor
{
	/**
     * @var clientPersistable
	 * @var request
     */
	private $clientPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Client Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$clientArray = array();
		$clientValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//trim an input 
		$clientTransformer = new ClientTransformer();
		$tRequest = $clientTransformer->trimInsertData($this->request);
		
		if($tRequest==1)
		{
			return $msgArray['content'];
		}	
		else
		{
			//validation
			$clientValidate = new ClientValidate();
			$status = $clientValidate->validate($tRequest);
			if($status=="Success")
			{
				foreach ($tRequest as $key => $value)
				{
					if(!is_numeric($value))
					{
						if (strpos($value, '\'') !== FALSE)
						{
							$clientValue[$data]= str_replace("'","\'",$value);
							$keyName[$data] = $key;
						}
						else
						{
							$clientValue[$data] = $value;
							$keyName[$data] = $key;
						}
					}
					else
					{
						$clientValue[$data]= $value;
						$keyName[$data] = $key;
					}
					$data++;
				}
				// set data to the persistable object
				for($data=0;$data<count($clientValue);$data++)
				{
					//set the data in persistable object
					$clientPersistable = new ClientPersistable();	
					$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
					//make function name dynamically
					$setFuncName = 'set'.$str;
					$getFuncName[$data] = 'get'.$str;
					$clientPersistable->$setFuncName($clientValue[$data]);
					$clientPersistable->setName($getFuncName[$data]);
					$clientPersistable->setKey($keyName[$data]);
					$clientArray[$data] = array($clientPersistable);
				}
				return $clientArray;
			}
			else
			{
				return $status;
			}
		}
	}
}