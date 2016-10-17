##### Gets Template           
            
##### `GET /templates/{templateId}`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Template Object
			}

**NOTES:** List all the template as per given template_id 

##### `GET /templates`
+ Header 
	- Authentication Token

+ Response

			{
				... Standard Template Object
			}

**NOTES:** List all the templates 


##### Updates Template    
       
##### `PATCH /templates/{templateId}`
+ Header
	- Authentication Token

+ Body

            {
                ... Standard Template Persistable Object
            }       
