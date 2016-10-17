##### Standard Invoice Object
			{
                "invocieId": int,
                "companyId":  {
					... Standard Company Object
				},
                "invoiceLabel": string,
                "invoiceType": enum,
                "createdAt" timestamp,
			}
            
##### Standard Invoice Persistable Object
			{
            	"companyId":int, 
                "invoiceLabel": string,
                "invoiceType": enum,
                "createdAt" timestamp,
			}

