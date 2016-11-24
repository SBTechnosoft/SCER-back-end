##### Standard Company Object

            {
                "companyId": int,
                "companyName": string,
				"companyDisplayName": string,
                "address1": string,
                "address2": string,
                "city":  {
					... Standard City Object
				},
                "state": {
                    ... Standard State Object
                },
                "pincode": char,
				"pan": char,
				"tin": char,
				"vatNo": char,
				"serviceTaxNo": char,
				"logo": {
					... Standard Document Object
				},
				"basicCurrencySymbol":char,
				"formalName":varchar,
				"noOfDecimalPoints":int,
				"currencySymbol":int,
				"isDefault": Enum,
				"isDisplay": Enum,
				"createdAt" timestamp,
				"updatedAt": datetime
            }
            
            
##### Standard Company Persistable Object

         {
            "companyName": string,
			"companyDisplayName": string,
			"address1": string,
			"address2": string,
			"cityId": int,
			"stateAbb": char,
			"pincode": char,
			"pan": string,
			"tin": string,
			"vatNo": string,
			"serviceTaxNo": string,
			"file[]":
			{
				...Standard Document Persistable Object
			}
			"basicCurrencySymbol":varchar,
			"formalName":varchar,
			"noOfDecimalPoints":int,
			"currencySymbol":int,
			"isDefault": Enum,
			"isDisplay": Enum,
		}

##### Is Display Enum
		{
			... Is Display Enum
		}

##### Is Default Enum
		{
			... Is Default Enum
		}
