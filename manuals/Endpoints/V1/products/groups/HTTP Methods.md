

##### Creates a products Group

##### `POST /productGroup`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard productGroup Persistable Object
            }
            
+ Response

            {
                ... Standard productGroup Object
            }
    

##### Get a products Group           
            
##### `GET /products/{productId}/productGroup`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard productGroup Object
			}

**NOTES:** List all the products Group in particular product

##### `GET /products/{productId}/productGroup/{productGroupId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard productGroup Object
			} 

**NOTES:** Give only particular productGroup of the particular product 


##### `GET /productGroup/{productGroupId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productGroup Object
            }
            
**NOTES:** List All the productGroup of productGroupId

##### `GET /productGroup`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productGroup Object
            }
            
**NOTES:** List All the productGroup available in the system

##### Updates a products Group  
       
##### `PATCH /productGroup/{productGroupId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard productGroup Persistable Object
            }
            
            
##### Deletes a products Group
       
##### `DELETE /productGroup/{productGroupId}`
+ Response

			{
				//Define reponse
			}

