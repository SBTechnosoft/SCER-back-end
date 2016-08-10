##### Creates Products Category

##### `POST /productCategory`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard ProductCategory Persistable Object
            }
            
+ Response

            {
                ... Standard ProductCategory Object
            }
    

##### Get Products Category           
            
##### `GET /productCategory/{productCategoryId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard ProductCategory Object
            }
            

##### `GET /productCategory`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard ProductCategory Object
            }
            
**NOTES:** List All the Product Category available in the system

##### Updates a Products Category  
       
##### `PATCH /productCategory/{productCategoryId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard ProductCategory Persistable Object
            }
            
            
##### Deletes Products Category 
       
##### `DELETE /productCategory/{productCategoryId}`
+ Response

			{
				HTTP_Status:200
			
			}

