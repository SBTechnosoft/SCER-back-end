##### Standard Company Object

            {
                "id": int,
                "name": string,
				"displayName": string,
                "address1": string,
                "address2": string,
                "city":  {
					... Standard city Object
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
				"logo": String,
				"isDisplay": bool
				
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
			"logo": String,
			"isDisplay": bool
			 
         }


