<?php	
namespace ERP\Core\Accounting\Taxation\Services;
use ERP\Model\Accounting\Taxation\TaxationModel;
use ERP\Core\Shared\Options\UpdateOptions;
use ERP\Core\Support\Service\AbstractService;
use ERP\Core\User\Entities\User;
use ERP\Core\Accounting\Taxation\Entities\EncodeTaxationData;
use ERP\Exceptions\ExceptionMessage;
use ERP\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class TaxationService extends AbstractService
{
    /**
     * @var taxationService
	 * $var taxationModel
     */
    private $taxationService;
    private $taxationModel;
	
    /**
     * @param TaxationService $taxationModel
     */
    public function initialize(TaxationService $taxationModel)
    {		
		echo "init";
    }
	
    /**
     * @param TaxationPersistable $persistable
     */
    public function create(TaxationPersistable $persistable)
    {
		return "create method of TaxationService";
		
    }
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getSaleTaxData(Request $request,$companyId)
	{
		//get data
		$saleTaxModel = new TaxationModel();
		$saleTaxData = $saleTaxModel->getSaleTaxData($companyId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($saleTaxData,$exceptionArray['204'])==0)
		{
			return $saleTaxData;
		}
		else
		{
			$encoded = new EncodeTaxationData();
			$encodeAllData = $encoded->getEncodedAllData($saleTaxData,$request->header());
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getPurchaseTaxData(Request $request,$companyId)
	{
		//get data
		$purchaseTaxModel = new TaxationModel();
		$purchaseTaxData = $purchaseTaxModel->getPurchaseTaxData($companyId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($purchaseTaxData,$exceptionArray['204'])==0)
		{
			return $purchaseTaxData;
		}
		else
		{
			$encoded = new EncodeTaxationData();
			$encodeAllData = $encoded->getPurchaseTaxEncodedAllData($purchaseTaxData,$request->header());
			return $encodeAllData;
		}
	}
	
	/**
     * get all the data and call the model for database selection opertation
     * @return status
     */
	public function getPurchaseData(Request $request,$companyId)
	{
		//get data
		$purchaseTaxModel = new TaxationModel();
		$purchaseTaxData = $purchaseTaxModel->getPurchaseData($companyId);
		
		//get exception message
		$exception = new ExceptionMessage();
		$exceptionArray = $exception->messageArrays();
		if(strcmp($purchaseTaxData,$exceptionArray['204'])==0)
		{
			return $purchaseTaxData;
		}
		else
		{
			$encoded = new EncodeTaxationData();
			$encodeAllData = $encoded->getPurchaseEncodedAllData($purchaseTaxData,$request->header());
			return $encodeAllData;
		}
	}
	
	/**
     * get and invoke method is of Container Interface method
     * @param int $id,$name
     */
    public function get($id,$name)
    {
		echo "get";		
    }   
	public function invoke(callable $method)
	{
		echo "invoke";
	}
}