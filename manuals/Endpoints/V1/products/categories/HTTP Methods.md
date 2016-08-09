


##### Creates a Products Category

##### `POST /productCategory`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard productCategory Persistable Object
            }
            
+ Response

            {
                ... Standard productCategory Object
            }
    

##### Get a Products Category           
            
##### `GET /products/{productId}/productCategory`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard productCategory Object
			}

**NOTES:** List all the product Category in particular product

##### `GET /products/{productId}/productCategory/{productCategoryId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard productCategory Object
			} 

**NOTES:** Give only particular productCategory of the particular product 


##### `GET /productCategory/{productCategoryId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productCategory Object
            }
            
**NOTES:** List All the productCategory of productCategoryId

##### `GET /productCategory`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productCategory Object
            }
            
**NOTES:** List All the productCategory available in the system

##### Updates a Products Category  
       
##### `PATCH /productCategory/{productCategoryId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard productCategory Persistable Object
            }
            
            
##### Deletes a Products Category 
       
##### `DELETE /productCategory/{productGroupId}`
+ Response

			{
				//Define reponse
			}

