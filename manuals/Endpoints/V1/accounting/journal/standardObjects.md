##### Standard JournalFolioId Object
			{
            	"jfId":int,
            }
			
##### Standard Journal Object
			{
				"journalId":int,
            	"jfId":int,
				"amount":decimal,
				"amountType":Enum,
				"entryDate":DateTime,
				"createdAt":TimeStamp,
				"updatedAt":DateTime,
				"ledger":{
					... Standard Ledger Object
				}
				"company": {
					... Standard Company Object
				}
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
##### Standard Inventory Persistable Object
			{
				"Inventory":
				[
					{
						"productId":int,
						"discount":decimal,
						"discountType":Enum,
						"price":decimal,
						"qty":decimal,
					},
					...
				]
				"invoiceNumber":String
			}
##### Amount Type Enum
			{
				creditType:'credit',
				debitType:'debit'
			}
##### Discount Type Enum
			{
				flatType:'flat',
				percentageType:'percentage'
			}