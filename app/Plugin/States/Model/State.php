<?php    
    class State extends AppModel {
        var $name = 'State';
        
        public $validate = array(
        'name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the state name.'
        )
        
    );
        
         /**************************************************/   
    public function checkUnique(){
        if(!empty($this->data['State']['id'])){
            $data = $this->find('first',array('conditions'=>array('State.name'=>$this->data['State']['name'],'State.id !='=>$this->data['State']['id'],'State.is_deleted'=>0)));
        }else{
            $data = $this->find('first',array('conditions'=>array('State.name'=>$this->data['State']['name'])));    
        }
        
        if(!empty($data) && count($data) > 0){
            return false;
        }else{
            return true;
        }
    }
    }

?>
