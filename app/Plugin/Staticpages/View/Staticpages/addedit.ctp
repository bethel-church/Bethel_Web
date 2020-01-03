    <?php  echo $this->Html->script('Staticpages.staticpages');
           echo $this->Html->script('ckeditor/ckeditor');
           echo $this->Html->css('Staticpages.pages'); 
    ?>
    
   
     <?php echo $this->Form->create(null, array('url' => array('controller' => 'staticpages', 'action' => 'addedit'),'id'=>'staticPageFormId'));              
               echo $this->Form->hidden('Staticpage.id',array('value'=>base64_encode($this->data['Staticpage']['id']))); 
         ?>
     <div class="row">
         <div class="col-lg-8">        
             <div class="col-lg-12">
                 <div class="form-group form-spacing">
                     <div class="col-lg-2 form-label">
                         <label>Page Name<span class="required"> * </span></label>
                     </div>
                     <div class="col-lg-5 form-box">                
                         <?php echo $this->Form->input('title',array('label' => false,'div' => false, 'placeholder' => 'Page Name','class' => 'form-control','maxlength' => 50));?>
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
                         <label>Keyword</label>
                     </div>
                     <div class="col-lg-5 form-box">                
                         <?php echo $this->Form->input('keyword',array('label' => false,'div' => false, 'placeholder' => 'Keyword','class' => 'form-control','maxlength' => 255));?>
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
                         <label>Meta Description</label>
                     </div>
                     <div class="col-lg-5 form-box">                
                         <?php echo $this->Form->input('meta_description',array('type'=>'textarea','label' => false,'div' => false, 'placeholder' => 'Meta Description','class' => 'form-control','maxlength' => 500));?>
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
                         <label>Meta Tags</label>
                     </div>
                     <div class="col-lg-5 form-box">                
                         <?php echo $this->Form->input('meta_tag',array('label' => false,'div' => false, 'placeholder' => 'Meta Tag','class' => 'form-control','maxlength' => 255));?>
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
                         <label>Page Position<span class="required"> * </span></label>
                     </div>
                     <div class="col-lg-5 form-box" style="margin-top:5px;">                
                      <?php                
                      $options = array(0 => ' Header', 1 => ' Footer',2=>' Left');
                      $attributes = array('legend' => false,'class'=>'radio-inline','separator'=>'&nbsp;&nbsp;&nbsp;','default'=>0);               
                      echo $this->Form->radio('page_position', $options, $attributes);               
                     ?>
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
                        <label>Page Content</label>  
                    </div> 
                    <div class="col-lg-10 form-box">             
                        <?php echo $this->Form->input('description', array('label' => false,'div' => false,'class' => 'ckeditor'));?>
                    </div>
                </div>
            </div>
        </div>
     </div>
     <div class="row">        
        <div class="col-lg-8 form-spacing">
            <div class="col-lg-12"> 
                <div class="form-group">
                    <div class="col-lg-2 form-label">
                        <label>Activate</label>  
                    </div> 
                    <div class="col-lg-10 form-box">  <?php if(isset($this->request->data['Staticpage']['status']) && $this->request->data['Staticpage']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>      
                        <?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
                    </div>
                </div>
            </div>
        </div>
     </div>
         <div class="row">   
        <div class="col-lg-12 form-spacing">&nbsp;</div>
         
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