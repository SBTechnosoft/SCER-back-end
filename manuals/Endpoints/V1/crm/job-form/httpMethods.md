##### Creates Job-Form

##### `POST crm/job-form`
+ Header
	- Authentication Token
	
+ Body

            {
                ... Standard Job-Form Persistable Object
            }
+ Error Message

			{
				... Error Message
			}             
+ Response

            {
                ... HTTP_Status:200
            }
			
##### Gets Job-Form Data           
            
##### `GET crm/job-form`
+ Header 
	- Authentication Token

+ Error Message

			{
				... Error Message
			} 
+ Response

			{
				... Standard Job-Form Object
			}

**NOTES:** List all the Job-Form data