##### Creates State

##### `POST /states`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard State Persistable Object
            }
            
+ Response

            {
                ... Standard State Object
            }
    

##### Get State           
            
##### `GET /states/{stateAbb}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard State Object
			}

**NOTES:** List all the state in particular stateAbb(ISO_Code)

##### `GET /states`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard State Object
			} 

**NOTES:** List All the state available in the system



##### Updates State    
       
##### `PATCH /states/{stateAbb}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard State Persistable Object
            }
            
            
##### Deletes State    
       
##### `DELETE /states/{stateAbb}`
+ Response

			{
				HTTP_Status:200
			}