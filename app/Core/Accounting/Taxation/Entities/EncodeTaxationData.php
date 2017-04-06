<?php
namespace ERP\Core\Accounting\Taxation\Entities;

use ERP\Core\Clients\Services\ClientService;
use ERP\Core\Companies\Services\CompanyService;
use Carbon;
use ERP\Core\Products\Services\ProductService;
/**
 *
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class EncodeTaxationData extends ProductService
{
	public function getEncodedAllData($status)
	{
		$decodedJson = json_decode($status,true);
		$companyService = new CompanyService();
		$data = array();
		for($decodedData=0;$decodedData<count($decodedJson);$decodedData++)
		{
			$calculateAdditionalTax=0;
			$decodedProductArrayData = json_decode($decodedJson[$decodedData]['product_array']);
			for($arrayData=0;$arrayData<count($decodedProductArrayData->inventory);$arrayData++)
			{
				$productService = new EncodeTaxationData();
				$productData = $productService->getProductData($decodedProductArrayData->inventory[$arrayData]->productId);
				$productDecodedData = json_decode($productData);
				$additionalTax = ($productDecodedData->purchasePrice/100)*$productDecodedData->additionalTax;
				$calculateAdditionalTax = $calculateAdditionalTax+$additionalTax;
			}
			$total[$decodedData] = $decodedJson[$decodedData]['total'];
			$tax[$decodedData] = $decodedJson[$decodedData]['tax'];
			$grandTotal[$decodedData] = $decodedJson[$decodedData]['grand_total'];
			$advance[$decodedData] = $decodedJson[$decodedData]['advance'];
			$balance[$decodedData] = $decodedJson[$decodedData]['balance'];
			$refund[$decodedData] = $decodedJson[$decodedData]['refund'];
			$entryDate[$decodedData] = $decodedJson[$decodedData]['entry_date'];
			$clientId[$decodedData] = $decodedJson[$decodedData]['client_id'];
			$companyId[$decodedData] = $decodedJson[$decodedData]['company_id'];
			
			$clientService = new ClientService();
			$clientData[$decodedData]  = $clientService->getClientData($clientId[$decodedData]);
			$decodedClientData[$decodedData] = json_decode($clientData[$decodedData]);
			
			// convert amount(round) into their company's selected decimal points
			$companyData[$decodedData] = $companyService->getCompanyData($companyId[$decodedData]);
			$companyDecodedData[$decodedData] = json_decode($companyData[$decodedData]);
				
			$total[$decodedData] = number_format($total[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			$tax[$decodedData] = number_format($tax[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			$grandTotal[$decodedData] = number_format($grandTotal[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			$advance[$decodedData] = number_format($advance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			$balance[$decodedData] = number_format($balance[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			$refund[$decodedData] = number_format($refund[$decodedData],$companyDecodedData[$decodedData]->noOfDecimalPoints,'.','');
			
			//date format conversion
			if(strcmp($entryDate[$decodedData],'0000-00-00 00:00:00')==0)
			{
				$convertedEntryDate[$decodedData] = "00-00-0000";
			}
			else
			{
				$convertedEntryDate[$decodedData] = Carbon\Carbon::createFromFormat('Y-m-d', $entryDate[$decodedData])->format('d-m-Y');
			}
			$data[$decodedData]= array(
				'invoiceNumber'=>$decodedJson[$decodedData]['invoice_number'],
				'salesType'=>$decodedJson[$decodedData]['sales_type'],
				'total'=>$total[$decodedData],
				'tax'=>$tax[$decodedData],
				'grandTotal'=>$grandTotal[$decodedData],
				'advance'=>$advance[$decodedData],
				'balance'=>$balance[$decodedData],
				'refund'=>$refund[$decodedData],
				'entryDate'=>$convertedEntryDate[$decodedData],
				'clientName'=>$decodedClientData[$decodedData]->clientName,
				'additionalTax'=>$calculateAdditionalTax
			);
		}
		$jsonEncodedData = json_encode($data);
		return $jsonEncodedData;
	}
}