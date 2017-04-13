##### Creates Taxation for tax sales

##### `GET /accounting/taxation/sale-tax/company/{companyId}`
+ Header
	- Authentication Token
	- 'operation':'excel',
	- 'fromDate':'',
	- 'toDate':''
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Sale-Tax Object
            }

##### Creates Taxation for tax purchase

##### `GET /accounting/taxation/purchase-tax/company/{companyId}`
+ Header
	- Authentication Token
	- 'operation':'excel'
	- 'fromDate':'',
	- 'toDate':''
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Purchase-Tax Object
            }

			
##### Creates Taxation for purchase-detail

##### `GET /accounting/taxation/purchase-detail/company/{companyId}`
+ Header
	- Authentication Token
	- 'operation':'excel'
	- 'fromDate':'',
	- 'toDate':''
+ Error Message

			{
				... Error Message
			}            
+ Response

            {
                ... Standard Purchase-Detail Object
            }
