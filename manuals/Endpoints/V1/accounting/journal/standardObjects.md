##### Standard Journals Persistable Object
			{
            	"journal_id":int,
                "jf_id":int,
                "amount":decimal,
                "amount_type":Enum,
                "entry_date":DateTime,
                "createdAt":TimeStamp,
                "updatedAt":DateTime,
                "deletedAt":DateTime,
                "ledger_id":int
            }

##### Amount Type Enum
			{
				creditType:'credit',
				debitType:'debit'
			}