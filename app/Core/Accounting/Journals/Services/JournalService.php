<?php
namespace ERP\Core\Accounting\Journals\Services;

use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use ERP\Model\Accounting\Journals\JournalModel;
use ERP\Core\Shared\Options\UpdateOptions;
// use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Exceptions\ExceptionMessage;
use ERP\Core\Accounting\Journals\Entities\EncodeAllData;
use ERP\Core\Accounting\Journals\Entities\EncodeData;

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
	public function getJournalArrayData($journalId)
	{
		$encodedArrayData = array();
		$journalModel = new JournalModel();
		$status = $journalModel->getJournalArrayData($journalId);
		
		// get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($status,$exceptionArray['404'])==0)
		{
			return $status;
		}
		else
		{
			for($encodeArray=0;$encodeArray<count(json_decode($status));$encodeArray++)
			{
				
				$encodedData = new EncodeData();
				$encodedResult = $encodedData->getEncodedData(json_decode($status)[$encodeArray]);
				$encodedArrayData[$encodeArray] = json_decode($encodedResult);
			}
			return json_encode($encodedArrayData);
		}
	}
	
	/**
     * get all the data between given date and call the model for database selection opertation
     * @return status
     */
	public function getJournalDetail()
	{
		$processArray = array();
		$processArray = func_get_arg(0);
		$companyId = func_get_arg(1);
		$fromDate = $processArray->getFromDate();
		$toDate = $processArray->getToDate();
		
		$journalModel = new JournalModel();
		$status = $journalModel->getData($fromDate,$toDate,$companyId);
		
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
	
	 /**
     * get the data from persistable object and call the model for database update opertation
     * @param JournalPersistable $persistable
     * @param updateOptions $options [optional]
	 * parameter is in array form.
     * @return status
     */
    public function update()
    {
		$journalArray = array();
		$getData = array();
		$funcName = array();
		$journalArray = func_get_arg(0);
		$multipleArray = array();
		$singleData = array();
		$arrayFlag=0;
		$flagData=0;
		$jfId = func_get_arg(1);

		for($keyData=0;$keyData<count(array_keys($journalArray));$keyData++)
		{
			if(strcmp(array_keys($journalArray)[$keyData],"flag")==0)
			{
				$flagData=1;
			}
		}
		if($flagData==1)
		{
			// only array exists
			for($persistableArray=0;$persistableArray<count($journalArray)-1;$persistableArray++)
			{
				$multipleArray[$persistableArray] = array();
				$multipleArray[$persistableArray]['amount']=$journalArray[$persistableArray][0]->getAmount();
				$multipleArray[$persistableArray]['amount_type']=$journalArray[$persistableArray][0]->getAmountType();
				$multipleArray[$persistableArray]['ledger_id']=$journalArray[$persistableArray][0]->getLedgerId();
			}
		}
		else
		{
			for($persistableArray=0;$persistableArray<count($journalArray);$persistableArray++)
			{
				// if array is available
				if(is_array($journalArray[$persistableArray][0]))
				{
					for($innerData=0;$innerData<count($journalArray[$persistableArray]);$innerData++)
					{
						$multipleArray[$innerData] = array();
						$multipleArray[$innerData]['amount']=$journalArray[$persistableArray][$innerData][0]->getAmount();
						$multipleArray[$innerData]['amount_type']=$journalArray[$persistableArray][$innerData][0]->getAmountType();
						$multipleArray[$innerData]['ledger_id']=$journalArray[$persistableArray][$innerData][0]->getLedgerId();
					}
				}
				else
				{
					$funcName = $journalArray[$persistableArray][0]->getName();
					$value = $journalArray[$persistableArray][0]->$funcName();
					$key = $journalArray[$persistableArray][0]->getKey();
					$singleData[$key] = $value;
				}
			}
		}
		if(count($multipleArray)!=0 && count($singleData)!=0)
		{
			$journalModel = new JournalModel();
			$status = $journalModel->updateArrayData($multipleArray,$singleData,$jfId);
			return $status;
		}
		else if(count($multipleArray)!=0)
		{
			$journalModel = new JournalModel();
			$status = $journalModel->updateData($multipleArray,$jfId);
			return $status;
		}
		else
		{
			$journalModel = new JournalModel();
			$status = $journalModel->updateData($singleData,$jfId);
			return $status;
		}
	}
}