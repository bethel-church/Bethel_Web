<?php    
    class Country extends AppModel {
        var $name = 'Country';
        
        public $validate = array(
        'name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the country name.'
        )
        
    );
        
         /**************************************************/   
    public function checkUnique(){
        if(!empty($this->data['Country']['id'])){
            $data = $this->find('first',array('conditions'=>array('Country.name'=>$this->data['Country']['name'],'Country.id !='=>$this->data['Country']['id'],'Country.is_deleted'=>0)));
        }else{
            $data = $this->find('first',array('conditions'=>array('Country.name'=>$this->data['Country']['name'])));    
        }
        
        if(!empty($data) && count($data) > 0){
            return false;
        }else{
            return true;
        }
    }
    }

?>
