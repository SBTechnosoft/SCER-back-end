##### Creates a Product transaction summary

##### `POST /productTrnSumm`
+ Header
	- Authentication Token


+ Body

            {
                ... Standard productsTrnSummary Persistable Object
            }
            
+ Response

            {
                ... Standard productsTrnSummary Object
            }
    

##### Get a Product transaction summary           
            
##### `GET /productTrnSumm/productTrnSummId`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard productsTrnSummary Object
			}

**NOTES:** List all the productsTrnSummary at particular product_Transaction_Summary_Id


##### `GET /productTrnSumm`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productsTrnSummary Object
            }
            
**NOTES:** List All the productsTrnSummary available in the system

##### `GET /products/{productsId}/productTrnSumm`
+ Header
	- Authentication Token

+ Response

            {
                ... Standard productTrnSumm Object
            }
            
**NOTES:** List All the productTransactionSummary at particular product_id




