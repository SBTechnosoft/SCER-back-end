<?php
namespace ERP\Api\V1_0\settings\QuotationNumbers\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\settings\QuotationNumbers\Persistables\QuotationPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\settings\QuotationNumbers\Validations\QuotationValidate;
use ERP\Api\V1_0\settings\QuotationNumbers\Transformers\QuotationTransformer;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class QuotationProcessor extends BaseProcessor
{
	/**
     * @var quotationPersistable
	 * @var request
     */
	private $quotationPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Invoice Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$quotationArray = array();
		$quotationValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		//trim an input 
		$quotationTransformer = new QuotationTransformer();
		$tRequest = $quotationTransformer->trimInsertData($this->request);
		
		//validation
		$quotationValidate = new QuotationValidate();
		$status = $quotationValidate->validate($tRequest);
		if($status=="Success")
		{
			foreach ($tRequest as $key => $value)
			{
				if(!is_numeric($value))
				{
					if (strpos($value, '\'') !== FALSE)
					{
						$quotationValue[$data]= str_replace("'","\'",$value);
						$keyName[$data] = $key;
					}
					else
					{
						$quotationValue[$data] = $value;
						$keyName[$data] = $key;
					}
				}
				else
				{
					$quotationValue[$data]= $value;
					$keyName[$data] = $key;
				}
				$data++;
			}
			
			// set data to the persistable object
			for($data=0;$data<count($quotationValue);$data++)
			{
				//set the data in persistable object
				$quotationPersistable = new QuotationPersistable();	
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
				//make function name dynamically
				$setFuncName = 'set'.$str;
				$getFuncName[$data] = 'get'.$str;
				$quotationPersistable->$setFuncName($quotationValue[$data]);
				$quotationPersistable->setName($getFuncName[$data]);
				$quotationPersistable->setKey($keyName[$data]);
				$quotationArray[$data] = array($quotationPersistable);
			}
			return $quotationArray;
		}
		else
		{
			return $status;
		}
	}
}