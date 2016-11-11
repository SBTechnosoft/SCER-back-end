##### Standard Journal Object
			{
            	"jfId":int,
            }
			

##### Standard Journals Persistable Object
			{
            	"jfId":int,
				"data":
				[
					{
						"amount":decimal,
						"amountType":Enum,
						"ledgerId":int,	
					},
					...
				]
				"entryDate":Date,
                "createdAt":TimeStamp,
                "updatedAt":DateTime,
                "companyId":int
			}

##### Amount Type Enum
			{
				creditType:'credit',
				debitType:'debit'
			}