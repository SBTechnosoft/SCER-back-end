##### Creates a Product transaction

##### `POST /productTrn`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Product transaction Persistable Object
            }
            
+ Response

            {
                ... Standard Product transaction Object
            }
    

##### Get a Product transaction           
            
##### `GET /productTrn/{productTrnId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Product transaction  Object
			}

**NOTES:** List all the product transact at particular product Transaction Id

##### `GET /products/{productsId}/productTrn`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productTransaction Object
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



##### Updates a Product transaction    
       
##### `PATCH /productTrn/{productTrnId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Product transaction Persistable Object
            }
            
            
##### Deletes a Product transaction    
       
##### `DELETE /productTrn/{productTrnId}`
+ Response

			{
				//Define reponse
			}