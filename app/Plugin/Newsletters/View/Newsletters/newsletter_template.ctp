<?php echo $this->Html->css('Newsletters.newsletter');  ?>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
            $recordExits = true;            
        }    
        echo $this->Form->create('Search', array('url' => array('controller' => 'newsletters', 'action' => 'newsletterTemplate'),'id'=>'emailId'));  ?>
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('label' => false,'div' => false, 'placeholder' => 'Search','class' => 'form-control','maxlength' => 55));?>
                 <span class="blue">(<b>Search by:</b> Template Title)</span>
            </div>           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
                <?php echo $this->Html->link('List All',array('controller'=>'newsletters','action'=>'newsletterTemplate'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New Template',array('controller'=>'newsletters','action'=>'add_template'),array('class' => 'icon-file-alt','title' => 'Add Newsletter Template'));?>
                   
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
    
    <div class="row">
        <div class="col-lg-12">            
            <div class="table-responsive">               
                 
                <?php if($recordExits)
                { ?>
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>                            
                            <th><?php echo $this->Paginator->sort('title', 'Title'); ?></th>                            
                            <th class="th_checkbox"><?php echo $this->Paginator->sort('created', 'Created'); ?> </th>                            
                            <th class="th_checkbox">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        
                        foreach($getData as $key => $getData)
                        {
                            $class = ($i%2 == 0) ? ' class="active"' : '';
                            ?>
                        <tr <?php echo $class;?>>                            
                            <td><?php echo $getData['NewsletterTemplate']['title'];?></td>                            
                            <td align="center"><?php echo date('M j, Y',strtotime($getData['NewsletterTemplate']['created']));?></td>                            
                            <td align="center">
                            <?php
                                echo $this->Html->link($this->Html->image("Newsletters.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'newsletters','action'=>'add_template',base64_encode($getData['NewsletterTemplate']['id'])),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("Newsletters.delete.png", array("alt" => "Edit","title" => "Delete")),array('controller'=>'newsletters','action'=>'deleteNlTemplate',base64_encode($getData['NewsletterTemplate']['id'])),array('escape' =>false),"Are you sure you wish to delete this newsletter template?");
                                                               
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
                    &nbsp;
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('Newsletters.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <div class="col-lg-2"><?php echo $this->Html->image("Newsletters.delete.png"). " Delete &nbsp;"; ?></div>
                     <div class="col-lg-2"> <?php echo $this->Html->image("Newsletters.edit.png"). " Edit"; ?> </div>
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Newsletters.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>