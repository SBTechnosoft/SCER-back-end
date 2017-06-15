##### Standard Email Persistable Object
		{
			"conversationId":int,
			"emailId":string,
			"ccEmailId":string,
			"bccEmailId":string,
			"contactNo":string,
			"subject":string,
			"conversation":text,
			"conversationType":Enum,
			"attachment":
				{
					... File Object
				}
			"client":
				{
					{
						"clientId":int,
					}
					...
				}
			"companyId":int,
			"branchId":int,
		}
