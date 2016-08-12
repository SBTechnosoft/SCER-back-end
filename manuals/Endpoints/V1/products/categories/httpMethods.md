##### Creates Products Category

##### `POST /product-categories`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Product Category Persistable Object
            }
            
+ Response

            {
                ... Standard ProductCategory Object
            }
    

##### Get Products Category           
            
##### `GET /product-categories/{productCategoryId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard ProductCategory Object
            }
            

##### `GET /product-categories`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard ProductCategory Object
            }
            
**NOTES:** List All the Product Category available in the system

##### Updates a Products Category  
       
##### `PATCH /product-category/{productCategoryId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard ProductCategory Persistable Object
            }
            
            
##### Deletes Products Category 
       
##### `DELETE /product-categories/{productCategoryId}`
+ Response

			{
				HTTP_Status:200
			}

