<?php
namespace ERP\Entities\Constants;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConstantClass 
{
    public function constantVariable()
	{
		$constantArray = array();
		$constantArray['documentUrl'] = "Storage/Document/";
		$constantArray['billDocumentUrl'] = "Storage/Bill/";
		$constantArray['clientUrl']="http://www.scerp1.com/clients";
		$constantArray['ledgerUrl']="http://www.scerp1.com/accounting/ledgers";
		$constantArray['journalUrl']="http://www.scerp1.com/accounting/journals";
		$constantArray['postMethod']="post";
		$constantArray['journalInward']="Inward";
		$constantArray['journalOutward']="Outward";
		
		return $constantArray;
	}
}
