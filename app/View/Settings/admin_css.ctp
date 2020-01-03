<?php
   echo $this->Html->script('admin/admin_setting_css');
  
?>
   <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
     <?php  echo $this->Form->create('UiSetting', array('url' => array('controller' => 'settings', 'action' => 'css'),'id'=>'cssform','type' => 'file'));            
           
        ?>
     <div class="row">
         <div class="col-lg-12">        
             <div class="col-lg-12">
                 <div class="form-group form-spacing">
                     <div class="col-lg-2 form-label">
                         <label>File Name<span class="required"> * </span></label>
                     </div>
                     <div class="col-lg-5 form-box">                
                          <?php echo $this->Form->input('UiSetting.file_name',array('id'=>'uiFileName','type'=>'select','options'=>$files,'label' => false,'div' => false, 'empty' => 'Select File Name','class' => 'form-control','escape'=>false));?>
                     </div>
                     <div class="col-lg-5">
                       <!--blank div-->
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="row">
         <div class="col-lg-12">        
             <div class="col-lg-12">
                 <div class="form-group form-spacing">
                     <div class="col-lg-2 form-label">
                         <label>File Content<span class="required"> * </span></label>
                     </div>
                     <div class="col-lg-10 form-box">                
                         <?php echo $this->Form->input('content',array('id'=>'fileContent','type'=>'textarea','rows'=>10,'label' => false,'div' => false, 'placeholder' => 'Enter File Content','class' => 'form-control'));?>
                     </div>
                     
                 </div>
             </div>
         </div>
     </div>

      <div class="col-lg-12 form-spacing">&nbsp;</div>
       
       <div class="col-lg-12">
           <div class="col-lg-12">
               <div class="col-lg-2">
                 <!--blank div-->
               </div>
               <div class="col-lg-10 form-box">
                   <?php echo $this->Form->button("Save", array('type' => 'submit','class' => 'btn btn-default'));?>
                    &nbsp;
                   <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
                   
               </div>
           </div>     
       </div>
       
       <div style="clear: both;"></div>
       
      <div class="col-lg-12 form-spacing">&nbsp;</div>
     <?php echo $this->Form->end(); ?>
     </div>

     
     
     
     