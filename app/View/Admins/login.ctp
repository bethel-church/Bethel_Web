<?php              
        echo $this->Html->css('Login.login');   
        echo $this->Html->css('material');              
        echo $this->Html->script('Login.admin_login');
?>
<div>
<div class="panel-body">        
        <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'login'),'id'=>'loginId', 'autocomplete'=>'off'));?>
        <fieldset>
                       
            <div class="form-group form_margin">  
            <div><div class="bethel_login_heading"><img height="50" src="/img/bethel.png" /></div></div>
            <div>&nbsp;</div>
             <div class="group">     
             
                <?php echo $this->Form->input('email',array('label' => false,'div' => false, 'required'=>true,'placeholder' => '','autocomplete'=>'off','class' => 'fancyInput','maxlength' => 55, 'type'=> 'text', 'error'=>false));?>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email Address</label> 
            </div>
             
              <div class="group">  
                 
                <?php echo $this->Form->input('password',array('label' => false,'div' => false, 'required'=>true,'placeholder' => '', 'autocomplete'=>'off','class' => 'fancyInput','maxlength' => 30,'type '=> 'password', 'error'=>false));?>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label> 
            </div>
              
              
            <div class="checkbox">
                <span style="float:left;margin-left:20px">
                    <?php 
                    
                    echo $this->Form->input('remember_me',array('label' => false,'div' => false,'type '=> 'checkbox','checked' => $remember_me));?>Remember Me
                </span>   
                <span style="float:right">
                    <?php echo $this->Form->submit('SIGN IN',array('class' => 'btn grey_btn btn-default'));?>                
                </span>
                
            </div>
            
        </fieldset>
        <?php echo $this->Form->end(); ?>
</div>
<div class="loginError"><?php echo $this->Session->flash();?></div>
</div>