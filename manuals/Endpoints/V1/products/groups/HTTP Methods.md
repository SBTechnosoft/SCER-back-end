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
            
##### `GET /productGroup/{productGroupId}/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productGroup Object
            }
            
##### `GET /productGroup`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productGroup Object
            }
            
**NOTES:** List All the Product Group available in the system

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

