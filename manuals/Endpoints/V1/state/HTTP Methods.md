##### Creates a state

##### `POST /state`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard state Persistable Object
            }
            
+ Response

            {
                ... Standard state Object
            }
    

##### Get a state           
            
##### `GET /state/{stateAbb}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard state Object
			}

**NOTES:** List all the state in particular stateAbb(ISO_Code)

##### `GET /state`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard state Object
			} 

**NOTES:** List All the state available in the system



##### Updates a state    
       
##### `PATCH /state/{stateAbb}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard state Persistable Object
            }
            
            
##### Deletes a state    
       
##### `DELETE /state/{stateAbb}`
+ Response

			{
				//Define reponse
			}