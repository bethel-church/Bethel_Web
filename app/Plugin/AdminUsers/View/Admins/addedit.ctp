<?php   echo $this->Html->script('AdminUsers.admins');
        echo $this->Html->css('AdminUsers.admins');?>    
    <div class="row">
            <div class="col-lg-6">                        
                <?php echo $this->Session->flash();?>   
            </div> 
            <div class="col-lg-6">                        
                <div class="addbutton">                
                        
                </div>
            </div>
    </div>   
    <div class="row">        
        <?php echo $this->Form->create(null, array('url' => array('controller' => 'admins', 'action' => 'addedit'),'id'=>'editProfileId'));
                echo $this->Form->hidden('id',array('value'=>$this->data['Admin']['id']));
                ?>
        <div class="col-lg-6">
            <div class="form-group form_margin">
                <label>First Name<span class="required"> * </span></label>                
                <?php echo $this->Form->input('first_name',array('label' => false,'div' => false, 'placeholder' => 'First Name','class' => 'form-control','maxlength' => 25));?>
            </div>
        
            <div class="form-group form_margin">
                <label>Last Name</label>                
                <?php echo $this->Form->input('last_name',array('label' => false,'div' => false, 'placeholder' => 'Last Name','class' => 'form-control','maxlength' => 25));?>
            </div>
            <!--<div class="form-group form_margin">
                <label>Role<span class="required"> * </span></label>                
                <?php echo $this->Form->input('admin_role_id',array('type'=>'select','options'=>$roles,'label' => false,'div' => false, 'empty' => 'Select Role','class' => 'form-control'));?>
            </div>
            <?php
            $readonly = "";
            if($this->request->data['Admin']['id'] !=""){
                $readonly = "readonly";
            }?>
            <div class="form-group form_margin">
                <label>Email<span class="required"> * </span></label>                
                <?php echo $this->Form->input('email',array('label' => false,'div' => false, 'placeholder' => 'Email','class' => 'form-control','maxlength' => 50,$readonly));?>
            </div>
            <?php if($this->request->data['Admin']['id'] ==""){ ?>
            <div class="form-group form_margin">
                <label>Password<span class="required"> * </span></label>                
                <?php echo $this->Form->input('password',array('type'=>'password','label' => false,'div' => false, 'placeholder' => 'Password','class' => 'form-control','maxlength' => 20));?>
            </div>
            <?php } ?>
            <div class="form-group form_margin">
                <label>Phone</label>
                <?php echo $this->Form->input('phone',array('label' => false,'div' => false, 'placeholder' => 'Phone','class' => 'form-control','maxlength' => 15));?>
            </div>
            <div class="form-group form_margin">
                <label>Activate </label>                
                <?php if(isset($this->request->data['Admin']['status']) && $this->request->data['Admin']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>
                     <?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
            </div>
             -->
                     
            <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>             
            <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.row -->