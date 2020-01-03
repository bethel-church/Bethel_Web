<?php
/**
 * Subscription controller.
 *
 * This file will render views from views/subscriptions/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Subscriptions controller
 *
 * To manage the subscription related codes
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/teams-controller.html
 */
class SubscriptionsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Subscriptions';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
    
/**
 * This controller uses the component
 *
 * @var array
 */ 
    public $components = array('Paginator','Common');
    
	/*
	 * To list out the various subscription plans
	 * 
	 * @author        Navdeep Kaur
	 * @copyright     smartData Enterprise Inc.
	 * @method        admin_index
	 * @param         none 
	 * @return        void 
	 * @since         version 0.0.1
	 * @version       0.0.1 
	 */
    public function index(){
		
		
		// Layout
		$this->layout = 'admin';
		
		// Title for layout
		$this->set('title_for_layout','Plugin Project | Subscriptions');
		$this->loadModel('Subscription');
		/* Active/Inactive/Delete functionality */ 
		if((isset($this->data["Subscription"]["setStatus"])))
		{ 
			if(!empty($this->request->data['Subscription']['status'])){ 
				$status = $this->request->data['Subscription']['status'];
			}else
			{
				$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
				$this->redirect(array('action' => 'index'));
				
			}
			$CheckedList = $this->request->data['checkboxes'];
			
			$model='Subscription';				
			$controller = $this->params['controller'];				
			$action = $this->params['action'];				
			$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
		}
		/* Active/Inactive/Delete functionality */
		$value ="";
		$criteria = "Subscription.is_deleted = 0 "; 
		
		if(!empty($this->params)){ 
			if(!empty($this->params->query['keyword'])){
				$value = trim($this->params->query['keyword']);	
			}
			if($value !="") {
				$criteria .= " AND (Subscription.name LIKE '%".$value."%')";						
			}
		}
		// Used to show count of data in breadcrum
		$getrecCount = $this->Subscription->find('count',array('conditions'=>array('is_deleted'=>0)));
		
		$this->Paginator->settings = array('conditions' => array($criteria),'order'=>'Subscription.id DESC');
		$getData =  $this->Paginator->paginate('Subscription');         
        $this->set(compact('getData','getrecCount'));
		$this->set('keyword',$value);
		if($value == "" && empty($getData)){
			$this->redirect(array('controller'=>'Subscriptions','action' => 'add'));
		}
		$this->set('subscriptions','class = "active"');		
		$this->set('breadcrumb','Subscriptions');
    }
	/*
	 * To add/edit subscription plans
	 * 
	 * @author        Navdeep Kaur
	 * @copyright     smartData Enterprise Inc.
	 * @method        admin_add
	 * @param         $subscriptionId 
	 * @return        void 
	 * @since         version 0.0.1
	 * @version       0.0.1 
	 */
    public function add($subscriptionId=null){
		
		$this->layout = 'admin';
		$this->set('title_for_layout','Athledo | Add Subscription');
		$this->loadModel('FrontendUser');
		$subscriptionId = base64_decode($subscriptionId);
        $this->set('subscriptionId',$subscriptionId);
        if(!empty($this->request->data)){
		
		#sanitize data (remove tags)
		$this->request->data = $this->sanitizeData($this->request->data);
		
            $this->Subscription->set($this->request->data);			
            if($this->Subscription->validates()){
			
				if($this->request->data['Subscription']['id'] !=""){
					$msz = "Subscription edited successfully";
					$this->request->data['Subscription']['id'] = $this->request->data['Subscription']['id'];
				}else{
					$msz = "Subscription added successfully";
					$this->request->data['Subscription']['id'] = "";
				}
				
				if($this->Subscription->save($this->request->data)){
					$this->Session->setFlash($msz,'default',array('class'=>'alert alert-success'));	
                    $this->redirect(array("controller"=>"subscriptions","action" => "index"));
				}
                
            }
        }else{
            
            $this->Subscription->id = $subscriptionId;
            $this->data = $this->Subscription->read();
        }
		
			$textAction = ($subscriptionId == null) ? 'Add' : 'Edit';			
			$this->set('subscriptions','class = "active"');			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','subscriptions/'.$textAction);
			$buttonText = ($subscriptionId == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
    }
	/*
	 * To delete the subscription plans
	 * 
	 * @author        Navdeep Kaur
	 * @copyright     smartData Enterprise Inc.
	 * @method        admin_delete
	 * @param         $subscriptionId 
	 * @return        void 
	 * @since         version 0.0.1
	 * @version       0.0.1 
	 */
    public function delete($subscriptionId=null){			
		$this->loadModel('Subscription');
		$subscriptionId = base64_decode($subscriptionId);
		
	
		if($this->Subscription->updateAll(array('Subscription.is_deleted'=>'1'),array('Subscription.id'=>$subscriptionId))){
			$this->Session->setFlash("Subscription deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
			$this->redirect(array("controller"=>"subscriptions","action" => "index"));
		}
	}

	/*
	 * To view the detail of subscription plans
	 * 
	 * @author        Navdeep Kaur
	 * @copyright     smartData Enterprise Inc.
	 * @method        admin_view
	 * @param         $subscriptionId 
	 * @return        void 
	 * @since         version 0.0.1
	 * @version       0.0.1 
	 */
	public function view($subscriptionId = null){
		$this->layout = '';
		$subscriptionId = base64_decode($subscriptionId);
		$data = $this->Subscription->find('first',array('conditions'=>array('Subscription.id'=>$subscriptionId)));
		$this->set('data',$data);
		$this->set('subscriptions','class = "active"');				
		$this->set('breadcrumb','subscriptions/View');
	}
	
	public function sanitizeData($data) {
	    if (empty ( $data )) {
		    return $data;
	    }
	    if (is_array ( $data )) {
		    foreach ( $data as $key => $val ) {
			    $data [$key] = $this->sanitizeData ( $val );
		    }
		    return $data;
	    } else {
		    $data = trim ( strip_tags ( $data ) );
		    return $data;
	    }
	}
	
}
