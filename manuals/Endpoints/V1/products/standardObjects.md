##### Standard Products Object

        {
            "productId": int,
            "productName": string,
            "meausurementUnit": string,
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
			"isDisplay": bool,
			"createdAt" timestamp,
			"updatedAt": datetime
            

        }

##### Standard Products Persistable Object

        {
            "productName": string,
            "meausurementUnit": string,
            "productCategory": {
                ... Standard Products Cateogory Persistable Object
            },
            "productGroup": {
                ... Standard Products Group Persistable Object
            },
            "CompanyId": int,
            "BranchId" : int,
			"isDisplay": bool,
			"createdAt" timestamp,
			"updatedAt": datetime,
			"deletedAt":datetime
        }

##### Standard Products Category Object

        {
            "productCategoryId": int,
            "productCategoryName": string,
            "productCategoryDesc": string,
            "productParentCategoryId": int,
			"isDisplay": bool,
			"createdAt" datetime,
			"updatedAt": datetime
        }

##### Standard Products Category Persistable Object

        {
            "productCategoryName": string,
            "productCategoryDesc": string,
            "productParentCategoryId": int,
			"isDisplay": bool,
        }

##### Standard Products Group Object

        {
			"productGroupId": int,
            "productGroupName": string,
            "productGroupDesc": string,
            "productParentGroupId": int,
			"isDisplay": bool,
			"createdAt" datetime,
			"updatedAt": datetime
        }

##### Standard Products Group Persistable Object

        {
            "productGroupName": string,
            "productGroupDesc": string,
            "productGroupParentCatId": int,
			"isDisplay": bool
        }
