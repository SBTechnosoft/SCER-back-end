##### Standard productsTrnSummary Object

            {
                "productTrnSummId": int,
                "transactionDate": DateTime,
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
				"createdAt": datetime
                
            }
            
            
##### Standard productsTrnSummary Persistable Object

 			{
            	"transactionDate": DateTime,
                "transactionType": string,
                "quantity": string,
                "productId": int,
                "CompanyId": int,
				"BranchId" : int
            }

