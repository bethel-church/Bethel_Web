<!DOCTYPE html>
    <html lang="en">
        <head>
           
            <?php echo $this->Html->charset('UTF-8'); ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="">  
            <title>Bethel Trips</title>
            <?php
                echo $this->Html->css('bootstrap'); 
                echo $this->Html->css('sb-admin');      
                echo $this->Html->css('bootstrap-datetimepicker');  
                echo $this->Html->css('ui_1.11.4_themes_smoothness_jquery-ui');         
                //echo $this->Html->css('font/css/font-awesome.min');
                //echo $this->Html->css('custom_admin');   
                //echo $this->Html->script('jquery.min');
                echo $this->Html->script('jquery-1.113');
                echo $this->Html->script('jquery.tablesorter');
                echo $this->Html->script('jquery.tablesorter.pager');
                echo $this->Html->script('jquery.validate');
                echo $this->Html->script('bootstrap');
                echo $this->Html->script('moment-with-locales');
                echo $this->Html->script('bootstrap-datetimepicker');
                echo $this->Html->script('ui_1.11.4_jquery-ui');
            ?>
           <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
          
        </head>
        <body>  
        <nav class="navbar opaqueHeader bottom_nav_bar" role="navigation">        
                    <div class="navbar-header">        
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div>
                         
                            <div class="logoimg leftAlign">
                              <a href="/trips/">
                                <img height="50" src="/img/bethel.png" />
                              </a>
                            </div>
                            <div class="leftAlign" >
                            <?php 
                            //$arr = array('2016','2017');
                            //$curr = Date('Y');//or set it to current selected year.
                            /* commented out as per new YEAR column in trips table
                            foreach ($trip_years as $key=>$value){
                             if($selected_year == $value){
                              $cclass = "currentLinks";
                             }else{
                              $cclass = "pastLinks";
                             }
                             echo "<span class='gapL'>";
                             echo $this->Html->link($value,'/trips/index/y:'.$value,array('title' => '', 'class'=>$cclass));  
                             echo "</span>";
                            }*/
                            $final_year = Date('Y') + 1;
                            $year_data = array();
                            for($y = 2016;$y<=$final_year;$y++){
                              $year_data[$y] = $y;
                            }
                            
                            foreach ($year_data as $key=>$value){
                             if($selected_year == $value){
                              $cclass = "currentLinks";
                             }else{
                              $cclass = "pastLinks";
                             }
                             echo "<span class='gapL'>";
                             echo $this->Html->link($value,'/trips/index/y:'.$value,array('title' => '', 'class'=>$cclass));  
                             echo "</span>";
                            }
                            
                            ?>
                            
                            </div>
                        </div>
                             
                    </div>
                    <?php  echo $this->element('admin/navigation'); ?>
                </nav>        
            <div id="wrapper" class="wrapper">
           
                
                
                    <?php
                     echo $this->fetch('content');
                        //if($this->name != 'CakeError') {
                        //    echo $this->fetch('content');
                        //}else{
                        //    echo '<h2>Oops! Page Not Found. </h2>'; 
                        //}
                    ?>                    
                </div><!-- /#page-wrapper -->
                <div class="push"></div>
                
            </div><!-- /#wrapper -->
            
            <div id="signOut" class="modal signout" data-id="" role="dialog" aria-labelledby="mySmallModalLabel">
                
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header modalTitle">
                      <h4 class="modal-title">SIGN OUT</h4>
                    </div>
                    <div class="modal-body">
                      <p>Please confirm you want to sign out.</p>
                    </div>
                    <div class="modal-footer">
                      <!--<button type="button" class="btn btn-default btnLinkCancel" data-dismiss="modal">CANCEL</button>
                      <button type="button" class="btn btn-primary btnLink confirmSignout">CONFIRM</button>-->
                    <?php echo $this->Html->link('CANCEL','#',array('id'=>'cancelB','title' => '', 'class'=>'btnLinkCancel'));?>  
                    <?php echo $this->Html->link('CONFIRM','#',array('id'=>'confirmB','title' => '', 'class'=>'btnLink'));?>  
                    </div>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
              </div><!-- /.modal -->
            
            
            <?php //echo $this->element('admin/footer'); ?>
            <script type="text/javascript">
                $( document ).ready(function() {
                  $('#cancelB').click(function(){
                    $('#signOut').modal('hide');
                  
                  });
                   $('#confirmB').click(function () {
                     $('#signOut').modal('hide');
                     <?php if($l_id !=""){?>
                     var url = '/leaders/logout';
                     <?php }else{?>
                     //var url = '/login/adminLogins/logout';
                     var url = '/admins/logout';
                     <?php } ?>
                     
                     $(location).attr('href',url);
                     
                  });           
                  $('button.confirmSignout').click(function () {
                    <?php if($l_id !=""){?>
                     var url = '/leaders/logout';
                     <?php }else{?>
                     //var url = '/login/adminLogins/logout';
                     var url = '/admins/logout';
                     <?php } ?>
                     $(location).attr('href',url);
                     
                  });
                  
                  function reposition() {
                    var modal = $(this),
                    dialog = modal.find('.modal-dialog');
                    modal.css('display', 'block');
                    
                    // Dividing by two centers the modal exactly, but dividing by three 
                    // or four works better for larger screens.
                    dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
                  }
                  // Reposition when a modal is shown
                  $('.modal').on('show.bs.modal', reposition);
                  // Reposition when the window is resized
                  $(window).on('resize', function() {
                      $('.modal:visible').each(reposition);
                  });
                  
                  
                });      
                
                
                  
                jQuery('#flashMessage').delay(5000).fadeOut('slow');
                jQuery('#SearchKeyword').on('blur',function(){
                //function checkSpecialChar(){
                    var keyword=$(this).val();
                 //   var iChars = "!#$%^*()+=-[]\\\';,.{}|\":<>?";
                    var iChars = "';,.{}|<>";
                    var count=keyword.length;
                    for (var i = 0; i < count; i++) {
                
                      if (iChars.indexOf(keyword.charAt(i)) != -1) {
                      alert ("Special Characters are not allowed in search.\nPlease remove them and try again.");
                      return false;
                      }
                
                    }
                
                });

          
            </script>
         </body>
    </html>