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
					console.log(value);
					formdata.append('file[]',value);
				});
				
            }
			$scope.submit_form = function()
			{
				// var formdata = new FormData();
				//state
				// $scope.formAdata.state_abb ="IN-LD";
				// $scope.formAdata.state_name = " ss-fghd ";
				// $scope.formAdata.is_display = ' yes ';
				
				// formdata.append('stateName',$scope.formAdata.state_name);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				
				//city
				// $scope.formAdata.city_name = " Ananddd ";
				// $scope.formAdata.is_display = ' no ';
				// $scope.formAdata.state_abb = 'IN-AG';
				
				// formdata.append('cityName',$scope.formAdata.city_name);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				
				//branch
				// $scope.formAdata.branch_name = "abcc!cc&-_`#().\'11";
				// $scope.formAdata.branch_name = "abc";
				// $scope.formAdata.address1 ="35,abc2";
				// $scope.formAdata.address2 = "sdgd2";
				// $scope.formAdata.pincode = 324692;
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.is_default = 'ok';
				// $scope.formAdata.state_abb= 'IN-MP';
				// $scope.formAdata.city_id= 1;
				// $scope.formAdata.company_id= 15;
				
				// formdata.append('branchName',$scope.formAdata.branch_name);
				// formdata.append('address1',$scope.formAdata.address1 );
				// formdata.append('address2',$scope.formAdata.address2 );
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('isDefault',$scope.formAdata.is_default);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//company
				// $scope.formAdata.company_name = "reema2232323222222222222222222222222222222222222";
				// $scope.formAdata.company_name = " 1sh1l lbaaaaa-&_().\'aadks "; //0-9 not allow(error:allow)
				// $scope.formAdata.company_name = " abc";
				// $scope.formAdata.company_display_name = " fff ";
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
				// $scope.formAdata.is_display = ' no1 ';
				// $scope.formAdata.is_default = ' ok ';
				// $scope.formAdata.state_abb= ' IN-MP ';
				// $scope.formAdata.city_id= 1;
				
				// formdata.append('companyName',$scope.formAdata.company_name);
				// formdata.append('companyDisplayName',$scope.formAdata.company_display_name);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('vatNo',$scope.formAdata.vat_no);
				// formdata.append('serviceTaxNo',$scope.formAdata.service_tax_no);
				// formdata.append('basicCurrencySymbol',$scope.formAdata.basic_currency_symbol);
				// formdata.append('formalName',$scope.formAdata.formal_name);
				// formdata.append('noOfDecimalPoints',$scope.formAdata.no_of_decimal_points);
				// formdata.append('currencySymbol',$scope.formAdata.currency_symbol);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('isDefault',$scope.formAdata.is_default);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				
				//productCategory
				// $scope.formAdata.productCatName = "abc";
				// $scope.formAdata.product_cat_desc = "abcdddddcc";
				// $scope.formAdata.is_display = 'yes ';
				// $scope.formAdata.product_parent_cat_id = 1;
				
				// formdata.append('productCategoryName',$scope.formAdata.productCatName);
				// formdata.append('productCategoryDescription',$scope.formAdata.product_cat_desc);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('productParentCategoryId',$scope.formAdata.product_parent_cat_id);
				
				//productGroup
				// $scope.formAdata.product_group_name = "\a&b#c4";
				// $scope.formAdata.product_group_desc = "abcdddd";
				// $scope.formAdata.is_display = 'yes ';
				// $scope.formAdata.product_group_parent_id = 0;
				
				// formdata.append('productGroupName',$scope.formAdata.product_group_name);
				// formdata.append('productGroupDescription',$scope.formAdata.product_group_desc);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('productGroupParentId',$scope.formAdata.product_group_parent_id);
				
				//product
				// $scope.formAdata.product_name = "abc";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.measurement_unit='lit';
				// $scope.formAdata.product_cat_id='1';
				// $scope.formAdata.product_group_id='4';
				// $scope.formAdata.company_id='15';
				// $scope.formAdata.branch_id='6';
				
				// formdata.append('productName',$scope.formAdata.product_name);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('measurementUnit',$scope.formAdata.measurement_unit);
				// formdata.append('productCategoryId',$scope.formAdata.product_cat_id);
				// formdata.append('productGroupId',$scope.formAdata.product_group_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				// formdata.append('branchId',$scope.formAdata.branch_id);
				
				//template
				// $scope.formAdata.template_name = " abcffee ";
				// $scope.formAdata.template_type = ' general ';
				// $scope.formAdata.template_body=' <b> hiee</b> ';
				
				// formdata.append('templateName',$scope.formAdata.template_name);
				// formdata.append('templateType',$scope.formAdata.template_type);
				// formdata.append('templateBody',$scope.formAdata.template_body);
				
				//invoice
				// $scope.formAdata.invoice_label = " abcd ";
				// $scope.formAdata.invoice_type = ' prefix ';
				// $scope.formAdata.start_at=' 1 ';
				// $scope.formAdata.end_at=' 10 ';
				// $scope.formAdata.company_id='14 ';
				
				// formdata.append('invoiceLabel',$scope.formAdata.invoice_label);
				// formdata.append('invoiceType',$scope.formAdata.invoice_type);
				// formdata.append('startAt',$scope.formAdata.start_at);
				// formdata.append('endAt',$scope.formAdata.end_at);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//quotation
				// $scope.formAdata.quotation_label = " abcd ";
				// $scope.formAdata.quotation_type = ' prefix ';
				// $scope.formAdata.start_at=' 1 ';
				// $scope.formAdata.end_at=' 10 ';
				// $scope.formAdata.company_id='14 ';
				
				// formdata.append('quotationLabel',$scope.formAdata.quotation_label);
				// formdata.append('quotationType',$scope.formAdata.quotation_type);
				// formdata.append('startAt',$scope.formAdata.start_at);
				// formdata.append('endAt',$scope.formAdata.end_at);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//ledger
				// $scope.formAdata.ledger_name = " abcc ";
				// $scope.formAdata.alias = ' dc ';
				// $scope.formAdata.inventory_affected=' yes ';
				// $scope.formAdata.address1 =" sdx czc ";
				// $scope.formAdata.address2 = " aEbc ";
				// $scope.formAdata.pan= ' qqqas1122c ';
				// $scope.formAdata.tin= ' 42aa3dgg773 ';
				// $scope.formAdata.gst = ' 71j0rg778a22b03 ';
				// $scope.formAdata.state_abb= ' IN-AG ';
				// $scope.formAdata.city_id= '1';
				// $scope.formAdata.ledger_grp_id='2 ';
				// $scope.formAdata.company_id='14 ';
				
				// formdata.append('ledgerName',$scope.formAdata.ledger_name);
				// formdata.append('alias',$scope.formAdata.alias);
				// formdata.append('inventoryAffected',$scope.formAdata.inventory_affected);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('gst',$scope.formAdata.gst);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				// formdata.append('ledgerGroupId',$scope.formAdata.ledger_grp_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				// journal
				// $scope.formAdata.jounalData=[];
				// var jounalData=[];
				// $scope.formAdata.jounalData = 'fdsa';
				// $scope.formAdata.jounalData = [{"DropCr":"dr","name":"Speial","Dbt":"2000"}];
				// $scope.formAdata.jounalData = [{"DropCr":"dr","name":"Speial","Dbt":"2000"}];
				// jounalData.name = "abc";
				// jounalData.dbt = "2000";
				// formdata.append('journalData[]',$scope.formAdata.jounalData);
				// console.log(journalData);
				// var journalData = [];
				// journalData = [{"DropCr":"dr","name":"abc","Dbt":"2000"},{"DropCr":"dr","name":"abc1","Dbt":"2000"}];
				///////////////////
				// $scope.formAdata.jounalData=[];
				// $scope.formAdata.jounalData = 
					// [{"DropCr":"dr","name":"abc","Dbt":"2000"},{"DropCr":"dr","name":"abc1","Dbt":"2000"}];
				
				// formdata.append('journalData1[]',$scope.formAdata.jounalData);
				// var abc=[];
				// var i=0;
				// angular.forEach(journalData, function (value,key) {
					// console.log(value);
					// abc[i] = value;
					// formdata.append('journalData1[]',abc[i]);
					// i++;
				// });
				// console.log(abc);
				// console.log(JSON.stringify(abc));
				//////////////////////////////////
				$scope.user = [{"jf_id":2,"data":[{},{}],}];
				// $scope.user={
					// 'array':[{"key":"value"},{"key1":"value1"}]
				// };
				// formdata.append('journalData1[]',$scope.user);
				// var productId = 7;
				// var productGrpId = 1;
				// var productCatId = 1;
				// var companyId=14;
				// var cityId = 1;
				 // var stateAbb = "IN-GJ";
				 // var branchId = 6;
				// var id = 42;
				// var templateId=1;
				// var bankId=164;
				// var invoiceId=2;
				// var quotationId=3;
				// var ledgerGrpId=1;
				// var ledgerId=7;
				var journal_id=1;
				
				// var url="http://www.scerp.com/accos";
				// var url="http://www.scerp.com/accounting/journals";
				var url="http://www.scerp.com/accounting/journals/"+journal_id;
				
				// var url="http://www.scerp.com/accounting/ledgers/company/"+companyId;
				// var url="http://www.scerp.com/accounting/ledgers/"+ledgerId;
				// var url="http://www.scerp.com/accounting/ledgers";
				// var url="http://www.scerp.com/accounting/ledger-groups/"+ledgerGrpId;
				// var url="http://www.scerp.com/accounting/ledger-groups";
				// SELECT max(invoice_id) invoice_id,invoice_label FROM `invoice_dtl` where company_id=1
				// var url="http://www.scerp.com/settings/quotation-numbers/company/"+companyId+"/latest";
				// var url="http://www.scerp.com/settings/quotation-numbers";
				// var url="http://www.scerp.com/settings/quotation-numbers/"+quotationId;
				// var url="http://www.scerp.com/settings/quotation-numbers/company/"+companyId;
				// var url="http://www.scerp.com/settings/invoice-numbers/company/"+companyId+"/latest";
				// var url="http://www.scerp.com/settings/invoice-numbers";
				// var url="http://www.scerp.com/settings/invoice-numbers/company/"+companyId;
				// var url="http://www.scerp.com/banks/"+bankId;
				// var url="http://www.scerp.com/banks";
				// var url="http://www.scerp.com/settings/templates/"+templateId;
				// var url="http://www.scerp.com/settings/templates";
				// var url="http://www.scerp.com/companies/"+companyId;
				// var url="http://www.scerp.com/companies";	
				// var url="http://www.scerp.com/branches";
				 // var url="http://www.scerp.com/branches/"+branchId;
				// var url="http://www.scerp.com/branches/company/"+companyId;
				 // var url="http://www.scerp.com/states/"+stateAbb;
				// var url="http://www.scerp.com/states";
				// var url="http://www.scerp.com/cities/state/"+stateAbb;
				 // var url="http://www.scerp.com/cities";
				 // var url="http://www.scerp.com/cities/"+cityId;
				// var url="http://www.scerp.com/product-categories/"+productCatId;
				// var url="http://www.scerp.com/product-categories";
				// var url="http://www.scerp.com/product-groups";
				// var url="http://www.scerp.com/product-groups/"+productGrpId;
				// var url="http://www.scerp.com/products/"+productId;
				// var url="http://www.scerp.com/products";
				// var url="http://www.scerp.com/products/company/"+companyId+"/branch/"+branchId;
				$http({
                        url: url,
                        // type:'patch',
						 method: 'post',
						// method: 'get',
						// method: "PATCH",
						// method:'delete',
						processData: false,
						// dataType: "json",
						// encode : true,
						// async: false,
                        headers: {'Content-Type': undefined},
                        // headers: {'Content-Type': 'application/json'},
                        // data:$scope.user					
                        
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


