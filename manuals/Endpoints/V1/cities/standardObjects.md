##### Standard City Object

            {
                "cityId": int,
                "cityName": string,
				"state": {
                    ... Standard State Object
				},
				"isDisplay": enum,
				"createdAt" timestamp,
				"updatedAt": datetime
                
            }
            
            
##### Standard city Persistable Object

 			{
            	"cityName": string,
            	"stateAbb": char,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime	
            }

