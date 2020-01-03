    
    <?php echo $this->Html->script('admin/admin_editprofile');?>    
    <div class="row">
            <div class="col-lg-6">                        
                <?php echo $this->Session->flash();?>   
            </div> 
            <div class="col-lg-6">                        
                <div class="addbutton">                
                        <?php //echo $this->Html->link('Back','/admin/admins/dashboard/',array('title' => 'Back'));?>
                </div>
            </div>
    </div>   
    <div class="row">        
        <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'addedit'),'id'=>'editProfileId'));?>
        <div class="col-lg-6">
            <div class="form-group form_margin">
                <label>First Name<span class="required"> * </span></label>                
                <?php echo $this->Form->input('first_name',array('label' => false,'div' => false, 'placeholder' => 'First Name','class' => 'form-control','maxlength' => 30));?>
            </div>
        
            <div class="form-group form_margin">
                <label>Last Name</label>                
                <?php echo $this->Form->input('last_name',array('label' => false,'div' => false, 'placeholder' => 'Last Name','class' => 'form-control','maxlength' => 20));?>
            </div>
            
                     
            <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>             
            <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.row -->