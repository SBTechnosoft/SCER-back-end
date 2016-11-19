<?php
namespace ERP\Api\V1_0\Settings\InvoiceNumbers\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Settings\InvoiceNumbers\Persistables\InvoicePersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Settings\InvoiceNumbers\Validations\InvoiceValidate;
use ERP\Api\V1_0\Settings\InvoiceNumbers\Transformers\InvoiceTransformer;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceProcessor extends BaseProcessor
{
	/**
     * @var invoicePersistable
	 * @var request
     */
	private $invoicePersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return Invoice Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$invoiceArray = array();
		$invoiceValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		
		//get exception message
		$exception = new ExceptionMessage();
		$msgArray = $exception->messageArrays();
		
		//trim an input 
		$invoiceTransformer = new InvoiceTransformer();
		$tRequest = $invoiceTransformer->trimInsertData($this->request);
		if($tRequest==1)
		{
			return $msgArray['content'];
		}	
		else
		{
			//validation
			$invoiceValidate = new InvoiceValidate();
			$status = $invoiceValidate->validate($tRequest);
			if($status=="Success")
			{
				foreach ($tRequest as $key => $value)
				{
					if(!is_numeric($value))
					{
						if (strpos($value, '\'') !== FALSE)
						{
							$invoiceValue[$data]= str_replace("'","\'",$value);
							$keyName[$data] = $key;
						}
						else
						{
							$invoiceValue[$data] = $value;
							$keyName[$data] = $key;
						}
					}
					else
					{
						$invoiceValue[$data]= $value;
						$keyName[$data] = $key;
					}
					$data++;
				}
				
				// set data to the persistable object
				for($data=0;$data<count($invoiceValue);$data++)
				{
					//set the data in persistable object
					$invoicePersistable = new InvoicePersistable();	
					$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
					//make function name dynamically
					$setFuncName = 'set'.$str;
					$getFuncName[$data] = 'get'.$str;
					$invoicePersistable->$setFuncName($invoiceValue[$data]);
					$invoicePersistable->setName($getFuncName[$data]);
					$invoicePersistable->setKey($keyName[$data]);
					$invoiceArray[$data] = array($invoicePersistable);
				}
				return $invoiceArray;
			}
			else
			{
				return $status;
			}
		}
	}
}