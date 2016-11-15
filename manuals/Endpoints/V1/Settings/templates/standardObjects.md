##### Standard Template Object

            {
                "templateId": int,
                "templateName":String,
                "templateBody":longtext,
                "templateType":Enum,
				"updatedAt":datetime
            }
            
##### Standard Template Persistable Object
			{
            	"templateName":String,
                "templateBody":longtext,
                "templateType":Enum,
				"updatedAt":datetime
            }
##### Template type Enum
			{
				generalTemplate:'general', 
				quotationTemplate:'quotation',
				emailTemplate :'email', 
				smsTemplate:'sms'
			}
