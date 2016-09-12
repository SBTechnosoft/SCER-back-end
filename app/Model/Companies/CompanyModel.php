<?php
namespace ERP\Model\Companies;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyModel extends Model
{
	/**
	 * insert data 
	 * returns the status
	*/
	public function insertData($companyName,$companyDispName,$address1,$address2,$pincode,$panNo,$tinNo,$vatNo,$serviceTaxNO,$basicCurrencySymbol,$formalName,$noOfDecimalPoints,$currencySymbol,$isDisplay,$isDefault,$stateAbb,$cityId)
	{
		DB::beginTransaction();
		$raw = DB::statement("insert into company_mst(company_name,company_display_name,address1,address2,pincode,pan,tin,vat_no,service_tax_no,basic_currency_symbol,formal_name,no_of_decimal_points,currency_symbol,is_display,is_default,state_abb,city_id) 
		values('".$companyName."', 
		'".$companyDispName."',
		'".$address1."',
		'".$address2."',
		'".$pincode."',
		'".$panNo."',
		'".$tinNo."',
		'".$vatNo."',
		'".$serviceTaxNO."',
		'".$basicCurrencySymbol."',
		'".$formalName."',
		'".$noOfDecimalPoints."',
		'".$currencySymbol."',
		'".$isDisplay."',
		'".$isDefault."',
		'".$stateAbb."',
		'".$cityId."')");
		DB::commit();
		
		if($raw==1)
		{
			$companyId = DB::select('SELECT  MAX(company_id) AS company_id from company_mst');
			$enocodedData = json_encode($companyId);
			$decodedJson = json_decode($enocodedData,true);
			$createdAt = $decodedJson[0]['company_id'];
			return $createdAt;
		}
		else
		{
			return "500:Internal Server Error";
		}
	}
	
	/**
	 * update data 
	 * returns the status
	*/
	public function updateData($companyName,$companyDispName,$address1,$address2,$pincode,$panNo,$tinNo,$vatNo,$serviceTaxNO,$basicCurrencySymbol,$formalName,$noOfDecimalPoints,$currencySymbol,$isDisplay,$isDefault,$stateAbb,$cityId,$companyId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update company_mst 
		set company_name='".$companyName."',
		company_display_name='".$companyDispName."',
		address1='".$address1."',
		address2='".$address2."',
		pincode='".$pincode."',
		pan='".$panNo."',
		tin='".$tinNo."',
		vat_no='".$vatNo."',
		service_tax_no='".$serviceTaxNO."',
		basic_currency_symbol='".$basicCurrencySymbol."',
		formal_name='".$formalName."',
		no_of_decimal_points='".$noOfDecimalPoints."',
		currency_symbol='".$currencySymbol."',
		is_display='".$isDisplay."',
		is_default='".$isDefault."',
		state_abb='".$stateAbb."',
		city_id='".$cityId."',
		updated_at='".$mytime."'
		where company_id = ".$companyId);
		DB::commit();
		
		if($raw==1)
		{
			return "200: Data Updated Successfully";
		}
		else
		{
			return "500: Internal Server Error";
		}
	}
	
	/**
	 * get All data 
	 * returns the status
	*/
	public function getAllData()
	{	
		DB::beginTransaction();		
		$raw = DB::select("select 
		company_id,
		company_name,
		company_display_name,
		address1,
		address2,
		pincode,
		pan,
		tin,
		vat_no,
		service_tax_no,
		basic_currency_symbol,
		formal_name,
		no_of_decimal_points,
		currency_symbol,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id 
		from company_mst where deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "204: No Content";
		}
		else
		{
			$enocodedData = json_encode($raw);
			return $enocodedData;
		}
	}
	
	/**
	 * get data as per given Company Id
	 * @param $companyId
	 * returns the status
	*/
	public function getData($companyId)
	{		
		DB::beginTransaction();
		$raw = DB::select("select 
		company_id,
		company_name,
		company_display_name,
		address1,
		address2,
		pincode,
		pan,
		tin,
		vat_no,
		service_tax_no,
		basic_currency_symbol,
		formal_name,
		no_of_decimal_points,
		currency_symbol,
		document_name,
		document_url,
		document_size,
		document_format,
		is_display,
		is_default,
		created_at,
		updated_at,
		deleted_at,
		state_abb,
		city_id 
		from company_mst where company_id = ".$companyId." and deleted_at='0000-00-00 00:00:00'");
		DB::commit();
		
		if(count($raw)==0)
		{
			return "404:Id Not Found";
		}
		else
		{
			$enocodedData = json_encode($raw,true); 	
			return $enocodedData;
		}
	}
	
	//delete
	public function deleteData($companyId)
	{
		DB::beginTransaction();
		$mytime = Carbon\Carbon::now();
		$raw = DB::statement("update company_mst 
		set deleted_at='".$mytime."' 
		where company_id=".$companyId);
		DB::commit();
		
		if($raw==1)
		{
			$branch = DB::statement("update branch_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			$product = DB::statement("update product_mst 
			set deleted_at='".$mytime."' 
			where company_id=".$companyId);
			if($branch==1 && $product==1)
			{
				return "200 :Data Deleted Successfully";
			}
			else
			{
				return "500 : Internal Server Error";
			}
		}
		else
		{
			return "500 : Internal Server Error";
		}
	}
}