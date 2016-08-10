##### Standard Product Transaction Object

            {
                "productTrnId": int,
                "transactionDate": datetime,
                "transactionType": string,
                "quantity": string,
                "price": string,
				"company": {
					... Standard Company Object
				},
				"branch": {
					... Standard Branch Object
				}
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime
                
            }
            
            
##### Standard Product Transaction Persistable Object

 			{
            	"transactionDate": datetime,
                "transactionType": string,
                "quantity": string,
                "price": string,
				"companyId": int,
				"branchId" : int,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime
            }

