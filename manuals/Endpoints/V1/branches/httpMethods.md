##### Creates Branch

##### `POST /branches`
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
            
##### `GET branches/company/{CompanyId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Branch Object
			}

**NOTES:** List all the branch in particular company 

##### `GET branches/{branchId}`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard Branch Object
			} 

**NOTES:** Give only particular branch as per branchId  


##### `GET /branches`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Branch Object
            }
            
**NOTES:** List all the branch available in the system


##### Updates Branch    
       
##### `PATCH branches/{branchId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Branch Persistable Object
            }
            
            
##### Deletes Branch    
       
##### `DELETE branches/{branchId}`
+ Response

			{
				HTTP_Status:200
			}