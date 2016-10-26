##### Standard Branch Object

            {
                "branch_id": int,
                "company_id":  {
					... Standard Company Object
				},
                "branch_name": string,
                "address1": string,
                "address2": string,
                "city_id":  {
					... Standard City Object
				},
				"state_abb": {
					... Standard State Object
				},
				
				"pincode": char,
				"is_default":enum,
				"is_display": enum,
				"created_at" timestamp,
				"updated_at": datetime
            }
            
##### Standard Branch Persistable Object
			{
            	"company_id":int, 
                "branch_name": string,
                "address1": string,
                "address2": string,
                "city_id": int,
				"state_abb": char,
				"pincode": char,
				"is_default":bool,
				"is_display": bool,
				"created_at" timestamp,
				"updated_at": datetime,
				"deleted_at" :datetime
            }

