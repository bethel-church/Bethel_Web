<?php    
    class User extends AppModel {
        var $name = 'User';      
        
        public $validate = array(
        'first_name' => array(
            
                'rule' => 'notEmpty',                
                'message'    => 'Please enter first name.'                
           
            
        ),        
        'middle_name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter middle name.'
        ),        
        'last_name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter last name.'
        ,        
        'type' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please specify member type.'
        ))
        
    );
        
        public function checkUnique(){
            if(!empty($this->data['User']['id'])){
                $data = $this->find('first',array('conditions'=>array('User.email'=>$this->data['User']['email'],'User.id !='=>$this->data['User']['id'],'User.is_deleted'=>0)));
            }else{
                $data = $this->find('first',array('conditions'=>array('User.email'=>$this->data['User']['email'],'User.is_deleted'=>0)));    
            }
            
            if(!empty($data) && count($data) > 0){
                return false;
            }else{
                return true;
            }
        }
        
    }
    
?>