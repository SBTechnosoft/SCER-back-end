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
		
		//validation
		$journalValidate = new JournalValidate();
		$status = $journalValidate->validate($tRequest);
		
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
}