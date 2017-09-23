##### Standard Setting Object
			{
				"settingId":int,
				"settingType":string,
				"barcodeWidth": decimal,
                "barcodeHeight": decimal,
				"chequeno": Enum
				"createdAt":timestamp,
				"updatedAt":datetime
			}
##### Standard Setting Persistable Object
			{
                "barcodeWidth": decimal,
                "barcodeHeight": decimal,
				"chequeno": Enum
            }
##### chequeno Enum
			{
				chequeno:'enable',
				chequeno:'disable'
			}
