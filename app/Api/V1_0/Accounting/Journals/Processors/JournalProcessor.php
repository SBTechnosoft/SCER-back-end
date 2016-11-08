<?php
namespace ERP\Api\V1_0\Accounting\Journals\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Accounting\Journals\Validations\JournalValidate;
use ERP\Api\V1_0\Accounting\Journals\Transformers\JournalTransformer;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalProcessor extends BaseProcessor
{
	/**
     * @var journalPersistable
	 * @var request
     */
	private $journalPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Journal Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$journalArray = array();
		$journalValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		//trim an input 
		
		$journalTransformer = new JournalTransformer();
		$tRequest = $journalTransformer->trimInsertData($this->request);
		exit;
		//validation
		$journalValidate = new JournalValidate();
		$status = $journalValidate->validate($tRequest);
		
		if($status=="Success")
		{
			foreach ($tRequest as $key => $value)
			{
				if(!is_numeric($value))
				{
					if (strpos($value, '\'') !== FALSE)
					{
						$journalValue[$data]= str_replace("'","\'",$value);
						$keyName[$data] = $key;
					}
					else
					{
						$journalValue[$data] = $value;
						$keyName[$data] = $key;
					}
				}
				else
				{
					$journalValue[$data]= $value;
					$keyName[$data] = $key;
				}
				$data++;
			}
			
			// set data to the persistable object
			for($data=0;$data<count($journalValue);$data++)
			{
				//set the data in persistable object
				$journalPersistable = new JournalPersistable();	
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
				//make function name dynamically
				$setFuncName = 'set'.$str;
				$getFuncName[$data] = 'get'.$str;
				$journalPersistable->$setFuncName($journalValue[$data]);
				$journalPersistable->setName($getFuncName[$data]);
				$journalPersistable->setKey($keyName[$data]);
				$journalArray[$data] = array($journalPersistable);
			}
			return $journalArray;
		}
		else
		{
			return $status;
		}
	}
}