    <?php    
        echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox');
		echo $this->Html->script('States.state');
		echo $this->Html->css('States.state');
    ?>   
   
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
        echo $this->Form->create('Search', array('url' => array('controller' => 'States', 'action' => 'index'),'id'=>'StateId','type'=>'get'));  ?>
	
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
				 <span class="blue">(<b>Search by:</b>State)</span>
            </div>
           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
				<?php echo $this->Html->link('List All',array('controller'=>'States','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New State',array('controller'=>'States','action'=>'addedit'),array('class' => 'icon-file-alt','title' => 'Add State'));?>
                </div>
            </div>
        </div>
		
        <?php echo $this->Form->end(); ?>
		
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('State', array('url' => array('controller' => 'States', 'action' => 'index'),'id'=>'StateFormId'));  ?>
    
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
                            <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
                                                        
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
                            <td><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['State']['id']);?>" ></td>     
                           <?php   $status = $getData['State']['status'];
                                    $statusImg = ($getData['State']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['State']['id'])); ?>
                            <?php  $pid = $getData['State']['id'];?>
                            <td><?php echo $this->Html->link($this->Html->image("States.".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['State']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                            <td><?php echo $getData['State']['name'];?></td>
                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("States.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'States','action'=>'addedit',base64_encode($getData['State']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("States.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller'=>'States','action'=>'delete',base64_encode($getData['State']['id'])),array('escape' =>false),"Are you sure you wish to delete this State?");
								
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
                            echo $this->element('States.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('States.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("States.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("States.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("States.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("States.inactive.png"). " Inactive"; ?> </div>
                     
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('States.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>