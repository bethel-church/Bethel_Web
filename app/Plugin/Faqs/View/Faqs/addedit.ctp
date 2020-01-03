    <?php     echo $this->Html->script('Faqs.faqs');
              echo $this->Html->css('Faqs.faq');
              echo $this->Html->script('ckeditor/ckeditor');
    ?>
    

     <?php echo $this->Form->create(null, array('url' => array('controller' => 'faqs', 'action' => 'addedit',base64_encode($this->data['Faq']['id'])),'id'=>'faqId'));              
               echo $this->Form->hidden('Faq.id',array('value'=>base64_encode($this->data['Faq']['id']))); 
         ?>
     <div class="row">
         <div class="col-lg-8">        
             <div class="col-lg-12">
                 <div class="form-group form-spacing">
                     <div class="col-lg-2 form-label">
                         <label>Question<span class="required"> * </span></label>
                     </div>
                     <div class="col-lg-5 form-box">                
                         <?php echo $this->Form->input('title',array('label' => false,'div' => false, 'placeholder' => 'Question','class' => 'form-control','maxlength' => 150));?>
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
                        <label>Answer</label>  
                    </div> 
                    <div class="col-lg-10 form-box">             
                        <?php echo $this->Form->input('description', array('label' => false,'div' => false,'class' => 'ckeditor'));?>
                              <?php if(isset($editorError) && $editorError == 1){
                                        echo '<div class="error" >Please enter the answer.</div>';
                                     }?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 form-spacing">
            <div class="col-lg-12"> 
                <div class="form-group">
                    <div class="col-lg-2 form-label">
                        <label>Activate</label>  
                    </div> 
                    <div class="col-lg-10 form-box"><?php if(isset($this->request->data['Faq']['status']) && $this->request->data['Faq']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>      
                        <?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
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
                      &nbsp;
                     <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
                     
                 </div>
             </div>     
         </div>
         <div class="col-lg-12 form-spacing">&nbsp;</div>
     </div><!-- /.row -->
     <?php echo $this->Form->end(); ?>