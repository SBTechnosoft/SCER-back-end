##### Creates Bill

##### `POST /accounting/bill/`
+ Header
	- Authentication Token
	- 'type':'sales'
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