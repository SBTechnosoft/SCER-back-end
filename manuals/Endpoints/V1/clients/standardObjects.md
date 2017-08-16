##### Standard Client Object

            {
                "clientId": int,
                "clientName": string,
				"contactNo": string,
				"emailId": string,
				"address1": string,
				"isDisplay": Enum,
				"createdAt": timestamp,
				"updatedAt": datetime,
				"profession":
				{
					... Standard Profession Object
				}
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
				"emailId": string,
				"address1": string,
				"professionId":int,
				"isDisplay": Enum,
				"cityId":int, 
				"stateAbb":char
			}
			
#####  Is Display Enum
			{
				... Is Display Enum
			}
