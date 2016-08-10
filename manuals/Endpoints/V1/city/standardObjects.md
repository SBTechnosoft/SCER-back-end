##### Standard City Object

            {
                "cityId": int,
                "cityName": string,
				"state": {
                    ... Standard State Object
				},
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime
                
            }
            
            
##### Standard city Persistable Object

 			{
            	"cityName": string,
            	"stateId": int,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime	
            }

