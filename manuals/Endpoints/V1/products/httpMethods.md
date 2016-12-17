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
            
##### `GET products/company/{CompanyId}
+ Header 
	- Authentication Token
	-'productName':''
+ Error Message

			{
				... Error Message
			}
+ Response

			{
				... Standard Product Object
			}

**NOTES:** List all the product in particular company/list the product as per given name and companyId

##### `GET products/branch/{branchId}`
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

**NOTES:** List all the product in particular branch

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

+ Error Message

			{
				... Error Message
			}
+ Response

            {
                ... Standard Products Object
            }
            
**NOTES:** List All the products available in the system




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