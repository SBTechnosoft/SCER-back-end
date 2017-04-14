##### Creates Product

##### `POST /products`
+ Header
	- Authentication Token
	
+ Body

            {
                ... Standard Products Persistable Object
            }
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }
			
##### `POST /products/batch`
+ Header
	- Authentication Token
	
+ Body

            {
                ... Standard Products Persistable Object
            }
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }

##### `POST /products/inward`
+ Header
	- Authentication Token


+ Body
			[
				{
					... Standard Products Transaction Persistable Object
				}
			]
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }			

##### `POST /products/outward`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Products Transaction Persistable Object
            }
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }
			
##### Gets Products           
            
##### `GET products/company/{CompanyId?}/branch/{branchId?}`
+ Header 
	- Authentication Token
	
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				... Standard Product Object
			}

**NOTES:** List all the product in particular company/&branch

##### `GET products/company/{companyId}`
+ Header 
	- Authentication Token
	- 'productName':'',
	- 'color':'',
	- 'size':'',
	- 'productCategoryId':'',
	- 'productGroupId':'',
	- 'salesType':'retail_sales/whole_sales',
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				{
					... Standard Product Object
				}
				...
			}

**NOTES:** List the product as per given parameter in header and companyId(searching and price-list)

##### `GET products/company/{companyId}/priceList`
+ Header 
	- Authentication Token
	- 'operation':'pdf/excel',
	- 'productCategoryId':''
	- 'productGroupId':''
	- 'salesType':'retail_sales/whole_sales'
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				"documentPath":''			
			}

**NOTES:** List the product-trn data as per given parameter in header and companyId (price-list)

##### `GET products/company/{companyId}/transaction/details`
+ Header 
	- Authentication Token
	- 'operation':'pdf/excel'
	- 'fromDate':''
	- 'toDate':''
	- 'productId':''
	- 'productCategoryId':''
	- 'productGroupId':''
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				"documentPath":''			
			}

**NOTES:** List the product-trn data as per given parameter in header and companyId (stock-register)

##### `GET products/company/{companyId}/transaction`
+ Header 
	- 'authenticationToken':''
	- 'fromDate':''
	- 'toDate':''
	- 'productId':''
	- 'productGroupId':''
	- 'productCategoryId':''
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				[
					... Standard Product Transaction Object
				]
				...
			}

**NOTES:** List the product-transaction data as per given data

##### `GET /products/{productId}`
+ Header
	- Authentication Token

+ Error Message

			{
				... Error Message
			}
+ Response 

			{
				... Standard Product Object
			} 

**NOTES:** Give only particular product from productId  

##### `GET /products`
+ Header
	- Authentication Token
	- 'productCode':'String'
+ Error Message

			{
				... Error Message
			}
+ Response

            {
                ... Standard Products Object
            }
            
**NOTES:** List All the products available in the system

##### `GET /products`
+ Header
	- Authentication Token,
	- 'jfId':''
+ Error Message

			{
				... Error Message
			}
+ Response

            {
                ... Standard Product Transaction Object
            }
            
**NOTES:** List All the product-transaction data available as per given jfId in the system

##### Updates Products    
       
##### `PATCH /products/{productId}`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Products Persistable Object
            }
+ Error Message

			{
				... Error Message
			}            
+ Response

			{
				... HTTP_Status:200
			}            
##### Deletes Products    
       
##### `DELETE /products/{productId}`

+ Error Message

			{
				... Error Message
			}
+ Response

			{
				... HTTP_Status:200
			}