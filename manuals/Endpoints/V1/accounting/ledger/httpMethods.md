##### Creates Ledgers

##### `POST /accounting/ledgers/`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Ledger Persistable Object
            }
            
+ Response

            {
                ... Standard Ledger Object
            }
    


##### Gets Ledgers           
            
##### `GET /accounting/ledgers/{ledgerId}/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Ledger Object
			}

**NOTES:** List the Ledger as per given ledger id 

##### `GET /accounting/ledgers/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Ledger Object
			}

**NOTES:** List all the ledger

##### `GET /accounting/ledgers/ledgerGrp/{ledgerGrpId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Ledger Object
			}

**NOTES:** List all the ledger as per the given ledger group id

##### Updates Ledgers    
       
##### `PATCH /accounting/ledgers/{ledgerId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Ledger Persistable Object
            }
            
            
##### Deletes Ledger    
       
##### `DELETE /accounting/ledgers/{ledgerId}`
+ Response
			{
				HTTP_Status:200
			}