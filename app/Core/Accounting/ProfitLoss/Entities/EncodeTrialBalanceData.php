<?php
namespace ERP\Core\Accounting\TrialBalance\Entities;

use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use ERP\Core\Companies\Services\CompanyService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeTrialBalanceData extends LedgerService
{
	public function getEncodedAllData($status)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedLedgerData = array();
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$ledgerId[$decodedData] = $decodedJson[$decodedData]['ledger_id'];
			$amount[$decodedData] = $decodedJson[$decodedData]['amount'];
			$amountType[$decodedData] = $decodedJson[$decodedData]['amount_type'];
			$trialBalanceId[$decodedData] = $decodedJson[$decodedData]['trial_balance_id'];
			$trialBalanceData = new EncodeTrialBalanceData();
			$ledgerData[$decodedData]  = $trialBalanceData->getLedgerData($ledgerId[$decodedData]);
			$decodedLedgerData[$decodedData] = json_decode($ledgerData[$decodedData]);
			
			// convert amount(round) into their company's selected decimal points
			$companyData[$decodedData] = $companyService->getCompanyData($decodedLedgerData[$decodedData]->company->companyId);
			$companyDecodedData[$decodedData] = json_decode($companyData[$decodedData]);
				
			$amount[$decodedData] = round($amount[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints);
		}
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'trialBalanceId'=>$trialBalanceId[$jsonData],
				'amount'=>$amount[$jsonData],
				'amountType' => $amountType[$jsonData],
				'ledger' => array(	
					'ledgerId' => $decodedLedgerData[$jsonData]->ledgerId,
					'ledgerName' => $decodedLedgerData[$jsonData]->ledgerName,
					'alias' => $decodedLedgerData[$jsonData]->alias,
					'inventoryAffected' => $decodedLedgerData[$jsonData]->inventoryAffected,
					'address1' => $decodedLedgerData[$jsonData]->address1,
					'address2' => $decodedLedgerData[$jsonData]->address2,
					'contactNo' => $decodedLedgerData[$jsonData]->contactNo,
					'emailId' => $decodedLedgerData[$jsonData]->emailId,
					'pan' => $decodedLedgerData[$jsonData]->pan,
					'tin' => $decodedLedgerData[$jsonData]->tin,
					'gstNo' => $decodedLedgerData[$jsonData]->gstNo,
					'createdAt' => $decodedLedgerData[$jsonData]->createdAt,
					'updatedAt' => $decodedLedgerData[$jsonData]->updatedAt,
					'openingBalance' => $decodedLedgerData[$jsonData]->openingBalance,
					'openingBalanceType' => $decodedLedgerData[$jsonData]->openingBalanceType,
					'currentBalance' => $decodedLedgerData[$jsonData]->currentBalance,
					'currentBalanceType' => $decodedLedgerData[$jsonData]->currentBalanceType,
					'ledgerGroupId' => $decodedLedgerData[$jsonData]->ledgerGroup->ledgerGroupId,
					'stateAbb' => $decodedLedgerData[$jsonData]->state->stateAbb,
					'cityId' => $decodedLedgerData[$jsonData]->city->cityId,
					'companyId' => $decodedLedgerData[$jsonData]->company->companyId
				)		
			);	
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}