<?php
namespace ERP\Core\Entities;

use ERP\Core\Accounting\LedgerGrps\Services\LedgerGrpService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class LedgerGrpDetail extends LedgerGrpService 
{
	public function getLedgerGrpDetails($ledgerGrpId)
	{
		//get the ledger grp data from database
		$encodeLedgerGrpDataClass = new LedgerGrpDetail();
		$ledgerGrpStatus = $encodeLedgerGrpDataClass->getLedgerGrpData($ledgerGrpId);
		$ledgerGrpDecodedJson = json_decode($ledgerGrpStatus,true);
		return $ledgerGrpDecodedJson;
	}
    
}