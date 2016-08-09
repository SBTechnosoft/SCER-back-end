##### Creates a branch

##### `POST /branch`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard branch Persistable Object
            }
            
+ Response

            {
                ... Standard branch Object
            }
    

##### Get a branch           
            
##### `GET /company/{CompanyId}/branch`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard branch Object
			}

**NOTES:** List all the branch in particular company 

##### `GET /branch/{branchId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard branch Object
			} 

**NOTES:** Give only particular branch as per branchId  


##### `GET /branch`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard branch Object
            }
            
**NOTES:** List All the branch available in the system


##### Updates a branch    
       
##### `PATCH /branch/{branchId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard branch Persistable Object
            }
            
            
##### Deletes a branch    
       
##### `DELETE /branch/{branchId}`
+ Response

			{
				//Define reponse
			}