##### Creates Branch

##### `POST /branch`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Branch Persistable Object
            }
            
+ Response

            {
                ... Standard Branch Object
            }
    

##### Gets Branch           
            
##### `GET /company/{CompanyId}/branch`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Branch Object
			}

**NOTES:** List all the branch in particular company 

##### `GET /branch/{branchId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard Branch Object
			} 

**NOTES:** Give only particular branch as per branchId  


##### `GET /branch`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Branch Object
            }
            
**NOTES:** List all the branch available in the system


##### Updates Branch    
       
##### `PATCH /branch/{branchId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Branch Persistable Object
            }
            
            
##### Deletes Branch    
       
##### `DELETE /branch/{branchId}`
+ Response

			{
				HTTP_Status:200
			}