##### Creates Purchase-Bill

##### `POST /accounting/purchase-bills/`
+ Header
	- Authentication Token
	- 'operation':'preprint'
+ Body

            {
                ... Standard Purchase-Bill Persistable Object
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                "documentPath":''
            }
			
##### `GET /accounting/purchase-bills/company/{companyId}`
+ Header
	- Authentication Token
	- "fromDate":"date"
	- "toDate":"date"
	- "billNumber":"string"
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Purchase-Bill Object
            }
			
##### `GET /accounting/purchase-bills`
+ Header
	- Authentication Token
	- "previousPurchaseId":"int",
	- "nextPurchaseId":"int",
	- "companyId":"int",
	- "operation":"first/last"
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Purchase-Bill Object
            }
			
	
##### `POST /accounting/purchase-bills/{purchaseBillId}`
+ Header
	- Authentication Token
+ Body

            {
                ... Standard Purchase-Bill Persistable Object
            }

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                "documentPath":''
            }
			
##### `DELETE /accounting/purchase-bills/{purchaseBillId}`
+ Header
	- Authentication Token

+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... HTTP_Status:200
            }
			
