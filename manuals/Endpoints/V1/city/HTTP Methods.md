##### Creates a city

##### `POST /city`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard city Persistable Object
            }
            
+ Response

            {
                ... Standard city Object
            }
    

##### Get a city           
            
##### `GET /city/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard city Object
			}

**NOTES:** List All the city available in the system

##### `GET /city/{cityId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard city Object
			} 

**NOTES:** Give only particular city at particular cityId 


##### `GET /state/{satateAbb}/city`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard city Object
            }
            
**NOTES:** List the city at particular stateAbb[ISO_Code] 




##### Updates a city    
       
##### `PATCH /city/{cityId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard city Persistable Object
            }
            
            
##### Deletes a city    
       
##### `DELETE /city/{cityId}`
+ Response

			{
				//Define reponse
			}