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
			
##### `POST crm/job-form`
+ Header
	- Authentication Token
	- "operation":'generateBill'
	
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
                "documentPath":''
            }