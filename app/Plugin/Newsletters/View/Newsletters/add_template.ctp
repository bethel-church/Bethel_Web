<?php  echo $this->Html->css('Newsletters.newsletter');  
       echo $this->Html->script('Newsletters.newsletter');
       echo $this->Html->script('ckeditor/ckeditor');
   ?>
       <?php echo $this->Form->create(null, array('url' => array('controller' => 'newsletters', 'action' => 'add_template'),'id'=>'templateId'));              
              echo $this->Form->hidden('NewsletterTemplate.id',array('value'=>base64_encode($this->data['NewsletterTemplate']['id']))); 
       ?>
       <div class="row">
              <div class="col-lg-8">        
               
                <div class="col-lg-12">
                      <div class="form-group form-spacing">
                        <div class="col-lg-2 form-label">
                          <label>Title<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-5 form-box">                
                          <?php echo $this->Form->input('NewsletterTemplate.title',array('label' => false,'div' => false, 'placeholder' => 'Name','class' => 'form-control','maxlength' => 100));?>
                        </div>
                        <div class="col-lg-5">
                          <!--blank div-->
                        </div>
                      </div>
                    </div>
                  </div>
       </div><!-- /.row -->
           <div class="row">        
           <div class="col-lg-8 form-spacing">
             <div class="col-lg-12"> 
               <div class="form-group">
               <div class="col-lg-2 form-label">
                   <label>Template<span class="required"> * </span></label>  
               </div> 
               <div class="col-lg-10 form-box">             
                   <?php echo $this->Form->input('NewsletterTemplate.template', array('label' => false,'div' => false,'class' => 'ckeditor'));?>
                   <?php if(isset($editorError) && $editorError == 1){
                                        echo '<div class="error" >Please enter template body.</div>';
                                     }?>
               </div>
               </div>
             </div>
           </div>
           
            <div class="col-lg-8">
             <div class="col-lg-12">
               <div class="col-lg-2">
                 <!--blank div-->
               </div>
               <div class="col-lg-10 form-box">
               <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>             
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
               
               </div>
             </div>     
           </div> 
       </div><!-- /.row -->
       <?php echo $this->Form->end(); ?>