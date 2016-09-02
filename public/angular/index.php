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
				// console.log($files);
				// imageFile = $files;
                angular.forEach($files, function (value,key) {
					// imageFile = value;
					formdata.append('file[]',value);
					console.log(value);
                 
                });
				
            }
			$scope.submit_form = function()
			{
				// var formdata = new FormData();
				// alert($scope.formAdata.txtname);
				formdata.append('txtname',$scope.formAdata.txtname);
				formdata.append('txtphone',$scope.formAdata.txtphone);
				
				// console.log(formdata);
				// var data  = angular.toJson(formdata);
				var companyId = 1;
				var id = 40;
				var url="http://localhost.scerp.com/Sample/Branch";
				// var url="http://localhost.scerp.com/Companies/Company/"+companyId;
				$http({
                        url: url,
                        // type:'patch',
						// method: 'post',
						method: 'get',
						// method: "PATCH",
						// method:'delete',
						processData: false,
                        headers: {'Content-Type': undefined},
                        // data:formdata						
                        
                    }).success(function(data, status, headers, config) {
						// console.log(JSON.stringify(data));						
						console.log(data);						
						$scope.status = status;
                    }).error(function(data, status, headers, config) {
                        $scope.status = status;
                    });
					
			}
			//$scope.imgsrc = ngFileSelect;
         }]); 
		 /*app.directive('fileModel',['$parse',function($parse){
			 
			 return{
				 
				 restrict: 'A',
				 link: function(scope,element,attrs){
					 
					 var model = $parse(attrs,fileModel);
					 var modelSetter = model.assign;
					 
					 element.bind('change',function(){
						 
						 $scope.$apply(function(){
							 
							 modelSetter(scope,elemets[0],files[0]);
						 })
					 })
					 
				 }
			 }
			 
		 }]);
		 app.service('multipartform',['$http',function($http){
			 
			 this.post = function(uploadUrl,data){
				 
				 var fd= new FormData();
				 
				 for(var key in data)
					 fd.append(key,data[key]);
				 
				 $http.post(uploadUrl,fd,{
					 
					 transformRequest:angular.indentity,
					 header: {'Content-Type': undefined }
				 })
			 }
			 
		 }]);*/
		 
		 
      </script> 
  
	

</html>


