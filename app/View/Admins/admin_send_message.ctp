    
    <?php echo $this->Html->script('admin/admin_editprofile');?>
    		
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <div class="row">
            <div class="col-lg-6">                        
                <?php echo $this->Session->flash();?>   
            </div> 
            <div class="col-lg-6">                        
                <div class="addbutton">                
                        <?php echo $this->Html->link('Twilio Api Settings','/admin/admins/twilio_settings/',array('title' => 'Twilio Settings'));?>
                </div>
            </div>
    </div>   
    <div class="row">        
        <?php echo $this->Form->create('SendMessage', array('url' => array('controller' => 'admins', 'action' => 'sendMessage'),'id'=>'editProfileId'));?>
        <div class="col-lg-6">
            <div class="form-group form_margin">
                <label>Receiver Number<span class="required"> * </span></label>                
                <?php echo $this->Form->input('message_to',array('label' => false,'div' => false, 'placeholder' => 'Receiver Number','class' => 'form-control','maxlength' => 30));?>
            </div>
        
            <div class="form-group form_margin">
                <label>Message</label>                
                <?php echo $this->Form->input('message',array('type'=>'textarea','label' => false,'div' => false, 'placeholder' => 'Message','class' => 'form-control'));?>
            </div>
            
                     
            <?php echo $this->Form->button("Send", array('type' => 'submit','class' => 'btn btn-default'));?>             
            <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.row -->