<?php
namespace ERP\Api\V1_0\Settings\Templates\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Settings\Templates\Persistables\TemplatePersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Settings\Templates\Validations\TemplateValidate;
use ERP\Api\V1_0\Settings\Templates\Transformers\TemplateTransformer;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TemplateProcessor extends BaseProcessor
{
	/**
     * @var templatePersistable
	 * @var request
     */
	private $templatePersistable;
	private $request;    
	
    
	public function createPersistableChange(Request $request,$templateId)
	{
		$templateValue = array();
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$templatePersistable;
		$templateArray = array();
		$templateValidate = new TemplateValidate();
		$status;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			//if data is not available in update request
			if(count($_POST)==0)
			{
				$status = "204: No Content Found For Update";
				return $status;
			}
			//data is avalilable for update
			else
			{
				for($data=0;$data<count($_POST);$data++)
				{
					//data get from body
					$templatePersistable = new TemplatePersistable();
					$value[$data] = $_POST[array_keys($_POST)[$data]];
					$key[$data] = array_keys($_POST)[$data];
					
					//trim an input 
					$templateTransformer = new TemplateTransformer();
					$tRequest = $templateTransformer->trimUpdateData($key[$data],$value[$data]);
					//get data from trim array
					
					$tKeyValue[$data] = array_keys($tRequest[0])[0];
					$tValue[$data] = $tRequest[0][array_keys($tRequest[0])[0]];
					
					//validation
					$status = $templateValidate->validateUpdateData($key[$data],$value[$data],$tRequest[0]);
					//enter data is valid(one data validate status return)
					if($status=="Success")
					{
						// check data is string or not
						if(!is_numeric($tValue[$data]))
						{
							if (strpos($tValue[$data], '\'') !== FALSE)
							{
								$templateValue[$data] = str_replace("'","\'",$tValue[$data]);
							}
							else
							{
								$templateValue[$data] = $tValue[$data];
							}
						}
						else
						{
							$templateValue[$data] = $tValue[$data];
						}
						//flag=0...then data is valid(consider one data at a time)
						if($flag==0)
						{
							$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue[$data])));
							//make function name dynamically
							$setFuncName = 'set'.$str;
							$getFuncName[$data] = 'get'.$str;
							$templatePersistable->$setFuncName($templateValue[$data]);
							$templatePersistable->setName($getFuncName[$data]);
							$templatePersistable->setKey($key[$data]);
							$templatePersistable->setTemplateId($templateId);
							$templateArray[$data] = array($templatePersistable);
						}
					}
					//enter data is not valid
					else
					{
						//if flag==1 then enter data is not valid ..so error return(consider one data at a time)
						$flag=1;
						if(!empty($status[0]))
						{
							$errorStatus[$errorCount]=$status[0];
							$errorCount++;
						}
					}
					if($data==(count($_POST)-1))
					{
						if($flag==1)
						{
							return json_encode($errorStatus);
						}
						else
						{
							return $templateArray;
						}
					}
				}
			}
		}
	}	
}