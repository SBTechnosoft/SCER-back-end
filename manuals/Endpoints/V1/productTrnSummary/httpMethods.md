##### Creates Product Transaction Summary

##### `POST /productTrnSumm`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard Products TrnSummary Persistable Object
            }
            
+ Response

            {
                ... Standard ProductsTrnSummary Object
            }
    

##### Gets Product Transaction Summary           
            
##### `GET /productTrnSumm/productTrnSummId`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard ProductsTrnSummary Object
			}

**NOTES:** List all the productsTrnSummary at particular productTrnSumm


##### `GET /productTrnSumm`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard ProductsTrnSummary Object
            }
            
**NOTES:** List All the productsTrnSummary available in the system

##### `GET /products/{productsId}/productTrnSumm`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productTrnSumm Object
            }
            
**NOTES:** List all the product transaction summary at particular productId




