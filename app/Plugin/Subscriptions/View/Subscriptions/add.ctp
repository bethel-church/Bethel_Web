  <?php
	  echo $this->Html->script('Subscriptions.subscriptions');
	  echo $this->Html->css('Subscriptions.subscription');
  ?>    
   
   <div class="row">        
     <?php echo $this->Form->create('Subscription',array('class'=>'niceform','id'=>'subscription_add')); ?>
    <?php echo $this->Form->hidden('Subscription.id',array('value'=>$subscriptionId)); ?> 
         <div class="col-lg-5">
          
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Name<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('Subscription.name',array('div'=>false,'label'=>false,'class'=>'form-control required special','placeholder'=>'Subscription Name','maxlength'=>50)); ?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Description<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('Subscription.description',array('div'=>false,'class'=>'form-control required','label'=>false,'type'=>'textarea','placeholder'=>'Subscription Description','maxlength'=>255)); ?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Frequency<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php $options = array('1'=>'Weekly','2'=>'Monthly','3'=>'Yearly');?>
                    <?php echo $this->Form->select('Subscription.frequency',$options,array('empty'=>'Please Select','div'=>false,'class'=>'form-control required','label'=>false)); ?>
                  </div>                  
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Amount ($)<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('Subscription.amount',array('type'=>'text','div'=>false,'label'=>false,'class'=>'numbers form-control required','placeholder'=>'Subscription Amount','maxlength'=>10)); ?>
                  </div>                 
               </div>
            </div>
			<div class="col-lg-12">
			   <div class="form-group form-spacing">
				  <div class="col-lg-4 form-label"> 
					 <label>Recurring Plan</label>
				  </div>
				  <div class="col-lg-8 form-box">  
					 <label class="checkbox-inline"><?php if(isset($this->request->data['Subscription']['is_recurring']) && $this->request->data['Subscription']['is_recurring'] == 0){  $checked1= "";}else{  $checked1= "checked";} ?>      
						<?php echo $this->Form->input('is_recurring',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked1));?>
					 </label>
				  </div>
			   </div>
			</div>
			<div class="col-lg-12">
			   <div class="form-group form-spacing">
				  <div class="col-lg-4 form-label"> 
					 <label>Activate</label>
				  </div>
				  <div class="col-lg-8 form-box">  
					 <label class="checkbox-inline"><?php if(isset($this->request->data['Subscription']['status']) && $this->request->data['Subscription']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>      
						<?php echo $this->Form->input('status',array('label' => false,'div' => false,'type '=> 'checkbox', 'checked' => $checked));?>
					 </label>
				  </div>
			   </div>
			</div>
            <div class="col-lg-12 form-spacing">
               <div class="col-lg-4"><!--blank Div--></div>
               <div class="col-lg-8 form-box">
                 <?php echo $this->Form->button($buttonText, array('type' => 'submit','class' => 'btn btn-default'));?>
                 &nbsp;
                 <?php echo $this->Form->button('Reset', array('type' => 'reset','class' => 'btn btn-default'));?>                 
               </div>
            </div>
         </div>   
      <?php echo $this->Form->end(); ?>           
   </div><!-- /.row -->
