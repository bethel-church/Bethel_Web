    <?php    
        echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox');
		echo $this->Html->script('Users.user_list');
		echo $this->Html->css('Users.user');
    ?>   
   
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
        echo $this->Form->create('Search', array('url' => array('controller' => 'users', 'action' => 'index'),'id'=>'userId','type'=>'get'));  ?>
		<?php echo $this->Form->hidden('alphabet_letter',array('id'=>'hiddenalpha')); ?>
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
				 <span class="blue">(<b>Search by:</b>First Name, Last Name, Email)</span>
            </div>
           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
				<?php echo $this->Html->link('List All',array('controller'=>'users','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New User',array('controller' => 'users', 'action' => 'addedit'),array('class' => 'icon-file-alt','title' => 'Add User'));?>
                </div>
            </div>
        </div>
		<div class="row padding_btm_20"> 
			<ul class="letters clearfix">
				<li class="widthFirst"><span class="blue"><b>Alphabetic Search:</b></span></li>
				<?php
				foreach($alphabetArray as $alphabetArr)
				{
					if($alphabetArr == $alphakeyword){ $active = "activeAlpha"; }else{ $active = "";}
					echo "<li>";						
					echo $this->Html->link($alphabetArr,'javascript:void(0)',array('escape' =>false,'onClick'=>'alphaSearch(this)','class'=>$active));
					echo "</li>";
				}
				?>
			</ul>
		</div>
        <?php echo $this->Form->end(); ?>
		
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'index'),'id'=>'UserFormId'));  ?>
    
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
                            <th><?php echo $this->Paginator->sort('first_name', 'Name'); ?></th>
                        
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
                            <th class="th_checkbox"><?php echo $this->Paginator->sort('gender', 'Gender'); ?> </th>
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
                            <td><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['User']['id']);?>" ></td>     
                           <?php   $status = $getData['User']['status'];
                                    $statusImg = ($getData['User']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['User']['id'])); ?>
                            <?php  $pid = $getData['User']['id'];?>
                            <td><?php echo $this->Html->link($this->Html->image("Users.".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['User']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                            <td><?php echo $this->Html->link($getData['User']['first_name'].' '.$getData['User']['last_name'],"/users/users/addedit/".base64_encode($getData['User']['id']),array('escape' =>false)); ?></td>
                          
                            <td><?php echo $this->Html->link($getData['User']['email'],"mailto:".$getData['User']['email'],array());?></td>
                            <td><?php
                                $gender = ($getData['User']['gender'] == 0) ? 'female' : 'male';
                                echo $this->Html->image("Users.".$gender.".png", array("alt" => ucfirst($gender),"title" => ucfirst($gender)));
                            ?></td>
                            <td><?php echo date('M j, Y',strtotime($getData['User']['created']));?></td>                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("Users.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller' => 'users', 'action' => 'addedit',base64_encode($getData['User']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("Users.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller' => 'users', 'action' => 'delete',base64_encode($getData['User']['id'])),array('escape' =>false),"Are you sure you wish to delete this user?");
								echo $this->Html->link($this->Html->image("Users.view.png", array("alt" => "User Detail","title" => "User Detail")),array('controller' => 'users', 'action' => 'view',base64_encode($getData['User']['id'])),array('escape' =>false,'class' => 'fancybox fancybox.ajax'));                                
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
                            echo $this->element('Users.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('Users.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("Users.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Users.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Users.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Users.inactive.png"). " Inactive"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Users.view.png"). " View"; ?> </div>
					 <div class="col-lg-2"> &nbsp;</div>
					 <div class="col-lg-2"> <?php echo $this->Html->image("Users.male.png"). " Male"; ?> </div>
					 <div class="col-lg-2"> <?php echo $this->Html->image("Users.female.png"). " Female"; ?> </div>
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Users.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>