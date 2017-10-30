##### Standard Bill Object
			{
				"saleId":int,
				"productArray":string,
				"paymentMode":Enum,
				"bankName":string,
				"checkNumber":string,
				"total":decimal,
				"totalDiscounttype":Enum,
				"totalDiscount":decimal,
				"tax":decimal,
				"grandTotal":decimal,
				"advance":decimal,
				"extraCharge":decimal,
				"balance":decimal,
				"salesType":Enum,
				"remark":string,
				"poNumber":string,
				"entryDate":datetime,
				"orderConfirmationDate":datetime,
				"createdAt":timestamp,
				"updatedAt":datetime,
				"jf_id":int,
				"client":{
					... standard client object
				}
            	"company":{
					... standard company object
				},
				"invoiceNumber":string,	
				"quotationNumber":string,	
				"jobCardNumber":string,
				"file":
				{
					{
						{
							...Standard Document Object
							"saleId":int,
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
				"isDraft":int,
				"entryDate":date,
				"contactNo":string,
				"emailId":string,
				"companyName":string,
				"clientName":string,
				"workno":string,
				"address1":string,
				"address2":string,
				"stateAbb":char,
				"cityId":int,
				"professionId":int,
				"invoiceNumber":string,
				"quotationNumber":string,	
				"jobCardNumber":string,
				...Standard Product Transaction Persistable Object,
				"transactionDate":date,
				"total":decimal,
				"totalDiscounttype":enum,
				"totalDiscount":decimal,
				"extraCharge":decimal,
				"tax":decimal,
				"grandTotal":decimal,
				"advance":decimal,
				"balance":decimal,
				"paymentMode":Enum,
				"bankName":string,
				"checkNumber":string,
				"poNumber":string,
				"remark":string,
				"file":
				{
					{
						Image Object
					}
					...
				}
				"scanFile":
				{
					{
						Base64 String
					}
					...
				}
            }

##### Standard Payment Persistable Object
			{
				entryDate:date,
				amount:decimal,
				paymentMode:Enum,
				paymentTransaction:string,
				bankName:string,
				checkNumber:string
			}
		
##### Payment Mode Enum
			{
				"cashPayment":'cash',
				"bankPayment":'bank',
				"cardPayment":'card'
			}