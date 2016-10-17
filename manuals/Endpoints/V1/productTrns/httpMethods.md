##### Creates Product Transaction

##### `POST /product-trns`
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
            
##### `GET /product-trns/{productTrnId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Product Transaction  Object
			}

**NOTES:** List all the product transact at particular product Transaction Id

##### `GET product-trns/products/{productId}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Transaction Object
            }
            
**NOTES:** List All the productTransaction at particular product_id

##### `GET /product-trns`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product transaction Object
            }
            
**NOTES:** List All the Product transaction available in the system



##### Updates Product Transaction    
       
##### `PATCH /product-trns/{productTrnId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Product Transaction Persistable Object
            }
            
            
##### Deletes  Product Transaction    
       
##### `DELETE /product-trns/{productTrnId}`
+ Response

			{
				HTTP_Status:200
			}