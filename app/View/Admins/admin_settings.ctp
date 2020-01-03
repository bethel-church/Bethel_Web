   <?php  echo $this->Html->script('admin/admin_setting');?>    
   
   <div class="row">        
      <?php echo $this->Form->create('Setting', array('url' => array('controller' => 'admins', 'action' => 'settings'),'id'=>'categoryId'));              
            echo $this->Form->hidden('Setting.id',array('value'=>base64_encode($this->data['Setting']['id']))); 
      ?>
      <div class="col-lg-8">           
         <div class="col-lg-12"><h3>Paypal API Details</h3></div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>API Signature<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('api_sign',array('label' => false,'div' => false, 'placeholder' => 'Api Signature','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>API Username<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('api_user',array('label' => false,'div' => false, 'placeholder' => 'Api Username','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
          <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>API Password<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('api_pass',array('label' => false,'div' => false, 'placeholder' => 'Api Username','class' => 'form-control','maxlength' => 255));?>
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
                            echo $this->Form->radio('enviroment', $options, $attributes);               
                        ?>
                  </label>
               </div>
            </div>
         </div>
         <div class="col-lg-12"><h3>Braintree API Details</h3></div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Merchant ID</label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('braintree_merchant_id',array('type'=>'text','label' => false,'div' => false, 'placeholder' => 'Merchant ID','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Public Key</label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('braintree_public_key',array('label' => false,'div' => false, 'placeholder' => 'Public Key','class' => 'form-control','maxlength' => 255));?>
               </div>
            </div>
         </div>
          <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Private Key</label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('braintree_private_key',array('label' => false,'div' => false, 'placeholder' => 'Private Key','class' => 'form-control','maxlength' => 255));?>
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
                            echo $this->Form->radio('braintree_environment', $options, $attributes);               
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
               <?php echo $this->Form->button($action, array('type' => 'submit','class' => 'btn btn-default'));?>
               &nbsp;
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>            
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->