##### Standard Products Object

        {
            "productId": int,
            "productName": string,
            "measurementUnit": string,
            "productCatId": {
                ... Standard Products Category Object
            },
            "productGrpId": {
                ... Standard Products Group Object
            }
            "companyId": {
                ... Standard Company Object
            },
            "branchId": {
                ... Standard Branch Object
            }
			"isDisplay": enum,
			"createdAt" timestamp,
			"updatedAt": datetime
            

        }

##### Standard Products Persistable Object

        {
            "productName": string,
            "measurementUnit": string,
            "productCatId": {
                ... Standard Products Cateogory Persistable Object
            },
            "productGrpId": {
                ... Standard Products Group Persistable Object
            },
            "CompanyId": int,
            "BranchId" : int,
			"isDisplay": enum,
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
