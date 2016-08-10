##### Creates Product Transaction

##### `POST /productTrn`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Product Transaction Persistable Object
            }
            
+ Response

            {
                ... Standard Product Transaction Object
            }
    

##### Get Product Transaction           
            
##### `GET /productTrn/{productTrnId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Product Transaction  Object
			}

**NOTES:** List all the product transact at particular product Transaction Id

##### `GET /products/{productsId}/productTrn`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Transaction Object
            }
            
**NOTES:** List All the productTransaction at particular product_id

##### `GET /productTrn`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product transaction Object
            }
            
**NOTES:** List All the Product transaction available in the system



##### Updates Product Transaction    
       
##### `PATCH /productTrn/{productTrnId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Product Transaction Persistable Object
            }
            
            
##### Deletes  Product Transaction    
       
##### `DELETE /productTrn/{productTrnId}`
+ Response

			{
				HTTP_Status:200
			}