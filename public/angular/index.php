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
					 // $scope.file = value;
				});
				// console.log($scope.file);
            }
			$scope.submit_form = function()
			{
				
				// var formdata = new FormData();
				//state
				// $scope.formAdata.state_abb =" IN-KK ";
				// $scope.formAdata.state_name = " ss-fghd ";
				// $scope.formAdata.is_display = ' no ';
				
				// formdata.append('stateName',$scope.formAdata.state_name);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				
				//city
				// $scope.formAdata.city_name = " Anandddd ";
				// $scope.formAdata.is_display = ' no';
				// $scope.formAdata.state_abb = 'IN-AG';
				
				// formdata.append('cityName',$scope.formAdata.city_name);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				
				//branch
				// $scope.formAdata.branch_name = "abcc!cc&-_`#().\'11";
				// $scope.formAdata.branch_name = "Brainers";
				// $scope.formAdata.address1 ="35,abc2";
				// $scope.formAdata.address2 = "sdgd2";
				// $scope.formAdata.pincode = 324692;
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.is_default = 'not';
				// $scope.formAdata.state_abb= 'IN-AN';
				// $scope.formAdata.city_id= 1;
				// $scope.formAdata.company_id= 1;
				
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
				// $scope.formAdata.company_name = " Swaminarayan Cycle Store"; //0-9 not allow(error:allow)
				// $scope.formAdata.company_display_name = " SCS ";
				// $scope.formAdata.company_name = " abfcgfggfdldddhvh";
				// $scope.formAdata.address1 =" address";
				// $scope.formAdata.address2 = " address2 ";
				// $scope.formAdata.pincode = 311411;
				// $scope.formAdata.pan= ' qqqas1122a ';
				// $scope.formAdata.tin= ' 42aa3dgg778 ';
				// $scope.formAdata.vat_no= ' aadgg78w1a0 ';
				// $scope.formAdata.sgst= ' aadgg78w1a3 ';
				// $scope.formAdata.cgst= ' aadgg78w1a4 ';
				// $scope.formAdata.service_tax_no = ' 71j0rg778a22b01 ';
				// $scope.formAdata.basic_currency_symbol= " ALR ";
				// $scope.formAdata.formal_name = " qgfrd-&_().\'frtgfrta ";
				// $scope.formAdata.no_of_decimal_points ='4'; 
				// $scope.formAdata.currency_symbol = ' prefix ';
				// $scope.formAdata.is_display = ' no ';
				// $scope.formAdata.is_default = ' ok ';
				// $scope.formAdata.state_abb= ' IN-AN ';
				// $scope.formAdata.city_id= 1;
				
				
				// formdata.append('companyDisplayName',$scope.formAdata.company_display_name);
				// formdata.append('companyName',$scope.formAdata.company_name);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('vatNo',$scope.formAdata.vat_no);
				// formdata.append('sgst',$scope.formAdata.sgst);
				// formdata.append('cgst',$scope.formAdata.cgst);
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
				// $scope.formAdata.productCatName = "abcd";
				// $scope.formAdata.product_cat_desc = "abcdddddcc";
				// $scope.formAdata.is_display = 'yes ';
				// $scope.formAdata.product_parent_cat_id = 1;
				
				// formdata.append('productCategoryName',$scope.formAdata.productCatName);
				// formdata.append('productCategoryDescription',$scope.formAdata.product_cat_desc);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('productParentCategoryId',$scope.formAdata.product_parent_cat_id);
				
				//productGroup
				// $scope.formAdata.product_group_name = "a&b";
				// $scope.formAdata.product_group_desc = "abcdddd";
				// $scope.formAdata.is_display = 'yes ';
				// $scope.formAdata.product_group_parent_id = 0;
				
				// formdata.append('productGroupName',$scope.formAdata.product_group_name);
				// formdata.append('productGroupDescription',$scope.formAdata.product_group_desc);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('productGroupParentId',$scope.formAdata.product_group_parent_id);
				
				//product
				// $scope.formAdata.product_name = "Ranger";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.product_cat_id='1';
				// $scope.formAdata.measurement_unit='piece';
				// $scope.formAdata.color='RD';
				// $scope.formAdata.size='12T.()';
				// $scope.formAdata.purchase_price='120.09';
				// $scope.formAdata.wholesale_margin='12.00';
				// $scope.formAdata.wholesale_margin_flat='12.00';
				// $scope.formAdata.semi_wholesale_margin='10.90';
				// $scope.formAdata.vat='10.00';
				// $scope.formAdata.margin='10.00';
				// $scope.formAdata.margin_flat='10.00';
				// $scope.formAdata.mrp='140.00';
				// $scope.formAdata.productDescription='dfsdfsd';
				// $scope.formAdata.additionalTax='21.00';
				// $scope.formAdata.minimumStockLevel='25';
				// $scope.formAdata.product_group_id='1';
				// $scope.formAdata.company_id='1';
				// $scope.formAdata.branch_id='1';
				
				// formdata.append('productName',$scope.formAdata.product_name);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('measurementUnit',$scope.formAdata.measurement_unit);
				// formdata.append('color',$scope.formAdata.color);
				// formdata.append('size',$scope.formAdata.size);
				// formdata.append('purchasePrice',$scope.formAdata.purchase_price);
				// formdata.append('wholesaleMargin',$scope.formAdata.wholesale_margin);
				// formdata.append('wholesaleMarginFlat',$scope.formAdata.wholesale_margin_flat);
				// formdata.append('semiWholesaleMargin',$scope.formAdata.semi_wholesale_margin);
				// formdata.append('vat',$scope.formAdata.vat);
				// formdata.append('mrp',$scope.formAdata.mrp);
				// formdata.append('margin',$scope.formAdata.margin);
				// formdata.append('marginFlat',$scope.formAdata.margin_flat);
				// formdata.append('productDescription',$scope.formAdata.productDescription);
				// formdata.append('additionalTax',$scope.formAdata.additionalTax);
				// formdata.append('minimumStockLevel',$scope.formAdata.minimumStockLevel);
				// formdata.append('productCategoryId',$scope.formAdata.product_cat_id);
				// formdata.append('productGroupId',$scope.formAdata.product_group_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				// formdata.append('branchId',$scope.formAdata.branch_id);
				
				//template
				// $scope.formAdata.template_name = " abcffd ";
				// $scope.formAdata.template_type = ' general ';
				// $scope.formAdata.template_body=' <b> hiee</b> ';
				// $scope.formAdata.company_id=' 14 ';
				
				// formdata.append('templateName',$scope.formAdata.template_name);
				// formdata.append('templateType',$scope.formAdata.template_type);
				// formdata.append('templateBody',$scope.formAdata.template_body);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//invoice
				// $scope.formAdata.invoice_label = " abceeee ";
				// $scope.formAdata.invoice_type = ' prefix ';
				// $scope.formAdata.start_at=' 12 ';
				// $scope.formAdata.end_at=' 17 ';
				// $scope.formAdata.company_id='15 ';
				
				// formdata.append('invoiceLabel',$scope.formAdata.invoice_label);
				// formdata.append('invoiceType',$scope.formAdata.invoice_type);
				// formdata.append('startAt',$scope.formAdata.start_at);
				// formdata.append('endAt',$scope.formAdata.end_at);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//quotation
				// $scope.formAdata.quotation_label = " abcd ";
				// $scope.formAdata.quotation_type = ' prefix ';
				// $scope.formAdata.start_at=' 10 ';
				// $scope.formAdata.end_at=' 12 ';
				// $scope.formAdata.company_id='14 ';
				
				// formdata.append('quotationLabel',$scope.formAdata.quotation_label);
				// formdata.append('quotationType',$scope.formAdata.quotation_type);
				// formdata.append('startAt',$scope.formAdata.start_at);
				// formdata.append('endAt',$scope.formAdata.end_at);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//ledger
				// $scope.formAdata.ledger_name = " re98SD";
				// $scope.formAdata.alias = ' ddsafS ';
				// $scope.formAdata.inventory_affected=' no ';
				// $scope.formAdata.address1 =" sdx czS ";
				// $scope.formAdata.address2 = " aEbS ";
				// $scope.formAdata.contact_no = " 8765456752 ";
				// $scope.formAdata.email_id = " reemapatel25@gmail.co.in ";
				// $scope.formAdata.invoice_number = "dsf-jjj ";
				// $scope.formAdata.pan= ' qqqas1122d ';
				// $scope.formAdata.tin= ' 42aa3dgg774 ';
				// $scope.formAdata.cgst = ' 71j0rg778a22b05 ';
				// $scope.formAdata.sgst = ' 71j0rg778a22b06 ';
				// $scope.formAdata.balanceFlag = ' opening ';
				// $scope.formAdata.amount =1231;
				// $scope.formAdata.amountType = ' credit';
				// $scope.formAdata.state_abb= ' IN-AG ';
				// $scope.formAdata.city_id= '1';
				// $scope.formAdata.ledger_grp_id='9 ';
				// $scope.formAdata.company_id='1';
				
				// formdata.append('ledgerName',$scope.formAdata.ledger_name);
				// formdata.append('alias',$scope.formAdata.alias);
				// formdata.append('inventoryAffected',$scope.formAdata.inventory_affected);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('contactNo',$scope.formAdata.contact_no);
				// formdata.append('emailId',$scope.formAdata.email_id);
				// formdata.append('invoiceNumber',$scope.formAdata.invoice_number);
				// formdata.append('pan',$scope.formAdata.pan);
				// formdata.append('tin',$scope.formAdata.tin);
				// formdata.append('cgst',$scope.formAdata.cgst);
				// formdata.append('sgst',$scope.formAdata.sgst);
				// formdata.append('balanceFlag',$scope.formAdata.balanceFlag);
				// formdata.append('amount',$scope.formAdata.amount);
				// formdata.append('amountType',$scope.formAdata.amountType);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				// formdata.append('ledgerGroupId',$scope.formAdata.ledger_grp_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				
				//client
				// $scope.formAdata.client_name = "saasdsa";
				// $scope.formAdata.company_name = "abcd";
				// $scope.formAdata.contact_no = "888545345461";
				// $scope.formAdata.work_no = "87654534546";
				// $scope.formAdata.email_id = "abcd@a.fc";
				// $scope.formAdata.address1 ="";
				// $scope.formAdata.address2 = "";
				// $scope.formAdata.is_display = 'yes';
				// $scope.formAdata.state_abb= 'IN-AG';
				// $scope.formAdata.city_id= 1;
				
				// formdata.append('clientName',$scope.formAdata.client_name);
				// formdata.append('companyName',$scope.formAdata.company_name);
				// formdata.append('contactNo',$scope.formAdata.contact_no);
				// formdata.append('workNo',$scope.formAdata.work_no);
				// formdata.append('emailId',$scope.formAdata.email_id);
				// formdata.append('address1',$scope.formAdata.address1);
				// formdata.append('address2',$scope.formAdata.address2);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				
				//User
				// $scope.formAdata.user_name = "palak";
				// $scope.formAdata.user_type = "staff";
				// $scope.formAdata.contact_no = "87654534542";
				// $scope.formAdata.email_id = "palak1@gmail.com";
				// $scope.formAdata.password = "reema";
				// $scope.formAdata.address ="35,abc1";
				// $scope.formAdata.pincode = "876781";
				// $scope.formAdata.company_id= '14';
				// $scope.formAdata.branch_id= '6';
				// $scope.formAdata.state_abb= 'IN-AG';
				// $scope.formAdata.city_id= 1;
				
				// formdata.append('userName',$scope.formAdata.user_name);
				// formdata.append('userType',$scope.formAdata.user_type);
				// formdata.append('password',$scope.formAdata.password);
				// formdata.append('contactNo',$scope.formAdata.contact_no);
				// formdata.append('emailId',$scope.formAdata.email_id);
				// formdata.append('address',$scope.formAdata.address);
				// formdata.append('pincode',$scope.formAdata.pincode);
				// formdata.append('companyId',$scope.formAdata.company_id);
				// formdata.append('branchId',$scope.formAdata.branch_id);
				// formdata.append('stateAbb',$scope.formAdata.state_abb);
				// formdata.append('cityId',$scope.formAdata.city_id);
				
				//pdf generate
				// $scope.formAdata.saleId = "303";
				// formdata.append('saleId',$scope.formAdata.saleId);
				
				//bill-payment
				// $scope.formAdata.entryDate = "22-10-2017";
				// $scope.formAdata.amount = "60";
				// $scope.formAdata.paymentMode = "bank";
				// $scope.formAdata.bankName = "abc";
				// $scope.formAdata.checkNumber = "cashsdas2dsa";
				// $scope.formAdata.paymentTransaction = "payment";
				
				// formdata.append('entryDate',$scope.formAdata.entryDate);
				// formdata.append('amount',$scope.formAdata.amount);
				// formdata.append('paymentMode',$scope.formAdata.paymentMode);
				// formdata.append('bankName',$scope.formAdata.bankName);
				// formdata.append('checkNumber',$scope.formAdata.checkNumber);
				// formdata.append('paymentTransaction',$scope.formAdata.paymentTransaction);
				
				//Settings
				// $scope.formAdata.barcodeWidth = "3";
				// $scope.formAdata.barcodeHeight = "32";
				// formdata.append('barcodeWidth',$scope.formAdata.barcodeWidth);
				// formdata.append('barcodeHeight',$scope.formAdata.barcodeHeight);
				
				// $scope.formAdata.product_name = "Ranger";
				// $scope.formAdata.is_display = 'no';
				// $scope.formAdata.product_cat_id='1';
				// $scope.formAdata.measurement_unit='piece';
				// $scope.formAdata.color='RD';
				// $scope.formAdata.size='12T.()';
				// $scope.formAdata.purchase_price='120.09';
				// $scope.formAdata.wholesale_margin='12.00';
				// $scope.formAdata.wholesale_margin_flat='12.00';
				// $scope.formAdata.semi_wholesale_margin='10.90';
				// $scope.formAdata.vat='10.00';
				// $scope.formAdata.margin='10.00';
				// $scope.formAdata.margin_flat='10.00';
				// $scope.formAdata.mrp='140.00';
				// $scope.formAdata.productDescription='dfsdfsd';
				// $scope.formAdata.additionalTax='21.00';
				// $scope.formAdata.minimumStockLevel='25';
				// $scope.formAdata.product_group_id='1';
				// $scope.formAdata.company_id='1';
				// $scope.formAdata.branch_id='1';
				
				// formdata.append('productName',$scope.formAdata.product_name);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('measurementUnit',$scope.formAdata.measurement_unit);
				// formdata.append('color',$scope.formAdata.color);
				// formdata.append('size',$scope.formAdata.size);
				// formdata.append('purchasePrice',$scope.formAdata.purchase_price);
				// formdata.append('wholesaleMargin',$scope.formAdata.wholesale_margin);
				// formdata.append('wholesaleMarginFlat',$scope.formAdata.wholesale_margin_flat);
				// formdata.append('semiWholesaleMargin',$scope.formAdata.semi_wholesale_margin);
				// formdata.append('vat',$scope.formAdata.vat);
				// formdata.append('mrp',$scope.formAdata.mrp);
				// formdata.append('margin',$scope.formAdata.margin);
				// formdata.append('marginFlat',$scope.formAdata.margin_flat);
				// formdata.append('productDescription',$scope.formAdata.productDescription);
				// formdata.append('additionalTax',$scope.formAdata.additionalTax);
				// formdata.append('minimumStockLevel',$scope.formAdata.minimumStockLevel);
				// formdata.append('productCategoryId',$scope.formAdata.product_cat_id);
				// formdata.append('productGroupId',$scope.formAdata.product_group_id);
				// formdata.append('companyId',$scope.formAdata.company_id);
				// formdata.append('branchId',$scope.formAdata.branch_id);
				
				//multiple inventory insertion
				 // $scope.inventory=[{"productName":"Rangerdd" ,"isDisplay":'no',"productCategoryId":'1',"measurementUnit":'piece',
				 // "size":44,'color':'ddd','purchasePrice':1200,'wholesaleMargin':12,'wholesaleMarginFlat':10,'semiWholesaleMargin':5,
				 // 'vat':5,'mrp':5000,'margin':5,'marginFlat':5,'productDescription':'desc','additionalTax':5,'minimumStockLevel':5,
				 // 'productGroupId':1,'companyId':9,'branchId':9},{"productName":"RangerDTBgg" ,"isDisplay":'no',"productCategoryId":'1',"measurementUnit":'piece',
				 // "size":44,'color':'ccc','purchasePrice':1200,'wholesaleMargin':12,'wholesaleMarginFlat':10,'semiWholesaleMargin':5,
				 // 'vat':5,'mrp':5000,'margin':5,'marginFlat':5,'productDescription':'desc','additionalTax':5,'minimumStockLevel':5,
				 // 'productGroupId':1,'companyId':9,'branchId':9}]
				// for(var i=0;i<$scope.inventory.length;i++)
				// {
					// angular.forEach($scope.inventory[i], function (input,key) {
						// formdata.append(+i+'['+key+']',input);
						
					// });
				// }
				//productGroup
				// $scope.formAdata.product_group_name = "a&b";
				// $scope.formAdata.product_group_desc = "abcdddd";
				// $scope.formAdata.is_display = 'yes ';
				// $scope.formAdata.product_group_parent_id = 0;
				
				// formdata.append('productGroupName',$scope.formAdata.product_group_name);
				// formdata.append('productGroupDescription',$scope.formAdata.product_group_desc);
				// formdata.append('isDisplay',$scope.formAdata.is_display);
				// formdata.append('productGroupParentId',$scope.formAdata.product_group_parent_id);
				
				//multiple product-group insertion
				 // $scope.inventory=[{"productGroupName":"Raerddd" ,"isDisplay":'no',"productGroupDescription":'1',
				 // "productGroupParentId":'piece'},{"productGroupName":"Ranged" ,"isDisplay":'no',"productGroupDescription":'1',
				 // "productGroupParentId":'piece'}]
				// for(var i=0;i<$scope.inventory.length;i++)
				// {
					// angular.forEach($scope.inventory[i], function (input,key) {
						// formdata.append(+i+'['+key+']',input);
						
					// });
				// }
				
				//multiple product-category insertion
				 $scope.inventory=[{"productCategoryDescription":"Ranger@ddd" ,"isDisplay":'no',"productCategoryName":'asd',
				 "productParentCategoryId":'1'},{"productCategoryDescription":"Rangerdood" ,"isDisplay":'no1',"productCategoryName":'sds',
				 "productParentCategoryId":'1'},{"productCategoryDescription":"Rangerddood" ,"isDisplay":'no',"productCategoryName":'dsa',
				 "productParentCategoryId":'1'},{"productCategoryDescription":"Rangerdsod" ,"isDisplay":'no',"productCategoryName":'dsa',
				 "productParentCategoryId":'1'}]
				 //multiple product-category insertion
				 $scope.inventory1=['productCategoryDescription','isDisplay','productCategoryName','productParentCategoryId']
				
				for(var i=0;i<$scope.inventory.length;i++)
				{
					var j = 0;
					angular.forEach($scope.inventory[i], function (input,key) {
						
						formdata.append("data["+i+"]["+j+"]",input);
						j++;
						
					});
				}
				for(var i=0;i<$scope.inventory1.length;i++)
				{
					formdata.append("mapping["+i+"]",$scope.inventory1[i]);
				}
				
				// console.log(formdata);
				// special journal
				// $scope.user = [{"jfId":100,"data":[{"amount": 10 ,"amountType":" credit ","ledgerId":35},{"amount":2,"amountType":"credit","ledgerId":35},{"amount":12,"amountType":"debit","ledgerId":35}],"entryDate":"22-10-2015","companyId":83}];
				// formdata.append('jfId','100');
 				// formdata.append('companyId',83);
				
				//Authenticate
				// $scope.formAdata.email_id = "superadmin@gmail.com";
				// $scope.formAdata.password = "n4!sJdq0@Adv";
				// formdata.append('emailId',$scope.formAdata.email_id);
				// formdata.append('password',$scope.formAdata.password);
				
				// special journal
				// $scope.user = [{"jfId":100,"data":[{"amount": 10 ,"amountType":" credit ","ledgerId":35},{"amount":2,"amountType":"credit","ledgerId":35},{"amount":12,"amountType":"debit","ledgerId":35}],"entryDate":"22-10-2015","companyId":83}];
				// formdata.append('jfId','100');
 				// formdata.append('companyId',83);
 
 				// formdata.append('entryDate','32-13-2016');
 				// var json=[{"amount": 100 ,"amountType":" debit ","ledgerId":297},{"amount":100,"amountType":"credit","ledgerId":298}];

				// ,{"amount":200,"amountType":"credit","ledgerId":85}
  				// for(var i=0;i<json.length;i++){
   
  					// angular.forEach(json[i], function (value,key) {
   						
   					// formdata.append('data['+i+']['+key+']',value);
  					// });
    
  				// }
				
				//update....
				// formdata.append('companyId',15);
 
 				// formdata.append('entryDate','22-10-2016');
 				// var json=[{"amount": 10 ,"amountType":" credit ","ledgerId":90},{"amount":10,"amountType":"debit","ledgerId":88}];

 				  
  				// for(var i=0;i<json.length;i++){
   
  					// angular.forEach(json[i], function (value,key) {
   						
   					// formdata.append('data['+i+']['+key+']',value);
  					// });
    
  				// }

				
				// sale/purchase
				// $scope.user = [{"jfId":4,"data":[{"amount": 10 ,"amountType":" credit ","ledgerId":1},{"amount":2,"amountType":"credit","ledgerId":1},{"amount":12,"amountType":"debit","ledgerId":1}],"entryDate":"22-10-2015","companyId":14,
				// "inventory":[{"productId": 10 ,"discount":12,"discountType":"flat","price":1300,"qty":44},{"productId": 10 ,"discount":12,"discountType":"flat","price":1300,"qty":44}],"companyId":14,"transactionDate":"22-10-2015","invoiceNumber":23,"billNumber":2}];
				
				// formdata.append('jfId',10);
 				// formdata.append('companyId',10);
				// formdata.append('entryDate','20-04-2017');
				// var json=[{"amount":100,"amountType":" debit ","ledgerId":90},{"amount":100,"amountType":"credit","ledgerId":78}];
				
				// var inventory = [{"productId": 1 ,"discount":'0',"discountType":"flat","price":900,"qty":10},{"productId": 1 ,"discount":'0',"discountType":"percentage","price":1000,"qty":4}];
				// formdata.append('transactionDate','06-04-2017');
				// formdata.append('tax',100);
				// formdata.append('invoiceNumber',1);
				// formdata.append('billNumber',3);
				
				// for(var i=0;i<json.length;i++){
   
  					// angular.forEach(json[i], function (value,key) {
   						
   					// formdata.append('data['+i+']['+key+']',value);
  					// });
    
  				// }
				// for(var i=0;i<inventory.length;i++){
   
  					// angular.forEach(inventory[i], function (value,key) {
   						
   					// formdata.append('inventory['+i+']['+key+']',value);
  					// });
    
  				// }
				//transaction
				// $scope.user = [{"inventory":[{"productId":7 ,"discount":12,"discountType":"flat","price":1300,"qty":44},{"productId": 7 ,"discount":12,"discountType":"flat","price":1300,"qty":44}],"companyId":14,"transactionDate":"22-10-2015"}];
				
				//,"contactNo":"  	87654534544" extra
				// Bill PDF generate & insert bill data
				// $scope.user = [{"billData":[{"companyId":14,"entryDate":"22-10-2015","contactNo":"  	8765463456","emailId":"reemapatel25@gmail.co.in","companyName":"siliconbraine","clientName":"abce","invoiceNumber":"INV/2016-12/54","billNumber":2,"address1":"sfja,sa","address2":"dfsd,ds","stateAbb":"IN-AG","cityId":1,"inventory":[{"productId": 10 ,"discount":12,"discountType":"flat","price":1300,"qty":44},{"productId": 10 ,"discount":12,"discountType":"flat","price":1300,"qty":44}],"total":100,"tax":10,"grandTotal":232,"advance":100,"balance":232,"paymentMode":"cash","bankName":"abc","checkNumber":"abbb34eQ1G","remark":"adsfsf afasf"}]}];
				// 
				//9875647544
				// bill
				// $scope.user = [{"billData":[{"companyId":83,"entryDate":"22-12-2015","contactNo":"",
				// "workNo":"9875647344","isDisplay":"no","emailId":"reemapatel25@gmail.co.in","companyName":"siliconbrain",
				// "clientName":"fsdfsadssd","invoiceNumber":"INV/2016-15/30","address1":"sfja,sa","address2":"dfsd,ds",
				// "stateAbb":"IN-AG","cityId":1,"total":100,"tax":10,"grandTotal":134,"advance":100,"balance":10,
				// "paymentMode":"bank","bankName":"abc","checkNumber":"abbb34eQ1G","remark":"adsfsf afasf"}]}];
				
				// $scope.user = [{"billData":[{"entryDate":"22-10-2015","contactNo":"8224441535",
				// "workNo":"9875647344","isDisplay":"no","emailId":"reemapatel25@gmail.co.in","companyName":"siliconbrain",
				// "clientName":"palassaaa","address1":"sfja,sa","address2":"dfsd,ds",
				// "stateAbb":"IN-AG","cityId":1,"total":100,"tax":10,"grandTotal":134,"advance":100,"balance":10,
				// "paymentMode":"bank","bankName":"abc","checkNumber":"abbb34eQ1G","remark":"adsfsf afasf"}]}];
				
				// $scope.user = [{"billData":[{
				// "clientName":"reemggga"}]}];
				
				// $scope.inventory=[{"productId": 6 ,"discount":12,"discountType":"flat","price":1300,"qty":44,'color':'ddd',
				// 'frameNo':'fff'},{"productId": 6 ,"discount":12,"discountType":"flat","price":1300,"qty":40,'color':'hh',
				// 'frameNo':'dsfds','size':'12T'}];
				// angular.forEach($scope.user[0]['billData'][0], function (input,key) {
					
					// formdata.append(key,input);
				// });
				// for(var i=0;i<$scope.inventory.length;i++)
				// {
					// angular.forEach($scope.inventory[i], function (input,key) {
						
						// formdata.append('inventory['+i+']['+key+']',input);
					// });
				// }
				
				
				
				// var userId=9;
				// var clientId=2;
				// var productId =6;
				// var productGrpId = 11;
				// var productCatId = 18;
				// var companyId=10;
				// var cityId = 1;
				 // var stateAbb = "IN-AG";
				 // var branchId = 6;
				// var id = 42;
				// var templateId=1;
				// var bankId=2;
				// var invoiceId=7;
				// var quotationId=3;
				// var ledgerGrpId=9;
				// var ledgerId=82;
				// var jfId=52;
				// var journalId=207;
				// var saleId = 371;
				
				// var url="http://www.scerp1.com/settings";
				
				// var url="http://www.scerp1.com/product-groups/batch";
				var url="http://www.scerp1.com/product-categories/batch";
				// var url="http://www.scerp1.com/products/batch";
				
				// var url="http://www.scerp1.com/accounting/taxation/purchase-detail";  //purchase
				// var url="http://www.scerp1.com/accounting/taxation/purchase-tax";  //purchase tax
				// var url="http://www.scerp1.com/accounting/taxation/sale-tax/company/"+companyId;  //sale tax
				
				// var url="http://www.scerp1.com/accounting/cash-flow/company/"+companyId+"/export";  //pdf generate
				// var url="http://www.scerp1.com/accounting/profit-loss/company/"+companyId+"/export";  //pdf generate
				// var url="http://www.scerp1.com/accounting/balance-sheet/company/"+companyId+"/export";  //pdf generate
				
				// var url="http://www.scerp1.com/accounting/cash-flow/company/"+companyId;  //cash-flow
				// var url="http://www.scerp1.com/accounting/profit-loss/company/"+companyId;  //profit-loss
				// var url="http://www.scerp1.com/accounting/balance-sheet/company/"+companyId;  //balance-sheet
				// var url="http://www.scerp1.com/products/company/"+companyId+"/priceList";  //priceList
				// var url="http://www.scerp1.com/products/company/"+companyId+"/transaction/details"; //stock-register
			
				// var url="http://www.scerp1.com/products/company/"+companyId+"/transaction";
				// var url = "http://www.scerp1.com/logout/user/"+userId;
				
				// var url = "http://www.scerp1.com/users";
				// var url = "http://www.scerp1.com/users/"+userId;
				// var url = "http://www.scerp1.com/authenticate/users/"+userId;
				// var url = "http://www.scerp1.com/authenticate";
				// var url = "http://www.scerp1.com/users/email-address/"+emailId;
				// var url = "http://www.scerp1.com/accounting/trial-balance/company/"+companyId;
				// var url = "http://www.scerp1.com/accounting/trial-balance/company/"+companyId+"/export";
			
				
				// var url = "http://www.scerp1.com/accounting/bills/"+saleId;
				// var url = "http://www.scerp1.com/accounting/bills/"+saleId+"/payment";
				// var url = "http://www.scerp1.com/accounting/bills";
				// var url = "http://www.scerp1.com/accounting/bills/company/"+companyId;
				
				// var url = "http://www.scerp1.com/clients/"+clientId;
				// var url = "http://www.scerp1.com/clients";
				
				// var url = "http://www.scerp1.com/documents/bill";
				// var url="http://www.scerp1.com/products/inward"; 
				// var url="http://www.scerp1.com/products/outward";
				
				// var url="http://www.scerp1.com/accounting/journals";
				// var url="http://www.scerp1.com/accounting/journals/"+jfId;
				// var url="http://www.scerp1.com/accounting/journals/"+journalId;
				// var url="http://www.scerp1.com/accounting/journals/company/"+companyId;
				// var url="http://www.scerp1.com/accounting/journals/next";
				
				// var url="http://www.scerp1.com/accounting/ledgers/"+ledgerId+"/transactions";
				// var url="http://www.scerp1.com/accounting/ledgers/company/"+companyId;
				// var url="http://www.scerp1.com/accounting/ledgers/"+ledgerId;
				// var url="http://www.scerp1.com/accounting/ledgers/ledgerGrp/"+ledgerGrpId;
				// var url="http://www.scerp1.com/accounting/ledgers";
				
				// var url="http://www.scerp1.com/accounting/ledger-groups/"+ledgerGrpId;
				// var url="http://www.scerp1.com/accounting/ledger-groups";
				// SELECT max(invoice_id) invoice_id,invoice_label FROM `invoice_dtl` where company_id=1
				// var url="http://www.scerp1.com/settings/quotation-numbers/company/"+companyId+"/latest";
				// var url="http://www.scerp1.com/settings/quotation-numbers/"+quotationId;
				// var url="http://www.scerp1.com/settings/quotation-numbers";
				// var url="http://www.scerp1.com/settings/quotation-numbers/company/"+companyId;
				// var url="http://www.scerp1.com/settings/quotation-numbers/company/"+companyId+"/latest";
				// var url="http://www.scerp1.com/settings/invoice-numbers/company/"+companyId+"/latest";
				// var url="http://www.scerp1.com/settings/invoice-numbers";
				// var url="http://www.scerp1.com/settings/invoice-numbers/"+invoiceId;
				// var url="http://www.scerp1.com/settings/invoice-numbers/company/"+companyId;
				// var url="http://www.scerp1.com/banks/"+bankId;
				// var url="http://www.scerp1.com/banks";
				// var url="http://www.scerp1.com/settings/templates/"+templateId;
				// var url="http://www.scerp1.com/settings/templates/company/"+companyId;
				// var url="http://www.scerp1.com/settings/templates";
				// var url="http://www.scerp1.com/companies/"+companyId;
				// var url="http://www.scerp1.com/companies";	
				// var url="http://www.scerp1.com/branches";	
				 // var url="http://www.scerp1.com/branches/"+branchId;
				// var url="http://www.scerp1.com/branches/company/"+companyId;
				 // var url="http://www.scerp1.com/states/"+stateAbb;
				// var url="http://www.scerp1.com/states";
				// var url="http://www.scerp1.com/cities/state/"+stateAbb;
				 // var url="http://www.scerp1.com/cities";
				 // var url="http://www.scerp1.com/cities/"+cityId;
				// var url="http://www.scerp1.com/product-categories/"+productCatId;
				// var url="http://www.scerp1.com/product-categories";
				// var url="http://www.scerp1.com/product-groups";
				// var url="http://www.scerp1.com/product-groups/"+productGrpId;
				// var url="http://www.scerp1.com/products/"+productId;
				// var url="http://www.scerp1.com/products";
				// var url="http://www.scerp1.com/products/company/"+companyId+"/branch/"+branchId;
				// var url="http://www.scerp1.com/products/company/"+companyId+"/branch";
				// var url="http://www.scerp1.com/products/company/"+companyId;
				
				$http({
                        url: url,
                        // type:'patch',
                        // type:'get',
						 // method: 'get',
						 method: 'post',
						 // method: 'patch',
						// enctype:'multipart/formdata',
						 // _method: 'patch',
						// method: 'post',
						// method: "PATCH",
						// method:'delete',
						processData: false,
						
                        // headers: {'Content-Type': undefined,'fromDate':'2-10-2016','toDate':'30-12-2016','type':'sales'},
                        // headers: {'Content-Type': undefined,'authenticationToken':'eb22240d835fc40bfa6eb0f203d89372','type':'payment'},
                        headers: {'Content-Type': undefined,'authenticationToken':'eb22240d835fc40bfa6eb0f203d89372'},
                        // headers: {'Content-Type': undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6','salesType':'retail_sales','operation':'excel'},
                        // headers: {'Content-Type': undefined,'type':'sales','authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6'},
                        // headers: {'Content-Type': undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6',
						// 'type':'sales'},
						//,'nextSaleId':351  
						//,'productCategoryId':18,'productGroupId':10
                        // headers: {'Content-Type': undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6','type':'retail_sales','fromDate':'22-10-2015','toDate':'22-10-2015'}
                        // headers: {'Content-Type': undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6','productId':6,'fromDate':'21-10-2015','toDate':'20-01-2017','operation':'pdf'},
                        // headers: {'Content-Type': undefined,'authenticationToken':'eb22240d835fc40bfa6eb0f203d89372'},
                        // headers: {'Content-Type': undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6','productName':'Spiderman','color':'BK.s'},
                        // headers: {'Content-Type':undefined,'authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6','productCode':'ABC_ABC_AB_CYCFDJ_BKBK_122T'},
                        // headers: {'Content-Type':undefined,'authenticationToken':'eb22240d835fc40bfa6eb0f203d89372','fromDate':'01/04/2017','toDate':'28/04/2017'},
                        // headers: {'Content-Type':'application/x-www-form-urlencoded','authenticationToken':'b3315489a0b0cfdba014cf56a5deaeb6'},
						 data:formdata
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


