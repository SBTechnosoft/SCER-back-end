##### Standard branch Object

            {
                "branchId": int,
                "company":  {
					... Standard company Object
				},
                "branchName": string,
                "address1": string,
                "address2": string,
                "city":  {
					... Standard city Object
				},
				"state": {
					... Standard state Object
				},
				"pincode": char,
				"is_default":char,
				"createdAt": datetime
                
            }
            
            
##### Standard branch Persistable Object

 			{
            	"companyId":int, 
                "branchName": string,
                "address1": string,
                "address2": string,
                "cityId": int,
				"stateAbb": char,
				"pincode": char,
				"is_default":char,
            }

