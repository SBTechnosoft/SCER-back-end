##### Standard Client Object

            {
                "clientId": int,
                "clientName": string,
				"contactNo": string,
				"workNo" string,
				"emailId": string,
				"address1": string,
				"address2": string,
				"isDisplay": Enum,
				"createdAt": timestamp,
				"updatedAt": datetime,
				"city": 
				{
					... Standard City Object
				}
				"state": 
				{
					... Standard State Object
				}
                
            }

##### Standard Client Persistable Object

 			{
            	"clientName": string,
				"contactNo": string,
				"workNo" string,
				"emailId": string,
				"address1": string,
				"address2": string,
				"isDisplay": Enum,
				"createdAt": timestamp,
				"updatedAt": datetime,
				"cityId":int, 
				"stateAbb":char
			}
			
#####  Is Display Enum
			{
				... Is Display Enum
			}
