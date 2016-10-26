##### Standard LedgerGrp Object

            {
                "ledger_id": int,
                "ledger_name":String,
                "alias":String,
                "inventory_affected":Enum,
                "address1":String,
                "address2":String,
                "pan":char,
                "tin":char,
                "gst_no":String,
				"city_id":  {
					... Standard City Object
				},
                "state_abb": {
                    ... Standard State Object
                },
				"created_at":TimeStamp,
                "updated_at":DateTime,
                "deleted_at":DateTime,
                "ledger_grp_id":int,
				"company_id":int
            }
            
##### Standard LedgerGrp Persistable Object
			{
            	"ledger_name":String,
                "alias":String,
                "inventory_affected":Enum,
                "address1":String,
                "address2":String,
                "pan":char,
                "tin":char,
                "gst_no":String,
                "state_abb":char,
                "city_id":int,
                "created_at":TimeStamp,
                "updated_at":DateTime,
                "deleted_at":DateTime,
                "ledger_grp_id":int,
				"company_id":int
            }

