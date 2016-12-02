##### Standard Template Object

            {
                "templateId": int,
                "templateName":String,
                "templateBody":longtext,
                "templateType":Enum,
				"createdAt":TimeStamp,
				"updatedAt":datetime,
				"company":
				{
					... Standard Company Object
				}
            }
            
##### Standard Template Persistable Object
			{
            	"templateName":String,
                "templateBody":longtext,
                "templateType":Enum,
				"companyId":int
            }
##### Template type Enum
			{
				generalTemplate:'general', 
				generalTemplate:'invoice', 
				quotationTemplate:'quotation',
				emailTemplate :'email', 
				smsTemplate:'sms'
			}
