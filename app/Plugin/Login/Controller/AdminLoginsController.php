<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class AdminLoginsController extends AppController {
	
        var $name = 'AdminLogins';
	var $uses = array();
        var $components = array('Email','Cookie','Common');
		   
        function beforeFilter(){
            parent::beforeFilter();    
            
        }        
        /*
            * admin_login function
            * Functionality -  Admin login functionality
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 24-Feb-2015
        */
        function login() {        
            $userId = $this->Session->read('loggedUserInfo.id');            
            if(!empty($userId)) {
                $this->redirect(array('controller'=>'trips','action'=>'index'));
            }
        // $this->layout = '';
	    $this->set('title','Sign in');
	    $remember_me= "";
	    $this->loadModel('Admin');
		//Code for webservice
			if($this->request->accepts('application/json')) {
				$input = json_decode(file_get_contents('php://input'), true);
				$this->request->data = $input;
				
			}
		
	    if(isset($this->request->data) && (!empty($this->request->data)))
            {	
		$this->Admin->set($this->request->data);
		$this->Admin->validator()->remove('email', 'rule1');
		
		if($this->Admin->validates(array('fieldList' => array('email', 'password')))) 
		{	
			
			$email = $this->request->data['Admin']['email'];
			$user_password  = md5($this->request->data['Admin']['password']);	
      				
			$userInfo = $this->Admin->find('first',array('fields'=>array('id','first_name','last_name','email','welcome','password','admin_role_id'),'conditions'=>array("Admin.email" => $email,"Admin.status"=>1,"Admin.is_deleted"=>0)));
			
			
			
			if(!empty($userInfo['Admin']['password']) && ($userInfo['Admin']['password'] == $user_password) ) {
				if($this->request->accepts('application/json')) {
					$messageId= 200 ;
					$message = "Success";
					$finalData['result'] = $userInfo['Admin']['id'];
					$finalData['message'] = $message;
					$finalData['messageId'] = $messageId;
					echo json_encode($finalData); exit;
				}
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
				//$this->redirect(array('controller'=>'trips','action'=>'index'));
			}
			else {						
				$this->Session->setFlash("This email and password combination does not match. Please try again.",'default',array('class'=>'flashError'));							
				
			    }
		}else{
		//configure::write("debug",2);
		   // pr($this->Admin->ValidationErrors);
		    //exit();
				$this->Session->setFlash("Please enter a valid email address.",'default',array('class'=>'flashError'));	
				
		    }
            }else {
				
				$email = $this->Cookie->read('AdminEmail');
				$password = base64_decode($this->Cookie->read('AdminPass'));				
				if(!empty($email) && !empty($password)) {
					$remember_me  = true;
					$this->request->data['Admin']['email']  = $email;
					$this->request->data['Admin']['password']  = $password;					
				}				
			}
			$this->set('remember_me',$remember_me);
        }     
        
		/*
            * admin_dashboard function
            * Functionality -  Dashboard functionality
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function dashboard()
        {            
			$this->set('breadcrumb','Dashboard');
        }
		/*
            * forgot_password function
            * Functionality - forgot password for admin profile
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */		
        function forgot_password()
        {
            
			$this->loadModel('Admin');
			$this->set('title','Forgot Password');
			$this->set('title_for_layout','Forgot Password');
			
			if(!empty($this->request->data)){
				$this->request->data = Sanitize::clean($this->request->data, array('encode' => false));
				
				
				if(empty($this->request->data['Admin']['email'])){ 
					$this->Session->setFlash('Please enter your email.','default',array('class'=>'alert alert-danger'));
					$this->redirect(array('controller'=>'PasswordSettings','action'=>'forgot_password'));
				}
				
				
				
				if(!empty($this->request->data['Admin']['email'])){ 
					$getStatus = $this->checkEmailValidation($this->request->data['Admin']['email']);
					if($getStatus) { 
						$userArr=$this->Admin->find('first',array('conditions'=>array('Admin.email'=>trim($this->request->data['Admin']['email']),'Admin.status'=>1),'fields'=>array('Admin.id','Admin.first_name','Admin.last_name','Admin.email')));
						
						if(count($userArr) > 0){  
							$passwd = $this->Common->getRandPass();
							$this->Admin->id = $userArr['Admin']['id'];
							$hashCode =  md5(uniqid(rand(), true));
							$this->Admin->saveField('random_key',$hashCode, false);
							
							/********CODE TO SEMD MAIL*******************************/
							$to = $userArr['Admin']['email'];
							$message = "forgot password mail content";
							mail($to, 'Forgot Password', $message);
							
							/********CODE TO SEMD MAIL*******************************/ 
							
							$this->Session->setFlash('Please check your mailbox to access the account.','default',array('class'=>'alert alert-danger'));
							$this->redirect(array('controller'=>'adminLogins','action'=>'forgot_password'));
						} else{ 
							$this->Session->setFlash('Invalid email address.','default',array('class'=>'alert alert-danger'));
							$this->redirect(array('controller'=>'adminLogins','action'=>'forgot_password'));
						}
					} else { 
						$this->Session->setFlash('You have entered wrong email address.','default',array('class'=>'alert alert-danger'));
						$this->redirect(array('controller'=>'adminLogins','action'=>'forgot_password'));
					}
				}
				
			}
			
        }
		/**
		Function Name:   checkEmailValidation
		params:          NULL	
		Description :    for the email validation - Front end
		*/
		public function checkEmailValidation($email){
			$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
			if (eregi($pattern, $email)){
			   return true;
			} else {
			   return false; 
			}
		}
	/*
            * admin_logout function
            * Functionality -  Logout Admin 
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function logout(){		            
            $this->Session->delete('loggedUserInfo');			
            $this->redirect('/');
        }
	

}
?>