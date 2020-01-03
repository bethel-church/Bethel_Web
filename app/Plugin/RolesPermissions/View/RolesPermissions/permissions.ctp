   <?php  echo $this->Html->script('RolesPermissions.roles');?>    
   
   <div class="row">        
      <?php echo $this->Form->create('RolePermission', array('url' => array('controller' => 'RolesPermissions', 'action' => 'permissions'),'id'=>'categoryId'));              
            echo $this->Form->hidden('RolePermission.role_id',array('value'=>$id));
            echo $this->Form->hidden('RolePermission.id',array('value'=>(isset($role_data['RolePermission']['id'])) ? $role_data['RolePermission']['id'] : ''));
      ?>
      <div class="col-lg-12">           
      
        
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-2 form-label"> 
                  <label>Permissions</label>
               </div>
               <div class="col-lg-10 form-box"> 
                <?php if(isset($role_data['RolePermission']['permission_ids']) && !empty($role_data['RolePermission']['permission_ids'])){
                       $explodeArr =  explode(',',$role_data['RolePermission']['permission_ids']);
                    }else{ $explodeArr[] = ""; } ?>
                <?php   foreach($permissions as $val){ 
                        if(in_array($val['Module']['id'],$explodeArr)) { $check = "'checked'=>'checked'"; }else{ $check= ""; } ?>
                    <div class="col-lg-3 form-box">
                        <label class="checkbox-inline">
                           <?php echo $this->Form->input('RolePermission.permission_id.'.$val['Module']['id'],array('label' => $val['Module']['module_name'],'div' => false,'type '=> 'checkbox' , 'checked' => (in_array($val['Module']['id'],$explodeArr)) ? 'checked' : ''));?>
                        </label>
                    </div>
                <?php } ?>
                    
                   
               </div>
            </div>
         </div>
         <div class="col-lg-12 form-spacing">
            <div class="col-lg-4">
               <!--blank Div-->
            </div>
            <div class="col-lg-8 form-box">
               <?php echo $this->Form->button("Save", array('type' => 'submit','class' => 'btn btn-default'));?>
               &nbsp;
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>            
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->