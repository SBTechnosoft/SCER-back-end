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
    
##### Gets Company           
            
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

##### Updates Company    
       
##### `PATCH /companies/{companyId}`
+ Header
	- Authentication Token
+ Body

            {
                ... Standard Company Persistable Object
            }
            
            
##### Deletes Company    
       
##### `DELETE /companies/{companyId}`
+ Response

			{
				HTTP_Status:200
			}