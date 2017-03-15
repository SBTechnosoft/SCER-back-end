##### Standard Balance-Sheet Object

            {
                balanceSheetId:int,
				"ledger":
				{
					... Standard Ledger Object
				},
				"amount":decimal,
				"amount_type":Enum,
				"createdAt":TimeStamp,
                "updatedAt":DateTime
			}
##### Amount Type Enum
			{
				... Amount Type Enum(journal)
			}