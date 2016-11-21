##### Standard Bill Persistable Object
			{
            	"companyId":String,
                "entryDate":date,
                "contactNo":string,
                "emailId":string,
                "companyName":string,
                "clientName":string,
                "invoiceNumber":string,
                "address1":String,
                "address2":String,
				"stateAbb":char,
                "cityId":int,
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
				"createdAt":TimeStamp,
                "updatedAt":DateTime,
                "deletedAt":DateTime,
            }

##### Payment Mode Enum
			{
				"mode1":'cash',
				"mode2":'credit'
			}