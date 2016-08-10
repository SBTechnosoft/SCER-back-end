##### Standard Company Object

            {
                "id": int,
                "name": string,
				"displayName": string,
                "address1": string,
                "address2": string,
                "city":  {
					... Standard City Object
				},
                "state": {
                    ... Standard State Object
                },
                "zip": string,
				"pincode": char,
				"pan": string,
				"tin": string,
				"vat": string,
				"serviceTax": string,
				"logo": {
					... Standard Document Object
				},
				"isDefault": bool
				"isDisplay": bool
				"createdAt" datetime
				"updatedAt": datetime
            }
            
            
##### Standard Company Persistable Object

         {
            "name": string,
			"displayName": string,
			"address1": string,
			"address2": string,
			"cityId": int,
			"stateAbb": char,
			"zip": string,
			"pincode": char,
			"pan": string,
			"tin": string,
			"vat": string,
			"serviceTax": string,
			"logo": {
				
			},
			"isDisplay": bool
			"isDefault": bool 
         }


