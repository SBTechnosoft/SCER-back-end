##### Creates Authentication Token

##### `POST /authenticate`
+ Header
	- Authentication Token


+ Body
			{
                ... Standard Authentication Persistable Object
            }
+ Error Message

			{
				... Error Message
			}               
+ Response

            {
                "authenticationToken":string
			}