##### Standard ProductsTrnSummary Object

            {
                "productTrnSummId": int,
                "transactionDate": datetime,
                "transactionType": string,
                "quantity": string,
				"company": {
					... Standard Company Object
				},
				"branch": {
					... Standard Branch Object
				}
				"products": {
					... Standard products Object
				}
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime
				
                
            }
            
            
##### Standard ProductsTrnSummary Persistable Object

 			{
            	"transactionDate": datetime,
                "transactionType": string,
                "quantity": string,
                "productId": int,
                "CompanyId": int,
				"BranchId" : int,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime
            }

