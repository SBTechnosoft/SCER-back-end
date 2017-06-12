##### Standard Ledger Object

            {
                "ledgerId": int,
                "ledgerName":String,
                "alias":String,
                "inventoryAffected":Enum,
                "address1":String,
                "address2":String,
				"contactNo":String,
				"emailId":String,
				"invoiceNumber":string,
                "pan":string,
                "tin":string,
                "cgst":String,
                "sgst":String,
				"isDealer":enum,
				"city":  {
					... Standard City Object
				},
                "state": {
                    ... Standard State Object
                },
				"createdAt":TimeStamp,
                "updatedAt":DateTime,
                "ledgerGroup": {
					... Standard Ledger Group Object
				},
				"company": {
					... Standard Company Object
				},
				openingBalance:decimal,
				openingBalanceType:Enum,
				currentBalance:decimal,
				currentBalanceType:Enum
			}
            
##### Standard Ledger Persistable Object
			{
            	"ledgerName":String,
                "alias":String,
                "inventoryAffected":Enum,
                "address1":String,
                "address2":String,
				"contactNo":String,
				"emailId":String,
                "pan":string,
                "tin":string,
				"cgst":String,
                "sgst":String,
				"isDealer":enum,
				"balanceFlag":Enum,
				"amount":decimal,
				"amountType":Enum,
                "stateAbb":char,
                "cityId":int,
                "ledgerGroupId":int,
				"companyId":int
            }

##### Standard Ledger Transaction Object
			{
				"id":int,
				"amount":decimal,
				"amountType":Enum,
				"entryDate":Date,
				"jf_id":int,
				"ledger":
				{
					... Standard Ledger Object
				}
			}
##### Inventory Affected Enum
			{
				inventoryAffected:'yes',
				inventoryNotAffected:'no'
			}
##### Amount Type Enum
			{
				... Amount Type Enum(journal)
			}
##### Balance Flag Enum
			{
				openingBalance:'opening',
				closingBalance:'closing'
			}
##### Is Dealer Enum
			{
				isDealer:'y',
				isNotDealer:'n'
			}