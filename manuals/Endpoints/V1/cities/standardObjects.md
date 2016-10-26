##### Standard City Object

            {
                "city_id": int,
                "city_name": string,
				"state_abb": {
                    ... Standard State Object
				},
				"is_display": bool,
				"created_at" timestamp,
				"updated_at": datetime
                
            }
            
            
##### Standard city Persistable Object

 			{
            	"city_name": string,
            	"state_abb": char,
				"is_display": bool,
				"created_at" timestamp,
				"updated_at": datetime,
				"deleted_at":datetime	
            }

