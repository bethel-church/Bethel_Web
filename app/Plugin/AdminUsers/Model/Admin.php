<?php    
    class Admin extends AppModel {
        var $name = 'Admin';
        
        public $validate = array(
        'first_name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the first name.'
        ),        
        'email' => array(
            'email' => array(
                'rule' => 'email',                
                'message'    => 'Please enter a valid email address.'                
            ),
            'rule1' => array(
                'rule' => 'checkUnique',                
                'message'    => 'Email already exists.'
            )
        ),  
        'password' => array(
            'rule'    => array('minLength', '6'),
            'message' => 'Please enter at least 6 characters password.'
        ),
        'phone' => array(
           'rule' => 'numeric',
           'allowEmpty' => true,
           'message' => 'Please enter the valid phone number.'
        )
        
    );
        
         /**************************************************/   
    public function checkUnique(){
        if(!empty($this->data['Admin']['id'])){
            $data = $this->find('first',array('conditions'=>array('Admin.email'=>$this->data['Admin']['email'],'Admin.id !='=>$this->data['Admin']['id'],'Admin.is_deleted'=>0)));
        }else{
            $data = $this->find('first',array('conditions'=>array('Admin.email'=>$this->data['Admin']['email'],'Admin.is_deleted'=>0)));    
        }
        
        if(!empty($data) && count($data) > 0){
            return false;
        }else{
            return true;
        }
    }
    }

?>