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
				
				<table border="1" style="height:100px">
					<thead>
					
						<th>Image</th>
						<th>Student Name</th>
						<th>Gender</th>
						<th>Phone</th>
						<th>Address</th>		
						<th>Action </th>
					</thead>
					
					
					<tbody id="showdata">
					<tr>
					<td>{{txtimg}}</td>
					<td>{{txtname}}</td>
					<td>{{txtgender}}</td>
					<td>{{txtphone}}</td>
					<td>{{txtaddress}}</td>
					<td><a href="#" >Edit</a> <a href="#">Delete</a></td>
					
					</tr>
					</tbody>
					
					
				</table>
			
			
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
				console.log(value);
                 
                });
				
            }
			$scope.submit_form = function()
			{
				// var formdata = new FormData();
				// alert($scope.formAdata.txtname);
				$scope.formAdata.company_name = "abcd";
				$scope.formAdata.company_display_name = "abcdddddddd";
				$scope.formAdata.address1 ="35,gdsgsdgsa dsgasdf";
				$scope.formAdata.address2 = "sdgdsg,gdsagsdg";
				$scope.formAdata.pincode = 624364;
				$scope.formAdata.pan= 5434545676;
				$scope.formAdata.tin= 32343454567;
				$scope.formAdata.vat_no= 32343454567;
				$scope.formAdata.service_tax_no = 323434545672123;
				$scope.formAdata.basic_currency_symbol= "INR";
				$scope.formAdata.formal_name = "sdgdsgsaddsg";
				$scope.formAdata.no_of_decimal_points = 2;
				$scope.formAdata.currency_symbol = 'suffix';
				$scope.formAdata.document_name= "sagdsds";
				$scope.formAdata.document_url= "sdgsd\gdsgg.dsgds";
				$scope.formAdata.document_size= 5454444;
				$scope.formAdata.document_format= 'png';
				$scope.formAdata.is_display = 'yes';
				$scope.formAdata.is_default = 'ok';
				$scope.formAdata.state_abb= 'IN-MP';
				$scope.formAdata.city_id= 1;
				
				
				formdata.append('company_name',$scope.formAdata.company_name);
				formdata.append('company_display_name',$scope.formAdata.company_display_name);
				formdata.append('address1',$scope.formAdata.address1 );
				formdata.append('address2',$scope.formAdata.address2 );
				formdata.append('pincode',$scope.formAdata.pincode);
				formdata.append('pan',$scope.formAdata.pan);
				formdata.append('tin',$scope.formAdata.tin);
				formdata.append('vat_no',$scope.formAdata.vat_no);
				formdata.append('service_tax_no',$scope.formAdata.service_tax_no);
				formdata.append('basic_currency_symbol',$scope.formAdata.basic_currency_symbol);
				formdata.append('formal_name',$scope.formAdata.formal_name);
				formdata.append('no_of_decimal_points',$scope.formAdata.no_of_decimal_points);
				formdata.append('currency_symbol',$scope.formAdata.currency_symbol);
				formdata.append('document_name',$scope.formAdata.document_name);
				formdata.append('document_url',$scope.formAdata.document_url);
				formdata.append('document_size',$scope.formAdata.document_size);
				formdata.append('document_format',$scope.formAdata.document_format);
				formdata.append('is_display',$scope.formAdata.is_display);
				formdata.append('is_default',$scope.formAdata.is_default);
				formdata.append('state_abb',$scope.formAdata.state_abb);
				formdata.append('city_id',$scope.formAdata.city_id);
				
				// console.log(formdata);
				// var data  = angular.toJson(formdata);
				var companyId = 1;
				// var id = 42;
				// var url="http://localhost.scerp.com/Sample/Branch";
				var url="http://localhost.scerp.com/Companies/Company";
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
						// console.log(JSON.stringify(data));						
						console.log(data);	//post	//get				
						$scope.status = status;
                    }).error(function(data, status, headers, config) {
                        $scope.status = status;
                    });
					
			}
			
         }]); 
		 
      </script> 
</html>

