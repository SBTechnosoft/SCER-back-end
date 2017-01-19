##### Standard Products Object

        {
            "productId": int,
            "productName": string,
            "measurementUnit": Enum,
			"purchasePrice": decimal,
			"wholesaleMargin": decimal,
			"semiWholesaleMargin": decimal,
			"vat": decimal,
			"margin": decimal,
			"mrp": decimal,
            "productCategory": {
                ... Standard Products Category Object
            },
            "productGroup": {
                ... Standard Products Group Object
            }
            "company": {
                ... Standard Company Object
            },
            "branch": {
                ... Standard Branch Object
            }
			"isDisplay": Enum,
			"createdAt" timestamp,
			"updatedAt": datetime
         }

##### Standard Products Persistable Object

        {
            "productName": string,
            "measurementUnit": Enum,
            "productCategoryId": int,
            "productGroupId": int,
			"purchasePrice": decimal,
			"wholesaleMargin": decimal,
			"semiWholesaleMargin": decimal,
			"vat": decimal,
			"margin": decimal,
			"mrp": decimal,
            "companyId": int,
            "branchId" : int,
			"isDisplay": Enum,
		}
		
##### Standard Product Transaction Persistable Object
			{
            	"inventory":
				[
					{
						"qty": decimal,
						"price": decimal,
						"discount":decimal,
						"discountValue":decimal,
						"discountType":Enum,
						"productId":int,
					},
					...
				],
				"transactionDate": date,
				"transactionType":Enum,
				"companyId": int,
				"invoiceNumber":string,
				"billNumber":string,
				"tax":decimal,
				"branchId":int,
				"jfId":int
			}
			
##### Standard Product Transaction Object
			{
            	"inventory":
				[
					{
						"qty": decimal,
						"price": decimal,
						"discount":decimal,
						"discountValue":decimal,
						"discountType":Enum,
						"productId":int,
						"transactionDate": date,
						"transactionType":Enum,
						"companyId": int,
						"invoiceNumber":string,
						"billNumber":string,
						"tax":decimal,
						"branchId":int,
						"jfId":int
					},
					...
				],
				
			}
#####  Is Display Enum
			{
				... Is Display Enum
			}
#####  Discount Type Enum
			{
				"flatType":"flat",
				"perCentageType":"percentage"
			}
##### Standard Products Category Object

        {
            "productCategoryId": int,
            "productCategoryName": string,
            "productCategoryDescription": string,
            "productParentCategoryId": int,
			"isDisplay": Enum,
			"createdAt" datetime,
			"updatedAt": datetime
        }

##### Standard Products Category Persistable Object

        {
            "productCategoryName": string,
            "productCategoryDescription": string,
            "productParentCategoryId": int,
			"isDisplay": Enum,
        }

##### Standard Products Group Object

        {
			"productGroupId": int,
            "productGroupName": string,
            "productGroupDescription": string,
            "productParentGroupId": int,
			"isDisplay":Enum,
			"createdAt" datetime,
			"updatedAt": datetime
        }

##### Standard Products Group Persistable Object

        {
            "productGroupName": string,
            "productGroupDesciption": string,
            "productGroupParentGroupId": int,
			"isDisplay": Enum
        }
		
#####  Is Display Enum
			{
				... Is Display Enum
			}
#####  Measurement Unit Enum
			{
				"type1":"kilo",
				"type2":"litre"
			}