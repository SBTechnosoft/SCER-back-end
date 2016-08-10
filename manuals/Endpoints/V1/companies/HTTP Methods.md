##### Creates Company

##### `POST /companies`

+ Body

            {
                ... Standard Company Persistable Object
            }
            
+ Response

            {
                ... Standard Company Object
            }
    
##### Gets a company           
            
##### `GET /companies/{companyId}`

+ Response

            {
                ... Standard Company Object
            }
**NOTES:** List the company of particular companyId

##### `GET /companies`

+ Response

            {
                ... Standard Company Object
            }
            

**NOTES:** List All the company available in the system         

##### Updates a company    
       
##### `PATCH /companies/{companyId}`
+ Header
	- Authentication Token
+ Body

            {
                ... Standard Company Persistable Object
            }
            
            
##### Deletes a company    
       
##### `DELETE /companies/{companyId}`
+ Response

			{
				//Define reponse
			}