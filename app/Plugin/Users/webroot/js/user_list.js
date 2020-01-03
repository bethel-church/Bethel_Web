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




jQuery(document).ready(function() {
            jQuery('.fancybox').fancybox({
                maxWidth	: 400,
                maxHeight	: 600,
                fitToView	: false,
                width		: '70%',
                height		: '70%',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none'
            });
            });
            function alphaSearch(linkObj){
            var linktext = $(linkObj).text();
            $('#hiddenalpha').val(linktext);
            $('#userId').submit();
}
	function setStatus(val1){
        var status = $("#statusHidden_"+val1).val();
        if(status ==1){
              var newStatus = 0;
              var msz = "Are you sure you want to deactivate this record? ";
        }else{
           var newStatus = 1;
           var msz = "Are you sure you want to activate this record?";
        }
        if (!confirm(msz)) {
                           return false;
        }
         $.ajax({
           url: '/admin/admins/setnewStatus/'+val1+'/'+newStatus+'/User',
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