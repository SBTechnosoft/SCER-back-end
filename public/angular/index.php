<?php
include ('connection.php');
?>

<!DOCTYPE html>
<html ng-app="myapp">

<head>
	<title> </title>
	
</head>

<script src="js/jquery-1.12.0.js" type="text/javascript"></script>
	<body>
		<center>
			<form id="myform" enctype="multipart/formdata" method="GET" ng-controller="HelloController"  >
			
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
			 var imageFile;
             $scope.getTheFiles = function ($files) {
				angular.forEach($files, function (value,key) {
				formdata.append('file[]',value);
				// console.log(value);
                 
                });
				
            }
			$scope.submit_form = function()
			{
				//state
				// $scope.formAdata.state_name = "abcd1";
				// $scope.formAdata.state_abb = "IN-GJ";
				// $scope.formAdata.is_display = 'no';
				
				// formdata.append('state_name',$scope.formAdata.state_name);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('is_display',$scope.formAdata.is_display);
				
				//city
				// $scope.formAdata.city_name = "Anand";
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.state_abb = 'IN-GJ';
				
				// formdata.append('city_name',$scope.formAdata.city_name);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('is_display',$scope.formAdata.is_display);
				
				//branch
				// $scope.formAdata.branch_name = "a3bcd12";
				// $scope.formAdata.address1 ="35,gdsgsdgsa dsgasdf1";
				// $scope.formAdata.address2 = "sdgdsg,gdsagsdg1";
				// $scope.formAdata.pincode = 324362;
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.is_default = 'not';
				// $scope.formAdata.state_abb= 'IN-MP';
				// $scope.formAdata.city_id= 1;
				// $scope.formAdata.company_id= 1;
				
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
				// $scope.formAdata.company_name = "a3bcd19";
				// $scope.formAdata.company_display_name = "a3bcdddddddd9";
				// $scope.formAdata.address1 ="35,gdsgsdgsa dsgasdf9";
				// $scope.formAdata.address2 = "sdgdsg,gdsagsdg9";
				// $scope.formAdata.pincode = 324369;
				// $scope.formAdata.pan= 3434545679;
				// $scope.formAdata.tin= 32343454569;
				// $scope.formAdata.vat_no= 32343454569;
				// $scope.formAdata.service_tax_no = 323434545672129;
				// $scope.formAdata.basic_currency_symbol= "INR";
				// $scope.formAdata.formal_name = "sdgdsgsaddsg7";
				// $scope.formAdata.no_of_decimal_points = 1;
				// $scope.formAdata.currency_symbol = 'suffix';
				// $scope.formAdata.document_name= "sagdsds7";
				// $scope.formAdata.document_url= "sdgsd\gdsgg.dsgds5";
				// $scope.formAdata.document_size= 5454449;
				// $scope.formAdata.document_format= 'png';
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.is_default = 'ok';
				// $scope.formAdata.state_abb= 'IN-GJ';
				// $scope.formAdata.city_id= 2;
				
				// formdata.append('company_name',$scope.formAdata.company_name);
				// formdata.append('company_display_name',$scope.formAdata.company_display_name);
				// formdata.append('address1',$scope.formAdata.address1 );
				// formdata.append('address2',$scope.formAdata.address2 );
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('vat_no',$scope.formAdata.vat_no);
				// formdata.append('service_tax_no',$scope.formAdata.service_tax_no);
				// formdata.append('basic_currency_symbol',$scope.formAdata.basic_currency_symbol);
				// formdata.append('formal_name',$scope.formAdata.formal_name);
				// formdata.append('no_of_decimal_points',$scope.formAdata.no_of_decimal_points);
				// formdata.append('currency_symbol',$scope.formAdata.currency_symbol);
				// formdata.append('document_name',$scope.formAdata.document_name);
				// formdata.append('document_url',$scope.formAdata.document_url);
				// formdata.append('document_size',$scope.formAdata.document_size);
				// formdata.append('document_format',$scope.formAdata.document_format);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('is_default',$scope.formAdata.is_default);
				// formdata.append('state_abb',$scope.formAdata.state_abb);
				// formdata.append('city_id',$scope.formAdata.city_id);
				
				//productCategory
				// $scope.formAdata.productCatName = "abc1234";
				// $scope.formAdata.product_cat_desc = "abcdddddddd";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.product_parent_cat_id = 0;
				
				// formdata.append('product_cat_name',$scope.formAdata.productCatName);
				// formdata.append('product_cat_desc',$scope.formAdata.product_cat_desc);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('product_parent_cat_id',$scope.formAdata.product_parent_cat_id);
				
				//productGroup
				// $scope.formAdata.product_group_name = "abc1234";
				// $scope.formAdata.product_group_desc = "abcdddddddd";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.product_group_parent_id = 0;
				
				// formdata.append('product_group_name',$scope.formAdata.product_group_name);
				// formdata.append('product_group_desc',$scope.formAdata.product_group_desc);
				// formdata.append('is_display',$scope.formAdata.is_display);
				// formdata.append('product_group_parent_id',$scope.formAdata.product_group_parent_id);
				
				var productGrpId = 1;
				// var productCatId = 5;
				// var companyId = 1;
				// var cityId = 2;
				// var stateAbb = "IN-GJ";
				// var branchId = 4;
				// var id = 42;
				// var url="http://localhost.scerp.com/Sample/Branch";
				// var url="http://localhost.scerp.com/Companies/Company";
				// var url="http://localhost.scerp.com/Branches/Branch/"+branchId;
				// var url="http://localhost.scerp.com/Branches/Branch/company/"+companyId;
				// var url="http://localhost.scerp.com/States/State/"+stateAbb;
				// var url="http://localhost.scerp.com/Cities/City/state/"+stateAbb;
				// var url="http://localhost.scerp.com/Cities/City/"+cityId;
				// var url="http://localhost.scerp.com/ProductCategories/ProductCategory/"+productCatId;
				var url="http://localhost.scerp.com/ProductGroups/ProductGroup/"+productGrpId;
				$http({
                        url: url,
                        // type:'patch',
						// method: 'post',
						// method: 'get',
						// method: "PATCH",
						method:'delete',
						processData: false,
                        headers: {'Content-Type': undefined},
                        data:formdata						
                        
                    }).success(function(data, status, headers, config) {
						// console.log(JSON.stringify(data));						
						console.log(data);	//post	//get	//update //delete
						$scope.status = status;
                    }).error(function(data, status, headers, config) {
                        $scope.status = status;
                    });
					
			}
			
         }]); 
		 
      </script> 
</html>


