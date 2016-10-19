##### Creates InvoiceNumbers

##### `POST /settings/invoice-numbers/`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard InvoiceNumber Persistable Object
            }
            
+ Response

            {
                ... Standard InvoiceNumber Object
            }
    

##### Gets InvoiceNumber           
            
##### `GET /settings/invoice-numbers/{invoceId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard InvoiceNumber Object
			}

**NOTES:** List the invoice number as per particular invoice id 

##### `GET /settings/invoice-numbers/`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard InvoiceNumber Object
			} 

**NOTES:** List all the invoice-number available in the system


##### `GET /settings/invoice-numbers/company/{companyId}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard InvoiceNumber Object
            }
            
**NOTES:** List all the invoice-number available in the system

##### `GET /settings/invoice-numbers/company/{companyId}/latest/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard InvoiceNumber Object
            }
            
**NOTES:** list the latest invoice numbers for particular company id