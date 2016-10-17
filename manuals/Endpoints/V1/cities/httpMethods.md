##### Creates City

##### `POST /cities`
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
    

##### Gets Cities           
            
##### `GET /cities/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard City Object
			}

**NOTES:** List all the city available in the system

##### `GET /cities/{cityId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard City Object
			} 

**NOTES:** Give only particular city at particular cityId 


##### `GET cities/states/{satateAbb}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard City Object
            }
            
**NOTES:** List the city at particular stateAbb[ISO_Code] 




##### Updates City    
       
##### `PATCH /cities/{cityId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard City Persistable Object
            }
            
            
##### Deletes City    
       
##### `DELETE /cities/{cityId}`
+ Response

			{
				HTTP_Status:200
			}