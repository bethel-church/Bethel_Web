
    <?php echo $this->Html->script('Login.forgotpassword');?>
    <?php echo $this->Html->css('Login.login');  ?>
    <div class="panel-body">        
            <?php echo $this->Form->create(null, array('url' => array('controller' => 'AdminLogins', 'action' => 'forgot_password'),'id'=>'forgotPasswordId'));?>
            <fieldset>
                <?php echo $this->Session->flash();?>                              
                <div class="form-group form_margin">                                        
                    <?php echo $this->Form->input('Admin.email',array('label' => false,'div' => false, 'placeholder' => 'E-mail','class' => 'form-control user-name','maxlength' => 55));?>                
                </div>                
                <div class="row">                                                            
                    <div class="col-lg-2">
                        <?php echo $this->Form->submit('Submit',array('class' => 'btn btn-default'));?>
                    </div>
                    <div class="col-lg-2">                        
                        <?php echo $this->Html->link('Cancel',array('controller'=>'AdminLogins','action'=>'login'),array('class' => 'btn btn-default'));?>
                    </div>                    
                </div>                
            </fieldset>
            <?php echo $this->Form->end(); ?>
    </div>