<?php
/**
 * Subscription Model.
 *
 * This is used to deal with the table subscriptions
 *  
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Navdeep Kaur
 * @copyright     smartData Enterprise Inc.
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         version 0.0.1
 * @version       0.0.1
 */
class Subscription extends AppModel{
    var $name='Subscription';
    
    //validation rules for the fields
    public $validate = array(
            'name'=>array(                 
                'rule1' => array(
                    'required' => true,
                    'rule' => array('notEmpty'),
                    'message' => 'Please enter name.'
                ),
                'rule2' => array(
                    'required' => true,
                    'rule' => array('checkUnique'),
                    'message' => 'Plan name already in use.'
                )
            ),    
            'description' => array(
                'required' => true,
                'rule' => array('notEmpty'),
                'message' => 'Please enter description.'
            ), 
            'frequency' => array(
                'required' => true,
                'rule' => array('notEmpty'),
                'message' => 'Please select frequency.'
            ), 
            'amount' => array(
                'required' => true,
                'rule' => array('notEmpty'),
                'message' => 'Please enter amount.'
            )
            
            
    );
    
     public function checkUnique(){
        App::uses('CakeSession', 'Model/Datasource');
     
        
        if(!empty($this->data['Subscription']['id'])){
            $data = $this->find('first',array('conditions'=>array('Subscription.name'=>$this->data['Subscription']['name'],'Subscription.id !='=>$this->data['Subscription']['id'],'Subscription.is_deleted'=>0)));
        }else{
            $data = $this->find('first',array('conditions'=>array('Subscription.name'=>$this->data['Subscription']['name'],'Subscription.is_deleted'=>0)));    
        }
        
        if(!empty($data) && count($data) > 0){
            return false;
        }else{
            return true;
        }
    } 

}
?>