##### Creates Journals

##### `POST /accounting/journals/`
+ Header
	- Authentication Token

+ Body

            {
				[
					{
						... Standard Journals Persistable Object
					}
				]
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }

##### Gets Journals           
            
##### `GET /accounting/journals/next/`
+ Header 
	- Authentication Token

+ Error Message

			{
				... Error Message
			}
+ Response

			{
				... Standard Journal Object
			}

**NOTES:** provide next increment journal folio id(jf_id)
