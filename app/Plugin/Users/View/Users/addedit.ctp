   <?php
   echo $this->Html->script('Users.users');
   echo $this->Html->css('Users.user');
   ?>    
     
   <div class="row"> 
      <?php echo $this->Form->create(null, array('url' => array('controller' => 'users', 'action' => 'addedit'),'id'=>'userId'));              
            echo $this->Form->hidden('User.id',array('value'=>base64_encode($this->data['User']['id'])));           
      ?>
         <div class="col-lg-5">
           <div class="col-lg-12"><h3><u>Basic Information</u></h3></div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>First Name<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('first_name',array('label' => false,'div' => false, 'placeholder' => 'First Name','class' => 'form-control','maxlength' => 30));?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Last Name<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('last_name',array('label' => false,'div' => false, 'placeholder' => 'Last Name','class' => 'form-control','maxlength' => 30));?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Email<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">
					 <?php if(isset($this->data['User']['id']) && $this->data['User']['id'] != "" ){ $readonly= "true";}else{ $readonly= ""; }  ?>
                     <?php echo $this->Form->input('email',array('label' => false,'div' => false, 'placeholder' => 'Email','class' => 'form-control','maxlength' => 55, 'readonly'=> $readonly));?>
                  </div>
               </div>
            </div>
            <?php if($this->request->data['User']['id'] == ""){ ?>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Password<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('password',array('type'=>'password','label' => false,'div' => false, 'placeholder' => 'Password','class' => 'form-control','maxlength' => 30,'readonly' => ''));?>
                  </div>
               </div>
            </div>
            <?php } ?>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Gender<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box" style="margin-top:5px;">                
                     <?php                
                     $options = array('0' => 'Female', '1' => 'Male');
                     $attributes = array('legend' => false,'class'=>'radio-inline','separator'=>'&nbsp;&nbsp;&nbsp;','default'=>0);               
                     echo $this->Form->radio('gender', $options, $attributes);               
                    ?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12"><h3><u>Address Information</u></h3></div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Address 1<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('address_1',array('label' => false,'div' => false, 'placeholder' => 'Address 1','class' => 'form-control','maxlength' => 80));?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Address 2</label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('address_2',array('label' => false,'div' => false, 'placeholder' => 'Address 2','class' => 'form-control','maxlength' => 80));?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Country<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php
                            echo $this->Form->input('country_id',array('options'=>$country,'div'=>false,'label'=>false,'class'=>'form-control','empty'=>'Select Country')); ?>
							<section id="stateloader" class="loader_img_state" style="display:none;"><?php echo $this->Html->image('ajax-loader.gif'); ?></section>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>State<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->hidden('hidden_state_val',array('value'=>$this->request->data['User']['state_id'],'id'=>'hiddenUserState'));
                            echo $this->Form->input('state_id',array('options'=>array(),'div'=>false,'label'=>false,'class'=>'form-control','empty'=>'Select State / Province','rel'=>(!empty($this->request->data['User']['state_id']) ? $this->request->data['User']['state_id'] : ''))); ?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>City<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('city',array('label' => false,'div' => false, 'placeholder' => 'City','class' => 'form-control','maxlength' => 30));?>
                  </div>
               </div>
            </div>
         
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Zip<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('zip',array('type'=>'text','label' => false,'div' => false, 'placeholder' => 'Zip','class' => 'form-control','maxlength' => 6));?>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label">
                     <label>Phone No.<span class="required"> * </span></label>
                  </div>
                  <div class="col-lg-8 form-box">                
                     <?php echo $this->Form->input('phone_no',array('label' => false,'div' => false, 'placeholder' => 'Phone Number','class' => 'form-control','maxlength' => 12));?>
                  </div>
               </div>
            </div>

            <div class="col-lg-12">
               <div class="form-group form-spacing">
                  <div class="col-lg-4 form-label"> 
                     <label>Activate</label>
                  </div>
                  <div class="col-lg-8 form-box">  
                     <label class="checkbox-inline"><?php if(isset($this->request->data['User']['status']) && $this->request->data['User']['status'] == 0){  $checked= "";}else{  $checked= "checked";} ?>
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
