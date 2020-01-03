   <?php  echo $this->Html->script('admin/admin_setting');?>    
   
   <div class="row">        
      <?php echo $this->Form->create('Setting', array('url' => array('controller' => 'admins', 'action' => 'settings'),'id'=>'categoryId'));              
            echo $this->Form->hidden('Setting.id',array('value'=>base64_encode($this->data['Setting']['id']))); 
      ?>
      <div class="col-lg-8">           
         <div class="col-lg-12"><h3>Twilio Details</h3></div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>ACCOUNT SID <span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('twilio_sid',array('label' => false,'div' => false, 'placeholder' => 'Twilio Account SID','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label> AUTH TOKEN <span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('twilio_auth_token',array('label' => false,'div' => false, 'placeholder' => 'Auth Token','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label> Phone No. <span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('twilio_phone_no',array('label' => false,'div' => false, 'placeholder' => 'Phone No.','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label"> 
                  <label>Mode</label>
               </div>
               <div class="col-lg-8 form-box">  
                  <label class="checkbox-inline">
                        <?php                
                            $options = array('0' => 'Test', '1' => 'Live');
                            $attributes = array('legend' => false,'class'=>'radio-inline','separator'=>'&nbsp;&nbsp;&nbsp;','default'=>0);               
                            echo $this->Form->radio('twilio_enviroment', $options, $attributes);               
                        ?>
                  </label>
               </div>
            </div>
         </div>
        
         <div class="col-lg-12 form-spacing">
            <div class="col-lg-4">
               <!--blank Div-->
            </div>
            <div class="col-lg-8 form-box">
               <?php echo $this->Form->button("Save", array('type' => 'submit','class' => 'btn btn-default'));?>
               &nbsp;
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>            
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->