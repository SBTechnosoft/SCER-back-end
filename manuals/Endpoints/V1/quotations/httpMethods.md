##### Creates Quotations

##### `POST /Quotations`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Quotation Persistable Object
            }
            
+ Response

            {
                ... Standard Quotation Object
            }
    

##### Gets Quotation           
            
##### `GET /Quotations/{QuotationId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Quotation Object
			}

**NOTES:** List the Quotation as per particular Quotation id 

##### `GET Quotations`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard Quotation Object
			} 

**NOTES:** List all the Quotation available in the system


##### `GET Quotations/company/{companyId}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard Quotation Object
            }
            
**NOTES:** List all the Quotation available in the system