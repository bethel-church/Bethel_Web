<?php    
       echo $this->Html->script('fancybox/jquery.fancybox');
        echo $this->Html->css('fancybox/jquery.fancybox'); 
        echo $this->Html->script('trips_list');
    ?>   
   <div>
   <?php 
   $trip_id = base64_encode($getTripData['Trip']['id']);
   if(LIVE){
    $path = AWS_S3_BUCKET_PATH."/receipts/".$getTripData['Trip']['id'];
   }else{
    $path = AWS_S3_BUCKET_PATH."\\receipts\\".$getTripData['Trip']['id'];
   }
   if(file_exists($path)){
    $class = "zipBtn btn btn-default";
    $st = "start_task()";
    //$st = "zip('$trip_id')";
   }else{
    $class = "zipBtn btn disabled btn-default";
    $st = "";
   }
   $class1 = "gBtn btn btn-default";
   ?>
   <div class="bigHeading leftAlign"><?php echo $getTripData['Trip']['name'];?></div>
    <div class="smHeading">Number of Students <br><span class="smH"><?php echo $students;?></span></div>
    <div class="smHeading">Student Passcode <br /><span class="smH"><?php echo $getTripData['Trip']['user_passcode'];?></span></div>
    <div class="smHeading">Trip Leader Passcode<br /><span class="smH"><?php echo $getTripData['Trip']['leader_passcode'];?></span></div>
    
    <div class="rightAlign">
    <span><?php echo $this->Form->button('FILTER', array('type' => 'button','class' => $class1,'data-target'=>'#filterModal','data-toggle'=>'modal'));?></span>
    &nbsp;&nbsp;
    <span><?php echo $this->Form->button('GENERATE ZIP FILE', array('type' => 'button','class' => $class));?></span></div>
   <div style="clear:both"></div>
   </div>
    <?php
        $recordExits = false;            
        if(isset($getData) && !empty($getData))
        {
           $recordExits = true;            
        }
     ?>   
		
    <div class="row">
        <div class="col-lg-4">                        
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
                            <th class="tableheadingSmall leftPadding">&nbsp;</th>
                            <th class="tableheadingSmall">Date & Time</th>
                            <th class="tableheadingSmall">Type</th>
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
                            
                            $timestamp = strtotime($getData['Transaction']['created']);
                            $date = Date('n/j/y __ g:i A', $timestamp);
                            $dd = explode("__",$date);
                            $dateString = $dd[0]."<br />".$dd[1];
                            //pr($getData);
                            //$class = ($i%2 == 0) ? ' class="active"' : '';
                            $class = "plainSmall";
                            $img = $getData['Transaction']['receipt'];
                            $curr = $getData['Transaction']['foreign_currency'];
                            ?>
                        <tr class="<?php echo $class;?>">
                            <td width="15%" class="leftPadding">
                            
                            <a href="/receipts/<?php echo $getData['Transaction']['trip_id']."/";echo $img;?>" class="fancybox image">
                                View Receipt
                            </a>
                            
                            </td>
                            <td  width="20%"><?php echo $dateString;//echo $this->Html->link($getData['Trip']['name'],"/trips/addedit/".base64_encode($getData['Trip']['id']),array('escape' =>false)); ?></td>
                          
                            <td width="5%"><?php echo $getData['Transaction']['type']; ?></td>
                            <td width="30%"><?php echo $getData['Transaction']['description']; ?></td>
                            <td width="10%"><?php echo "$".$getData['Transaction']['usd']; ?></td>
                            <td width="20%"><?php echo $curr." ".$foreign_currency; ?></td>
                                             
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
    
<!-- Modal for filter dialogue -->    
 <!-- Modal -->
<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modalTitle">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="fr"></span>FILTER RECEIPTS</h4>
      </div>
      <div class="modal-body">
        <p>
        <table width ="100%">
        <tr><td><input type="checkbox" name="receiptType[]" value="All Receipts"> All Receipts</td><td><input type="checkbox" name="receiptType[]" value="Food"> Food </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" value="Baggage/Visa/Departure Tax"> Baggage/Visa/Departure Tax</td><td><input type="checkbox" name="receiptType[]" value="Airport Fees"> Airport Fees </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" value="Transportation"> Transportation</td><td><input type="checkbox" name="receiptType[]" value="Lodging"> Lodging </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" value="Supplies"> Supplies</td><td><input type="checkbox" name="receiptType[]" value="Fun Day ($25 Max)"> Fun Day ($25 Max) </td></tr>
        <tr><td><input type="checkbox" name="receiptType[]" value="Gifts/Donations"> Gifts/Donations</td><td><input type="checkbox" name="receiptType[]" value="Other Expenses"> Other Expenses </td></tr>
        </table>
        </p>
        <p>
        <input type="checkbox" name="dateR" checked value="1"> Show receipts from all dates <br />
        <div id="dRange">Show receipts from <span id="stDate" style="cursor:pointer">Jan1, 2016  <input type="hidden"  name="startDate" id="datetimepicker1" /></span> until <span id="enDate">Dec 31, 2016</span></div>
        
        </p>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <?php echo $this->Html->link('CANCEL','#',array('id'=>'ca','title' => '', 'class'=>'btnLinkCancel'));?>  
        <?php echo $this->Html->link('FILTER','#',array('id'=>'confirmA','title' => '', 'class'=>'btnLink'));?>  
        
      </div>
    </div>

  </div>
</div>   
   <?php  echo $this->Form->end(); ?>
    
    
        <script type="text/javascript">
          
            $(function () {
               var $btn = $('#fr');
               //var $d = new Date("2016", "1", "1");
                $('#datetimepicker1').datetimepicker({
                  defaultDate:new moment("2014 04 25", "YYYY MM DD")
                  
                });
                $("#datetimepicker1").on("dp.change", function (e) {
                  //alert($('#datetimepicker1').data("DateTimePicker").date());
              
              });
                
            });
            $('#stDate').on('click', function (e) {
               
              	$('#datetimepicker1').data('DateTimePicker').toggle();
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

$('button.zipBtn').on('click', function (e) {
    e.preventDefault();
    
    $('#myModal').modal({
      backdrop: 'static',
      keyboard: false
    })
    
    $('#myModal').modal('show');
    start_task();
    
  });
  
   $('#cancelA').click(function(){
                    $('#myModal').modal('hide');
                  
                  });
       $('#ca').click(function(){
                    $('#filterModal').modal('hide');
                  
                  });            
  
        var source = 'THE SOURCE';
         
        function start_task()
        {
            $("#progr").show();
            var st = '/trips/sse/<?php echo $trip_id;?>';
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
                    var txt = '<a href="/trips/dd/<?php echo $trip_id;?>"> Download Zip File</a>';
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