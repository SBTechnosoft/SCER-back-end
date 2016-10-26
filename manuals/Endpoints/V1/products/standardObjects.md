##### Standard Products Object

        {
            "product_id": int,
            "product_name": string,
            "measurement_unit": string,
            "product_category_id": {
                ... Standard Products Category Object
            },
            "product_group_id": {
                ... Standard Products Group Object
            }
            "company_id": {
                ... Standard Company Object
            },
            "branch_id": {
                ... Standard Branch Object
            }
			"is_display": enum,
			"created_at" timestamp,
			"updated_at": datetime
            

        }

##### Standard Products Persistable Object

        {
            "product_name": string,
            "measurement_unit": string,
            "product_category_id": {
                ... Standard Products Cateogory Persistable Object
            },
            "product_group_id": {
                ... Standard Products Group Persistable Object
            },
            "company_id": int,
            "branch_id" : int,
			"is_display": enum,
			"created_at" timestamp,
			"updated_at": datetime,
			"deleted_at":datetime
        }

##### Standard Products Category Object

        {
            "product_category_id": int,
            "product_category_name": string,
            "product_category_desc": string,
            "product_parent_category_id": int,
			"is_display": bool,
			"created_at" datetime,
			"updated_at": datetime
        }

##### Standard Products Category Persistable Object

        {
            "product_category_name": string,
            "product_category_desc": string,
            "product_parent_category_id": int,
			"is_display": bool,
        }

##### Standard Products Group Object

        {
			"product_group_id": int,
            "product_group_name": string,
            "product_group_desc": string,
            "product_parent_group_id": int,
			"is_display": bool,
			"created_at" datetime,
			"updated_at": datetime
        }

##### Standard Products Group Persistable Object

        {
            "product_group_name": string,
            "product_group_desc": string,
            "product_group_parent_cat_id": int,
			"is_display": bool
        }
