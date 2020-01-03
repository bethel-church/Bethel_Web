    jQuery(document).ready(function()
    {
        jQuery("#categoryId").validate(
        {	
            errorElement: "div",
            rules: {	                 
                "data[Category][category]": {
                    required: true
                }/*,
                "data[Category][parent_id]": {
                    required: true
                }*/
            },
             messages: {
                "data[Category][category]": {
                    required: "Please enter the category name."                    
                },
                "data[Category][parent_id]": {
                    required: "Please select the parent category."                    
                }
            }         
        });
        
    }); 