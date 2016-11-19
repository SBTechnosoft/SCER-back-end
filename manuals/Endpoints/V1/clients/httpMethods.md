##### Creates Client

##### `POST /clients`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Client Persistable Object
            }
            
+ Response

            {
                ... Standard Client Object
            }
    

##### Get Client
            
##### `GET /clients/{clientId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Client Object
			}

**NOTES:** List all the client in particular client-id