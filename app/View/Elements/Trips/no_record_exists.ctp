<div >
    <div class="">                        
         <div class="alert alert-info" style="text-align:center;width:100% !important" id="flashMessage_NO">
             <?php echo "No trips found. Click "; echo $this->Html->link('here',array('controller' => 'trips', 'action' => 'add'),array('class' => 'icon-file-alt','title' => 'Add Trip')); echo " to add a new one.";?>   
         </div>
    </div>            
</div>