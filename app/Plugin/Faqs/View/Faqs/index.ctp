    <?php  echo $this->Html->script('Faqs.faqs');
            echo $this->Html->css('Faqs.faq');
    ?>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }    
        echo $this->Form->create('Search', array('url' => array('controller' => 'faqs', 'action' => 'index'),'id'=>'titleId','type'=>'get'));  ?>
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search ','class' => 'form-control','maxlength' => 55));?>
                 <span class="blue">(<b>Search by:</b> Question)</span>
            </div>
            
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
                <?php echo $this->Html->link('List All',array('controller'=>'faqs','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New FAQ',array('controller'=>'faqs','action'=>'addedit'),array('class' => 'icon-file-alt','title' => 'Add Faq'));?>
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
    
    <?php echo $this->Form->create('Faq', array('url' => array('controller' => 'faqs', 'action' => 'index'),'id'=>'FaqFormId'));  ?>
    
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
                            <th><?php echo $this->Paginator->sort('title', 'Question'); ?></th>                            
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
                            <td><input type="checkbox" name="checkboxes[]" class ="checkboxlist" value="<?php echo base64_encode($getData['Faq']['id']);?>" ></td>
                            <?php   $status = $getData['Faq']['status'];
                                    $statusImg = ($getData['Faq']['status'] == 1) ? 'active' : 'inactive';
                                    echo $this->Form->hidden('status',array('value'=>$status,'id'=>'statusHidden_'.$getData['Faq']['id'])); ?>
                            <?php  $pid = $getData['Faq']['id'];?>
                            <td><?php echo $this->Html->link($this->Html->image("Faqs.".$statusImg.".png", array("alt" => ucfirst($statusImg),"title" => ucfirst($statusImg))),'javascript:void(0)',array('escape'=>false,'id'=>'link_status_'.$getData['Faq']['id'],'onclick'=>'setStatus('.$pid.')')) ; ?></td>
                            <td><?php echo $getData['Faq']['title'];?></td>                            
                            <td><?php echo date('M j, Y',strtotime($getData['Faq']['created']));?></td>                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("Faqs.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'faqs','action'=>'addedit',base64_encode($getData['Faq']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("Faqs.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller'=>'faqs','action'=>'delete',base64_encode($getData['Faq']['id'])),array('escape' =>false),"Are you sure you wish to delete this FAQ?");
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
                            echo $this->element('Faqs.operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('Faqs.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("Faqs.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Faqs.edit.png"). " Edit"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Faqs.active.png"). " Active"; ?> </div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Faqs.inactive.png"). " Inactive"; ?> </div>
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Faqs.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>