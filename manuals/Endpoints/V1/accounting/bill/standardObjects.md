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
				"grandTotal":decimal,
				"advance":decimal,
				"balance":decimal,
				"paymentMode":Enum,
				"bankName":string,
				"checkNumber":string,
				"remark":string,
				"file":
				{
					... Standard Document Persistable Object
				}
            }

##### Payment Mode Enum
			{
				"cashPayment":'cash',
				"creditPayment":'credit',
				"bankPayment":'bank',
				"cardPayment":'card'
			}