##### Standard InvoiceNumber Object
			{
                "invocieId": int,
                "companyId":  {
					... Standard Company Object
				},
                "invoiceLabel": string,
                "invoiceType": enum,
                "createdAt" timestamp,
			}
            
##### Standard InvoiceNumber Persistable Object
			{
            	"companyId":int, 
                "invoiceLabel": string,
                "invoiceType": enum,
                "createdAt" timestamp,
			}

