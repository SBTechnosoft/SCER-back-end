##### Creates State

##### `POST /state`
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
            
##### `GET /state/{stateAbb}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard State Object
			}

**NOTES:** List all the state in particular stateAbb(ISO_Code)

##### `GET /state`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard State Object
			} 

**NOTES:** List All the state available in the system



##### Updates State    
       
##### `PATCH /state/{stateAbb}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard State Persistable Object
            }
            
            
##### Deletes State    
       
##### `DELETE /state/{stateAbb}`
+ Response

			{
				HTTP_Status:200
			}