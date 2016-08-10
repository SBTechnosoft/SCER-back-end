##### Standard Document Object

            {
                "documentId": int,
                "documentName": string,
                "documentUrl": string
                "documentSize": int,
                "documentFormat": Standard Document Format Enum,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime
            }

##### Standard Document Persistable Object

            {
                "document": file,
				"isDisplay": bool,
				"createdAt" timestamp,
				"updatedAt": datetime,
				"deletedAt":datetime
            }
            
