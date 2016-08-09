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
				"createdAt": datetime
                
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
				"BranchId" : int
            }

##### Standard Products Category Object
			
			{
				//Define Standard Products Category Object Here
			}

##### Standard Products Category Persistable Object

			{
				//define Standard Products Cateogry Persistable Object here
			}

##### Standard Products Group Object

			{
				//define Standard Products Group Object here
			}

##### Standard Products Group Persistable Object 

			{
				//define Standard Products group Object Here
			}