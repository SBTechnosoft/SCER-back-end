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
            
##### `GET /productCategory/{productCategoryId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productCategory Object
            }
            

##### `GET /productCategory`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productCategory Object
            }
            
**NOTES:** List All the Product Category available in the system

##### Updates a Products Category  
       
##### `PATCH /productCategory/{productCategoryId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard productCategory Persistable Object
            }
            
            
##### Deletes a Products Category 
       
##### `DELETE /productCategory/{productCategoryId}`
+ Response

			{
				//Define reponse
			}

