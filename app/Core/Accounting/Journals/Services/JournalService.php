<?php
namespace ERP\Core\Accounting\Journals\Services;

use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use ERP\Model\Accounting\Journals\JournalModel;
use ERP\Core\Shared\Options\UpdateOptions;
// use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Journals\Entities\EncodeAllData;

/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalService 
{
    /**
     * @var templateService
	 * $var templateModel
     */
    private $templateService;
    // private $templateModel;
	
    /**
     * @param TemplateService $templateService
     */
    public function initialize(JournalService $templateService)
    {		
		echo "init";
    }
	
    /**
     * @param TemplatePersistable $persistable
     */
    public function create(JournalPersistable $persistable)
    {
		return "create method of TemplateService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param JournalPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$journalArray = array();
		$amountArray = array();
		$amountTypeArray = array();
		$ledgerIdArray = array();
		$jfIdArray = array();
		$entryDateArray = array();
		$companyIdArray = array();
		$journalArray = func_get_arg(0);
		
		for($data=0;$data<count($journalArray);$data++)
		{
			$amountArray[$data] = $journalArray[$data]->getAmount();
			$amountTypeArray[$data] = $journalArray[$data]->getAmountType();
			$jfIdArray[$data] = $journalArray[$data]->getJfId();
			$ledgerIdArray[$data] = $journalArray[$data]->getLedgerId();
			$entryDateArray[$data] = $journalArray[$data]->getEntryDate();
			$companyIdArray[$data] = $journalArray[$data]->getCompanyId();
		}
		// data pass to the model object for insert
		$journalModel = new JournalModel();
		$status = $journalModel->insertData($amountArray,$amountTypeArray,$jfIdArray,$ledgerIdArray,$entryDateArray,$companyIdArray);
		return $status;
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getJournalData()
	{
		$journalModel = new JournalModel();
		$status = $journalModel->getJournalData();
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($status,$exceptionArray['404'])==0)
		{
			return $status;
		}
		else
		{
			return $status;
		}
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getJournalDetail()
	{
		$processArray = array();
		$processArray = func_get_arg(0);
		$fromDate = $processArray->getFromDate();
		$toDate = $processArray->getToDate();
		
		$journalModel = new JournalModel();
		$status = $journalModel->getData($fromDate,$toDate);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($status,$exceptionArray['404'])==0)
		{
			return $status;
		}
		else
		{
			$encoded = new EncodeAllData();
			$encodeAllData = $encoded->getEncodedAllData($status);
			return $encodeAllData;
		}
	}
}