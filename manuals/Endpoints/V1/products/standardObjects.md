##### Standard Products Object

        {
            "productId": int,
            "productName": string,
            "measurementUnit": string,
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
            "measurementUnit": string,
            "productCategoryId": int,
            "productGroupId": int,
            "companyId": int,
            "branchId" : int,
			"isDisplay": Enum,
			"createdAt" timestamp,
			"updatedAt": datetime,
			"deletedAt":datetime
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