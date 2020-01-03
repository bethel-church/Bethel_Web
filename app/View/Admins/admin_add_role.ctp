   <?php  echo $this->Html->script('admin/admin_roles');?>    
   
   <div class="row">        
      <?php echo $this->Form->create('AdminRole', array('url' => array('controller' => 'admins', 'action' => 'addRole'),'id'=>'adminRoleId'));              
            echo $this->Form->hidden('AdminRole.id',array('value'=>base64_encode($this->data['AdminRole']['id']))); 
      ?>
      <div class="col-lg-5">           
      
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Role Name<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('role_name',array('label' => false,'div' => false, 'placeholder' => 'Role Name','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Role Description<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('role_description',array('type'=>'textarea','label' => false,'div' => false, 'placeholder' => 'Role Description','class' => 'form-control'));?>
               </div>
            </div>
         </div>  
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label"> 
                  <label>Activate</label>
               </div>
               <div class="col-lg-8 form-box">  
                  <label class="checkbox-inline"><?php if(isset($this->request->data['AdminRole']['status']) && $this->request->data['AdminRole']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>
                     <?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
                  </label>
               </div>
            </div>
         </div>
         <div class="col-lg-12 form-spacing">
            <div class="col-lg-4">
               <!--blank Div-->
            </div>
            <div class="col-lg-8 form-box">
               <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>
               &nbsp;
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>            
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->