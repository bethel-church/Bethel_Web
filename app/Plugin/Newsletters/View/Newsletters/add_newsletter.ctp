<?php
       echo $this->Html->script('Newsletters.newsletter');
       echo $this->Html->css('Newsletters.newsletter'); 
       echo $this->Html->script('ckeditor/ckeditor');
       
?>
       <?php echo $this->Form->create(null, array('url' => array('controller' => 'newsletters', 'action' => 'add_newsletter'),'id'=>'newsletterId'));              
              echo $this->Form->hidden('Newsletter.id',array('value'=>base64_encode($this->data['Newsletter']['id']))); 
       ?>
       <div class="row">
              <div class="col-lg-8">        
               
                <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Subject<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-5 form-box">                
                          <?php echo $this->Form->input('Newsletter.title',array('label' => false,'div' => false, 'placeholder' => 'Name','class' => 'form-control','maxlength'=>100));?>
                        </div>
                        <div class="col-lg-5">
                          <!--blank div-->
                        </div>
                      </div>
                    </div>
                  </div>
       </div><!-- /.row -->
         <div class="row">
              <div class="col-lg-8">        
               
                <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Template</label>
                        </div>
                        <div class="col-lg-5 form-box">                
                          <?php echo $this->Form->input('Newsletter.newsletter_template_id',array('type'=>'select','options'=>$template,'label' => false,'div' => false, 'empty' => 'Select Template','class' => 'form-control','onchange'=>'getTemplate(this.value)'));?>
                        </div>                        
                        <div class="col-lg-5 form-label">
                         <label>&nbsp; <?php echo $this->Html->link("Add New Template",array("controller"=>"Newsletters","action"=>"add_template")); ?></label>
                        </div>
                      </div>
                    </div>
                  </div>
       </div><!-- /.row -->
       <div class="row">
              <div class="col-lg-8">        
               
                <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Description<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-10 form-box">                
                           <?php echo $this->Form->input('Newsletter.description', array('id'=>'newletterTemp','label' => false,'div' => false,'class' => 'ckeditor'));?>
                           <?php if(isset($editorError) && $editorError == 1){
                                        echo '<div class="error" >Please enter descripton.</div>';
                                     }?>
                        </div>
                       
                      </div>
                    </div>
                  </div>
       </div><!-- /.row -->
       <div class="row">
              <div class="col-lg-8">        
               
                <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Send Type<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-5 form-box">     <?php $sendType =array(0=>'Send',1=>'Schedule');?>           
                           <?php echo $this->Form->input('Newsletter.send_type',array('type'=>'select','options'=>$sendType,'label' => false,'div' => false, 'empty' => '--Select--','class' => 'form-control','onchange'=>'togglediv(this.value)'));?>
                        </div>
                        <div class="col-lg-5">
                          <!--blank div-->
                        </div>
                      </div>
                    </div>
                  </div>
       </div><!-- /.row -->
       <div id="schedulerDiv" class="row" style="display:none;">
              <div class="col-lg-8">               
                     <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Schedule Date<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-5 form-box">  <?php if($this->request->data['Newsletter']['schedule_date'] !="") { $date  = date('m-d-Y',strtotime($this->request->data['Newsletter']['schedule_date'])); }else{ $date= ""; }?>           
                             <?php echo $this->Form->input('Newsletter.schedule_date',array('type'=>'text','label' => false,'div' => false, 'value'=>$date,'placeholder' => 'Schedule Date','class' => 'form-control datepicker','maxlength' => 55,'readonly'=>true));?>
                        </div>
                        <div class="col-lg-5">
                          <!--blank div-->
                        </div>
                      </div>
                    </div>
              </div>
       </div><!-- /.row -->
       <div class="row">
            <div class="col-lg-8">
             <div class="col-lg-12">
               <div class="col-lg-2">
                 <!--blank div-->
               </div>
               <div class="col-lg-10 form-box">
               <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>
               <?php echo $this->Form->button("Save and send", array('name'=>'save_send_button','type' => 'submit','value'=>'save_send','class' => 'btn btn-default'));?>
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
               <?php //echo $this->Html->link('Save & Send', '/admin/admins/send_newsletter/'.base64_encode($this->data['Newsletter']['id']),array('class' => 'btn btn-default'));?>
               
               </div>
             </div>     
           </div> 
       </div><!-- /.row -->
       <?php echo $this->Form->end(); ?>
        <script type= "text/javascript">
              function getTemplate(templateId)
              {
                     jQuery.ajax({
                            type: "POST",
                            url: "/newsletters/newsletters/getNewletterTemplate/"+templateId,
                           
                            success: function(result){
                                CKEDITOR.instances['newletterTemp'].setData(result);
                            }
                     });
                  
              }
              function togglediv(typeId){
                     if(typeId==1){
                            $('#schedulerDiv').show();
                     }else{
                            $('#schedulerDiv').hide();
                     }
                     
              }
       </script>
        
         <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 <script>
$(function() {
$( ".datepicker" ).datepicker();
});
</script>