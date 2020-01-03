    <?php        
        echo $this->Html->script('admin/admin_changepassword');
    ?>    
    <div class="row">            
            <div class="col-lg-6">                        
               <?php echo $this->Session->flash();?>   
            </div>             
    </div>
    
    <div class="row">
        <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'changepassword'),'id'=>'changePasswordId'));?>
        <div class="col-lg-6">
          
            <div class="form-group form_margin">
                <label>Old Password<span class="required"> * </span></label>                
                <?php echo $this->Form->input('old_password',array('label' => false,'div' => false, 'placeholder' => 'Old Password','class' => 'form-control  required','maxlength' => 30,'type' => 'password'));?>                
            </div>
            
            <div class="form-group form_margin">
                <label>New Password<span class="required"> * </span></label>
                <?php echo $this->Form->input('password',array('label' => false,'div' => false, 'placeholder' => 'New Password','class' => 'form-control required','maxlength' => 30,'type' => 'password'));?>                
            </div>
            
            <div class="form-group form_margin">
                <label>Confirm Password<span class="required"> * </span></label>
                <?php echo $this->Form->input('confirm_password',array('label' => false,'div' => false, 'placeholder' => 'Confirm Password','class' => 'form-control required','maxlength' => 30,'type' => 'password'));?>
              
            </div>         
            
            <?php echo $this->Form->button('Update', array('type' => 'submit','class' => 'btn btn-default'));?>
            <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
            
        </div>
        
    </div><!-- /.row -->