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
			"product":
			[
				{
					"productId": int,
					"productName":string
					"productInformation": string,
					"Qty": decimal,
					"tax": decimal,
					"additionalTax": string,
					"discountType":enum,
					"discount": decimal,
					"price":decimal,
				}
				...
			]			
			"bankName":string,
			"chequeNo":string,
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

##### Service Type Enum
			{
				paidType:'paid',
				freeType:'free' 
			}
