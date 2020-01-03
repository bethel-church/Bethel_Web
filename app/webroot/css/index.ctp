<?php    
        //echo $this->Html->script('fancybox/jquery.fancybox');
        //echo $this->Html->css('fancybox/jquery.fancybox');
		    //echo $this->Html->script('trips_list');
		    //echo $this->Html->css('Users.user');
    ?>   
   <div style="margin-bottom:10px;">
   <div class="mainH noborder leftAlign" style="height:36px;border:1px solid #ccc;border-radius:4px;">
       <?php 
       if($selected_trip_type ==1){
        $active_class = "inactiveHeading";
        $archived_class = "activeHeading";
       }else{
        $active_class = "activeHeading";
        $archived_class = "inactiveHeading";
       }
       ?>
      <div id="activeD" style="width:50%px;margin:5px;"  class="leftAlign">
      <a style="line-height:23px;" href="/trips/index/a:0" class="<?php echo $active_class;?>">ACTIVE TRIPS (<?php echo $active_trips['active'];?>)</a>
      </div>
      <div style="width:50%px" class="rightAlign">
      <a style="line-height:23px;" href="/trips/index/a:1" class="<?php echo $archived_class;?>">ARCHIVED TRIPS (<?php echo $active_trips['archived'];?>)</a>
      </div>
    </div>
    <div class="rightAlign"><?php echo $this->Form->button('+ NEW TRIP', array('type' => 'submit','onclick'=>'add()','class' => 'addAction btn btn-default'));?></div>
   <div style="clear:both"></div>
   </div>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
        echo $this->Form->create('Search', array('url' => array('controller' => 'trips', 'action' => 'index'),'id'=>'tripsId','type'=>'get'));  ?>
		<?php echo $this->Form->hidden('alphabet_letter',array('id'=>'hiddenalpha')); ?>
        <div>
       
            <div class="searchBox leftAlign">   
                 <?php echo $this->Form->input('keyword',array('value'=>$keyword,'label' => false,'div' => false, 'placeholder' => 'Search', 'class' => 'form-control'));?>
				 
            </div>
           
            
            
        </div>
		
        <?php echo $this->Form->end(); ?>
		
    <div class="row">
        <div class="col-lg-4" style="width:100%">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('Trip', array('url' => array('controller' => 'trips', 'action' => 'index'),'id'=>'TripFormId'));  ?>
    
    <div class="row">
        <div class="col-lg-12">            
            <div class="table-responsive">               
                 
                <?php if($recordExits)
                { ?>
                <table class="table table-hover table-striped tablesorter noborder">
                    <thead>
                        <tr>
                            <th class="tableheading leftPadding">Trip Name<?php //echo $this->Paginator->sort('name', 'Trip Name'); ?></th>
                            <th class="tableheading"><?php echo "Trip Leader"; //echo $this->Paginator->sort('gender', 'Gender'); ?> </th>
                            <th class="tableheading"></th>
                        </tr>
                    </thead>
                    <tbody class="dyntable">
                        <?php
                        $i = 0;
                        
                        foreach($getData as $key => $getData)
                        {
                            $leader = "";
                            //pr($getData);
                            $first = $getData['User']['first_name'];
                            $middle = $getData['User']['middle_name'];
                            $last = $getData['User']['last_name'];
                            $leader = "";
                            if($first != ""){
                              $leader = $first;
                            }
                            if($middle !=""){
                              $leader .= " ".$middle;
                            }
                            if($last != ""){
                              $leader .= " ".$last;
                            }
                            
                            //$class = ($i%2 == 0) ? ' class="active"' : '';
                            $class = "plain";
                            ?>
                        <tr class="<?php echo $class;?>">
                            <td class="leftPadding" width="30%"><?php echo $getData['Trip']['name'];//echo $this->Html->link($getData['Trip']['name'],"/trips/addedit/".base64_encode($getData['Trip']['id']),array('escape' =>false)); ?></td>
                          
                            <td width="20%"><?php echo $leader; ?></td>
                            <td width="50%" >
                            <div class="rightAlign" style="margin-right:20px">
                            <?php
                                $base_id = urlencode(base64_encode($getData['Trip']['id']));
                                
                                $reg_id = $getData['Trip']['id'];
                                if($getData['Trip']['edit_blocked'] == 1){
                                  $button_class = "btn btn-default buttonGap button_class btnEnabled";
                                  $clickEdit = "edit1('".$base_id."')";
                                }else{
                                  $button_class = "btn btn-default buttonGap button_class btnEnabled";
                                  $clickEdit = "edit('".$base_id."')";
                                }
                                if($getData['Trip']['archived'] == "0"){
                                  //don't show edit button if trip is archived
                                  echo $this->Form->button('EDIT', array('type' => 'button','onclick'=>$clickEdit,'class' => $button_class));
                                }
                                echo $this->Form->button('VIEW', array('type' => 'button','onclick'=>'view(\''.$base_id.'\')','class' => 'btn btn-default buttonGap btnEnabled'));
                                
                                if($getData['Trip']['archived'] == "0"){
                                  echo $this->Form->button('ARCHIVE', array('type' => 'button', 'data-id'=>$reg_id,'data-keyboard'=>'false','data-backdrop'=>'static','class' => 'btn btn-default buttonGap btnEnabled btnArchive'));
                                  //echo "</div>";
                                }else{
                                  //echo "</div>";
                                  echo $this->Form->button('MAKE ACTIVE', array('type' => 'button','onclick'=>'activate(\''.$base_id.'\')','class' => 'btn btn-default buttonGap btnEnabled'));
                                  echo $this->Form->button('DELETE', array('type' => 'button', 'data-id'=>$reg_id,'data-keyboard'=>'false','data-backdrop'=>'static','class' => 'btn btn-default buttonGap btnEnabled btnDelete'));
                                  
                                  //echo $this->Form->button('DELETE', array('type' => 'button','onclick'=>'del(\''.$base_id.'\')','class' => 'btn btn-default buttonGap btnEnabled'));
                                  //$dd = $this->html->link("Un-Archive",array("controller"=>"trips","action"=>"unarchive",$reg_id));
                                  //echo "<div class=\"rightAlign archiveH\">ARCHIVED&nbsp;&nbsp;&nbsp;&nbsp;<br /><span class=\"smallkH\">$dd</span></div>";
                                }
                                
                               
                            ?>
                              </div>
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
                            //echo $this->element('Trips/operation');  // Active/ Inactive/ Delete
                        }
                     ?>
                    </div>
                 </div>
                <div class="row">
                                      
                     <div class="col-lg-12"> <?php
                        if($this->Paginator->param('pageCount') > 1)
                        {
                            echo $this->element('Trips/pagination');
                        }
                        ?>
                     </div>
                 </div>
                <div class="row padding_btm_20 ">
                    
                 </div>
              
               <?php
                }else{ 
                    echo $this->element('Trips/no_record_exists');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
    
    <div id="myModal" class="modal ff" data-id="vinod" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <h4 class="modal-title">ARCHIVE</h4>
      </div>
      <div class="modal-body">
        <p>Please confirm you want to archive this trip. Users
will no longer be able to add or edit receipts.</p>
<p>You can "un-archive" at any time.</p>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default btnLinkCancel" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-primary btnLink btnConfirm">CONFIRM</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'cancelA','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php echo $this->Html->link('CONFIRM','#',array('id'=>'confirmA','title' => '', 'class'=>'btnLink'));?>  
                    
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
    

<div id="myModal1" class="modal ff" data-id="vinod1" role="dialog" aria-labelledby="mySmallModalLabel1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <h4 class="modal-title">DELETE</h4>
      </div>
      <div class="modal-body">
        <p>Please confirm you want to delete this trip. <br />
        By deleting, this trip will be completely erased from
the system and will not be recoverable.</p>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default btnLinkCancel" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-primary btnLink btnConfirm">CONFIRM</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'cancelA1','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php echo $this->Html->link('CONFIRM','#',array('id'=>'confirmA1','title' => '', 'class'=>'btnLink'));?>  
                    
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->    
    
   <?php  echo $this->Form->end(); ?>
   <script type="text/javascript">
   function edit(id){
   
    var url = '/trips/addedit/'+id;
    $(location).attr('href',url);
   }
   //for trips for which we have got some transactions
   function edit1(id){
   
    var url = '/trips/addedit1/'+id;
    $(location).attr('href',url);
   }
   function add(){
   
    var url = '/trips/add/';
    $(location).attr('href',url);
   }
   function view(id){
    var url = '/trips/view/'+id;
    $(location).attr('href',url);
   }
   function unarchive(id){
    var url = '/trips/unarchive/'+id;
    $(location).attr('href',url);
   }
   
   function del(id){
    var url = '/trips/delete/'+id;
    $(location).attr('href',url);
   }
   
   function activate(id){
    var url = '/trips/unarchive/'+id;
    $(location).attr('href',url);
   }
      
   $( document ).ready(function() {
    
     $('#cancelA').click(function(){
                    $('#myModal').modal('hide');
                  
                  });
                   $('#confirmA').click(function () {
                     $('#myModal').modal('hide');
                     var id = $('#myModal').data('id');
                      if(id !=""){
                        var url = '/trips/archive/'+id;
                        $(location).attr('href',url);
                      }
                     
                  }); 
    
    $('button.btnArchive').on('click', function (e) {
    e.preventDefault();
    
    var id = $(this).data('id');
    
    $('#myModal').data('id', id);
    $('#myModal').modal({
      backdrop: 'static',
      keyboard: false
    })
    //var id = $('#myModal').data('id');
    
    
    $('#myModal').data('id', id).modal('show');
    
  });

  //$('#myModal').on('shown.bs.modal', alignModal);

$('button.btnConfirm').click(function () {
    //alert("hiding");
    
    var id = $('#myModal').data('id');
    if(id !=""){
      var url = '/trips/archive/'+id;
      $(location).attr('href',url);
    }
});
   
   
   
   //code for delete button feature
    $('#cancelA1').click(function(){
                    $('#myModal1').modal('hide');
                  
                  });
                   $('#confirmA1').click(function () {
                     $('#myModal1').modal('hide');
                     var id = $('#myModal1').data('id');
                      if(id !=""){
                        var url = '/trips/delete/'+id;
                        $(location).attr('href',url);
                      }
                     
                  }); 
   $('button.btnDelete').on('click', function (e) {
    e.preventDefault();
    
    var id = $(this).data('id');
    
    $('#myModal1').data('id', id);
    $('#myModal1').modal({
      backdrop: 'static',
      keyboard: false
    })
    
    $('#myModal1').data('id', id).modal('show');
    
  });


$('button.btnConfirm1').click(function () {
    //alert("hiding");
    
    var id = $('#myModal1').data('id');
    if(id !=""){
      var url = '/trips/delete/'+id;
      $(location).attr('href',url);
    }
});
   
    
});
   
   
   
   </script>