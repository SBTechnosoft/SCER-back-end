<?php
namespace ERP\Api\V1_0\Settings\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use ERP\Core\Settings\Services\SettingService;
use ERP\Http\Requests;
use ERP\Api\V1_0\Support\BaseController;
use ERP\Api\V1_0\Settings\Processors\SettingProcessor;
use ERP\Core\Settings\Persistables\SettingPersistable;
use ERP\Core\Support\Service\ContainerInterface;
use ERP\Entities\AuthenticationClass\TokenAuthentication;
use ERP\Entities\Constants\ConstantClass;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\ParameterBag;
use DB;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class SettingController extends BaseController implements ContainerInterface
{
	/**
     * @var settingService
     * @var processor
     * @var request
     * @var settingPersistable
     */
	private $settingService;
	private $processor;
	private $request;
	private $settingPersistable;	
	
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
		echo "enter";
		
		exit;
		// Authentication
		$tokenAuthentication = new TokenAuthentication();
		$authenticationResult = $tokenAuthentication->authenticate($request->header());
		
		// get constant array
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		if(strcmp($constantArray['success'],$authenticationResult)==0)
		{
			$this->request = $request;
			// check the requested Http method
			$requestMethod = $_SERVER['REQUEST_METHOD'];
			// insert
			if($requestMethod == 'POST')
			{
				$processor = new SettingProcessor();
				$settingPersistable = new SettingPersistable();		
				$settingService= new SettingService();			
				$settingPersistable = $processor->createPersistable($this->request);
				if($settingPersistable[0][0]=='[')
				{
					return $settingPersistable;
				}
				else if(is_array($settingPersistable))
				{
					$status = $settingService->insert($settingPersistable);
					return $status;
				}
				else
				{
					return $settingPersistable;
				}
			}
		}
		else
		{
			return $authenticationResult;
		}
	}
	
	public function update(Request $request)
    {
		// echo "jj";
		DB::beginTransaction();
		$raw = DB::statement("CURSOR cursor_name IS select product_trn_id from product_trn");
		DB::commit();
		// echo "hh";
		print_r($raw);
		echo "jj";
		// print_r($request->getPost());
		exit;
		
		// print_r($request->file());
		$raw_data = file_get_contents('php://input');
		   $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

		   //./..... My edit --------- /
			if(empty($boundary)){
				parse_str($raw_data,$data);
				return $data;
			}
		   /// ........... My edit ends ......... /
			// Fetch each part
			$parts = array_slice(explode($boundary, $raw_data), 1);
			
			$data = array();
			
			
		foreach ($parts as $part) {
			// If this is the last part, break
			if ($part == "--\r\n") break; 

			// Separate content from headers
			$part = ltrim($part, "\r\n");
			list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

			// Parse the headers list
			$raw_headers = explode("\r\n", $raw_headers);
			$headers = array();
			foreach ($raw_headers as $header) {
				list($name, $value) = explode(':', $header);
				$headers[strtolower($name)] = ltrim($value, ' '); 
			} 

			// Parse the Content-Disposition to get the field name, etc.
			if (isset($headers['content-disposition'])) {
				$filename = null;
				preg_match(
					'/^(.+); name="([^"]+)"(; filename="([^"]+)")?/', 
					$headers['content-disposition'], 
					$matches
				);
				list(, $type, $name) = $matches;
				isset($matches[4]) and $filename = $matches[4]; 

				// handle your fields here
				switch ($name) {
					// this is a file upload
					case 'userfile':
						 file_put_contents($filename, $body);
						 break;

					// default for all other files is to populate $data
					default: 
						 $data[$name] = substr($body, 0, strlen($body) - 2);
						 break;
				} 
			}

		}
		
		print_r($data);
		
		
		
		
		
		
		// $jsonIterator = new RecursiveIteratorIterator(
		// new RecursiveArrayIterator(json_decode($request->input(), TRUE)),
		// RecursiveIteratorIterator::SELF_FIRST);
		// $abc = $request->input();
		
		// print_r($request->body);
		// echo "First output = ";
		// print_r($request->input());
		// print_r($request->body());
		// $jsonData = $request->input();
		// print_r($jsonData);
		// $raw_data = file_get_contents('php://input');
		// echo "Second output = ";
		// print_r($raw_data);
		// $_POST = json_decode(file_get_contents('php://input'), true);
		$data = file_get_contents("php://input",false,null,-1);
		// $data = json_decode($data,true);
		// print_r($data);
		// $queryString = urlencode($data);
		// $data1 = array();
		// parse_str($queryString, $data1);
		// var_dump($data1);
		// dd($request->all());
		// print_r(Input::get());
		// print_r(parse_str(file_get_contents('php://input'), $request->input()))
		
		// $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
		// print_r($boundary);
		// print_r(file_get_contents("php://input"),$request->input());
		// print_r($request->all());
		// print_r(Input::get());
		// print_r(Input::all());
		
		//----------------------------------------------------------------
		
		// $parserSelector = new ParserSelector();
		// $parser = $parserSelector->getParserForContentType($contentType);
		// $multipart = $parser->parse($content);
	}
	
}
