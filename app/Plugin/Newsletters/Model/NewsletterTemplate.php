<?php    
    class NewsletterTemplate extends AppModel {
        var $name = 'NewsletterTemplate';        
        public $validate = array(
        'name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the template name.'
        ),
		 'template' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the template content.'
        )
        );        
 
    }
?>
