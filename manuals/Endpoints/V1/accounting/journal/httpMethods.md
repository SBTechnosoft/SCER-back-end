##### Creates Journals

##### `POST /accounting/journals/`
+ Header
	- Authentication Token
	- 'type':'sales'
	- 'type':'purchase'
+ Body

            {
				[
					{
						... Standard Journals Persistable Object,
						... Standard Inventory Persistable Object
						
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

##### `POST /accounting/journals/`
+ Header
	- Authentication Token
	- "fromDate"
	- "toDate"

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
				... Standard JournalFolioId Object
			}

**NOTES:** provide next increment journal folio id(jf_id)
