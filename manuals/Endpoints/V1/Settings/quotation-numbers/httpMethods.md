##### Creates QuotationNumebrs

##### `POST /settings/quotation-numebrs/`
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
            
##### `GET /settings/quotation-numbers/{quotationId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard QuotationNumber Object
			}

**NOTES:** List the Quotation Number as per particular Quotation id 

##### `GET /settings/quotation-numbers/`
+ Header
	- Authentication Token

+ Response 

			{
				... Standard QuotationNumber Object
			} 

**NOTES:** List all the QuotationNumber available in the system


##### `GET /settings/quotation-numbers/company/{companyId}`
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