<?php
namespace ERP\Core\Accounting\Journals\Services;

use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use ERP\Core\Accounting\Journals\Entities\Journal;
use ERP\Model\Accounting\Journals\JournalModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
// use ERP\Core\Accounting\Ledgers\Entities\EncodeData;
// use ERP\Core\Accounting\Ledgers\Entities\EncodeAllData;
use ERP\Exceptions\ExceptionMessage;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalService extends AbstractService
{
    /**
     * @var journalService
	 * $var journalModel
     */
    private $journalService;
    private $journalModel;
	
    /**
     * @param JournalService $journalService
     */
    public function initialize(JournalService $journalService)
    {		
		echo "init";
    }
	
    /**
     * @param JournalPersistable $persistable
     */
    public function create(JournalPersistable $persistable)
    {
		return "create method of JournalService";
		
    }
	
	 /**
     * get the data from persistable object and call the model for database insertion opertation
     * @param JournalPersistable $persistable
     * @return status
     */
	public function insert()
	{
		$journalArray = array();
		$getData = array();
		$keyName = array();
		$funcName = array();
		$journalArray = func_get_arg(0);
		for($data=0;$data<count($journalArray);$data++)
		{
			$funcName[$data] = $journalArray[$data][0]->getName();
			$getData[$data] = $journalArray[$data][0]->$funcName[$data]();
			$keyName[$data] = $journalArray[$data][0]->getkey();
		}
		//data pass to the model object for insert
		$journalModel = new JournalModel();
		$status = $journalModel->insertData($getData,$keyName);
		return $status;
	}
}