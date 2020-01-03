<?php
    /*
        * Users Controller class
        * Functionality -  Manage the Users Management
        * Modified date - 
    */
    class UsersController extends UsersAppController {
        var $name = 'Users';
        
        public $components = array('Paginator','Image','Email');        
        
        function beforeFilter(){
            parent::beforeFilter();    
            
        }   
        /* User Functionality start */
        
        /*
            * index function
            * Functionality -  Users Listing
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function index()
        {
			
            /* Active/Inactive/Delete functionality */
			if((isset($this->data["User"]["setStatus"])))
			{
				if(!empty($this->request->data['User']['status'])){
					$status = $this->request->data['User']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'index'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='User';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */			
			$value ="";
			$value1= "";
			$show ="";
            $account_type ="";
			$criteria="User.is_deleted =0 "; 

			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					
					if($value !="") {
						$criteria .= " AND (User.first_name LIKE '%".$value."%' OR User.middle_name LIKE '%".$value."%' OR User.last_name LIKE '%".$value."%' )";						
					}
					if(!empty($this->params->query['alphabet_letter'])){
						$value1 = trim($this->params->query['alphabet_letter']);	
					}
					if($value1 !="") {
						$criteria .= " AND (User.first_name LIKE '".$value1."%')";						
					}
			}
			
            $this->Paginator->settings = array('conditions' => array($criteria),
				'limit' =>10,
				'fields' => array('User.id',
                                  'User.first_name',
                                  'User.last_name',
                                  'User.middle_name',
                                  
								  'User.type',
								  'User.created',
                                  //'UserProfile.id'
                                  
								  ),
				'order' => array(
					'User.id' => 'DESC'
				)
            );
            
			$alphabetArray = array();
		//	$alphabetArray['0-9'] = '0-9';		
			for($i = 65 ; $i<=90; $i++)
			{
				$alphabetArray[chr($i)] = chr($i);
			}
			
			$this->set('getData',$this->Paginator->paginate('User'));
			
			$this->set('keyword', $value);
			$this->set('alphakeyword', $value1);
			$this->set('show', $show);
			$this->set('alphabetArray',$alphabetArray);
			$this->set('navusers','class = "active"');			
			$this->set('breadcrumb','Users');
      			
        }        
        /*
            * addedit function
            * Functionality -  Add & edit the Users
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function addedit($id = null)
        {
			
			
            if(empty($this->request->data)){				
					$this->request->data = $this->User->read(null, base64_decode($id));
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
			    $this->request->data['User']['id'] = base64_decode($this->request->data['User']['id']);
			    
			    #sanitize data (remove tags)
			    $this->request->data = $this->sanitizeData($this->request->data);
			    
				if($this->request->data['User']['id'] == ""){
					$this->request->data['User']['password'] = md5($this->request->data['User']['password']);	
				}
				
                $this->User->set($this->request->data);	
				if($this->User->validates()) 				
				{ 													
					if($this->User->save($this->request->data))
					{ 	
                        $this->Session->setFlash("User has been added sucessfully.",'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'index'));
					}
				}    
			}
			// Calling arrays
			$this->getCountries();
			$textAction = ($id == null) ? 'Add' : 'Edit';			
			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','users/'.$textAction);
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
			
        }
        /*
            * view function
            * Functionality -  User detail view
            * Developer -Navdeep kaur
            * Created date - 24-Feb-2014
            * Modified date - 
        */
        function view($id = null)
        {
            $getData =  array();
            if(!empty($id))
            {
                $conditions = "User.id = ".base64_decode($id);	
                $getData = $this->User->find('first',array('conditions' => array($conditions)));                
            }
            $this->set(compact('getData'));
        }
        
        /*
            * delete function
            * Functionality -  Add & edit the Users
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */       
	function delete($id = null)
	{
		if(!empty($id))
		{
			$id = base64_decode($id);
			if($this->User->updateAll(array('User.is_deleted'=>'1'),array('User.id'=>$id))){
				$this->Session->setFlash("User has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
				$this->redirect($this->referer());
			}				
		}
	}
# common function to get country realted states	

	public function get_states(){
		$this->layout = '';
        $this->autoRender = false;
		
		$states = array();
		$this->loadModel('State');
		if(!empty($this->request->data)){
			$country_id = $this->request->data['State']['country_id'];
			$states = $this->State->find('all',array('conditions'=>array('State.country_id'=>$country_id,'State.is_deleted'=>0,'State.status'=>1,'State.name <>'=>'null'),'order'=>array('State.name ASC'),'fields'=>array('State.id','name')));
		}
		echo json_encode($states);
        exit();
	}	
        /* User Functionality end */        
        
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
	
	function login() {        
         
		    
        // $this->layout = '';
	    $this->set('title','Sign in');
	    $remember_me= "";
	    $this->loadModel('User');
		//Code for webservice
			//if($this->request->accepts('application/json')) {
		//	print_r($this->request->accepts()); die();
		
			if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
        
				$input = json_decode(file_get_contents('php://input'), true);
				//pr($input); die;
				if(!empty($input)){
		    
				$this->request->data['User'] = $input;
       				
  				if($this->request->data['User']['email'] ==""){
  					$finalData['message'] = "Email is missing";
  					$finalData['messageId'] = 300;
  					echo json_encode($finalData); exit;
  				}
  				if($this->request->data['User']['password'] ==""){
  					$finalData['message'] = "Password is missing";
  					$finalData['messageId'] = 301;
  					echo json_encode($finalData); exit;
  				}
				}else{
					$finalData['message'] = "Invalid JSON";
					$finalData['messageId'] = 201;
					echo json_encode($finalData); exit;
				}
				
			}else{
     
				$userId = $this->Session->read('loggedUserInfo.id');            
				if(!empty($userId)) {
				    $this->redirect(array('controller'=>'users','action'=>'dashboard','plugin'=>'login'));
				}
				
			}
		
	    if(isset($this->request->data) && (!empty($this->request->data)))
      {	
		    $this->User->set($this->request->data);
		      //$this->User->validator()->remove('email', 'rule1');
		
		if($this->User->validates(array('fieldList' => array('email', 'password')))) 
		{	
			 
			$email = $this->request->data['User']['email'];
			$user_password  = md5($this->request->data['User']['password']);					
			$userInfo = $this->User->find('first',array('fields'=>array('id','first_name','last_name','email','password'),'conditions'=>array("User.email" => $email,"User.password" => $user_password,"User.status"=>1,"User.is_deleted"=>0)));
		
			if(count($userInfo) > 0 ) {
				if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
					$messageId= 200 ;
					$message = "Success";
					$finalData['result'] = array("userID"=>$userInfo['User']['id'],"first_name"=>$userInfo['User']['first_name'],"last_name"=>$userInfo['User']['last_name']);
					$finalData['message'] = $message;
					$finalData['messageId'] = $messageId;
					echo json_encode($finalData); exit;
				}
				$this->Session->write('loggedUserInfo', $userInfo['User']);
				$this->Session->write('ADMIN_SESSION', $userInfo['User']['id']);
				
				if(!empty($this->request->data['User']['remember_me'])) {
				$email = $this->Cookie->read('UserEmail');
				$password = base64_decode($this->Cookie->read('UserPass'));						
				if(!empty($email) && !empty($password)) {
					$this->Cookie->delete('UserEmail');
					$this->Cookie->delete('UserPass');     
				} 						
					$cookie_email = $this->request->data['User']['email'];						
					$this->Cookie->write('UserEmail', $cookie_email, false, '+2 weeks');						
					$cookie_pass = $this->request->data['User']['password'];
					$this->Cookie->write('UserPass', base64_encode($cookie_pass), false, '+2 weeks'); 
				}else {
					$email = $this->Cookie->read('UserEmail');
					$password = base64_decode($this->Cookie->read('UserPass'));
					if(!empty($email) && !empty($password)) {
							$this->Cookie->delete('UserEmail');
							$this->Cookie->delete('UserPass');     
					}
				}
				
				$this->redirect(array('controller'=>'users','action'=>'dashboard','plugin'=>'login'));
			}
			else {
				$messageId= 302 ;
				$message = "Email or Password is incorrect";
				$this->Session->setFlash("Email or Password is incorrect",'default',array('class'=>'flashError'));							
				
			    }
		}else{
			$messageId= 303 ;
				$message = "Invalid email or password";
				$this->Session->setFlash("Please enter the valid Email or Password.",'default',array('class'=>'flashError'));	
				
		    }
            }else {
				
				$email = $this->Cookie->read('UserEmail');
				$password = base64_decode($this->Cookie->read('UserPass'));				
				if(!empty($email) && !empty($password)) {
					$remember_me  = true;
					$this->request->data['User']['email']  = $email;
					$this->request->data['User']['password']  = $password;					
				}				
			}
			if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
					
					$finalData['result'] = "";
					$finalData['message'] = $message;
					$finalData['messageId'] = $messageId;
					echo json_encode($finalData); exit;
				}
			$this->set('remember_me',$remember_me);
        }  
}
?>