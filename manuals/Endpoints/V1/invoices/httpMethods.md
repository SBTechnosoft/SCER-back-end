##### Creates Invoices

##### `POST /invoices`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Invoice Persistable Object
            }
            
+ Response

            {
                ... Standard Invoice Object
            }
    

##### Gets Invoice           
            
##### `GET /invoices/{invoceId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Invoice Object
			}

**NOTES:** List the invoice as per particular invoice id 

##### `GET invoices`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard Invoice Object
			} 

**NOTES:** List all the invoice available in the system


##### `GET invoices/company/{companyId}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Invoice Object
            }
            
**NOTES:** List all the invoice available in the system