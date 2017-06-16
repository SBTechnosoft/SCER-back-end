<?php
namespace ERP\Core\Crm\Conversations\Services;

use ERP\Model\Crm\Conversations\ConversationModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConversationService extends AbstractService
{
    /**
     * @var conversationService
	 * $var conversationModel
     */
    private $conversationService;
    private $conversationModel;
	
    /**
     * @param ConversationService $conversationService
     */
    public function initialize(ConversationService $conversationService)
    {		
		echo "init";
    }
	
    /**
     * @param ConversationPersistable $persistable
     */
    public function create(ConversationPersistable $persistable)
    {
		return "create method of ConversationService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param ConversationPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$conversationArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$conversationArray = func_get_arg(0);
		$inputData = func_get_arg(1);
		$conversationType= func_get_arg(2);
		$documentFlag=0;
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		//split error-message and data
		$errorArray = $conversationArray[count($conversationArray)-1];
		$conversationArray = array_splice($conversationArray,0,-1);
			
		//check document is available
		if(is_array($conversationArray[count($conversationArray)-1][0]))
		{
			$documentCount = count($conversationArray[count($conversationArray)-1]);
			//get document data
			for($documentArray=0;$documentArray<$documentCount;$documentArray++)
			{
				$document[$documentArray] = array();
				$document[$documentArray][0] = $conversationArray[count($conversationArray)-1][$documentArray][0];
				$document[$documentArray][1] = $conversationArray[count($conversationArray)-1][$documentArray][1];
				$document[$documentArray][2] = $conversationArray[count($conversationArray)-1][$documentArray][2];
				$document[$documentArray][3] = $conversationArray[count($conversationArray)-1][$documentArray][3];
			}
			$documentFlag=1;
		}
		else
		{
			$document='';
		}
		for($data=0;$data<count($conversationArray);$data++)
		{
			if($documentFlag==1 && $data==(count($conversationArray)-1))
			{
				break;
			}
			else
			{
				$funcName[$data] = $conversationArray[$data][0]->getName();
				$getData[$data] = $conversationArray[$data][0]->$funcName[$data]();
				$keyName[$data] = $conversationArray[$data][0]->getkey();
			}
		}
		// data pass to the model object for insert
		$conversationModel = new ConversationModel();
		if(strcmp($conversationType,'email')==0)
		{
			$status = $conversationModel->insertEmailData($getData,$keyName,$document,$errorArray,$inputData);
		}
		else
		{
			$status = $conversationModel->insertSmsData($getData,$keyName,$document,$errorArray,$inputData);
		}
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