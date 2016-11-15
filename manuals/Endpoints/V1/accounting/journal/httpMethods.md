##### Creates Journals

##### `POST /accounting/journals/`
+ Header
	- Authentication Token

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

##### `GET /accounting/journals/fromDate/toDate`
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

**NOTES:** provide the journal information between given date or given current year information