##### Standard Invoice Number Configuration Object
			{
                "invocieId": int,
                "company":  {
					... Standard Company Object
				},
                "invoiceLabel": string,
                "invoiceType": Enum,
                "createdAt" timestamp,
			}
            
##### Standard Invoice Number Configuration Persistable Object
			{
            	"companyId":int, 
                "invoiceLabel": string,
                "invoiceType": Enum,
                "createdAt" timestamp,
			}

##### Invoice Type Enum
			{
				beforeInvoice:'prefix',
				afterInvoice:'postfix' 
			}

