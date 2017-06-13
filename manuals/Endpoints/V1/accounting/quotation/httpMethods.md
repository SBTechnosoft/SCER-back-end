##### Creates Quotation

##### `POST /accounting/quotations/`
+ Header
	- Authentication Token
+ Body

            {
                ... Standard Quotation Persistable Object
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                "documentPath":''
            }
			
##### `GET /accounting/quotations/`
+ Header
	- Authentication Token
	- "quotationNumber":"string"
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Quotation Object
            }