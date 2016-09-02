<?php
namespace ERP\Model\Companies;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * @author Reema Patel<reema.p@siliconbrain.in>
 */
class CompanyModel extends Model
{
	protected $table = 'company_mst';
	
	/**
	 * insert data 
	 * @param  name and age
	 * returns the status
	*/
	// public function insertData($name,$age)
	// {
		// DB::beginTransaction();
		// $raw = DB::statement("insert into product (name,age) values('".$name."', '".$age."')");
		// DB::commit();
		
		// if($raw==1)
		// {
			// return "<br>data inserted successfully";
		// }
		// else
		// {
			// return "data is not inserted successfully";
		// }
	// }
	/**
	 * update data 
	 * @param  name,age and id
	 * returns the status
	*/
	// public function updateData($name,$age,$id)
	// {
		// DB::beginTransaction();
		// $raw = DB::statement("update product set name='".$name."',age='".$age."' where id = ".$id);
		// DB::commit();
		
		// if($raw==1)
		// {
			// return "1";
		// }
		// else
		// {
			// return "0";
		// }
	// }
	/**
	 * get All data 
	 * @param  id
	 * returns the status
	*/
	public function getAllData()
	{		
		$raw = DB::select("select 
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
		from company_mst");
		
		$enocodedData = json_encode($raw); 	
		if($enocodedData=='[]')
		{
			return "404:Data not found!";
		}
		else
		{
			return $enocodedData;
		}
	}
	public function getData($companyId)
	{		
		
		$raw = DB::select("select 
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
		from company_mst where company_id = ".$companyId);
		
		$enocodedData = json_encode($raw,true); 	
		// print_r($raw[0]->{'company_display_name'});
		// print_r($raw);
		// print_r($enocodedData['company_display_name']);
		if($enocodedData=='[]')
		{
			return "404:Data not found!";
		}
		else
		{
			return $enocodedData;
		}
	}
	// public function deleteData($id)
	// {
		// DB::beginTransaction();
		// $raw = DB::statement("delete from product where id =".$id);
		// DB::commit();
		// if($raw==1)
		// {
			// return "data deleted successfully";
		// }
		// else
		// {
			// return "data is not deleted successfully";
		// }
	// }
}
