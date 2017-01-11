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
                ... Standard Bill Object
            }
			
##### `GET /accounting/bills/`
+ Header
	- Authentication Token
	- 'salesType':'retail_sales/whole_sales'
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
			
