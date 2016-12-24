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
		$constantArray['billDocumentUrl'] = "Storage/Bill/Document";
		$constantArray['billUrl']="Storage/Bill/";
		$constantArray['clientUrl']="http://www.scerp1.com/clients";
		$constantArray['ledgerUrl']="http://www.scerp1.com/accounting/ledgers";
		$constantArray['journalUrl']="http://www.scerp1.com/accounting/journals";
		$constantArray['productUrl']="http://www.scerp1.com/accounting/products";
		$constantArray['postMethod']="post";
		$constantArray['getMethod']="get";
		$constantArray['journalInward']="Inward";
		$constantArray['journalOutward']="Outward";
		
		return $constantArray;
	}
}
