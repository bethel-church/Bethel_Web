    <?php echo $this->Html->script('admin/admin_forgotpassword');?>
    <div class="panel-body">        
            <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'forgot_password'),'id'=>'forgotPasswordId'));?>
            <fieldset>
                <?php echo $this->Session->flash();?>                              
                <div class="form-group form_margin">                                        
                    <?php echo $this->Form->input('email',array('label' => false,'div' => false, 'placeholder' => 'E-mail','class' => 'form-control user-name','maxlength' => 55));?>                
                </div>                
                <div class="row">                                                            
                    <div class="col-lg-2">
                        <?php echo $this->Form->submit('Submit',array('class' => 'btn btn-default'));?>
                    </div>
                    <div class="col-lg-2">                        
                        <?php echo $this->Html->link('Cancel','/admin/',array('class' => 'btn btn-default'));?>
                    </div>                    
                </div>                
            </fieldset>
            <?php echo $this->Form->end(); ?>
    </div>