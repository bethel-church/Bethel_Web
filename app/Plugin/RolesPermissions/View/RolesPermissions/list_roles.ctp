    <?php    
        echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox');
		echo $this->Html->script('RolesPermissions.roles');
		echo $this->Html->css('RolesPermissions.rolespermissions');
    ?>   
   <script>
	jQuery(document).ready(function() {
            jQuery('.fancybox').fancybox({
                maxWidth	: 400,
                maxHeight	: 600,
                fitToView	: false,
                width		: '70%',
                height		: '70%',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none'
            });
    });
            
	
   </script>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
        echo $this->Form->create('Search', array('url' => array('controller' => 'RolesPermissions', 'action' => 'listRoles'),'id'=>'AdminRoleId'));  ?>
		
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
				 <span class="blue">(<b>Search by:</b>Role Name)</span>
            </div>
           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
				<?php echo $this->Html->link('List All',array('controller'=>'RolesPermissions','action'=>'listRoles'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New Role',array('controller'=>'RolesPermissions','action'=>'addRole'),array('class' => 'icon-file-alt','title' => 'Add AdminRole'));?>
                </div>
            </div>
        </div>
		
        <?php echo $this->Form->end(); ?>
		
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('AdminRole', array('url' => array('controller' => 'RolesPermissions', 'action' => 'listRoles'),'id'=>'AdminRoleFormId'));  ?>
    
    <div class="row">
        <div class="col-lg-12">            
            <div class="table-responsive">               
                 
                <?php if($recordExits)
                { ?>
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th class="th_checkbox"><input type="checkbox" class="checkall"></th>
							<th class="th_checkbox"><?php echo $this->Paginator->sort('status', 'Status'); ?> </th>
                            <th><?php echo $this->Paginator->sort('role_name', 'Role Name'); ?></th>				            
                            <th class="th_checkbox"><?php echo $this->Paginator->sort('created', 'Created'); ?> </th>                            
                            <th class="th_checkbox">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="dyntable">
                        <?php
                        $i = 0;
                        
                        foreach($getData as $key => $getData)
                        {
                            $class = ($i%2 == 0) ? ' class="active"' : '';
                            ?>
                        <tr <?php echo $class;?>>
                            <td ><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['AdminRole']['id']);?>" ></td>     
                           <?php   $status = $getData['AdminRole']['status'];
                                    $statusImg = ($getData['AdminRole']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['AdminRole']['id'])); ?>
                            <?php  $pid = $getData['AdminRole']['id'];?>
                            <td ><?php echo $this->Html->link($this->Html->image("admin/".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['AdminRole']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                            <td><?php echo $getData['AdminRole']['role_name'];?></td>
                            
                            <td ><?php echo date('M j, Y',strtotime($getData['AdminRole']['created']));?></td>                            
                            <td >
                            <?php
                                echo $this->Html->link($this->Html->image("RolesPermissions.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'RolesPermissions','action'=>'addRole',base64_encode($getData['AdminRole']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("RolesPermissions.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller'=>'RolesPermissions','action'=>'deleteRole',base64_encode($getData['AdminRole']['id'])),array('escape' =>false),"Are you sure you wish to delete this role?");
								echo $this->Html->link($this->Html->image("RolesPermissions.view.png", array("alt" => "Admin Detail","title" => "Role Detail")),array('controller'=>'RolesPermissions','action'=>'viewRole',base64_encode($getData['AdminRole']['id'])),array('escape' =>false,'class' => 'fancybox fancybox.ajax'));
								echo $this->Html->link($this->Html->image("RolesPermissions.lock.png", array("alt" => "Permissions","title" => "Permissions")),array('controller'=>'RolesPermissions','action'=>'permissions',base64_encode($getData['AdminRole']['id'])),array('escape' =>false,'class' => ''));                                
                            ?>
                            
                            </td>                    
                        </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                    
                </table>
                <div class="row oprdiv">
                  <div class="col-lg-12 actiondivinr"> 
                     <?php
                        if($recordExits)
                        {
                            echo $this->element('RolesPermissions.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('RolesPermissions.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("RolesPermissions.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("RolesPermissions.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("RolesPermissions.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("RolesPermissions.inactive.png"). " Inactive"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("RolesPermissions.view.png"). " View"; ?> </div>
					 <div class="col-lg-2"> &nbsp;</div>
					 <div class="col-lg-2"> <?php echo $this->Html->image("RolesPermissions.lock.png"). " Permissions"; ?> </div>
					 
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('RolesPermissions.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>