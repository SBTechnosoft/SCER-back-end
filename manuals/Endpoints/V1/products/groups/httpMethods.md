##### Creates Products Group

##### `POST /productGroup`
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
            
##### `GET /productGroup/{productGroupId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Group Object
            }
            
##### `GET /productGroup`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Product Group Object
            }
            
**NOTES:** List all the product group available in the system

##### Updates Products Group  
       
##### `PATCH /productGroup/{productGroupId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Product Group Persistable Object
            }
            
            
##### Deletes Products Group
       
##### `DELETE /productGroup/{productGroupId}`
+ Response

			{
				HTTP_Status:200
			}

