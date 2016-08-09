##### Standard Product Object

            {
                "productId": int,
                "productName": string,
                "meausurementUnit": string,
				"productCategory": {
					... Standard Product Category Object
				},
				"productGroup": {
					... Standard Product Group Object
				}
				"company": {
					... Standard Company Object
				},
				"branch": {
					... Standard Branch Object
				}
				"createdAt": datetime
                
            }
            
            
##### Standard Product Persistable Object

 			{
            	"productName": string,
            	"meausurementUnit": string,
				"productCategory": {
					... Standard Product Cateogory Persistable Object
				},
				"productGroup": {
					... Standard Product Group Persistable Object
				},
				"CompanyId": string,
				"BranchId" : string
            }

##### Standard Product Category Object
			
			{
				//Define Standard Product Category Object Here
			}

##### Standard Product Category Persistable Object

			{
				//define Standard Product Cateogry Persistable Object here
			}

##### Standard Product Group Object

			{
				//define Standard Product Group Object here
			}

##### Standard Product Group Persistable Object 

			{
				//define Standard Product group Object Here
			}