##### Standard InvoiceNumber Object
			{
                "invocie_id": int,
                "company_id":  {
					... Standard Company Object
				},
                "invoice_label": string,
                "invoice_type": enum,
                "created_at" timestamp,
			}
            
##### Standard InvoiceNumber Persistable Object
			{
            	"company_id":int, 
                "invoice_label": string,
                "invoice_type": enum,
                "created_at" timestamp,
			}

