<?php
ob_start();
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    
    var $helpers = array('Form','Html');
    var $components =array('RequestHandler','Session');
        
    function beforeRender() {        
        if($this->name == 'CakeError') {
            $this->layout = 'admin';
        }
    }
    
  
    
    function beforeFilter()
    {
		

  	   if(in_array($this->params['action'],array('index','login','forgot_password')) && $this->params['controller'] == 'admins' ){
  		    $this->layout='admin_login';		
  	    }else{
  		  if( ! in_array($this->params['plugin'],array('shoppingcart','users','login'))){
    			if(! in_array($this->params['action'],array('admin_getAllLinks'))){
    				if(! in_array($this->params['action'],array('addedit')) && $this->params['controller'] !="admin_users"){
    		      $this->checkAdminSession();
    				}
    			}
  		  }
		  $this->layout='admin';		
	   }
	
            
      $loggedUserInfo = $this->Session->read('loggedUserInfo'); 
      $this->set('loggedUserInfo',$loggedUserInfo);            
      
    }
    
    /* Check admin session is Exist OR Not*/
   function checkAdminSession() { 
	//pr($this->Session->read('loggedUserInfo')); die;
    if(!$this->Session->check('loggedUserInfo')) {    
        //echo "checking Admin session";exit();                                            
		    //$this->redirect('/');
		}else {
			$roleId = $this->Session->read('loggedUserInfo.admin_role_id');
		
			$currentPlugin = $this->params['plugin']; 
	if($currentPlugin !=""){
			$this->loadModel('Module');
			$this->loadModel('RolePermission');
			$plugindata = $this->Module->find('first',array('conditions'=>array('plugin'=>$currentPlugin),'fields'=>array('id','plugin')));
	
			if(!empty($plugindata)){
				$chkpermission = $this->RolePermission->query("SELECT * FROM role_permissions
WHERE role_id = ".$roleId." AND FIND_IN_SET(".$plugindata['Module']['id'].",permission_ids )");
				//pr($chkpermission); die;
				if(empty($chkpermission)){
					$this->redirect(array('controller' => 'admins', 'action' => 'no_access','plugin'=>false,'admin'=>false)); 
				}
			}
		}
	    }
	
	}
    
    /** Check User Login Session */
    function checkUserSession()
    {
        if(!$this->Session->check('UserInfo')) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }else{            
        }
    }
    
    /** The function setStatus to active/Inactive/Delete the records based on controller/model */    
    function setStatus($status,$CheckedList,$model,$controller,$action)
    {
        if(count($CheckedList) < 1)
        {
            $this->Session->setFlash("Please select the at least one record.",'default',array('class'=>'alert alert-danger'));					
            
        }else
        {
            for($i=0; $i<count($CheckedList); $i++)
            {				
                $this->$model->id = null;
                $this->$model->id = base64_decode($CheckedList[$i]); 
                $id = base64_decode($CheckedList[$i]);
                if($status == '1' || $status == '2')
                {
                    $statusValue = ($status == 1)  ? '1' : '0';
                    $operation = ($status == 1)  ? 'active' : 'inactive';
					$operation1 = ($status == 1)  ? 'activated' : 'inactivated';
                    $this->$model->saveField('status', $statusValue);	
                }else{
					$this->$model->updateAll(array($model.'.is_deleted'=>'1'),array($model.'.id'=>$id));
                    //$this->$model->delete();
                    $operation1 = 'deleted';
                }
            }
            $message = (count($CheckedList) == 1) ? "Record has been ".$operation1." successfully" : "Records have been ".$operation1." successfully";				$this->Session->setFlash($message,'default',array('class'=>'alert alert-success'));
        }
        $this->redirect(array("controller" =>$controller , "action" => $action));			
    }
    
    /** function to unbind all the models */
	function unbindModelAll() { 
	    $unbind = array(); 
	    foreach ($this->belongsTo as $model=>$info) 
	    { 
	      $unbind['belongsTo'][] = $model; 
	    } 
	    foreach ($this->hasOne as $model=>$info) 
	    { 
	      $unbind['hasOne'][] = $model; 
	    } 
	    foreach ($this->hasMany as $model=>$info) 
	    { 
	      $unbind['hasMany'][] = $model; 
	    } 
	    foreach ($this->hasAndBelongsToMany as $model=>$info) 
	    { 
	      $unbind['hasAndBelongsToMany'][] = $model; 
	    } 
	    parent::unbindModel($unbind); 
	}
	
	
	
	
	
    /*
	* This function is used to send email with template  
	* @author        Navdeep Kaur
	* @copyright     smartData Enterprise Inc.
	* @method        sendEmail
	* @param         $to, $subject, $messages, $from, $reply,$path,$file_name
	* @return        void 
	* @since         version 0.0.1
	* @version       0.0.1 
	*/
	public function sendEmail($to = null, $subject ='', $messages = null, $from=null, $reply = null,$path=null,$file_name = null){
		$this->Email->smtpOptions = array(
			'host' => Configure::read('host'),
			'username' =>Configure::read('username'),
			'password' => Configure::read('password'),
			'timeout' => Configure::read('timeout')
		);                
        
		$this->Email->delivery = 'mail';//possible values smtp or mail 
        $admin_name = Configure::read('ADMIN_NAME');
		if(empty($reply)){
			$reply = $admin_name.'<'.Configure::read('replytoEmail').'>';
		}
		if(empty($from)){
			$from = $admin_name.'<'.Configure::read('fromEmail').'>';
		}
		$this->Email->from = $from;
		$this->Email->replyTo = $reply;
		if($to == 'admin'){
			$this->Email->to = $from;
		} else {
			$this->Email->to = $to;
		}
		
		if(!empty($path) && !empty($file_name))
		    $this->Email->attachments = array($file_name,$path.$file_name);
		    
		    if(empty($subject)){
		       $subject='Admin'; 
		    }
		    $this->Email->subject = $subject;
		    $this->set('data',$messages);
		    $this->set('smtp_errors', $this->Email->smtpError);
		    $this->Email->sendAs= 'both';
		    $this->Email->template='comman_template';
		 
		if($this->Email->send()){
			return true;
		} else {
			return false;
		}
	}
/*
 * This function is used to get the list of countries
 * @author        Navdeepkaur
 * @copyright     smartData Enterprise Inc.
 * @method        getCountries
 * @param         null
 * @return        array 
 * @since         version 0.0.1
 * @version       0.0.1 
 */	
	public function getCountries(){		
		$this->loadModel('Country');
		$country = $this->Country->find('list',array('conditions'=>array('Country.is_deleted'=>0,'Country.status'=>1),'fields'=>array('Country.id','name'),'order'=>'name ASC'));
		$this->set('country',$country);		
	}
	
	function getSubscriptions(){
		$this->loadModel('Subscription');
		$data=  $this->Subscription->find('list',array('conditions'=>array('Subscription.is_deleted'=>0,'Subscription.status'=>1)));
		$this->set('subscriptionPlans',$data);
	}
	function getCompanyName($cId){
		$this->loadModel('Company');
		$data =  $this->Company->find('first',array('conditions'=>array('Company.id'=>$cId),'fields'=>array('name')));
		return $data['Company']['name'];
	}
}