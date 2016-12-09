<?php
namespace ERP\Core\Accounting\Ledgers\Entities;

use ERP\Core\Accounting\Ledgers\Entities\Ledger;
use ERP\Core\Accounting\Ledgers\Services\LedgerService;
use Carbon;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeTrnAllData extends LedgerService
{
	public function getEncodedAllData($status,$ledgerId)
	{
		$convertedCreatedDate =  array();
		$convertedUpdatedDate =  array();
		$encodeAllData =  array();
		$decodedJson = json_decode($status,true);
		$ledger = new Ledger();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$createdAt[$decodedData] = $decodedJson[$decodedData]['created_at'];
			$updatedAt[$decodedData] = $decodedJson[$decodedData]['updated_at'];
			$id[$decodedData] = $decodedJson[$decodedData][$ledgerId.'_id'];
			$amount[$decodedData] = $decodedJson[$decodedData]['amount'];
			$amountType[$decodedData] = $decodedJson[$decodedData]['amount_type'];
			$entryDate[$decodedData] = $decodedJson[$decodedData]['entry_date'];
			$jfId[$decodedData] = $decodedJson[$decodedData]['jf_id'];
			$ledgersId[$decodedData] = $decodedJson[$decodedData]['ledger_id'];
			
			//get the ledger detail from database
			$encodeDataClass = new EncodeTrnAllData();
			$ledgerStatus[$decodedData] = $encodeDataClass->getLedgerData($ledgersId[$decodedData]);
			$ledgerDecodedJson[$decodedData] = json_decode($ledgerStatus[$decodedData],true);
			
			//date format conversion
			$convertedCreatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt[$decodedData])->format('d-m-Y');
			$convertedUpdatedDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt[$decodedData])->format('d-m-Y');
			$convertedEntryDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');
			
			$ledger->setCreated_at($convertedCreatedDate[$decodedData]);
			$getCreatedDate[$decodedData] = $ledger->getCreated_at();
			$ledger->setUpdated_at($convertedUpdatedDate[$decodedData]);
			$getUpdatedDate[$decodedData] = $ledger->getUpdated_at();
			$ledger->setEntryDate($convertedEntryDate[$decodedData]);
			$getEntryDate[$decodedData] = $ledger->getEntryDate();
		}
		$data = array();
		for($jsonData=0;$jsonData<count($decodedJson);$jsonData++)
		{
			$data[$jsonData]= array(
				'Id'=>$id[$jsonData],
				'amount' => $amount[$jsonData],
				'amountType' => $amountType[$jsonData],
				'entryDate' => $getEntryDate[$jsonData],
				'jfId' => $jfId[$jsonData],
				'createdAt' => $getCreatedDate[$jsonData],
				'updatedAt' => $getUpdatedDate[$jsonData],
				'ledger' => array(
					'ledgerId' => $ledgerDecodedJson[$jsonData]['ledgerId'],
					'ledgerName' => $ledgerDecodedJson[$jsonData]['ledgerName'],
					'alias' => $ledgerDecodedJson[$jsonData]['alias'],
					'inventoryAffected' => $ledgerDecodedJson[$jsonData]['inventoryAffected'],
					'address1' => $ledgerDecodedJson[$jsonData]['address1'],
					'address2' => $ledgerDecodedJson[$jsonData]['address2'],
					'contactNo' => $ledgerDecodedJson[$jsonData]['contactNo'],
					'emailId' => $ledgerDecodedJson[$jsonData]['emailId'],
					'pan' => $ledgerDecodedJson[$jsonData]['pan'],
					'tin' => $ledgerDecodedJson[$jsonData]['tin'],
					'gstNo' => $ledgerDecodedJson[$jsonData]['gstNo'],
					'createdAt' => $ledgerDecodedJson[$jsonData]['createdAt'],
					'updatedAt' => $ledgerDecodedJson[$jsonData]['updatedAt'],
					'ledgerGroupId' => $ledgerDecodedJson[$jsonData]['ledgerGroup']['ledgerGroupId'],
					'stateAbb' => $ledgerDecodedJson[$jsonData]['state']['stateAbb'],
					'cityId' => $ledgerDecodedJson[$jsonData]['city']['cityId'],
					'companyId' => $ledgerDecodedJson[$jsonData]['company']['companyId']
				)
			);
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}