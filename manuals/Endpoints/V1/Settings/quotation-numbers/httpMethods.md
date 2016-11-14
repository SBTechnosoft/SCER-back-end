##### Creates QuotationNumebrs

##### `POST /settings/QuotationNumebrs/`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard QuotationNumber Persistable Object
            }
            
+ Response

            {
                ... Standard QuotationNumber Object
            }
    

##### Gets QuotationNumber           
            
##### `GET /settings/QuotationNumebrs/{QuotationId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard QuotationNumber Object
			}

**NOTES:** List the Quotation Number as per particular Quotation id 

##### `GET /settings/QuotationNumebrs/`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard QuotationNumber Object
			} 

**NOTES:** List all the QuotationNumber available in the system


##### `GET /settings/QuotationNumebrs/company/{companyId}`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard QuotationNumber Object
            }
            
**NOTES:** List all the QuotationNumber available in the system

##### `GET /settings/quotation-numbers/company/{companyId}/latest/`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard QuotationNumber Object
            }
            
**NOTES:** list the latest Quotation Numbers for particular company id