<?php
namespace ERP\Api\V1_0\Invoices\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class InvoiceTransformer
{
    /**
     * @param 
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		//data get from body
		$invoiceLabel = $request->input('invoice_label'); 
		$invoiceType = $request->input('invoice_type'); 
		$startAt = $request->input('start_at'); 
		$endAt = $request->input('end_at'); 
		$companyId = $request->input('company_id');  
		
		//trim an input
		$tInvoiceLabel = trim($invoiceLabel);
		$tInvoiceType = trim($invoiceType);
		$tStartAt = trim($startAt);
		$tEndAt = trim($endAt);
		$tCompanyId = trim($companyId);
		
		//make an array
		$data = array();
		$data['invoice_label'] = $tInvoiceLabel;
		$data['invoice_type'] = $tInvoiceType;
		$data['start_at'] = $tStartAt;
		$data['end_at'] = $tEndAt;
		$data['company_id'] = $tCompanyId;
		return $data;
	}
}