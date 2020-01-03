<?php    
    class Emailtemplate extends AppModel {
        var $name = 'Emailtemplate';        
        public $validate = array(
        'name' => array(
            'rule'    => 'notEmpty',
            'message' => 'Please enter the template name.'
        )
        );        
    /*
	 * to get the email template content by passing the unique code
	 * 
	 * @author        Navdeep Kaur
	 * @copyright     smartData Enterprise Inc.
	 * @method        getEmailTemplate
	 * @param         $code 
	 * @return        Email tempalte data 
	 * @since         version 0.0.1
	 * @version       0.0.1 
	 */
    function getEmailTemplate($code = null) {
        if(!empty($code)){
            $result = $this->find('first', array('conditions' => array('Emailtemplate.template_code LIKE' => "$code")));
            if(is_array($result) && !empty($result)) { 
                return ($result);
            }else {
                return false;
            }
        } else {
            return false;
        }
	}
    }
?>
