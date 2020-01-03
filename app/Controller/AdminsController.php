<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class AdminsController extends AppController {
	
        var $name = 'Admins';        
        var $components = array('Email','Cookie','Common','Paginator');
		   
        function beforeFilter(){
        
            //parent::beforeFilter();    
            
        }        
       
        
    		function no_access(){
    		
    		}
		
    		function abc(){
          echo "here all done";exit();
        }
        function login(){
        $userId = $this->Session->read('loggedUserInfo.id');
        $trip_leader = $this->Session->read('trip_leader.id');
        //echo "here all done";exit();            
        if(!empty($userId)) {
            $this->redirect(array('controller'=>'trips','action'=>'index'));
        }
        
        $this->layout = 'admin_login';
  	    $this->set('title','Sign in');
  	    $remember_me= "";
  	    $this->loadModel('Admin');
  		
  		
  	    if(isset($this->request->data) && (!empty($this->request->data))){	
      		$this->Admin->set($this->request->data);
      		$this->Admin->validator()->remove('email', 'rule1');
  				if($this->Admin->validates(array('fieldList' => array('email', 'password')))) 
      		{	
      			
      			$email = $this->request->data['Admin']['email'];
      			$user_password  = md5($this->request->data['Admin']['password']);	
            $userInfo = $this->Admin->find('first',array('fields'=>array('id','first_name','last_name','email','welcome','password','admin_role_id'),'conditions'=>array("Admin.email" => $email,"Admin.status"=>1,"Admin.is_deleted"=>0)));
    	      if(!empty($userInfo['Admin']['password']) && ($userInfo['Admin']['password'] == $user_password) ) {
  				    $this->Session->write('loggedUserInfo', $userInfo['Admin']);
      				$this->Session->write('ADMIN_SESSION', $userInfo['Admin']['id']);
  				    if(!empty($this->request->data['Admin']['remember_me'])) {
        				$email = $this->Cookie->read('AdminEmail');
        				$password = base64_decode($this->Cookie->read('AdminPass'));						
        				if(!empty($email) && !empty($password)) {
        					$this->Cookie->delete('AdminEmail');
        					$this->Cookie->delete('AdminPass');     
        				} 						
      					$cookie_email = $this->request->data['Admin']['email'];						
      					$this->Cookie->write('AdminEmail', $cookie_email, false, '+2 weeks');						
      					$cookie_pass = $this->request->data['Admin']['password'];
      					$this->Cookie->write('AdminPass', base64_encode($cookie_pass), false, '+2 weeks'); 
  				    }else {
      					$email = $this->Cookie->read('AdminEmail');
      					$password = base64_decode($this->Cookie->read('AdminPass'));
  					    if(!empty($email) && !empty($password)) {
    							$this->Cookie->delete('AdminEmail');
    							$this->Cookie->delete('AdminPass');     
  					    }
  				    }
  				    $this->redirect('/trips');
  			  }else {						
  				$this->Session->setFlash("This email and password combination does not match. Please try again.",'default',array('class'=>'flashError'));							
  			 }
    		}else{
    		  $this->Session->setFlash("Please enter a valid email address.",'default',array('class'=>'flashError'));	
    		}
      }else{
  				$email = $this->Cookie->read('AdminEmail');
  				$password = base64_decode($this->Cookie->read('AdminPass'));				
  				if(!empty($email) && !empty($password)) {
  					$remember_me  = true;
  					$this->request->data['Admin']['email']  = $email;
  					$this->request->data['Admin']['password']  = $password;					
  				}
          //echo "here all done1";exit();				
  		}
  		$this->set('remember_me',$remember_me); 
    }
    
    function logout(){		            
      $this->Session->delete('loggedUserInfo');		
      $this->Session->delete('trip_leader_info');		
      $this->redirect('/');
    }
}
?>