<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class LeadersController extends AppController {
	
        var $name = 'Leaders';        
        var $components = array('Email','Cookie','Common','Paginator');
		   
        function beforeFilter(){
        
            //parent::beforeFilter();    
            
        }        
       
        
    		function no_access(){
    		
    		}
		    function check_data(){
		      //print "<pre>";
          //print_r($this->request->data);
          $email = trim($this->request->data['Leader']['email']);
          $passcode = trim($this->request->data['Leader']['password']);
          if($email == "" || $passcode == ""){
            return false;
          }
          return true;
        }
    		
        function login(){
        //$userId = $this->Session->read('loggedUserInfo.id');
        $trip_leader = $this->Session->read('trip_leader_info.id');
        //echo "here all done";exit();            
        if(!empty($trip_leader)) {
            //$this->redirect(array('controller'=>'trips','action'=>'index'));
            $this->redirect("/trips/view/".base64_encode($trip_leader));
        }
        
        $this->layout = 'admin_login';
  	    $this->set('title','Sign in');
  	    $remember_me= "";
  	    $this->loadModel('Trip');
  		
  		
  	    if(isset($this->request->data) && (!empty($this->request->data))){	
      		$this->Trip->set($this->request->data);
      		//$this->Trip->validator()->remove('email', 'rule1');
  				if($this->check_data()) 
      		{	
      			
      			$email = $this->request->data['Leader']['email'];
      			$user_password  = $this->request->data['Leader']['password'];	
            $userInfo = $this->Trip->find('first',array('fields'=>array('id','name','leader_passcode'),'conditions'=>array("Trip.leader_email" => $email)));
    	      if(!empty($userInfo['Trip']['leader_passcode']) && ($userInfo['Trip']['leader_passcode'] == $user_password) ) {
  				    $this->Session->write('trip_leader_info', $userInfo['Trip']);
      				//$this->Session->write('ADMIN_SESSION', $userInfo['Admin']['id']);
      				//print "<pre>";
              //print_r($userInfo);exit();
  				    if(!empty($this->request->data['Leader']['remember_me'])) {
        				$email = $this->Cookie->read('LeaderEmail');
        				$password = base64_decode($this->Cookie->read('LeaderPass'));						
        				if(!empty($email) && !empty($password)) {
        					$this->Cookie->delete('LeaderEmail');
        					$this->Cookie->delete('LeaderPass');     
        				} 						
      					$cookie_email = $this->request->data['Leader']['email'];						
      					$this->Cookie->write('LeaderEmail', $cookie_email, false, '+2 weeks');						
      					$cookie_pass = $this->request->data['Leader']['password'];
      					$this->Cookie->write('LeaderPass', base64_encode($cookie_pass), false, '+2 weeks'); 
  				    }else {
      					$email = $this->Cookie->read('LeaderEmail');
      					$password = base64_decode($this->Cookie->read('LeaderPass'));
  					    if(!empty($email) && !empty($password)) {
    							$this->Cookie->delete('LeaderEmail');
    							$this->Cookie->delete('LeaderPass');     
  					    }
  				    }
  				    $id = base64_encode($userInfo['Trip']['id']);
  				    $this->redirect('/trips/view/'.$id);
  			  }else {						
  				$this->Session->setFlash("This email and password combination does not match. Please try again.",'default',array('class'=>'flashError'));							
  			 }
    		}else{
    		  $this->Session->setFlash("Please enter a valid email address.",'default',array('class'=>'flashError'));	
    		}
      }else{
  				$email = $this->Cookie->read('LeaderEmail');
  				$password = base64_decode($this->Cookie->read('LeaderPass'));				
  				if(!empty($email) && !empty($password)) {
  					$remember_me  = true;
  					$this->request->data['Leader']['email']  = $email;
  					$this->request->data['Leader']['password']  = $password;					
  				}
          //echo "here all done1";exit();				
  		}
  		$this->set('remember_me',$remember_me); 
    }
    
    function logout(){		            
      $this->Session->delete('loggedUserInfo');	
      $this->Session->delete('trip_leader_info');	
      //$trip_leader = $this->Session->read('trip_leader_info.id');		
      $this->redirect('/leaders/login');
    }
    function index(){
      $this->redirect('/leaders/login');
    }
}
?>