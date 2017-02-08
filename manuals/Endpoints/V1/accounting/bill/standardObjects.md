##### Standard Bill Object
			{
				"saleId":int,
				"productArray":string,
				"paymentMode":Enum,
				"bankName":string,
				"checkNumber":string,
				"total":decimal,
				"tax":decimal,
				"color":string,
				"frameNo":string,
				"grandTotal":decimal,
				"advance":decimal,
				"balance":decimal,
				"salesType":Enum,
				"remark":string,
				"entryDate":datetime,
				"createdAt":timestamp,
				"updatedAt":datetime,
				"client":{
					... standard client object
				}
            	"company":{
					... standard company object
				},
                
				"invoiceNumber":string,	
				"file":
				{
					{
						{
							"documentId":int,
							"saleId":int,
							...Standard Document Object
							"documentType":string,
							"createdAt":timestamp,
							"updatedAt":datetime
						}
						...
					}
					...
				}
            }
			
##### Standard Bill Persistable Object
			{
            	"companyId":String,
                "entryDate":date,
				"contactNo":string,
				"emailId":string,
				"companyName":string,
				"clientName":string,
				"workno":string,
				"address1":String,
				"address2":String,
				"stateAbb":char,
				"cityId":int,
				"invoiceNumber":string,	
				...Standard Product Transaction Persistable Object,
				"transactionDate":date,
				"total":decimal,
				"tax":decimal,
				"color":string,
				"frameNo":string,
				"grandTotal":decimal,
				"advance":decimal,
				"balance":decimal,
				"paymentMode":Enum,
				"bankName":string,
				"checkNumber":string,
				"remark":string,
				"file":
				{
					{
						"documentId":int,
						"saleId":int,
						...Standard Document Object
						"documentType":string,
						"createdAt":timestamp,
						"updatedAt":datetime
					}
					...
				}
            }

##### Payment Mode Enum
			{
				"cashPayment":'cash',
				"bankPayment":'bank',
				"cardPayment":'card'
			}