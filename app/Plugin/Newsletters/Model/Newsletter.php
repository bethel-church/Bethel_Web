<?php    
    class Newsletter extends AppModel {
        var $name = 'Newsletter';        
        public $validate = array(
        'title' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the newsletter subject.'
        ),
		 'description' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter newsletter content.'
        )
		 ,
		 'send_type' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please select send type.'
        )
        );        
 
    }
?>
