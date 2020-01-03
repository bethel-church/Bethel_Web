    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
            $recordExits = true;            
        }    
        echo $this->Form->create('Search', array('url' => array('controller' => 'admins', 'action' => 'newsletters'),'id'=>'emailId','type'=>'get'));  ?>
        <div class="row padding_btm_20">
            <div class="col-lg-4">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Search','class' => 'form-control','maxlength' => 55));?>
                 <span class="blue">(<b>Search by:</b> Newsletter Subject)</span>
            </div>           
            <div class="col-lg-4">                        
                <?php echo $this->Form->button('Search', array('type' => 'submit','class' => 'btn btn-default'));?>
                <?php echo $this->Html->link('List All',array('controller'=>'admins','action'=>'newsletters'),array('class' => 'btn btn-default'));?>
            </div>
            <div class="col-lg-4">    
                <div class="addbutton">                
                    <?php echo $this->Html->link('Add New Newsletter ||','/admin/admins/add_newsletter',array('class' => 'icon-file-alt','title' => 'Add Newsletter'));?>
                    <?php echo $this->Html->link('Newsletter Template','/admin/admins/newsletterTemplate',array('class' => 'icon-file-alt','title' => 'Manage Templates'));?>
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
                            <th><?php echo $this->Paginator->sort('title', 'Subject'); ?></th>                            
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
                            <td><?php echo $getData['Newsletter']['title'];?></td>                            
                            <td align="center"><?php echo date('M j, Y',strtotime($getData['Newsletter']['created']));?></td>                            
                            <td align="center">
                            <?php
                                echo $this->Html->link($this->Html->image("admin/edit.png", array("alt" => "Edit","title" => "Edit")),"/admin/admins/add_newsletter/".base64_encode($getData['Newsletter']['id']),array('escape' =>false));
                                echo $this->Html->link($this->Html->image("admin/delete.png", array("alt" => "Edit","title" => "Delete")),"/admin/admins/deleteNewsletter/".base64_encode($getData['Newsletter']['id']).'/',array('escape' =>false),"Are you sure you wish to delete this Newsletter?");
                                if($getData['Newsletter']['is_sent'] == 0){
                                    echo $this->Html->link($this->Html->image("admin/mail.png", array("alt" => "Send Newsletter","title" => "Send Newsletter")),"/admin/admins/send_newsletter/".base64_encode($getData['Newsletter']['id']),array('escape' =>false));
                                }else{
                                    echo $this->Html->image("admin/mail_sent.png",array('alt'=>'Newsletter Sent','title'=>'Newsletter Sent'));
                                }
                                
                                
                              //  echo $this->Html->link($this->Html->image("frontend/send-icon.png", array("alt" => "Send Newsletter","title" => "Send Newsletter")),"/admin/admins/sendNewsletter/".base64_encode($getData['Newsletter']['id']),array('escape' =>false));
                               
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
                            echo $this->element('admin/pagination');                 
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                    <div class="col-lg-2">   
                         LEGENDS:                        
                    </div>
                    <div class="col-lg-2"><?php echo $this->Html->image("admin/delete.png"). " Delete &nbsp;"; ?></div>
                    <div class="col-lg-2"> <?php echo $this->Html->image("admin/edit.png"). " Edit"; ?> </div>
                    <div class="col-lg-2"> <?php echo $this->Html->image("admin/mail.png"). " Send Newletter"; ?> </div>
                    <div class="col-lg-2"> <?php echo $this->Html->image("admin/mail_sent.png"). " Newsletter Sent"; ?> </div>
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('admin/no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
   <?php  echo $this->Form->end(); ?>