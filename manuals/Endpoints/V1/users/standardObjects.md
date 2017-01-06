##### Standard User Object

            {
                "userId": int,
                "userName": string,
				"emailId":string,
				"password":string,
				"contactNo":string,
				"address":string,
				"pincode":int,
				"state": {
                    ... Standard State Object
				},
				"city": {
                    ... Standard City Object
				},
				"company": {
                    ... Standard Company Object
				},
				"branch": {
                    ... Standard Branch Object
				},
				"createdAt" timestamp,
				"updatedAt": datetime
            }
            
            
##### Standard user Persistable Object

 			{
            	"userName": string,
				"emailId":string,
				"password":string,
				"contactNo":string,
				"address":string,
				"pincode":int,
				"stateAbb":char,
				"cityId": int,
				"companyId":int,
				"branchId": int,
			}

