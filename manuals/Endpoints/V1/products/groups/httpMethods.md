##### Creates Products Group

##### `POST /product-groups`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Product Group Persistable Object
            }
            
+ Response

            {
                ... Standard Product Group Object
            }
    

##### Get Products Group           
            
##### `GET /product-groups/{productGroupId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Group Object
            }
            
##### `GET /product-groups`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Group Object
            }
            
**NOTES:** List all the product group available in the system

##### Updates Products Group  
       
##### `PATCH /product-groups/{productGroupId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Product Group Persistable Object
            }
            
            
##### Deletes Products Group
       
##### `DELETE /product-groups/{productGroupId}`
+ Response

			{
				HTTP_Status:200
			}

