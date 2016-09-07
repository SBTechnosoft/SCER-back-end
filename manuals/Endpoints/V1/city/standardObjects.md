##### Standard City Object

            {
                "cityId": int,
                "cityName": string,
				"stateAbb": {
                    ... Standard State Object
				},
				"isDisplay": bool,
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

