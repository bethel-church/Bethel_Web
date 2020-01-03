<?php   echo $this->Html->script('Countries.admins');
        echo $this->Html->css('Countries.admins');?>    
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
        <?php echo $this->Form->create(null, array('url' => array('controller' => 'Countries', 'action' => 'addedit'),'id'=>'editProfileId'));
                echo $this->Form->hidden('id',array('value'=>$this->data['Country']['id']));
                ?>
        <div class="col-lg-6">
            <div class="form-group form_margin">
                <label>Name<span class="required"> * </span></label>                
                <?php echo $this->Form->input('name',array('label' => false,'div' => false, 'placeholder' => 'First Name','class' => 'form-control','maxlength' => 30));?>
            </div>
        
            <div class="form-group form_margin">
                <label>Activate </label>                
                <?php if(isset($this->request->data['Country']['status']) && $this->request->data['Country']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>
                     <?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
            </div>
             
                     
            <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>             
            <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.row -->