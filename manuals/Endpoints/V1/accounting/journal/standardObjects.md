##### Standard Journal Object
			{
            	"jf_id":int,
            }
			

##### Standard Journals Persistable Object
			{
            	"jf_id":int,
				"data":
				[
					{
						"amount":decimal,
						"amount_type":Enum,
						"ledger_id":int,	
					},
					...
				]
				"entry_date":DateTime,
                "createdAt":TimeStamp,
                "updatedAt":DateTime,
                "companyId":int
			}

##### Amount Type Enum
			{
				creditType:'credit',
				debitType:'debit'
			}