    <div class="row">
        <div class="col-lg-2">   
            <?php echo $this->Form->input('setStatus', array('type' => 'hidden','id'=>'setStatus'));
                  echo $this->Form->input('status', array('label' => false,'div' => false,'options' => array('1' => 'Activate', '2' => 'Deactivate','3' => 'Delete'),'class' => 'form-control','id' => 'statusId','empty'=>'-Select Action-'));?>      
        </div>
        <div class="col-lg-2">   
             <?php echo $this->Form->button('Submit', array('type' => 'submit','class' => 'btn btn-default disabled','id' => 'operationId'));?>
        </div>
    </div>