          jQuery(document).ready(function(){    
    /* check and uncheck functionality Start*/
    jQuery('.checkall').click(function(){
        if(!jQuery(this).is(':checked')){            
            jQuery('.dyntable input[type=checkbox]').each(function(){
                jQuery(this).attr('checked',false);				
            });
        }else{			
            jQuery('.dyntable input[type=checkbox]').each(function(){
                jQuery(this).attr('checked',true);				
            });
        }
    });    
    jQuery('.checkboxlist').click(function(){
        var selectedCounter = 0, no_of_records = 0;        
        jQuery('.dyntable input[type=checkbox]').each(function(){
                  no_of_records++;
                  if (jQuery(this).is(':checked')) {
                      selectedCounter++;
                  }
        })
        checkAllStatus = (no_of_records == selectedCounter) ? true : false;        
        jQuery('.checkall').attr('checked',checkAllStatus);	                    
    });
    jQuery('#operationId').click(function(){      
        var selectedCounter = 0;              
        jQuery('.dyntable input[type=checkbox]').each(function(){
                  if (jQuery(this).is(':checked')) {
                      selectedCounter++;
                  }
        });
        if(selectedCounter < 1)
        {
            alert('Please select the at least one record.');
            return false;
        }
        return confirm("Are you sure you wish to continue?");      
    });
    jQuery('#statusId').change(function(){        
        $class = (jQuery(this).val() == '') ? 'btn btn-default disabled' : 'btn btn-default' ;
        jQuery('#operationId').attr('class',$class);
    });
    /* check and uncheck functionality End*/
    
});    
    
    
    
    
    jQuery(document).ready(function()
    {
        $.validator.addMethod('positiveNumber',
            function (value) { 
                return Number(value) >= 0;
            }, '');
        
        jQuery("#subscription_add").validate(
        {	
            errorElement: "span",
            rules: {	                 
                "data[Subscription][name]": {
                    required: true
                },
                "data[Subscription][description]": {
                    required: true
                },
                 "data[Subscription][frequency]": {
                    required: true
                },
                 "data[Subscription][amount]": {
                    required: true,
                    positiveNumber : true
                }
            },
             messages: {
                "data[Subscription][name]": {
                    required: "Please enter plan name."                    
                },
                "data[Subscription][description]": {
                    required: "Please enter description."                    
                },
                 "data[Subscription][frequency]": {
                    required: "Please select plan duration."
                },
                 "data[Subscription][amount]": {
                    required: "Please enter amount.",
                    positiveNumber : "Please enter valid price."
                }
            }         
        });

           
  
			jQuery('.fancybox').fancybox({
                maxWidth	: 750,
                maxHeight	: 1000,
                fitToView	: false,
                width		: '70%',
                height		: '90%',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none'
            });
		});

    function setStatus(val1){
        var status = $("#statusHidden_"+val1).val();
        if(status ==1){
              var newStatus = 0;
              var msz = "Are you sure, you want to deactivate this record? ";
        }else{
           var newStatus = 1;
           var msz = "Are you sure, you want to activate this record?";
        }
        if (!confirm(msz)) {
                           return false;
        }
         $.ajax({
           url: '/admin/admins/setnewStatus/'+val1+'/'+newStatus+'/Subscription',
           success: function(data) { 
            // $('.result').html(data);
                 if(data == 0){
                    imgdata = "<img src = '/img/admin/inactive.png' />";
                 }else{
                    imgdata = "<img src = '/img/admin/active.png' />";
                 }
                 $('#link_status_'+val1).html(imgdata);
                 $('#statusHidden_'+val1).val(data);
         
           }
         });
    }