##### Creates City

##### `POST /city`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard City Persistable Object
            }
            
+ Response

            {
                ... Standard City Object
            }
    

##### Gets City           
            
##### `GET /city/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard City Object
			}

**NOTES:** List all the city available in the system

##### `GET /city/{cityId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard City Object
			} 

**NOTES:** Give only particular city at particular cityId 


##### `GET /state/{satateAbb}/city`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard City Object
            }
            
**NOTES:** List the city at particular stateAbb[ISO_Code] 




##### Updates City    
       
##### `PATCH /city/{cityId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard City Persistable Object
            }
            
            
##### Deletes City    
       
##### `DELETE /city/{cityId}`
+ Response

			{
				HTTP_Status:200
			}