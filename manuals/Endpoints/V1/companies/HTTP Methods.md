##### Creates an appraiser company

##### `POST /company`

+ Body

            {
                ... Standard Appraiser Company Persistable Object
            }
            
+ Response

            {
                ... Standard Appraiser Company Object
            }
    
##### Gets a company           
            
##### `GET /company/{companyId}`

+ Response

            {
                ... Standard Appraiser Company Object
            }
**NOTES:** List the company of particular companyId

##### `GET /company`

+ Response

            {
                ... Standard Appraiser Company Object
            }
            

**NOTES:** List All the company available in the system         


##### `GET branch/{branchId}/company`

+ Response

            {
                ... Standard Appraiser Company Object
            }
           
**NOTES:** List the company of particular branch

##### Updates a company    
       
##### `PATCH /company/{companyId}`
+ Header
	- Authentication Token
+ Body

            {
                ... Standard Appraiser Company Persistable Object
            }
            
            
##### Deletes a company    
       
##### `DELETE /company/{companyId}`
+ Response

			{
				//Define reponse
			}