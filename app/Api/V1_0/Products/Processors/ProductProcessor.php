<?php
namespace ERP\Api\V1_0\Products\Processors;

use ERP\Api\V1_0\Support\BaseProcessor;
use ERP\Core\Products\Persistables\ProductPersistable;
use Illuminate\Http\Request;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use ERP\Core\Products\Validations\ProductValidate;
use ERP\Api\V1_0\Products\Transformers\ProductTransformer;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductProcessor extends BaseProcessor
{
	/**
     * @var productPersistable
	 * @var request
     */
	private $productPersistable;
	private $request;    
	
    /**
     * get the form-data and set into the persistable object
     * $param Request object [Request $request]
     * @return product Persistable object
     */	
    public function createPersistable(Request $request)
	{	
		$this->request = $request;	
		$productValue = array();
		$tKeyValue = array();
		$keyName = array();
		$value = array();
		$data=0;
		//trim an input 
		$productTransformer = new ProductTransformer();
		$tRequest = $productTransformer->trimInsertData($this->request);
		
		//validation
		$productValidate = new ProductValidate();
		$status = $productValidate->validate($tRequest);
		if($status=="Success")
		{
			foreach ($tRequest as $key => $value)
			{
				if(!is_numeric($value))
				{
					if (strpos($value, '\'') !== FALSE)
					{
						$productValue[$data]= str_replace("'","\'",$value);
						$keyName[$data] = $key;
					}
					else
					{
						$productValue[$data] = $value;
						$keyName[$data] = $key;
					}
				}
				else
				{
					$productValue[$data]= $value;
					$keyName[$data] = $key;
				}
				$data++;
			}
			
			// set data to the persistable object
			for($data=0;$data<count($productValue);$data++)
			{
				//set the data in persistable object
				$productPersistable = new ProductPersistable();	
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $keyName[$data])));
				//make function name dynamically
				$setFuncName = 'set'.$str;
				$getFuncName[$data] = 'get'.$str;
				$productPersistable->$setFuncName($productValue[$data]);
				$productPersistable->setName($getFuncName[$data]);
				$productPersistable->setKey($keyName[$data]);
				$productArray[$data] = array($productPersistable);
			}
			return $productArray;
		}
		else
		{
			return $status;
		}
	}
	public function createPersistableChange(Request $request,$productId)
	{
		$errorCount=0;
		$errorStatus=array();
		$flag=0;
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// update
		if($requestMethod == 'POST')
		{
			$productValue = array();
			$productPersistable;
			$productArray = array();
			$productValidate = new ProductValidate();
			$status;
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
					$productPersistable = new ProductPersistable();
					$value[$data] = $_POST[array_keys($_POST)[$data]];
					$key[$data] = array_keys($_POST)[$data];
					
					//trim an input 
					$productTransformer = new ProductTransformer();
					$tRequest = $productTransformer->trimUpdateData($key[$data],$value[$data]);
					
					//get key value from trim array
					$tKeyValue[$data] = array_keys($tRequest[0])[0];
					$tValue[$data] = $tRequest[0][array_keys($tRequest[0])[0]];
					
					//validation
					$status = $productValidate->validateUpdateData($key[$data],$value[$data]);
					//enter data is valid(one data validate status return)
					if($status=="Success")
					{
						// check data is string or not
						if(!is_numeric($tValue[$data]))
						{
							if (strpos($tValue[$data], '\'') !== FALSE)
							{
								$productValue[$data] = str_replace("'","\'",$tValue[$data]);
							}
							else
							{
								$productValue[$data] = $tValue[$data];
							}
						}
						else
						{
							$productValue[$data] = $tValue[$data];
						}
						//flag=0...then data is valid(consider one data at a time)
						if($flag==0)
						{
							$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $tKeyValue[$data])));
							//make function name dynamically
							$setFuncName = 'set'.$str;
							$getFuncName[$data] = 'get'.$str;
							$productPersistable->$setFuncName($tValue[$data]);
							$productPersistable->setName($getFuncName[$data]);
							$productPersistable->setKey($key[$data]);
							$productPersistable->setProductId($productId);
							$productArray[$data] = array($productPersistable);
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
							return $productArray;
						}
					}
				}
			}
		}
		
		//delete
		else if($requestMethod == 'DELETE')
		{
			$productPersistable = new productPersistable();		
			$productPersistable->setproductId($productId);			
			return $productPersistable;
		}
	}	
}