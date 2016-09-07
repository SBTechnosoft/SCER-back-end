##### Standard Branch Object

            {
                "branchId": int,
                "companyId":  {
					... Standard Company Object
				},
                "branchName": string,
                "address1": string,
                "address2": string,
                "cityId":  {
					... Standard City Object
				},
				"stateAbb": {
					... Standard State Object
				},
				
				"pincode": char,
				"isDefault":enum,
				"isDisplay": enum,
				"createdAt" timestamp,
				"updatedAt": datetime
            }
            
##### Standard Branch Persistable Object
			{
            	"companyId":int, 
                "branchName": string,
                "address1": string,
                "address2": string,
                "cityId": int,
				"stateAbb": char,
				"pincode": char,
				"isDefault":bool,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt" :datetime
            }

