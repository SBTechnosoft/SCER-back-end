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

**NOTES:** Provide details of the Ledger based on the Ledger Number

##### `GET /accounting/ledgers/`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Ledger Object
			}

**NOTES:** List all the ledger

##### `GET /accounting/ledgers/{ledgerId}/transactions`
+ Header 
	- Authentication Token

+ Response

			{
				... //TO DO
			}



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

+ Error Message

			{
				... Error message
			}
+  Status
			{
				... HTTP_Status:200
			}
##### Deletes Ledger    
       
##### `DELETE /accounting/ledgers/{ledgerId}`
+ Response
			{
				HTTP_Status:200
			}