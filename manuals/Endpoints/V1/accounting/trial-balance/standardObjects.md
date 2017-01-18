##### Standard Trial-Balance Object

            {
                trialBalanceId:int,
				"ledger":
				{
					... Standard Ledger Object
				},
				"amount":decimal,
				"amount_type":Enum,
				"createdAt":TimeStamp,
                "updatedAt":DateTime,
                "company": {
					... Standard Company Object
				},
			}
##### Amount Type Enum
			{
				... Amount Type Enum(journal)
			}