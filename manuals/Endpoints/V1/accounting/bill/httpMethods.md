##### Creates Bill

##### `POST /accounting/bills/`
+ Header
	- Authentication Token
	- 'type':'sales'
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
                ... HTTP_Status:200
            }