##### Standard Product Transaction Object

            {
                "productTrnId": int,
                "transactionDate": datetime,
                "transactionType": Enum,
                "quantity": decimal,
                "price": decimal,
				"company": {
					... Standard Company Object
				},
				"branch": {
					... Standard Branch Object
				}
				"isDisplay": Enum,
				"createdAt" timestamp,
				"updatedAt": datetime
                
            }
            
            
##### Standard Product Transaction Persistable Object

 			{
            	"transactionDate": datetime,
                "transactionType": Enum,
                "quantity": decimal,
                "price": decimal,
				"companyId": int,
				"branchId" : int,
				"isDisplay": Enum,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime
            }
#####  Is Display Enum
			{
				... Is Display Enum
			}
#####  Transaction Type Enum
			{
				"creditType":"Inward",
				"debitType":"Outward"
			}