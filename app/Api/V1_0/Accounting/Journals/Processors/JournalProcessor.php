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
			$journalTransformer = new JournalTransformer();
			$tRequest = $journalTransformer->trimInsertData($this->request);
			
			if($tRequest==1)
			{
				return $msgArray['content'];
			}	
			else if(is_array($tRequest))
			{
				//validation
				$journalValidate = new JournalValidate();
				$status = $journalValidate->validate($tRequest);
				// echo "else";
				// print_r($status);
				if($status=="Success")
				{
					$journalPersistable=array();
					for($data=0;$data<count($tRequest[0]);$data++)
					{
						$journalPersistable[$data] = new JournalPersistable();
						$journalPersistable[$data]->setJfId($tRequest['jfId']);
						$journalPersistable[$data]->setEntryDate($tRequest['entryDate']);
						$journalPersistable[$data]->setCompanyId($tRequest['companyId']);
						
						$journalPersistable[$data]->setAmount($tRequest[0][$data]['amount']);
						$journalPersistable[$data]->setAmountType($tRequest[0][$data]['amountType']);
						$journalPersistable[$data]->setLedgerId($tRequest[0][$data]['ledgerId']);
					}
					return $journalPersistable;
				}
				else
				{
					return $status;
				}
			}
			else
			{
				return $tRequest;
			}
		}
	}
	public function createPersistableData(Request $request)
	{
		$this->request = $request;	
		
		//trim an input 
		$journalTransformer = new JournalTransformer();
		$tRequest = $journalTransformer->trimDateData($this->request);
		
		$journalPersistable = new JournalPersistable();
		$journalPersistable->setFromdate($tRequest['fromDate']);
		$journalPersistable->setTodate($tRequest['toDate']);
		
		return $journalPersistable;
	}
}