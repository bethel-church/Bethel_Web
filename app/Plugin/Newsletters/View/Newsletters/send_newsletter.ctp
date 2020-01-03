 <?php echo $this->Html->css('Newsletters.newsletter');  ?>
   <div class="row">        
      <?php echo $this->Form->create('Newsletter', array('url' => array('controller' => 'newsletters', 'action' => 'send_newsletter'),'id'=>'categoryId'));              
           
            echo $this->Form->hidden('Newsletter.id',array('value'=>$newsletterId));
      ?>
      <div class="col-lg-12">           
         <div class="col-lg-12"><?php echo $this->Session->flash();?>   </div>
         <div class="col-lg-12">
            <div class="form-group form-spacing">
               <div class="col-lg-2 form-label"> 
                  <label>Send To</label>
               </div>
               <div class="col-lg-3 form-box">                   
                    <?php $userCount =array(0=>'All',1=>'Individual');?>           
                       <?php echo $this->Form->input('Newsletter.send_to',array('default'=>0,'type'=>'select','options'=>$userCount,'label' => false,'div' => false, 'empty' => '--Select--','class' => 'form-control','onchange'=>'toggleSendTodiv(this.value)'));?>
                                      
               </div>
            </div>
         </div>
         <div class="col-lg-12" id="userChkDiv" >
            <div class="form-group form-spacing">
               <div class="col-lg-2 form-label"> 
                  <label>Users</label>
               </div>
               <div class="col-lg-10 form-box"> 
                <?php /*if(isset($userData) && !empty($userData)){
                       $explodeArr =  explode(',',$role_data['RolePermission']['permission_ids']);
                    }else{ $explodeArr[] = ""; }*/ ?>
                    <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th class="th_checkbox"><input type="checkbox" class="checkall"></th>
							<th><?php echo "Name"; ?> </th>
                              <th><?php echo "Email"; ?> </th>
                        </tr>
                    </thead>
                    <tbody class="dyntable">
                        <?php
                        $i = 0;
                        
                        foreach($userData as $val)
                        {
                            $class = ($i%2 == 0) ? ' class="active"' : '';
                            ?>
                        <tr <?php echo $class;?>>
                            <td align="center"><?php echo $this->Form->input('Newsletter.user_id.'.$val['User']['id'],array('label' =>'','value'=>$val['User']['email'],'div' => false,'class'=>'userchecks','type '=> 'checkbox' /*, 'checked' => (in_array($val['Module']['id'],$explodeArr)) ? 'checked' : ''*/));?></td>     
                           
                          
                            <td><?php echo $val['User']['first_name'] ;?></td>
                            <td><?php echo $val['User']['email']; ?></td>
                            
                                            
                        </tr>
                        <?php
                            $i++;
                        } ?>
                    </tbody>
                    
                </table>
                <?php   foreach($userData as $val){
                  
                  
                     //   if(in_array($val['Module']['id'],$explodeArr)) { $check = "'checked'=>'checked'"; }else{ $check= ""; } ?>
                   <!-- <div class="col-lg-3 form-box">
                        <label class="checkbox-inline">
                           <?php // echo $this->Form->input('Newsletter.user_id.'.$val['User']['id'],array('label' => $val['User']['first_name'],'value'=>$val['User']['email'],'div' => false,'type '=> 'checkbox' /*, 'checked' => (in_array($val['Module']['id'],$explodeArr)) ? 'checked' : ''*/));?>
                           
                        </label>
                    </div>-->
                <?php } ?>
                    
                   
               </div>
               <span id="mydiv" style="display:none;" for="AnnouncementPrivacyPrivacySetting" class="error">Select atleast one user</span>
            </div>
         </div>
         <div class="col-lg-12 form-spacing">
            <div class="col-lg-2">
               <!--blank Div-->
            </div>
            <div class="col-lg-3 form-box">
               <?php echo $this->Form->button("Send", array('type' => 'submit','class' => 'btn btn-default'));?>
                           
            </div>
         </div>
      </div>
      <?php echo $this->Form->end(); ?>
   </div><!-- /.row -->
    <script type= "text/javascript">
      $( document ).ready(function() {
           $("input[type=checkbox]").attr("disabled", true);
      });
              function getTemplate(templateId)
              {
                     jQuery.ajax({
                            type: "POST",
                            url: "/admin/admins/getNewletterTemplate/"+templateId,
                           
                            success: function(result){
                                CKEDITOR.instances['newletterTemp'].setData(result);
                            }
                     });
                  
              }
              function toggleSendTodiv(typeId){
                     if(typeId==1){
                           // $('#userChkDiv').show();
                           $("input[type=checkbox]").prop('disabled',false);
                           $("button[type=submit]").prop('disabled',true);
                           
                     }else{ 
                         // $("input[type=checkbox]").prop('disabled',true);
                        $("input[type=checkbox]").attr("disabled", true);
                        $("button[type=submit]").prop('disabled',false);
                          //  $('#userChkDiv').hide();
                     }
                     
              }
                $('#categoryId').on('submit',function(e){
                    var sendTo = $('#NewsletterSendTo').val();
                    if(sendTo == 1){       
                        if ($("input[type=checkbox]:checked").length === 0) {
                            e.preventDefault();
                            $('#mydiv').show();
                         
                            return false;
                        }else {	 
                           $('#mydiv').hide();
                        }
                    }	
                });
                 $('.userchecks').on('click',function(e){
                    //var sendTo = $(this).val();
                    //if(sendTo == 1){
                   //  e.preventDefault();
		      if ($("input[type=checkbox]:checked").length === 0) {
                           
                            $("button[type=submit]").prop('disabled',true);
			   
                           
                        }else {	 
                            $("button[type=submit]").prop('disabled',false);
                        }
			
		     var selectedCounter = 0, no_of_records = 0;        
		     jQuery('.userchecks').each(function(){
			     
			       no_of_records++;
			       if (jQuery(this).is(':checked')) {
				   selectedCounter++;
			       }
		     })
		     var checkAllStatus = (no_of_records == selectedCounter) ? true : false;
		     jQuery('.checkall').prop('checked',checkAllStatus);
                    //}	
                });
		 
		 $('.checkall').click(function(){
		  
		     var checkAll = $(".checkall:checked").length;
		      if(checkAll == 1){
			$("input[type=checkbox]:checked").each(function(){
			   $("input[type=checkbox]").attr("checked",true);
			});
			$("button[type=submit]").prop('disabled',false);
		      } else {
			$("input[type=checkbox]:checked").each(function(){
			   $("input[type=checkbox]").attr("checked",false);
			});
			$("button[type=submit]").prop('disabled',true);
		      }
		 });
       </script>