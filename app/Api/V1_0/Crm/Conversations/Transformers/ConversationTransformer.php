<?php
namespace ERP\Api\V1_0\Crm\Conversations\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ConversationTransformer
{
    /**
     * @param Request $request(object) and conversation-type 
	 * trim data
     * @return array
     */
    public function trimInsertData(Request $request,$conversationType)
    {
		$data = array();
		// data get from body and trim an input
		$data['email_id'] = trim($request->input('emailId'));
		$data['cc_email_id'] = trim($request->input('ccEmailId'));
		$data['bcc_email_id'] = trim($request->input('bccEmailId'));
		$data['subject'] = trim($request->input('subject'));
		$data['conversation'] = trim($request->input('conversation'));
		$data['company_id'] = trim($request->input('companyId'));
		$data['branch_id'] = trim($request->input('branchId'));
		$data['conversation_type'] = trim($conversationType);
		$data['contact_no'] = trim($request->input('contactNo'));
		$data['client_id'] = array();
		$countClientId = count($request->input()['client']);
		for($arrayData=0;$arrayData<$countClientId;$arrayData++)
		{
			$data['client_id'][$arrayData] = $request->input()['client'][$arrayData]['clientId'];
		}
		return $data;
	}
}