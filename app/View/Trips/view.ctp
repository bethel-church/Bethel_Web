<?php    
        //comment out old Jquery and Fancy box scripts
       echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox'); 
        echo $this->Html->script('trips_list');
    ?>   
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
 
   <div>
   <?php 
   $trip_id = base64_encode($getTripData['Trip']['id']);
   if(LIVE){
    $path = AWS_S3_BUCKET_PATH."/receipts/".$getTripData['Trip']['id'];
   }else{
    $path = AWS_S3_BUCKET_PATH."\\receipts\\".$getTripData['Trip']['id'];
   }
   if($receipt_type != ""){
    $cls = "gBtnGreen";
    $clear_filter = "cf";
   }else{
    $cls = "gBtn";
    $clear_filter = "cf_hide";
   }
   
   //if(file_exists($path)){
   if($getTripData['Trip']['file_exists']==1){
    $class = "zipBtn btn btn-default";
    $class_xls = "zipBtn1 btn btn-default";
    $st = "start_task()";
    //$st = "zip('$trip_id')";
   }else{
    $class = "zipBtn btn disabled btn-default";
    $class_xls = "zipBtn1 btn disabled btn-default";
    $st = "";
   }
   $class1 = $cls." btn btn-default";
   ?>
   <div class="bigHeading leftAlign"><?php echo $getTripData['Trip']['name'];?></div>
    <div class="" >
    <span style="margin-left:5px;position:relative">
    <a class="<?php echo $clear_filter;?>" href="/trips/view/<?php echo $trip_id;?>">Clear Filter</a>
    <?php echo $this->Form->button('&nbsp;&nbsp;FILTER&nbsp;&nbsp;', array('type' => 'button','class' => $class1,'data-target'=>'#filterModal','data-toggle'=>'modal'));?></span>
    &nbsp;&nbsp;
    <span><?php echo $this->Form->button('DOWNLOAD EXCEL', array('type' => 'button','class' => $class_xls));?></span>
    &nbsp;&nbsp;
    <span><?php echo $this->Form->button('DOWNLOAD ZIP', array('type' => 'button','class' => $class));?></span></div>
   <div style="clear:both"></div>
   </div>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
    ?>   
		
    <div style="margin-bottom:15px">
   
    
    <div class="smHeading">Number of Students: <br><span class="smH"><?php echo $students;?></span></div>
    <div class="smHeading">Student Passcode: <br /><span class="smH"><?php echo $getTripData['Trip']['user_passcode'];?></span></div>
    <div class="smHeading">Trip Leader Passcode:<br /><span class="smH"><?php echo $getTripData['Trip']['leader_passcode'];?></span></div>
    <!--<div ><div style="font-size:34px">|</div></div>-->
    <div class="smHeading">Check Amount:<br><span class="smH">$<?php echo $total_budget;?>&nbsp;&nbsp;<a href="" data-target='#myEditDialogue' data-toggle='modal'>EDIT</a></span></div>
    <div class="smHeading">Total Spent:<br /><span class="smH">$<?php echo $total_spent;?></span></div>
    <div class="smHeading">Remaining:<br /><span class="smH">$<?php echo $total_left;?></span></div>
    
    
    <div class="rightAlign">
    <?php if($receipt_type != ""){?>
      <div class="smHeadingBlack">Filtered Total:<br /><span class="smH">$<?php echo $filtered_spent;?></span>
      </div>
    <?php } ?>
    </div>
   <div style="clear:both"></div>
   </div>
   	
    <div class="row">
        <div class="col-lg-12">                        
             <?php echo $this->Session->flash();?>   
        </div>            
    </div>
    
    <?php echo $this->Form->create('Trip', array('url' => array('controller' => 'trips', 'action' => 'view'),'id'=>'TripFormId'));  ?>
    <input name="data[Trip][id]" id="TripId" type="hidden" value="<?php echo $trip_id;?>" />
    <div class="row">
        <div class="col-lg-12">            
            <div class="table-responsive">               
                 
                <?php if($recordExits)
                { ?>
                <table id="myT" class="table table-hover table-striped tablesorter noborder">
                    <thead>
                        <tr>
                            <th class="tableheadingSmall leftPadding">&nbsp;</th>
                            <th class="tableheadingSmall">Date & Time</th>
                            <th class="tableheadingSmall">Type</th>
                            <th class="tableheadingSmall">Team Member</th>
                            <th class="tableheadingSmall">Description</th>
                            <th class="tableheadingSmall">USD</th>
                            <th class="tableheadingSmall">Foreign Currency</th>
                        </tr>
                    </thead>
                    <tbody class="dyntable">
                        <?php
                        $i = 0;
                        
                        foreach($getData as $key => $getData)
                        {
                            $leader = "";
                            if($getData['Transaction']['foreign_currency_amount'] > 0){
                              $foreign_currency = $getData['Transaction']['foreign_currency_amount'];
                            }else{
                              $foreign_currency = "--";
                            }
                            //print "<pre>";
                            //print_r($getData);
                            $timestamp = strtotime($getData['Transaction']['created']);
                            $date = Date('n/j/y __ g:i A', $timestamp);
                            $dd = explode("__",$date);
                            $dateString = $dd[0]."<br />".$dd[1];
                            //pr($getData);
                            //$class = ($i%2 == 0) ? ' class="active"' : '';
                            $class = "plainSmall";
                            $img = $getData['Transaction']['receipt'];
                            $curr = $getData['Transaction']['foreign_currency'];
                            
                            $name = $getData['User']['first_name'];
                            if($getData['User']['middle_name'] != "")
                            $name .= " ".$getData['User']['middle_name'];
                            $name .= " ".$getData['User']['last_name'];
                            
                            ?>
                        <tr class="<?php echo $class;?>">
                            <td width="7%" class="leftPadding">
                            
                            <!--<a href="/receipts/<?php echo $getData['Transaction']['trip_id']."/";echo $img;?>" class="fancybox image">
                                View Receipt
                            </a>-->
                            <a href="/receipts/<?php echo $getData['Transaction']['trip_id']."/";echo $img;?>" data-fancybox="gallery" class="fancybox image">
                                View Receipt
                            </a>
                            </td>
                            <td  width="13%"><?php echo $dateString;//echo $this->Html->link($getData['Trip']['name'],"/trips/addedit/".base64_encode($getData['Trip']['id']),array('escape' =>false)); ?></td>
                          
                            <td width="10%"><?php echo $getData['Transaction']['type']; ?></td>
                            <td width="17%"><?php echo $name; ?></td>
                            <td width="25%"><?php echo $getData['Transaction']['description']; ?></td>
                            <td width="10%"><?php echo "$".$getData['Transaction']['usd']; ?></td>
                            <td width="18%"><?php echo $curr." ".$foreign_currency; ?></td>
                                             
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
                    echo $this->element('Trips/no_record_exists_transactions');
                } ?>
            </div>
        </div>         
    </div><!-- /.row -->
    
    
    <div id="myModal" class="modal ff" data-id="vinod" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <h4 class="modal-title">DOWNLOAD</h4>
      </div>
      <div class="modal-body">
        <div id="progr" style="display:none;float:left;padding-left:0px">
      <div id="results" style=" width:345px; height:20px; overflow:auto;"></div>
        <div style="margin-bottom:0px;" class="progress">
        <div class="progress-bar progress-bar-striped active" id="progress_bar" role="progressbar"
            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
          
        </div>
        
      </div>
    </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default btnLinkCancel" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-primary btnLink btnConfirm">CONFIRM</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'cancelA','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php //echo $this->Html->link('CONFIRM','#',array('id'=>'confirmA','title' => '', 'class'=>'btnLink'));?>  
                    
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
<?php
//$arr = array('Food','Baggage/Visa/Departure Tax','Transportation','Supplies','Gifts/Donations','Airport Fees','Lodging','Fun Day ($25 Max)','Other Expenses');
if($all_receipts != "")
$t = "checked";
if(in_array("Food",$arr))
$t1 = "checked";
if(in_array("Baggage/Visa/Departure Tax",$arr))
$t2 = "checked";
if(in_array("Airport Fees",$arr))
$t3 = "checked";
if(in_array("Transportation",$arr))
$t4 = "checked";
if(in_array("Lodging",$arr))
$t5 = "checked";
if(in_array("Supplies",$arr))
$t6 = "checked";
if(in_array("Fun Day ($25 Max)",$arr))
$t7 = "checked";
if(in_array("Gifts/Donations",$arr))
$t8 = "checked";
if(in_array("Other Expenses",$arr))
$t9 = "checked";
?>
<!-- Modal for filter dialogue -->    
 <!-- Modal -->
<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="width:580px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="fr"></span>FILTER RECEIPTS</h4>
      </div>
      <div class="modal-body">
        
        <table id="ft" width ="100%">
        <tr><td><input type="checkbox" name="receiptType[]" class="checkAll" <?php echo $t;?> value="All Receipts"> All Receipts</td><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t1;?> value="Food"> Food </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t2;?> value="Baggage/Visa/Departure Tax"> Baggage/Visa/Departure Tax</td><td><input type="checkbox" name="receiptType[]" <?php echo $t3;?> class="cb-element"  value="Airport Fees"> Airport Fees </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t4;?> value="Transportation"> Transportation</td><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t5;?> value="Lodging"> Lodging </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t6;?> value="Supplies"> Supplies</td><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t7;?> value="Missions $25 per person (Not 2nd Year)"> Missions $25 per person (Not 2nd Year) </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t8;?> value="Gifts/Donations"> Gifts/Donations</td><td><input type="checkbox" name="receiptType[]" class="cb-element" <?php echo $t9;?> value="Other Expenses"> Other Expenses </td></tr>
        </table>
        <p style="border: 1px solid #f2f2f2;margin-top:10px;"></p>
        <p>
        <?php 
        //var_dump($date_range);
        if($date_range != ""){
          $checked = "checked=\"true\"";
        }
        ?>
        <input type="checkbox" id="dateR" name="dateR" <?php echo $checked;?> value="1"> Show receipts from all dates <br />
        
        <div id="dRange">Show receipts from <span id="stDate" ><?php echo Date("M j, Y",strtotime($startDate));?> </span> until <span id="enDate"><?php echo Date("M j, Y",strtotime($endDate));?></span></div>
        <input type="hidden" name="startDate" id="datetimepicker1" value="<?php echo $startDate;?>"/>
        <input type="hidden" name="endDate" id="datetimepicker2" value="<?php echo $endDate;?>" />
        </p>
        
        <p>
        <?php 
        if($memberS != ""){
          $checked1 = "checked=\"true\"";
        }
        ?>
        <input type="checkbox" id="memberS" name="memberS" <?php echo $checked1;?> value="1"> Show receipts from all users <br />
        <div id="memS" class="memS" >
        <table id="ft" width ="100%">
        <tr>
        <?php 
          $i = 1;
          foreach ($trip_members as $trip_member){
            if(in_array($trip_member['User']['id'], $selMembers)){
              $checked =  " checked ";
            }else{
              $checked = "";
            }
            $name = $trip_member['User']['first_name']. " ". $trip_member['User']['middle_name']. " ".$trip_member['User']['last_name'];
            
            ?>
            <td><input type="checkbox" name="selMembers[]" class="checkAll1" <?php echo $checked;?> value="<?php echo $trip_member['User']['id']; ?>">&nbsp;<?php echo $name;?></td>
            <?php
            if($i%2 == 0)
              echo "<tr>";
            $i++;  
          }
        ?>
        </tr>
        </table>
        </div>
        
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'ca','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php echo $this->Html->link('FILTER','#',array('id'=>'filterA','title' => '', 'class'=>'btnLink'));?>  
        
      </div>
    </div>

  </div>
</div>   
   <?php  echo $this->Form->end(); ?>
   
<!-- Modal for Edit Check Amount Dialogue -->   
<div id="myEditDialogue" class="modal ff1"  role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <h4 class="modal-title">EDIT CHECK AMOUNT</h4>
      </div>
      <?php echo $this->Form->create('Trip', array('url' => array('controller' => 'trips', 'action' => 'updatecheck'),'id'=>'TripFormId11'));  ?>
      <input name="data[Trip][id]" id="TripId" type="hidden" value="<?php echo $trip_id;?>" />
    
      <div class="modal-body">
      <span class="highlight">$</span>
         <?php 
                       // print "<pre>";
                       // print_r($this->data);
                        echo $this->Form->input('budget',array('label' => false,'div' => false, 'placeholder' => '','class' => 'fancyInput inputField','maxlength' => 7, 'type'=> 'text', 'error'=>false, 'value'=> $total_budget));?>
                        
                        <span class="bar"></span>
                        <!--<label>Trip Name</label> -->
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default btnLinkCancel" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-primary btnLink btnConfirm">CONFIRM</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'cancelUA','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php echo $this->Html->link('UPDATE','#',array('id'=>'confirmUA','title' => '', 'class'=>'btnLink','onclick'=>'frmSubmit()'));?>  
                    
      </div>
      <?php  echo $this->Form->end(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
        <script type="text/javascript">
          function frmSubmit(){
          
          document.getElementById('TripFormId11').submit();
          }
        
          $.noConflict();
            $(function () {
               var $btn = $('#fr');
               //var $d = new Date("2016", "1", "1");
                $("#myT").tablesorter({sortList: [[1,1]], headers:{0:{sorter:false},4:{sorter:false}}}); 
                // 
             
               $(".checkAll").change(function () {
                  if($(this).prop("checked")){
                    //$('.cb-element').attr('checked', 'checked');
                    $(".cb-element").prop("checked", true);
                  }else{
                    //$(".cb-element").prop("checked", false);
                  }
              }); 
              
              $(".cb-element").change(function(){
                if ($('.cb-element:checked').length == $('.cb-element').length) {
                   //do something
                    $(".checkAll").prop("checked", true);
                }else{
                    //$('.checkAll').attr('checked', '');
                    $(".checkAll").prop("checked", false);
                }
              });
              
              $("#dateR").change(function(){
                if ($('#dateR:checked').length == $('#dateR').length) {
                   
                    $('#dRange').hide();
                }else{
                    $('#dRange').show();
                }
              });
              
              $("#memberS").click(function(){
               // alert(1);
                $('#memS').toggle();
              });
              
              
              //check all once page loads and if all receipts is selected
              chkAll();
              shwDate();
              
              $('#datetimepicker1').datepicker({
              //options
               defaultDate:new Date("<?php echo $startDate;?>"),
               dateFormat:"M d, yy",
               onSelect:
                function(dateText, inst) {
                    $('#stDate').text(dateText);
                }
              //...
              });
              $('#stDate').click(function(){
               $('#datetimepicker1').datepicker('show');
              });
              
              $('#datetimepicker2').datepicker({
              //options
               defaultDate:new Date("<?php echo $endDate;?>"),
               dateFormat:"M d, yy",
               onSelect:
                function(dateText, inst) {
                    
                    $('#enDate').text(dateText);
                }
              //...
              });
              $('#enDate').click(function(){
               $('#datetimepicker2').datepicker('show');
              });
              
            });
            
            function chkAll(){
              if($(".checkAll").prop("checked")){
                    $('.cb-element').attr('checked', 'checked');
                  } 
              if($("#memberS").prop("checked")){
                    $('.checkAll1').attr('checked', false);
                    $('#memS').hide();
              } 
                  
            }
            function shwDate(){
              
              if($("#dateR").prop("checked")){
                    $('#dRange').hide();
                  } 
            }
            /*
            $('#stDate').on('click', function (e) {
               
              	$('#datetimepicker1').data('DateTimePicker').toggle();
            });*/
            $('#filterA').on('click', function (e) {
              $('#TripFormId').submit();
            });

        </script>
    </div>
</div>
   
   
   <script type="text/javascript">
   function edit(id){
   
    var url = 'trips/addedit/'+id;
    $(location).attr('href',url);
   }
   function add(){
   
    var url = 'trips/addedit/';
    $(location).attr('href',url);
   }
   function view(id){
    var url = 'trips/view/'+id;
    $(location).attr('href',url);
   }
   
   $('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('New message to ' + recipient)
  modal.find('.modal-body input').val(recipient)
})
function zip(id){
    var url = '/trips/download/'+id;
    $(location).attr('href',url);
}

$('button.zipBtn1').on('click', function (e) {
    e.preventDefault();
    
    $('#myModal').modal({
      backdrop: 'static',
      keyboard: false
    })
    
    $('#myModal').modal('show');
    start_task('xls');
    
  });

$('button.zipBtn').on('click', function (e) {
    e.preventDefault();
    
    $('#myModal').modal({
      backdrop: 'static',
      keyboard: false
    })
    
    $('#myModal').modal('show');
    start_task('all');
    
  });
  
  
  
  $('#cancelUA').click(function(){
      $('#myEditDialogue').modal('hide');
                  
  });
  $('#cancelA').click(function(){
      $('#myModal').modal('hide');
  });
       $('#ca').click(function(){
                    $('#filterModal').modal('hide');
                  
                  });            
  
        var source = 'THE SOURCE';
         
        function start_task(type)
        {
            $("#progr").show();
            var st = '/trips/sse/<?php echo $trip_id;?>/'+type;
            source = new EventSource(st);
            $(".progress").show(); 
            //a message is received
            source.addEventListener('message' , function(e) 
            {
                var result = JSON.parse( e.data );
                 
                add_log(result.message);
                var el = document.getElementById('progress_bar');
                if(el){
                  el.style.width = result.progress + "%";
                  el.innerHTML = result.progress + "%";
                }
                //document.getElementById('progressor').style.width = result.progress + "%";
                //document.getElementById('progressor_text').innerHTML = result.progress +"%";
                if(e.data.search('TERMINATE') != -1)
                {
                    //alert(type);
                    if(type == "xls"){
                      var txt = '<a href="/trips/dd/<?php echo $trip_id;?>/'+type+'"> Download Excel File</a>';
                    }else{
                      var txt = '<a href="/trips/dd/<?php echo $trip_id;?>/'+type+'"> Download Zip File</a>';
                    }
                    add_log(txt);
                    source.close();
                    $(".progress").hide();
                }
            });
             
            source.addEventListener('error' , function(e)
            {
                add_log('Error occured');
                 
                //kill the object ?
                source.close();
            });
        }
          
        function stop_task()
        {
            source.close();
            add_log('Interrupted');
        }
         
        function add_log(message)
        {
            var r = document.getElementById('results');
            //r.innerHTML += message + '<br>';
            r.innerHTML = message;
            r.scrollTop = r.scrollHeight;
        }
        
        
   
</script>