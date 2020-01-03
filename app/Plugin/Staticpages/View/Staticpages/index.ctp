    
    <?php   echo $this->Html->script('Staticpages.staticpages');
            echo $this->Html->css('Staticpages.pages'); 
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }    
        echo $this->Form->create('Search', array('url' => array('controller' => 'staticpages', 'action' => 'index'),'id'=>'titleId','type'=>'get'));  ?>
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
                 <span class="blue">(<b>Search by:</b> Page Name)</span>
            </div>
           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
                <?php echo $this->Html->link('List All',array('controller'=>'staticpages','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New Page',array('controller'=>'staticpages','action'=>'addedit'),array('class' => 'icon-file-alt','title' => 'Add Page'));?>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end();    
    ?>
    
    <div class="row">
        <div class="col-lg-4">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('Staticpage', array('url' => array('controller' => 'staticpages', 'action' => 'index'),'id'=>'staticPageFormId'));  ?>
    
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
                            <th class="th_checkbox"><?php echo "Page Position"; ?> </th>
                            <th><?php echo $this->Paginator->sort('title', 'Page Name'); ?></th>                            
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
                        <td><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['Staticpage']['id']);?>" ></td>
                        <?php   $status = $getData['Staticpage']['status'];
                                    $statusImg = ($getData['Staticpage']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['Staticpage']['id'])); ?>
                            <?php  $pid = $getData['Staticpage']['id'];?>
                            <td><?php echo $this->Html->link($this->Html->image("Staticpages.".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['Staticpage']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                        <td><?php
                                    if($getData['Staticpage']['page_position'] == 0) { $pos = "Header"; }else if($getData['Staticpage']['page_position'] == 1){ $pos ="Footer"; }else{ $pos ="Left Nav"; }
                                    echo ucfirst($pos);
                                 ?>
                        </td>
                            <td><?php echo $getData['Staticpage']['title'];?></td>                            
                            <td><?php echo date('M j, Y',strtotime($getData['Staticpage']['created']));?></td>                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("Staticpages.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'staticpages','action'=>'addedit',base64_encode($getData['Staticpage']['id'])),array('escape' =>false));
                                
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
                            echo $this->element('Staticpages.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('Staticpages.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     
                     <div class="col-lg-2"> <?php echo $this->Html->image("Staticpages.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Staticpages.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Staticpages.inactive.png"). " Inactive"; ?> </div>
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Staticpages.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>