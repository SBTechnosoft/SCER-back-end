##### Standard Ledger Object

            {
                "ledgerId": int,
                "ledgerName":String,
                "alias":String,
                "inventoryAffected":Enum,
                "address1":String,
                "address2":String,
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
                "pan":char,
                "tin":char,
                "gstNo":String,
                "stateAbb":char,
                "cityId":int,
                "createdAt":TimeStamp,
                "updatedAt":DateTime,
                "deletedAt":DateTime,
                "ledgerGroupId":int,
				"companyId":int
            }

##### Inventory Affected Enum
			{
				inventoryAffected:'yes',
				inventoryNotAffected:'no'
			}