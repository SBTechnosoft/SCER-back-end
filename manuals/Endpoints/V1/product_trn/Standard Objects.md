##### Standard Product transaction Object

            {
                "productTrnId": int,
                "transactionDate": DateTime,
                "transactionType": string,
                "quantity": string,
                "price": string,
				"company": {
					... Standard Company Object
				},
				"branch": {
					... Standard Branch Object
				}
				"createdAt": datetime
                
            }
            
            
##### Standard Product transaction Persistable Object

 			{
            	"transactionDate": DateTime,
                "transactionType": string,
                "quantity": string,
                "price": string,
				"CompanyId": int,
				"BranchId" : int
            }

