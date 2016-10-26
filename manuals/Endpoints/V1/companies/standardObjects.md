##### Standard Company Object

            {
                "company_id": int,
                "company_name": string,
				"company_display_name": string,
                "address1": string,
                "address2": string,
                "city_id":  {
					... Standard City Object
				},
                "state_abb": {
                    ... Standard State Object
                },
                "pincode": char,
				"pan": char,
				"tin": char,
				"vat_no": char,
				"service_tax_no": char,
				"logo": {
					... Standard Document Object
				},
				"basic_currency_symbol":char,
				"formal_name":varchar,
				"no_of_decimal_points":int,
				"currency_symbol":int,
				"is_default": enum,
				"is_display": enum,
				"created_at" timestamp,
				"updated_at": datetime
            }
            
            
##### Standard Company Persistable Object

         {
            "company_name": string,
			"company_display_name": string,
			"address1": string,
			"address2": string,
			"city_id": int,
			"state_abb": char,
			"pincode": char,
			"pan": string,
			"tin": string,
			"vat_no": string,
			"service_tax_no": string,
			"basic_currency_symbol":varchar,
			"formal_name":varchar,
			"no_of_decimal_items":int,
			"currency_symbol":int,
			"is_default": bool,
			"is_display": bool,
			"created_at" timestamp,
			"updated_at": datetime,
			"deleted_at":datetime			
         }

