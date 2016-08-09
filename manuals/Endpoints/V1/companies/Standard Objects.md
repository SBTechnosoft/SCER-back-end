##### Standard Company Object

            {
                "id": int,
                "name": string,
				"displayName": string,
                "address1": string,
                "address2": string,
                "city": string,
                "state": {
                    ... Standard State Object
                },
                "zip": string,
				"pan": string,
				"tin": string,
				"vat": string,
				"serviceTax": string,
				"logo": {
					... Standard Document Object
				},
				"isDisplay": bool
            }
            
            
##### Standard Company Persistable Object

         {
             "name": string,
             "address1": string,
             "address2": string,
             "city": string,
             "state": string,
             "zip": string
         }
