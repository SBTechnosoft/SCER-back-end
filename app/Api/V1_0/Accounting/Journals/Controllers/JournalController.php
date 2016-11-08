<?php
namespace ERP\Api\V1_0\Accounting\Journals\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Accounting\Journals\Services\JournalService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Accounting\Journals\Processors\JournalProcessor;
use ERP\Core\Accounting\Journals\Persistables\JournalPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Exceptions\ExceptionMessage;
use ERP\Model\Accounting\Journals\JournalModel;
use Illuminate\Support\Collection;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class JournalController extends BaseController implements ContainerInterface
{
	/**
     * @var journalService
     * @var processor
     * @var request
     * @var journalPersistable
     */
	private $journalService;
	private $processor;
	private $request;
	private $journalPersistable;	
	
	/**
	 * get and invoke method is of ContainerInterface method
	 */		
    public function get($id,$name)
	{
		// echo "get";
	}
	public function invoke(callable $method)
	{
		// echo "invoke";
	}
	
	/**
	 * insert the specified resource 
	 * @param  Request object[Request $request]
	 * method calls the processor for creating persistable object & setting the data
	*/
    public function store(Request $request)
    {
		echo "hi";
		$array1 = array();
		// print_r(array_get($array1,'journalData[0]'));
		// print_r($request->input('journalData[0]'));
		// print_r(Input::all());
		// print_r($.parseJSON('journalData'));
		// print_r(Response::json());
		// $request = array();
		// $request = new Request();
		// $journalData = $request->abc;
		// echo "hi";
		// print_r($this->request->input('json_decode'));
		// print_r($request);
		// print_r($journalData['journalData']);
		// print_r(Input::get('journalData'));
		print_r($request);
		// print_r($request->input('journalData'));
		// print_r($array1->value['journalData.name']);
		// print_r(Request::json()->get('journalData.name'));
		// print_r($request->input('journalData'));
		// print_r(json_decode($request->input('jounalData.name')));
		// print_r($request::get());
		// foreach($request->input() as $app) {
			// echo "a";
			// print_r($app);
			// print_r($app->first());
		// }
		// print_r($ac['journalData'][0]);
		// print_r($request->getContent('journalData'));
		
		// print_r($request->input('journalData.name'));
		// var_dump(json_decode($response)->results[0]->geometry->location->lat);
		// print_r(var_dump(json_decode($response->journalData[0])));
		

		// print_r($response->content());
		// print_r($request->input());
		// print_r(json_decode($request->input(), true));
		exit;
		$this->request = $request;
		// check the requested Http method
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		// insert
		if($requestMethod == 'POST')
		{
			$processor = new JournalProcessor();
			// $journalPersistable = new JournalPersistable();
			
			$journalPersistable = $processor->createPersistable($this->request);
			// if($journalPersistable[0][0]=='[')
			// {
				// return $journalPersistable;
			// }
			// else
			// {
				// $jornalService= new JournalService();
				// $status = $journalService->insert($journalPersistable);
				// return $status;
			// }
		}
	}
	/**
     * get the specified resource.
     * @param  int  $ledgerId
     */
    public function getData($ledgerId=null)
    {
		if($ledgerId==null)
		{	
			$ledgerService= new LedgerService();
			$status = $ledgerService->getAllLedgerData();
			
			return $status;
		}
		else
		{	
			$ledgerService= new LedgerService();
			$status = $ledgerService->getLedgerData($ledgerId);
			return $status;
		}        
    }
	
}
