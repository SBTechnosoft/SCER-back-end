##### Standard Company Object

            {
                "companyId": int,
                "companyName": string,
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
				"basicCurrencySymbol":varchar,
				"formalName":varchar,
				"noOfDecimalItems":int,
				"currencySymbol":int,
				"isDefault": bool,
				"isDisplay": bool,
				"createdAt" timestamp,
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
			"documentId": int,
			"basicCurrencySymbol":varchar,
			"formalName":varchar,
			"noOfDecimalItems":int,
			"currencySymbol":int,
			"isDefault": bool,
			"isDisplay": bool,
			"createdAt" timestamp,
			"updatedAt": datetime,
			"deletedAt":datetime			
         }

