<?php
   //echo $this->Html->script('Users.users');
   echo $this->Html->css('material'); 
?><div class="row"> 
<div class="bigHeading leftAlign fullWidth">
   <div style="float:left" ><?php echo $heading;?> Trip</div><div class="validationErrors" style="float:right"><?php echo $this->Session->flash();?></div>
   </div>
   <div id='er' style="float:right;width:100%;margin-bottom:10px">
    <?php
      if(count($errors)>0){
        foreach ($errors as $key=>$error){
          foreach ($error as $subkey=>$msg){
            echo "<div class=\"errMsg\">$msg</div>";
          }
        }
      }
    ?>
   </div>
   <div>
      <?php echo $this->Form->create(null, array('url' => array('controller' => 'trips', 'action' => 'addedit1'),'type'=>'file','id'=>'userId'));              
             $vv = base64_encode($this->data['Trip']['id']);
            echo $this->Form->hidden('Trip.id',array('value'=>$vv));           
      ?>
         <div class="col-lg-5 fullWidth" >
           <!--<div class="col-lg-12"><h3><u>Basic Information</u></h3></div>-->
           
          
            <div class="col-lg-12" >
               <div class="form-group form-spacing">
                  <div class="leftAlign" style="width:45%">
                    <div class="group">     
               
                        <?php 
                        //pr($this->request->data);
                        echo $this->Form->input('name',array('label' => false,'div' => false, 'placeholder' => '','class' => 'fancyInput inputField','maxlength' => 55, 'type'=> 'text', 'error'=>false));
                        ?>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Trip Name</label> 
                   </div>
                 </div>
                 <div class="rightAlign" style="width:45%">
                    <div class="group" >    
                        <?php 
                        
                        echo $this->Form->input('leader_email',array('label' => false,'div' => false, 'placeholder' => '','class' => 'fancyInput inputField','maxlength' => 55, 'type'=> 'text', 'error'=>false));?>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label>Trip Leader Email Address</label> 
                      
                   </div>
                 
                  
                  </div>
                  <?php
                  echo $this->Form->hidden('user_passcode');
                  echo $this->Form->hidden('leader_passcode');
                  echo $this->Form->hidden('main_leader');
                  
                  ?>
                  <div style="clear:both"></div>
               </div>
            </div>
            
          
             
            <div class="col-lg-12 form-spacing" style="height:0px">
               &nbsp;
            </div>
            <div class="col-lg-12 form-spacing">
               
               <div class="form-box rightAlign">
                 <?php echo $this->Form->button('CANCEL', array('type' => 'button','onclick'=>'triplisting()','class' => 'btn btn-default btnLinkLgCancel'));?>
                 &nbsp;
                 <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default btnLinkLg'));?> 
               </div>
               
            </div>
         </div>   
      <?php echo $this->Form->end(); ?>    
      </div>       
   </div><!-- /.row -->
   <script type="text/javascript">
   function triplisting(){
    var url = '/trips/';
    $(location).attr('href',url);
   }
   var oldFile = "";
   var newFile = "";
   function selectFile(fileName){
    oldFile = $("#fileContainer").html();
    newFile = '<span class="st">Current File:</span> '+fileName;
    
    $("#fileContainer").html(fileName);
    $("#fileContainer").show();
    $("#fileUpload").show();
   }
   
   function uploadFile(){
      //alert($("#userId").attr("action"));
      var data = new FormData();
      jQuery.each(jQuery('#csvFile')[0].files, function(i, file) {
        data.append('csv', file);
      });
      
      jQuery.ajax({
        url: '/trips/csv/',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data){
        data = jQuery.parseJSON(data);
        //alert(data.status);
        if(typeof data =='object'){
  
        
          if(data.status == "success"){
            //var select = $('#leader_dropdown');
            var select = $("<select id=\"leader_dropdown\" class=\"trip_leader\" name=\"data[Trip][main_leader]\" />"); 
            select.empty().append('<option value="">--Select Leader--</option>');
            $.each(data.data, function( i, leader ) {
              select.append('<option value="'+i+'">'+leader+'</option>');
            });
           // $('#trip_sel').empty().html('<br />');
            $('#trip_sel').empty().append(select);
            
          } else if(data.status == "failure") {
            $("#fileContainer").html(oldFile);
            var message = '<div class="errMsg">'+data.data+'</div>';
            $("#er").html(message);
            var el = $("#csvFile");
            el.replaceWith(el.val('').clone(true));
          }
        }
                   
        }
      });
      
   }
   
    $(document ).ready(function() {
          $("input:file").change(function (e){
            var fileName = e.target.files[0].name;
            selectFile(fileName);
           
            
          });
          $("#close_icon").click(function (){
            $("#csvFile").val("");
            $("#fileContainer").hide();
          });
          
          $("#dfile").click(function (){
            $("#fileContainer").html(oldFile);
            $("#fileUpload").hide();
            var el = $("#csvFile");
            el.replaceWith(el.val('').clone(true));
          });
          $("#ufile").click(function (){
            $("#fileContainer").html(newFile);
            uploadFile();
            $("#fileUpload").hide();
          });
          
    });
   </script>