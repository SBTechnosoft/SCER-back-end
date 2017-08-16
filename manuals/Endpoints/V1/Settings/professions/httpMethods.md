##### Creates Profession

##### `POST /settings/professions/`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Profession Persistable Object
            }
+ Error Message

			{
				... Error Message
			}             
+ Response

            {
               ... HTTP_Status:200
            }
    
##### Gets Profession           
            
##### `GET /settings/professions/{professionId}`
+ Header 
	- Authentication Token

+ Error Message

			{
				... Error Message
			} 
+ Response

			{
				... Standard Profession Object
			}

**NOTES:** List all the Profession as per given template_id 

##### `GET /settings/professions`
+ Header 
	- Authentication Token
	
+ Error Message

			{
				... Error Message
			} 
+ Response

			{
				... Standard Profession Object
			}

**NOTES:** List all the professions 

##### `GET /settings/professions/company/{companyId}`
+ Header 
	- Authentication Token
	
+ Error Message

			{
				... Error Message
			} 
+ Response

			{
				... Standard Profession Object
			}

**NOTES:** List all the templates as per given company-id

##### Updates Profession    
       
##### `UPDATE /settings/professions/{professionId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Profession Persistable Object
            }       
+ Error Message

			{
				... Error Message
			}             
+ Response

            {
               ... HTTP_Status:200
            }

##### `DELETE /settings/professions/{professionId}`
+ Header
	- Authentication Token
     
+ Error Message

			{
				... Error Message
			}             
+ Response

            {
               ... HTTP_Status:200
            }