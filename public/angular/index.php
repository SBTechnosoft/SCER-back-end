<?php
include ('connection.php');
?>

<!DOCTYPE html>
<html ng-app="myapp">

<head>
	<title> </title>
	
</head>


	<body>
		<center>
			<form id="myform" enctype="multipart/formdata" method="POST" ng-controller="HelloController">
			
				<table id="data" style="height:250px">
					
					<tr>
						<td> <a class="group1" href="index.html">Student Name </a></td>
						<td> :</td>
						<td> <input type="text" name="studentname" id="studentname" class="studentname" ng-model="formAdata.txtname"></td>
						
						
					</tr>
					<?php 
					if(isset($check))
					{
						echo $check;
					}
					?>
						<tr>
						<td> Gender </td>
						<td> :</td>
						<td> 
							<select name="gender" id="gender" ng-model="formAdata.txtgender">
								<option value="Male">MALE </option>
								<option value="Female">FEMALE </option>
							</select>
						</td>
						
					</tr>
					
					
					<tr>
						<td> Phone </td>
						<td> :</td>
						<td> <input type="tel" name="phone" id="phone" ng-model="formAdata.txtphone" ></td>
						
					</tr>
					
					<tr>
						<td> Image  </td>
						<td> :</td>
						<td> <!--<input type="file"  name="myfile" onchange="angular.element(this).scope().submit_form(this.files)" id="myfile" >-->
						<input type="file"  name="myfile[]" ng-files="getTheFiles($files)" id="myfile" multiple></td>
						<td><img ng-src="" /></td>
						
					</tr>
					
					<tr>
						<td> address </td>
						<td> :</td>
						<td> <input type="text" name="address" id="address" ng-model="formAdata.txtaddress"></td>
						
					</tr>
					
					
					
					<tr>
					<input type="hidden" value="1" name="id" id="id" />
						<td> <input type="submit" value="SAVE" name="save" id="save" class="save" ng-click="submit_form()" >{{status}}</td>
						
						
					</tr>	
				</table>
			</form>
				
				
			
		</center>
		
	</body>

	<script src="js/angular.min.js"></script>
	 <script> 
         var app = angular.module("myapp", []);
		 app.directive('ngFiles', ['$parse', function ($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function (event) {
                    onChange(scope, { $files: event.target.files });
                });
            };

            return {
                link: fn_link
            }
        } ]);
         app.controller("HelloController", ['$scope','$http',function($scope,$http) { 
            $scope.formAdata=[];
			 
			 var formdata = new FormData();
			 
			
             $scope.getTheFiles = function ($files) {
				 
				angular.forEach($files, function (value,key) {
					formdata.append('file[]',value);
				});
				
            }
			$scope.submit_form = function()
			{
				//state
				// $scope.formAdata.state_abb ="IN-AB";
				// $scope.formAdata.state_name = " ss-d&fe& ";
				// $scope.formAdata.is_display = ' yes ';
				
				// formdata.append('state_name',$scope.formAdata.state_name);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('is_display',$scope.formAdata.is_display);
				
				//city
				// $scope.formAdata.city_name = " Anandddqd ";
				// $scope.formAdata.is_display = ' yes ';
				// $scope.formAdata.state_abb = 'IN-MP';
				
				// formdata.append('city_name',$scope.formAdata.city_name);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('is_display',$scope.formAdata.is_display);
				
				//branch
				// $scope.formAdata.branch_name = "abcc!cc&-_`#().\'11";
				// $scope.formAdata.branch_name = "ab1cdfcA1";
				// $scope.formAdata.address1 ="35,abc1";
				// $scope.formAdata.address2 = "sdgd1";
				// $scope.formAdata.pincode = 324691;
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.is_default = 'not';
				// $scope.formAdata.state_abb= 'IN-AP';
				// $scope.formAdata.city_id= 12;
				// $scope.formAdata.company_id= 67;
				
				// formdata.append('branch_name',$scope.formAdata.branch_name);
				// formdata.append('address1',$scope.formAdata.address1 );
				// formdata.append('address2',$scope.formAdata.address2 );
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('is_default',$scope.formAdata.is_default);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('city_id',$scope.formAdata.city_id);
				// formdata.append('company_id',$scope.formAdata.company_id);
				
				//company
				// $scope.formAdata.company_name = "reema2232323222222222222222222222222222222222222";
				// $scope.formAdata.company_name = " 1sh1l lbaaaaa-&_().\'aadks "; //0-9 not allow(error:allow)
				// $scope.formAdata.company_name = " a Wf&assf-a";
				// $scope.formAdata.company_display_name = " '`rc a-zA-Z -#&)\'`.a ";
				// $scope.formAdata.address1 =" sdx cz *,-\/_`#\[\]().\'fs.'0a";
				// $scope.formAdata.address2 = " aEbc abc*dfghjd/ih'b1 ";
				// $scope.formAdata.pincode = 311411;
				// $scope.formAdata.pan= ' qqqas1122a ';
				// $scope.formAdata.tin= ' 42aa3dgg778 ';
				// $scope.formAdata.vat_no= ' aadgg78w1a0 ';
				// $scope.formAdata.service_tax_no = ' 71j0rg778a22b01 ';
				// $scope.formAdata.basic_currency_symbol= " ALR ";
				// $scope.formAdata.formal_name = " qgfrd-&_().\'frtgfrta ";
				// $scope.formAdata.no_of_decimal_points = 4;
				// $scope.formAdata.currency_symbol = ' prefix ';
				// $scope.formAdata.is_display = ' no ';
				// $scope.formAdata.is_default = ' ok ';
				// $scope.formAdata.state_abb= ' IN-MP ';
				// $scope.formAdata.city_id= 3;
				
				// formdata.append('company_name',$scope.formAdata.company_name);
				// formdata.append('company_display_name',$scope.formAdata.company_display_name);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('vat_no',$scope.formAdata.vat_no);
				// formdata.append('service_tax_no',$scope.formAdata.service_tax_no);
				// formdata.append('basic_currency_symbol',$scope.formAdata.basic_currency_symbol);
				// formdata.append('formal_name',$scope.formAdata.formal_name);
				// formdata.append('no_of_decimal_points',$scope.formAdata.no_of_decimal_points);
				// formdata.append('currency_symbol',$scope.formAdata.currency_symbol);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('is_default',$scope.formAdata.is_default);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('city_id',$scope.formAdata.city_id);
				
				//productCategory
				// $scope.formAdata.productCatName = "abc12$34";
				// $scope.formAdata.product_cat_desc = "abcdddddcc ddd";
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.product_parent_cat_id = 1;
				
				// formdata.append('product_cat_name',$scope.formAdata.productCatName);
				// formdata.append('product_cat_desc',$scope.formAdata.product_cat_desc);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('product_parent_cat_id',$scope.formAdata.product_parent_cat_id);
				
				//productGroup
				// $scope.formAdata.product_group_name = "\a&b#c12  ,-_`#().\'34";
				// $scope.formAdata.product_group_desc = "abcdddd'dd''dd";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.product_group_parent_id = 0;
				
				// formdata.append('product_group_name',$scope.formAdata.product_group_name);
				// formdata.append('product_group_desc',$scope.formAdata.product_group_desc);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('product_group_parent_id',$scope.formAdata.product_group_parent_id);
				
				//product
				// $scope.formAdata.product_name = "abc12 ,-\/_`#(.34";
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.measurement_unit='litre';
				// $scope.formAdata.product_cat_id='1';
				// $scope.formAdata.product_group_id='2';
				// $scope.formAdata.company_id='67';
				// $scope.formAdata.branch_id='18';
				
				// formdata.append('product_name',$scope.formAdata.product_name);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('measurement_unit',$scope.formAdata.measurement_unit);
				// formdata.append('product_cat_id',$scope.formAdata.product_cat_id);
				// formdata.append('product_group_id',$scope.formAdata.product_group_id);
				// formdata.append('company_id',$scope.formAdata.company_id);
				// formdata.append('branch_id',$scope.formAdata.branch_id);
				
				//template
				// $scope.formAdata.template_name = " abc ";
				// $scope.formAdata.template_type = ' general ';
				// $scope.formAdata.template_body=' <b> hi</b> ';
				
				// formdata.append('template_name',$scope.formAdata.template_name);
				// formdata.append('template_type',$scope.formAdata.template_type);
				// formdata.append('template_body',$scope.formAdata.template_body);
				
				//invoice
				// $scope.formAdata.invoice_label = " abcd ";
				// $scope.formAdata.invoice_type = ' prefix ';
				// $scope.formAdata.start_at=' 1 ';
				// $scope.formAdata.end_at=' 10 ';
				// $scope.formAdata.company_id='1 ';
				
				// formdata.append('invoice_label',$scope.formAdata.invoice_label);
				// formdata.append('invoice_type',$scope.formAdata.invoice_type);
				// formdata.append('start_at',$scope.formAdata.start_at);
				// formdata.append('end_at',$scope.formAdata.end_at);
				// formdata.append('company_id',$scope.formAdata.company_id);
				
				//quotation
				// $scope.formAdata.quotation_label = " abcd ";
				// $scope.formAdata.quotation_type = ' prefix ';
				// $scope.formAdata.start_at=' 1 ';
				// $scope.formAdata.end_at=' 10 ';
				// $scope.formAdata.company_id='1 ';
				
				// formdata.append('quotation_label',$scope.formAdata.quotation_label);
				// formdata.append('quotation_type',$scope.formAdata.quotation_type);
				// formdata.append('start_at',$scope.formAdata.start_at);
				// formdata.append('end_at',$scope.formAdata.end_at);
				// formdata.append('company_id',$scope.formAdata.company_id);
				
				// var productId = 2;
				// var productGrpId = 2;
				// var productCatId = 13;
				// var companyId=1;
				// var cityId = 16;
				 // var stateAbb = "IN-GS";
				 // var branchId = 2;
				// var id = 42;
				// var templateId=1;
				// var bankId=1;
				// var invoiceId=2;
				// var quotationId=1;
				
				
				// var url="http://www.scerp.com/quotations/quotation/"+companyId;
				// var url="http://www.scerp.com/quotations/quotation";
				// var url="http://www.scerp.com/invoices/invoice/"+companyId;
				// var url="http://www.scerp.com/invoices/invoice";
				// var url="http://www.scerp.com/banks/bank/"+bankId;
				// var url="http://www.scerp.com/banks/bank";
				// var url="http://www.scerp.com/templates/template/"+templateId;
				// var url="http://www.scerp.com/templates/template";
				// var url="http://www.scerp.com/companies/"+companyId;
				// var url="http://www.scerp.com/companies";
				// var url="http://www.scerp.com/branches/branch";
				 // var url="http://www.scerp.com/branches/branch/"+branchId;
				var url="http://www.scerp.com/branches/company/"+companyId;
				 // var url="http://www.scerp.com/states/state/"+stateAbb;
				//var url="http://www.scerp.com/states/state";
				// var url="http://www.scerp.com/cities/city/state/"+stateAbb;
				 // var url="http://www.scerp.com/cities/city";
				 // var url="http://www.scerp.com/cities/city/"+cityId;
				// var url="http://www.scerp.com/product-categories/product-category/"+productCatId;
				// var url="http://www.scerp.com/product-categories/product-category";
				// var url="http://www.scerp.com/product-groups/product-group";
				// var url="http://www.scerp.com/product-groups/product-group/"+productGrpId;
				// var url="http://www.scerp.com/products/product/"+productId;
				// var url="http://www.scerp.com/products/product";
				var url="http://www.scerp.com/products/company/"+companyId+"/branch/"+branchId;
				$http({
                        url: url,
                        // type:'patch',
						 method: 'post',
						// method: 'get',
						// method: "PATCH",
						// method:'delete',
						processData: false,
                        headers: {'Content-Type': undefined},
                        data:formdata						
                        
                    }).success(function(data, status, headers, config) {
						console.log(data);	//post	//get	//update //delete
						$scope.status = status;
                    }).error(function(data, status, headers, config) {
                        $scope.status = status;
                    });
			}
			
         }]); 
		 
      </script> 
</html>


