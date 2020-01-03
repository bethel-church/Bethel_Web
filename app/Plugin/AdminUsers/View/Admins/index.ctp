    <?php    
        echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox');
		echo $this->Html->script('AdminUsers.admins');
		echo $this->Html->css('AdminUsers.admins');
    ?>   
   
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
        echo $this->Form->create('Search', array('url' => array('controller' => 'Admins', 'action' => 'index'),'id'=>'AdminId','type'=>'get'));  ?>
	
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
				 <span class="blue">(<b>Search by:</b>First Name, Last Name, Email)</span>
            </div>
           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
				<?php echo $this->Html->link('List All',array('controller'=>'Admins','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New Admin',array('controller'=>'admins','action'=>'addedit'),array('class' => 'icon-file-alt','title' => 'Add Admin'));?>
                </div>
            </div>
        </div>
		
        <?php echo $this->Form->end(); ?>
		
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('Admin', array('url' => array('controller' => 'Admins', 'action' => 'index'),'id'=>'AdminFormId'));  ?>
    
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
                            <th><?php echo $this->Paginator->sort('first_name', 'First Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('last_name', 'Last Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
                            <th class="th_checkbox"><?php echo $this->Paginator->sort('phone', 'Phone'); ?> </th>
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
                            <td><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['Admin']['id']);?>" ></td>     
                           <?php   $status = $getData['Admin']['status'];
                                    $statusImg = ($getData['Admin']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['Admin']['id'])); ?>
                            <?php  $pid = $getData['Admin']['id'];?>
                            <td><?php echo $this->Html->link($this->Html->image("AdminUsers.".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['Admin']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                            <td><?php echo $getData['Admin']['first_name'];?></td>
                            <td><?php echo $getData['Admin']['last_name'];?></td>
                            <td><?php echo $this->Html->link($getData['Admin']['email'],"mailto:".$getData['Admin']['email'],array());?></td>
                            <td><?php echo $getData['Admin']['phone']; ?></td>
                            <td><?php echo date('M j, Y',strtotime($getData['Admin']['created']));?></td>                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("AdminUsers.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'Admins','action'=>'addedit',base64_encode($getData['Admin']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("AdminUsers.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller'=>'Admins','action'=>'delete',base64_encode($getData['Admin']['id'])),array('escape' =>false),"Are you sure you wish to delete this Admin?");
								
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
                            echo $this->element('AdminUsers.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('AdminUsers.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("AdminUsers.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("AdminUsers.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("AdminUsers.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("AdminUsers.inactive.png"). " Inactive"; ?> </div>
                     
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('AdminUsers.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>