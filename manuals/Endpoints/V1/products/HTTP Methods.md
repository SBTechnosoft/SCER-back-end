##### Creates a Product

##### `POST /products`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Products Persistable Object
            }
            
+ Response

            {
                ... Standard Products Object
            }
    

##### Get a Products           
            
##### `GET /company/{CompanyId}/branch/{branchId}/products/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Product Object
			}

**NOTES:** List all the product in particular company and branch

##### `GET /products/{productId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard Product Object
			} 

**NOTES:** Give only particular product from productId  


##### `GET /products`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Products Object
            }
            
**NOTES:** List All the products available in the system


##### Updates a products    
       
##### `PATCH /products/{productId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Products Persistable Object
            }
            
            
##### Deletes a products    
       
##### `DELETE /products/{productId}`
+ Response

			{
				//Define reponse
			}