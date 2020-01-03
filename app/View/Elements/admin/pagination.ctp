<ul class="pagination">                
    <li class="disabled"><?php echo $this->Paginator->prev(' << ' . __(''),array(),null,array('class' => 'prev disabled'));?></li>
    <li><?php  echo $this->Paginator->numbers(array('separator' => ''));?></li>
    <li><?php   echo $this->Paginator->next(' >> ' . __(''),array(),null,array('class' => 'next disabled'));?></li>
</ul>