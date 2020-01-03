    <?php   	   
	    echo $this->Html->css('Emailtemplates.emailtemplate');
    ?>  
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }    
        echo $this->Form->create('Search', array('url' => array('controller' => 'emailtemplates', 'action' => 'index'),'id'=>'emailId','type'=>'get'));  ?>
        
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Keyword Search','class' => 'form-control','maxlength' => 55));?>
                 <span class="blue">(<b>Search by:</b> Template Name)</span>
            </div>           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
                <?php echo $this->Html->link('List All',array('controller'=>'emailtemplates','action'=>'index'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">
                <div class="addbutton newtempwdth"> 
                <?php echo $this->Html->link('Add New Template',array('controller'=>'emailtemplates','action'=>'addedit'),array('class' => 'icon-file-alt','title' => 'Add New Email Template'));?>                
                </div>
                <span class="red fltrght">*Please remove this link after adding all templates</span>
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
                            <th ><?php echo $this->Paginator->sort('name', 'Template Name'); ?></th>                            
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
                            <td><?php echo $getData['Emailtemplate']['name'];?></td>                            
                            <td><?php echo date('M j, Y',strtotime($getData['Emailtemplate']['created']));?></td>                            
                            <td>
                            <?php
                                echo $this->Html->link($this->Html->image("Emailtemplates.edit.png", array("alt" => "Edit","title" => "Edit")),array('controller'=>'emailtemplates','action'=>'addedit',base64_encode($getData['Emailtemplate']['id'])),array('escape' =>false));
                                
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
                            echo $this->element('Emailtemplates.pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                     <div class="col-lg-2">   
                          LEGENDS:                        
                     </div>
                     <!--<div class="col-lg-2"><?php echo $this->Html->image("Emailtemplates.delete.png"). " Delete &nbsp;"; ?></div>-->
                     <div class="col-lg-2"> <?php echo $this->Html->image("Emailtemplates.edit.png"). " Edit"; ?> </div>
                   
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Emailtemplates.no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>