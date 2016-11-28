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
                "pan":char,
                "tin":char,
                "gstNo":String,
				"city":  {
					... Standard City Object
				},
                "state": {
                    ... Standard State Object
                },
				"createdAt":TimeStamp,
                "updatedAt":DateTime,
                "deletedAt":DateTime,
                "ledgerGroup": {
					... Standard Ledger Group Object
				},
				"company": {
					... Standard Company Object
				}
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
                "pan":char,
                "tin":char,
                "gstNo":String,
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