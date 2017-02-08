##### Creates Bill

##### `POST /accounting/bills/`
+ Header
	- Authentication Token
	- 'salesType':'retail_sales/whole_sales'
+ Body

            {
                ... Standard Bill Persistable Object
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                "documentPath":''
            }
			
##### `GET /accounting/bills/company/{companyId}`
+ Header
	- Authentication Token
	- 'salesType':'retail_sales/whole_sales'
	- "fromDate":"date"
	- "toDate":"date"
+ Body

            {
                ... Standard Bill Object
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }
			
##### `DELETE /accounting/bills/{saleId}`
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
			
