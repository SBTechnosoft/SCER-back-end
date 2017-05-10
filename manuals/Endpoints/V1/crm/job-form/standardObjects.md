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
                "pincode": int,
				"pan": string,
				"tin": string,
				"vatNo": string,
				"sgst":string,
				"cgst":string,
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
            
            
##### Standard Job-Form Persistable Object

         {
			"clientName":string,
            "contactNo": string,
			"emailId": string,
			"address":string,
			"jobCardNo": string,
			"productId": int,
			"productInformation": string,
			"Qty": decimal,
			"tax": decimal,
			"discountType":enum,
			"discount": decimal,
			"additionalTax": string,
			"price":decimal,
			"labourCharge":decimal,
			"serviceType": enum,
			"entryDate": date,
			"delieveryDate": date,
			"advance":decimal,
			"total":decimal,
			"paymentMode":enum,
			"stateAbb": int,
			"cityId": int,
			"companyId":int
		}
