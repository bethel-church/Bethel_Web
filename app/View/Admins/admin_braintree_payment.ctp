      <?php echo $this->Html->script('admin/admin_test_page');?> 
       <?php
		/* Calendar */		
		echo $this->Html->css('calendar/themes/base/jquery.ui.all');
		echo $this->Html->css('calendar/demos');		
		echo $this->Html->script('calendar/jquery.ui.core');
		echo $this->Html->script('calendar/jquery.ui.datepicker');   		
		/* Calendar */
//echo $this->Html->script('admin/admin_reports'); ?>
    <script>
      function showBox(){
                     if($('#OrderIsRecurring').is(':checked')){ 
                            $('#schedulerDiv').show();
                     }else{
                            $('#schedulerDiv').hide();
                     }
                     
              }
              $(function() {
$( ".datepicker" ).datepicker();
});
    </script>
   <div class="row">        
      <?php echo $this->Form->create('Order', array('url' => array('controller' => 'admins', 'action' => 'braintree_payment'),'id'=>'testPageID'));              
           // echo $this->Form->hidden('Setting.id',array('value'=>base64_encode($this->data['Setting']['id']))); 
      ?>
         <div class="row">
            <div class="col-lg-12">                        
                 <?php echo $this->Session->flash();?>   
            </div>            
         </div>
      <div class="col-lg-5">           
          <div class="col-lg-12"><h3>Card Details</h3></div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Name On Card<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.name_on_card',array('div'=>false,'label'=>false,'placeholder' => 'Card Holder Name','class' => 'form-control','type'=>'text','maxlength'=>50)); ?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>CC Number<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.cc_number',array('div'=>false,'label'=>false,'placeholder' => 'Credit Card Number','class' => 'form-control','type'=>'text','maxlength'=>16)); ?>
               </div>
            </div>
         </div>
          <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Expiration<span class="required"> * </span></label>
               </div>
               <div class="col-lg-4 form-box">                
                 <?php $months = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec') ?>
                               <?php echo $this->Form->select('Order.exp_month',$months,array('div'=>false,'label'=>false,'class' => 'form-control','empty'=>'Select Month')); ?>
               </div>
               <div class="col-lg-4 form-box">               
                  <?php for($i=date('Y');$i < date('Y')+10; $i++){
                                  $year[$i]= $i;
                              }
                  ?> 
                  <?php echo $this->Form->select('Order.exp_year',$year,array('div'=>false,'label'=>false,'class'=>'form-control ','empty'=>'Select Year')); ?>
               </div>
            </div>
         </div>
          <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Card Type<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                 <?php $cardtype = array ("amex"=>"American Express","Discover"=>"Discover","MasterCard"=>"MasterCard","Visa" => "Visa"); ?>
                   <?php echo $this->Form->select('Order.card_type',$cardtype,array('div'=>false,'label'=>false,'class' => 'form-control','empty'=>'Select')); ?>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>CVV<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.cvv',array('div'=>false,'label'=>false,'placeholder' => 'CVV','class' => 'form-control','type'=>'text','maxlength'=>4)); ?>
               </div>
            </div>
         </div>
          <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Amount ($)<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.amount',array('div'=>false,'label'=>false,'placeholder' => 'Amount','class' => 'form-control','type'=>'text','maxlength'=>5)); ?>
               </div>
            </div>
         </div>
           <div class="col-lg-12"><h3>Billing Information</h3></div>
           <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label> Name<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.billing_name',array('label' => false,'div' => false, 'placeholder' => 'UserName','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
         </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Street 1<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.billing_street_1',array('label' => false,'div' => false, 'placeholder' => 'Street 1','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Street 2</label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.billing_street_2',array('label' => false,'div' => false, 'placeholder' => 'Street 2','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
        </div>
       
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>State<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                      <?php echo $this->Form->input('Order.billing_state',array('label' => false,'div' => false, 'placeholder' => 'State','class' => 'form-control','maxlength' => 55));?>
                  </div>
               </div>
            </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>City<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.billing_city',array('label' => false,'div' => false, 'placeholder' => 'City','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Zip<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                    <?php echo $this->Form->input('Order.billing_zip',array('label' => false,'div' => false, 'placeholder' => 'Zip','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Phone<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                   <?php echo $this->Form->input('Order.billing_phone',array('label' => false,'div' => false, 'placeholder' => 'Phone','class' => 'form-control','maxlength' => 55));?>
               </div>
            </div>
        </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Email<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box">                
                  <?php echo $this->Form->input('Order.billing_email',array('div'=>false,'label'=>false,'placeholder' => 'Email','class' => 'form-control required','type'=>'text','maxlength'=>100)); ?>
               </div>
            </div>
         </div>
        <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-4 form-label">
                  <label>Recurring<span class="required"> * </span></label>
               </div>
               <div class="col-lg-8 form-box" style="padding-top: 7px;">                
                   <?php echo $this->Form->input('is_recurring',array('label' => false,'div' => false,'type '=> 'checkbox','value'=>1,'onclick'=>'showBox()'));?>
               </div>
            </div>
        </div>
         <div id="schedulerDiv" class="row" style="display:none;">
                <div class="col-lg-12">
                     <div class="form-group form-spacing">
                        <div class="col-lg-4 form-label">
                           <label>Payment Date<span class="required"> * </span></label>
                        </div>
                        <div class="col-lg-8 form-box" >                
                            <?php if(isset($this->request->data['Order']['payment_date']) && $this->request->data['Order']['payment_date'] !="") { $date  = date('m-d-Y',strtotime($this->request->data['Order']['payment_date'])); }else{ $date= ""; }?>           
                             <?php echo $this->Form->input('Order.payment_date',array('type'=>'text','label' => false,'div' => false, 'value'=>$date,'placeholder' => 'Payment Date','class' => 'form-control datepicker','maxlength' => 55));?>
                        </div>
                     </div>
                 </div>
              
       </div><!-- /.row -->
         <div class="col-lg-12 form-spacing">
            <div class="col-lg-4">
               <!--blank Div-->
            </div>
            <div class="col-lg-8 form-box">
               <?php echo $this->Form->button("Save", array('type' => 'submit','class' => 'btn btn-default'));?>
               &nbsp;
               <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>            
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->