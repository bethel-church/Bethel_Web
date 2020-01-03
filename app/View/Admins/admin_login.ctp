    <?php echo $this->Html->script('admin/admin_login'); ?>
    <div class="panel-body">        
            <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'login'),'id'=>'loginId'));?>
            <fieldset>
                <?php //echo $this->Session->flash();?>              
                <div class="form-group form_margin"> 
                    <div><div style="height:200px;width:200px:background-color:#f5f5f5">Bethel Missions</div></div>
                    <div>Email Address</div>                                       
                    <div><?php echo $this->Form->input('email',array('label' => false,'div' => false, 'placeholder' => '','class' => 'form-control user-name','maxlength' => 55));?>
                    </div>
                </div>
                <div class="form-group form_margin">                    
                    <?php echo $this->Form->input('password',array('label' => false,'div' => false, 'placeholder' => '','class' => 'form-control user-password','maxlength' => 30,'type '=> 'password'));?>
                </div>
                <div class="checkbox">
                    <label>
                        <?php
                        
                        
                        echo $this->Form->input('remember_me',array('label' => false,'div' => false,'type '=> 'checkbox','checked' => $remember_me));?>Remember Me
                       
                    </label>
                    
                    
                    <label style="float:right">
                        <?php echo $this->Html->link('Forgot password','/admin/admins/forgot_password',array('title' => 'Forgot password'));?>                        
                    </label>
                    
                </div>
                <?php echo $this->Form->submit('Login',array('class' => 'btn btn-default'));?>                
            </fieldset>
            <?php echo $this->Form->end(); ?>
    </div>