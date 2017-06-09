<?php
namespace ERP\Api\V1_0\Products\Transformers;

use Illuminate\Http\Request;
use ERP\Http\Requests;
use ERP\Exceptions\ExceptionMessage;
use Carbon;
use ERP\Core\Products\Entities\EnumClasses\DiscountTypeEnum;
use ERP\Entities\EnumClasses\IsDisplayEnum;
use ERP\Core\Products\Entities\EnumClasses\measurementUnitEnum;
use ERP\Entities\Constants\ConstantClass;
use stdClass;
use ERP\Model\ProductCategories\ProductCategoryModel;
use ERP\Model\ProductGroups\ProductGroupModel;
use ERP\Model\Branches\BranchModel;
use ERP\Model\Companies\CompanyModel;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class ProductTransformer extends ExceptionMessage
{
    /**
     * @param Request $request
     * @return array
     */
    public function trimInsertData(Request $request)
    {
		$isDisplayFlag=0;
		$measurementUnitFlag=0;
		
		//data get from body
		$productName = $request->input('productName'); 
		$measurementUnit = $request->input('measurementUnit'); 
		$color = $request->input('color'); 
		$size = $request->input('size'); 
		$isDisplay = $request->input('isDisplay'); 			
		$purchasePrice = $request->input('purchasePrice'); 			
		$wholeSaleMargin = $request->input('wholesaleMargin'); 			
		$wholeSaleMarginFlat = $request->input('wholesaleMarginFlat'); 			
		$semiWholeSaleMargin = $request->input('semiWholesaleMargin'); 			
		$vat = $request->input('vat'); 			
		$mrp = $request->input('mrp'); 			
		$margin = $request->input('margin'); 			
		$marginFlat = $request->input('marginFlat'); 			
		$productDescription = $request->input('productDescription'); 			
		$additionalTax = $request->input('additionalTax'); 			
		$minimumStockLevel = $request->input('minimumStockLevel'); 			
		$companyId = $request->input('companyId'); 			
		$productCatId = $request->input('productCategoryId'); 			
		$productGrpId = $request->input('productGroupId'); 			
		$branchId = $request->input('branchId'); 	 
		
		//trim an input
		$tProductName = trim($productName);
		$tMeasUnit = trim($measurementUnit);
		$tColor = trim($color);
		$tSize = trim($size);
		$tIsDisplay = trim($isDisplay);
		$tPurchasePrice = trim($purchasePrice);
		$tWholeSaleMargin = trim($wholeSaleMargin);
		$tWholeSaleMarginFlat = trim($wholeSaleMarginFlat);
		$tSemiWholeSaleMargin = trim($semiWholeSaleMargin);
		$tVat = trim($vat);
		$tMrp = trim($mrp);
		$tMargin = trim($margin);
		$tMarginFlat = trim($marginFlat);
		$tProductDescription = trim($productDescription);
		$tAdditionalTax = trim($additionalTax);
		$tMinimumStockLevel = trim($minimumStockLevel);
		$tCompanyId = trim($companyId);
		$tProductCatId = trim($productCatId);
		$tProductGrpId = trim($productGrpId);
		$tBranchId = trim($branchId);
		
		$enumIsDispArray = array();
		$isDispEnum = new IsDisplayEnum();
		$enumIsDispArray = $isDispEnum->enumArrays();
		if($tIsDisplay=="")
		{
			$tIsDisplay=$enumIsDispArray['display'];
		}
		else
		{
			foreach ($enumIsDispArray as $key => $value)
			{
				if(strcmp($value,$tIsDisplay)==0)
				{
					$isDisplayFlag=1;
					break;
				}
				else
				{
					$isDisplayFlag=2;
				}
			}
		}
		
		$enumMeasurementUnitArray = array();
		$measurementUnitEnum = new measurementUnitEnum();
		$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
		if($tMeasUnit!="")
		{
			foreach ($enumMeasurementUnitArray as $key => $value)
			{
				if(strcmp($value,$tMeasUnit)==0)
				{
					$measurementUnitFlag=1;
					break;
				}
				else
				{
					$measurementUnitFlag=2;
				}
			}
		}
		if($isDisplayFlag==2 || $measurementUnitFlag==2)
		{
			return "1";
		}
		else
		{
			//make an array
			$data = array();
			$data['product_name'] = $tProductName;
			$data['measurement_unit'] = $tMeasUnit;
			$data['color'] = $tColor;
			$data['size'] = $tSize;
			$data['is_display'] = $tIsDisplay;
			$data['purchase_price'] = $tPurchasePrice;
			$data['wholesale_margin'] = $tWholeSaleMargin;
			$data['wholesale_margin_flat'] = $tWholeSaleMarginFlat;
			$data['vat'] = $tVat;
			$data['mrp'] = $tMrp;
			$data['margin'] = $tMargin;
			$data['margin_flat'] = $tMarginFlat;
			$data['product_description'] = $tProductDescription;
			$data['additional_tax'] = $tAdditionalTax;
			$data['minimum_stock_level'] = $tMinimumStockLevel;
			$data['semi_wholesale_margin'] = $tSemiWholeSaleMargin;
			$data['company_id'] = $tCompanyId;
			$data['product_category_id'] = $tProductCatId;
			$data['product_group_id'] = $tProductGrpId;
			$data['branch_id'] = $tBranchId;
			return $data;
		}
	}
	
	/**
     * @param Request $request
     * @return array
     */
    public function trimInsertBatchData(Request $request)
    {
		$transformerClass = new ProductTransformer();
		$exceptionArray = $transformerClass->messageArrays();
		
		//data mapping
		$mappingResult = $this->mappingData($request->input());
		
		if(is_array($mappingResult))
		{
			$data = array();
			$errorArray = array();
			$inputRequestData = $mappingResult;
			$errorIndex = 0;
			$dataIndex = 0;
			for($arrayData=0;$arrayData<count($inputRequestData);$arrayData++)
			{
				$tIsDisplay='';
				$isDisplayFlag=0;
				$measurementUnitFlag=0;
				//trim an input
				$tProductName = trim($inputRequestData[$arrayData]['productName']);
				$tMeasUnit = trim($inputRequestData[$arrayData]['measurementUnit']);
				$tColor = trim($inputRequestData[$arrayData]['color']);
				$tSize = trim($inputRequestData[$arrayData]['size']);
				// $tIsDisplay = trim($inputRequestData[$arrayData]['isDisplay']);
				$tPurchasePrice = trim($inputRequestData[$arrayData]['purchasePrice']);
				$tWholeSaleMargin = trim($inputRequestData[$arrayData]['wholesaleMargin']);
				$tWholeSaleMarginFlat = trim($inputRequestData[$arrayData]['wholesaleMarginFlat']);
				$tSemiWholeSaleMargin = trim($inputRequestData[$arrayData]['semiWholesaleMargin']);
				$tVat = trim($inputRequestData[$arrayData]['vat']);
				$tMrp = trim($inputRequestData[$arrayData]['mrp']);
				$tMargin = trim($inputRequestData[$arrayData]['margin']);
				$tMarginFlat = trim($inputRequestData[$arrayData]['marginFlat']);
				$tProductDescription = trim($inputRequestData[$arrayData]['productDescription']);
				$tAdditionalTax = trim($inputRequestData[$arrayData]['additionalTax']);
				$tMinimumStockLevel = trim($inputRequestData[$arrayData]['minimumStockLevel']);
				$tCompanyId = trim($inputRequestData[$arrayData]['companyId']);
				$tProductCatId = trim($inputRequestData[$arrayData]['productCategoryId']);
				$tProductGrpId = trim($inputRequestData[$arrayData]['productGroupId']);
				$tBranchId = trim($inputRequestData[$arrayData]['branchId']);
				
				$tProductName = preg_replace('/[^a-zA-Z0-9 &,\/_`#().\'-]/', '',$tProductName);
				
				$enumIsDispArray = array();
				$isDispEnum = new IsDisplayEnum();
				$enumIsDispArray = $isDispEnum->enumArrays();
				if($tIsDisplay=="")
				{
					$tIsDisplay=$enumIsDispArray['display'];
				}
				else
				{
					foreach ($enumIsDispArray as $key => $value)
					{
						if(strcmp($value,$tIsDisplay)==0)
						{
							$isDisplayFlag=1;
							break;
						}
						else
						{
							$isDisplayFlag=2;
						}
					}
				}
				
				$enumMeasurementUnitArray = array();
				$measurementUnitEnum = new measurementUnitEnum();
				$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
				if($tMeasUnit!="")
				{
					foreach ($enumMeasurementUnitArray as $key => $value)
					{
						if(strcmp($value,$tMeasUnit)==0)
						{
							$measurementUnitFlag=1;
							break;
						}
						else
						{
							$measurementUnitFlag=2;
						}
					}
				}
				if($isDisplayFlag==2 || $measurementUnitFlag==2)
				{
					$errorArray[$errorIndex] = array();
					$errorArray[$errorIndex]['productName'] = $tProductName;
					$errorArray[$errorIndex]['measurementUnit'] = $tMeasUnit;
					$errorArray[$errorIndex]['color'] = $tColor;
					$errorArray[$errorIndex]['size'] = $tSize;
					$errorArray[$errorIndex]['isDisplay'] = $tIsDisplay;
					$errorArray[$errorIndex]['purchasePrice'] = $tPurchasePrice;
					$errorArray[$errorIndex]['wholesaleMargin'] = $tWholeSaleMargin;
					$errorArray[$errorIndex]['wholesaleMarginFlat'] = $tWholeSaleMarginFlat;
					$errorArray[$errorIndex]['semiWholesaleMargin'] = $tSemiWholeSaleMargin;
					$errorArray[$errorIndex]['vat'] = $tVat;
					$errorArray[$errorIndex]['mrp'] = $tMrp;
					$errorArray[$errorIndex]['margin'] = $tMargin;
					$errorArray[$errorIndex]['marginFlat'] = $tMarginFlat;
					$errorArray[$errorIndex]['productDescription'] = $tProductDescription;
					$errorArray[$errorIndex]['additionalTax'] = $tAdditionalTax;
					$errorArray[$errorIndex]['minimumStockLevel'] = $tMinimumStockLevel;
					$errorArray[$errorIndex]['companyId'] = $tCompanyId;
					$errorArray[$errorIndex]['productCategoryId'] = $tProductCatId;
					$errorArray[$errorIndex]['productGroupId'] = $tProductGrpId;
					$errorArray[$errorIndex]['branchId'] = $tBranchId;
					if($isDisplayFlag==2)
					{
						$errorArray[$errorIndex]['remark'] = $exceptionArray['isDisplayEnum'];
					}		
					else
					{
						$errorArray[$errorIndex]['remark'] = $exceptionArray['measurementUnitEnum'];
					}
					$errorIndex++;
				}
				else
				{
					//make an array
					$data[$dataIndex] = array();
					$data[$dataIndex]['product_name'] = $tProductName;
					$data[$dataIndex]['measurement_unit'] = $tMeasUnit;
					$data[$dataIndex]['color'] = $tColor;
					$data[$dataIndex]['size'] = $tSize;
					$data[$dataIndex]['is_display'] = $tIsDisplay;
					$data[$dataIndex]['purchase_price'] = $tPurchasePrice;
					$data[$dataIndex]['wholesale_margin'] = $tWholeSaleMargin;
					$data[$dataIndex]['wholesale_margin_flat'] = $tWholeSaleMarginFlat;
					$data[$dataIndex]['vat'] = $tVat;
					$data[$dataIndex]['mrp'] = $tMrp;
					$data[$dataIndex]['margin'] = $tMargin;
					$data[$dataIndex]['margin_flat'] = $tMarginFlat;
					$data[$dataIndex]['product_description'] = $tProductDescription;
					$data[$dataIndex]['additional_tax'] = $tAdditionalTax;
					$data[$dataIndex]['minimum_stock_level'] = $tMinimumStockLevel;
					$data[$dataIndex]['semi_wholesale_margin'] = $tSemiWholeSaleMargin;
					$data[$dataIndex]['company_id'] = $tCompanyId;
					$data[$dataIndex]['product_category_id'] = $tProductCatId;
					$data[$dataIndex]['product_group_id'] = $tProductGrpId;
					$data[$dataIndex]['branch_id'] = $tBranchId;
					$dataIndex++;
				}
			}
			$trimArray = array();
			$trimArray['errorArray']= $errorArray;
			$trimArray['dataArray'] = $data;
			return $trimArray;
		}
		else
		{
			return $mappingResult;
		}
	}
	
	/**
     * @param request array
     * @return array/error-message
     */
	public function mappingData()
	{
		$transformerClass = new ProductTransformer();
		$exceptionArray = $transformerClass->messageArrays();
		
		$rquestArray = func_get_arg(0);
		$mappingArray = $rquestArray['mapping'];
		$dataArray = $rquestArray['data'];
		
		$keyNameCount = array_count_values($mappingArray);
		//searching data in mapping array ..it is duplicate or not?
		for($index=0;$index<count($keyNameCount);$index++)
		{
			$value = $keyNameCount[array_keys($keyNameCount)[$index]];
			if($value>1 || array_keys($keyNameCount)[$index]=="")
			{
				return $exceptionArray['mapping'];
			}
		}
		
		if(count($mappingArray)!=19)
		{
			return $exceptionArray['missingField'];
		}
		
		$requestArray = array();
		$categoryId = array();
		//make an requested array
		for($arrayData=0;$arrayData<count($dataArray);$arrayData++)
		{
			$categoryFlag=0;
			$groupFlag=0;
			$branchFlag=0;
			$companyFlag=0;
			//replace category-name with their id
			if(in_array("productCategoryId",$mappingArray))
			{
				$arrayKey = array_keys($mappingArray,"productCategoryId");
				
				//replace category-name with parent-category-id
				$convertedCatString = preg_replace('/[^A-Za-z0-9]/', '',$dataArray[$arrayData][$arrayKey[0]]);
				
				//database selection
				$categoryModel = new ProductCategoryModel();
				$convertedCatString = strtoupper($convertedCatString);
				$categoryResult = $categoryModel->getCategoryId($convertedCatString);
				if(strcmp($categoryResult,$exceptionArray['204'])==0)
				{
					$categoryFlag=1;
				}
				else
				{
					$dataArray[$arrayData][$arrayKey[0]] = $categoryResult;
				}
			}
			
			//replace group-name with their id
			if(in_array("productGroupId",$mappingArray))
			{
				$arrayKey = array_keys($mappingArray,"productGroupId");
				// replace group-name with parent-group-id
				$convertedGrpString = preg_replace('/[^A-Za-z0-9]/', '',$dataArray[$arrayData][$arrayKey[0]]);
				$convertedGrpString = strtoupper($convertedGrpString);
				// database selection
				$groupModel = new ProductGroupModel();
				$groupResult = $groupModel->getGroupId($convertedGrpString);
				
				if(strcmp($groupResult,$exceptionArray['204'])==0)
				{
					$groupFlag=1;
				}
				else
				{
					$dataArray[$arrayData][$arrayKey[0]] = $groupResult;
				}
			}
			
			//replace branch-name with their id
			if(in_array("branchId",$mappingArray))
			{
				$arrayKey = array_keys($mappingArray,"branchId");
				// replace group-name with parent-group-id
				$convertedBranchString = preg_replace('/[^A-Za-z0-9]/', '',$dataArray[$arrayData][$arrayKey[0]]);
				$convertedBranchString = strtoupper($convertedBranchString);
				// database selection
				$branchModel = new BranchModel();
				
				$branchResult = $branchModel->getBranchId($convertedBranchString);
				if(strcmp($branchResult,$exceptionArray['204'])==0)
				{
					$branchFlag=1;
				}
				else
				{
					$dataArray[$arrayData][$arrayKey[0]] = $branchResult;
				}
			}
			
			//replace company-name with their id
			if(in_array("companyId",$mappingArray))
			{
				$arrayKey = array_keys($mappingArray,"companyId");
				// replace group-name with parent-group-id
				$convertedCompanyString = preg_replace('/[^A-Za-z0-9]/', '',$dataArray[$arrayData][$arrayKey[0]]);
				$convertedCompanyString = strtoupper($convertedCompanyString);
				// database selection
				$companyModel = new CompanyModel();
				
				$companyResult = $companyModel->getCompanyId($convertedCompanyString);
				
				if(strcmp($companyResult,$exceptionArray['204'])==0)
				{
					$companyFlag=1;
				}
				else
				{
					$dataArray[$arrayData][$arrayKey[0]] = $companyResult;
				}
			}
			
			if($categoryFlag==1 || $groupFlag==1 || $branchFlag==1 || $companyFlag==1)
			{
				if($categoryFlag==1)
				{
					return $exceptionArray['invalidCategoryName'];
				}
				if($groupFlag==1)
				{
					return $exceptionArray['invalidGroupName'];
				}
				if($branchFlag==1)
				{
					return $exceptionArray['invalidBranchName'];
				}
				if($companyFlag==1)
				{
					return $exceptionArray['invalidCompanyName'];
				}
			}
			else
			{
				$requestArray[$arrayData] = array();
				$requestArray[$arrayData][array_keys($keyNameCount)[0]] = $dataArray[$arrayData][0];
				$requestArray[$arrayData][array_keys($keyNameCount)[1]] = $dataArray[$arrayData][1];
				$requestArray[$arrayData][array_keys($keyNameCount)[2]] = $dataArray[$arrayData][2];
				$requestArray[$arrayData][array_keys($keyNameCount)[3]] = $dataArray[$arrayData][3];
				$requestArray[$arrayData][array_keys($keyNameCount)[4]] = $dataArray[$arrayData][4];
				$requestArray[$arrayData][array_keys($keyNameCount)[5]] = $dataArray[$arrayData][5];
				$requestArray[$arrayData][array_keys($keyNameCount)[6]] = $dataArray[$arrayData][6];
				$requestArray[$arrayData][array_keys($keyNameCount)[7]] = $dataArray[$arrayData][7];
				$requestArray[$arrayData][array_keys($keyNameCount)[8]] = $dataArray[$arrayData][8];
				$requestArray[$arrayData][array_keys($keyNameCount)[9]] = $dataArray[$arrayData][9];
				$requestArray[$arrayData][array_keys($keyNameCount)[10]] = $dataArray[$arrayData][10];
				$requestArray[$arrayData][array_keys($keyNameCount)[11]] = $dataArray[$arrayData][11];
				$requestArray[$arrayData][array_keys($keyNameCount)[12]] = $dataArray[$arrayData][12];
				$requestArray[$arrayData][array_keys($keyNameCount)[13]] = $dataArray[$arrayData][13];
				$requestArray[$arrayData][array_keys($keyNameCount)[14]] = $dataArray[$arrayData][14];
				$requestArray[$arrayData][array_keys($keyNameCount)[15]] = $dataArray[$arrayData][15];
				$requestArray[$arrayData][array_keys($keyNameCount)[16]] = $dataArray[$arrayData][16];
				$requestArray[$arrayData][array_keys($keyNameCount)[17]] = $dataArray[$arrayData][17];
				$requestArray[$arrayData][array_keys($keyNameCount)[18]] = $dataArray[$arrayData][18];
			}
		}
		return $requestArray;
	}
	
	/**
     * @param 
     * @return array
     */
    public function trimInsertInOutwardData(Request $request,$inOutWard)
    {
		$discountTypeFlag=0;
		$requestArray = array();
		$exceptionArray = array();
		$numberOfArray = count($request->input()['inventory']);
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//data get from body and trim an input
		$companyId = trim($request->input()['companyId']); 
		$transactionDate = trim($request->input()['transactionDate']); 
		$tax = trim($request->input()['tax']); 
		if(array_key_exists($constantArray['invoiceNumber'],$request->input()))
		{
			$invoiceNumber = trim($request->input()['invoiceNumber']);
			$billNumber="";
		}
		else
		{
			$billNumber = trim($request->input()['billNumber']); 
			$invoiceNumber="";
		}
		
		//transaction date conversion
		$splitedDate = explode("-",$transactionDate);
		$transformTransactionDate = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
		// $transformEntryDate = Carbon\Carbon::createFromFormat('d-m-Y', $transactionDate)->format('Y-m-d');
		
		//get exception message
		$exception = new ProductTransformer();
		$exceptionArray = $exception->messageArrays();
		
		$enumDiscountTypeArray = array();
		$discountTypeEnum = new DiscountTypeEnum();
		$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
		
		for($arrayData=0;$arrayData<$numberOfArray;$arrayData++)
		{
			$tempArray[$arrayData] = array();
			$tempArray[$arrayData][0] = trim($request->input()['inventory'][$arrayData]['productId']);
			$tempArray[$arrayData][1] = trim($request->input()['inventory'][$arrayData]['discount']);
			$tempArray[$arrayData][2] = trim($request->input()['inventory'][$arrayData]['discountType']);
			$tempArray[$arrayData][3] = trim($request->input()['inventory'][$arrayData]['price']);
			$tempArray[$arrayData][4] = trim($request->input()['inventory'][$arrayData]['qty']);
			
			if($tempArray[$arrayData][1]!=0 && $tempArray[$arrayData][1]!="")
			{
				if(strcmp($tempArray[$arrayData][2],$constantArray['percentage'])==0)
				{
					$tempArray[$arrayData][5]=($tempArray[$arrayData][1]/100)*$tempArray[$arrayData][3];
				}
				else
				{
					$tempArray[$arrayData][5]=$tempArray[$arrayData][1];
				}
			}
			else
		    {
				$tempArray[$arrayData][5] = 0;
				$tempArray[$arrayData][1] = 0;
		    }
			foreach ($enumDiscountTypeArray as $key => $value)
			{
				if(strcmp($value,$tempArray[$arrayData][2])==0)
				{
					$discountTypeFlag=1;
					break;
				}
				else
				{
					$discountTypeFlag=0;
				}
			}
			if($discountTypeFlag==0)
			{
				$discountTypeFlag=0;
				break;
			}
		}
		
		if($discountTypeFlag==0)
		{
			return "1";
		}
		else
		{
			// make an array
			$simpleArray = array();
			$simpleArray['transactionDate'] = $transformTransactionDate;
			$simpleArray['companyId'] = $companyId;
			$simpleArray['transactionType'] = $inOutWard;
			$simpleArray['invoiceNumber'] = $invoiceNumber;
			$simpleArray['billNumber'] = $billNumber;
			$simpleArray['tax'] = $tax;
			
			$trimArray = array();
			for($data=0;$data<$numberOfArray;$data++)
			{
				$trimArray[$data]= array(
					'productId' => $tempArray[$data][0],
					'discount' => $tempArray[$data][1],
					'discountType' => $tempArray[$data][2],
					'price' => $tempArray[$data][3],
					'qty' => $tempArray[$data][4],
					'discountValue' => $tempArray[$data][5]
				);
			}
			array_push($simpleArray,$trimArray);
			return $simpleArray;
		}
	}
	
	/**
     * @param key and value
     * @return array
     */
	public function trimUpdateData($arrayData)
	{
		$productEnumArray = array();
		$isDisplayFlag=0;
		$measurementUnitFlag=0;
		$tProductArray = array();
		$productValue;
		// $keyValue = func_get_arg(0);
		$convertedValue="";
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		$index=0;
		foreach($arrayData as $keyValue => $value)
		{
			$convertedValue = "";
			for($asciiChar=0;$asciiChar<strlen($keyValue);$asciiChar++)
			{
				if(ord($keyValue[$asciiChar])<=90 && ord($keyValue[$asciiChar])>=65) 
				{
					$convertedValue1 = "_".chr(ord($keyValue[$asciiChar])+32);
					$convertedValue=$convertedValue.$convertedValue1;
				}
				else
				{
					$convertedValue=$convertedValue.$keyValue[$asciiChar];
				}
			}
			$productValue[$index] = $value;
			$tProductArray[$index]= array($convertedValue=> trim($productValue[$index]));
			$productEnumArray = array_keys($tProductArray[$index])[0];
			
			//check enum data
			$enumMeasurementUnitArray = array();
			$measurementUnitEnum = new measurementUnitEnum();
			$enumMeasurementUnitArray = $measurementUnitEnum->enumArrays();
			
			if(strcmp($constantArray['measurementUnit'],$productEnumArray)==0)
			{
				foreach ($enumMeasurementUnitArray as $innerKey => $innerValue)
				{
					if(strcmp($tProductArray[$index]['measurement_unit'],$innerValue)==0)
					{
						$measurementUnitFlag=1;
						break;
					}
					else
					{
						$measurementUnitFlag=2;
					}
				}
			}
			// echo $measurementUnitFlag;
			$enumIsDispArray = array();
			$isDispEnum = new IsDisplayEnum();
			$enumIsDispArray = $isDispEnum->enumArrays();
			
			if(strcmp($constantArray['isDisplay'],$productEnumArray)==0)
			{
				foreach ($enumIsDispArray as $innerKey => $innerValue)
				{
					if(strcmp($tProductArray[$index]['is_display'],$innerValue)==0)
					{
						$isDisplayFlag=1;
						break;
					}
					else
					{
						$isDisplayFlag=2;
					}
				}
			}
			if($isDisplayFlag==2 || $measurementUnitFlag==2)
			{
				return "1";
			}
			$index++;
		}
		return $tProductArray;
	}
	
	/**
	 * trim request data for update
     * @param object
     * @return array
     */
	public function trimUpdateProductData($productArray,$inOutWard)
	{
		$discountTypeFlag=0;
		$requestArray = array();
		$exceptionArray = array();
		$tProductArray = array();
		$convertedValue="";
		$arraySample = array();
		$tempArrayFlag=0;
		$productArrayFlag=0;
		$tempFlag=0;
		
		$constantClass = new ConstantClass();
		$constantArray = $constantClass->constantVariable();
		
		//get exception message
		$exception = new ProductTransformer();
		$exceptionArray = $exception->messageArrays();
		for($requestArray=0;$requestArray<count($productArray);$requestArray++)
		{
			//check if array is exists
			if(strcmp(array_keys($productArray)[$requestArray],$constantArray['inventory'])==0)
			{
				//number of array elements
				for($arrayElement=0;$arrayElement<count($productArray['inventory']);$arrayElement++)
				{
					$tempArrayFlag=1;
					$tempArray[$arrayElement] = array();
					$tempArray[$arrayElement]['product_id'] = trim($productArray['inventory'][$arrayElement]['productId']);
					$tempArray[$arrayElement]['discount'] = trim($productArray['inventory'][$arrayElement]['discount']);
					$tempArray[$arrayElement]['discount_type'] = trim($productArray['inventory'][$arrayElement]['discountType']);
					$tempArray[$arrayElement]['price'] = trim($productArray['inventory'][$arrayElement]['price']);
					$tempArray[$arrayElement]['qty'] = trim($productArray['inventory'][$arrayElement]['qty']);
					
					if($tempArray[$arrayElement]['discount']!=0 && $tempArray[$arrayElement]['discount']!="")
					{
						if(strcmp($tempArray[$arrayElement]['discount_type'],$constantArray['percentage'])==0)
						{
							$tempArray[$arrayElement]['discount_value']=($tempArray[$arrayElement]['discount']/100)* $tempArray[$arrayElement]['price'];
						}
						else
						{
							$tempArray[$arrayElement]['discount_value'] = $tempArray[$arrayElement]['discount'];
						}
					}
					else
					{
						$tempArray[$arrayElement]['discount_value']=0;
					}
					//check enum type[amount-type]
					$enumDiscountTypeArray = array();
					$discountTypeEnum = new DiscountTypeEnum();
					$enumDiscountTypeArray = $discountTypeEnum->enumArrays();
					foreach ($enumDiscountTypeArray as $key => $value)
					{
						if(strcmp($value,$tempArray[$arrayElement]['discount_type'])==0)
						{
							$discountTypeFlag=1;
							break;
						}
						else
						{
							$discountTypeFlag=0;
						}
					}
				}
				if($discountTypeFlag==0)
				{
					return "1";
				}
			}
			else
			{
				$key = array_keys($productArray)[$requestArray];
				$value = $productArray[$key];
				$productArrayFlag=1;
				for($asciiChar=0;$asciiChar<strlen($key);$asciiChar++)
				{
					if(ord($key[$asciiChar])<=90 && ord($key[$asciiChar])>=65) 
					{
						$convertedValue1 = "_".chr(ord($key[$asciiChar])+32);
						$convertedValue=$convertedValue.$convertedValue1;
					}
					else
					{
						$convertedValue=$convertedValue.$key[$asciiChar];
					}
				}
				if(strcmp($convertedValue,$constantArray['transactionDate'])==0)
				{
					$transformTransactionDate=trim($value);
					$splitedDate = explode("-",$transformTransactionDate);
					$tProductArray[$convertedValue] = $splitedDate[2]."-".$splitedDate[1]."-".$splitedDate[0];
					// $transformTransactionDate = Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
					// $tProductArray[$convertedValue]=trim($transformTransactionDate);
					$convertedValue="";
				}
				else
				{
					$tProductArray[$convertedValue]=trim($value);
					$convertedValue="";
				}
				$tempFlag=1;
			}
			if($tempFlag==1)
			{
				if($requestArray==count($productArray)-1)
				{
					$tProductArray['transaction_type']=$inOutWard;
					$tProductArray['flag']="1";
				}
			}
		}
		if($productArrayFlag==1 && $tempArrayFlag==1)
		{
			array_push($tProductArray,$tempArray);
			return $tProductArray;
		}
		else if($productArrayFlag==1)
		{
			return $tProductArray;
		}
		else
		{
			return $tempArray;
		}
	}
}