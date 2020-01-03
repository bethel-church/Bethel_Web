<?php
    /*
        * newsletters Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility');
	App::uses('CakeEmail', 'Network/Email');
    class NewslettersController extends NewslettersAppController {
	
        var $name = 'Newsletters';        
        var $components = array('Email','Cookie','Paginator');
		   
        function beforeFilter(){
            parent::beforeFilter();    
            
        }        

	
	public function index() {
				
		$this->loadModel('Newsletter');
		$value ="";
		$show ="";
		$criteria="is_deleted = 0"; 
		
		
		if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					if($value !="") {
						$criteria .= " AND Newsletter.title LIKE '%".$value."%'";							
					}
			}

		$this->Paginator->settings = array('conditions' => array($criteria),'order'=>'id desc','limit'=>10); 
		$data = $this->Paginator->paginate('Newsletter');
		
		
		// Used to show count of data in breadcrum
		$getrecCount = $this->Newsletter->find('count',array('conditions'=>array('is_deleted'=>0)));
		$this->set('getrecCount',$getrecCount);
		
		$this->set('keyword', $value);
		$this->set('getData',$data);
		if($value == "" && empty($data)){
				$this->redirect(array('controller'=>'Newsletters','action' => 'add_newsletter'));
		}	
		$this->set('breadcrumb','Newsletters');		
		
	}
	function add_newsletter($id = null)
        {
			
			
			$this->loadModel('Newsletter');
			$this->loadModel('NewsletterTemplate');
			if(empty($this->request->data)){
				$this->request->data = $this->Newsletter->read(null, base64_decode($id));			
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
				
				#sanitize data (remove tags)
				$temp = $this->request->data['Newsletter']['description'];
				$this->request->data = $this->sanitizeData($this->request->data);
				$this->request->data['Newsletter']['description'] = $temp;
				
				$this->Newsletter->set($this->request->data);	
				if($this->Newsletter->validates()) 				
				{ 
					$templateContent =  strip_tags(trim($this->request->data['Newsletter']['description']));
					$templateContent = str_replace('&nbsp;', '', $templateContent);
					
					
					if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$templateContent))) == 0) {
						$templateContent = "";
					}
					
					if($templateContent != ""){
					$flag = 0;
					if(isset($this->request->data['save_send_button']) && $this->request->data['save_send_button'] == "save_send"){
						$flag = 1;

					}
					if($this->request->data['Newsletter']['send_type'] == 1){
						$bDate = explode('/',$this->data['Newsletter']['schedule_date']);
                        
						$schDate = $bDate[2].'-'.$bDate[0].'-'.$bDate[1];
						$this->request->data['Newsletter']['schedule_date'] = $schDate;
					}
					$this->request->data['Newsletter']['id'] = base64_decode($this->request->data['Newsletter']['id']);					
					$this->request->data['Newsletter']['title'] = trim($this->request->data['Newsletter']['title']);
										
					if($this->Newsletter->save($this->request->data))
					{ $newsltterId = $this->Newsletter->id;
						if($flag ==1){
							$this->Session->setFlash("Newsletter has been saved sucessfully,select users to whom you wish to send newsletter.",'default',array('class'=>'alert alert-success'));	
							
							$this->redirect(array('controller'=>'newsletters','action' => 'send_newsletter',base64_encode($newsltterId)));
						}
						$this->Session->setFlash("Newsletter has been saved sucessfully.",'default',array('class'=>'alert alert-success'));
						$this->redirect(array('controller'=>'newsletters','action' => 'index'));
						
					}
				}else{
						$this->set("editorError",1);
					}
				}    
			}
			$template = $this->NewsletterTemplate->find('list',array('conditions'=>array('is_deleted'=>0),'fields'=>array('id','title')));
			$this->set('template',$template);
			$textAction = ($id == null) ? 'Add' : 'Update';			
		
			$this->set('action',$textAction);			
			$this->set('breadcrumb','newsletters/'.$textAction);
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
			
        }
		
	/*****Newsletters Template Functions***********************************/
	public function newsletterTemplate() {
		
	$this->loadModel('NewsletterTemplate');
		$value ="";
		$show ="";
		$criteria="is_deleted = 0 "; 
		if(!empty($this->request->data['Search'])){
			if(isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword'])){		   
				$value = $this->request->data['Search']['keyword'];				  
			}	
			if($value !="") {
					$criteria .= " AND NewsletterTemplate.title LIKE '%".$value."%'";						
			}				
			
		}
		$this->Paginator->settings = array('conditions' => array($criteria));
		$this->set('getData',$this->Paginator->paginate('NewsletterTemplate'));
		$this->set('keyword', $value);			
		$this->set('navNewsletterTemplate','class = "active"');			
		$this->set('breadcrumb','NewsletterTemplate');		
		
	}
	function add_template($id = null)
        {
			
			$this->loadModel('NewsletterTemplate');
			if(empty($this->request->data)){
				$this->request->data = $this->NewsletterTemplate->read(null, base64_decode($id));			
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
				$this->request->data['NewsletterTemplate']['id'] = base64_decode($this->request->data['NewsletterTemplate']['id']);
				
				#sanitize data (remove tags)
				$temp = $this->request->data['NewsletterTemplate']['template'];
				$this->request->data = $this->sanitizeData($this->request->data);
				$this->request->data['NewsletterTemplate']['template'] = $temp;
				
				$this->NewsletterTemplate->set($this->request->data);	
				if($this->NewsletterTemplate->validates()) 				
				{
					$templateContent =  strip_tags(trim($this->request->data['NewsletterTemplate']['template']));
					$templateContent = str_replace('&nbsp;', '', $templateContent);
					
					
					if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$templateContent))) == 0) {
						$templateContent = "";
					}
					
					if($templateContent != ""){
			
					$this->request->data['NewsletterTemplate']['title'] = trim($this->request->data['NewsletterTemplate']['title']);					
					if($this->NewsletterTemplate->save($this->request->data))
					{ 	
						$this->Session->setFlash("NewsletterTemplate has been saved sucessfully.",'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'newsletterTemplate'));
					}
					}else{
						$this->set("editorError",1);
					}
				}    
			}
			$textAction = ($id == null) ? 'Add' : 'Update';			
		
			$this->set('action',$textAction);
		//	$this->set('breadcrumb','newsletters/Newsletter Templates/'.$textAction);
			$this->set('breadcrumb','newsletters/'.$textAction." Template");
			
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
			
        }
	function send_newsletter($newsletterId=null){
		$newsletterId=  base64_decode($newsletterId);
		$this->loadModel('Newsletter');
		$this->loadModel('User');
	
		
		$userData = $this->User->find('all',array('conditions'=>array('User.status'=>1,'User.is_deleted'=>0),'fields'=>array('User.id','User.first_name','User.last_name','User.email')));
		$this->set('userData',$userData);
		$this->set('newsletterId',$newsletterId);
		if(!empty($this->request->data)){ 
		$data = $this->Newsletter->find('first',array('conditions'=>array('Newsletter.id'=>$this->request->data['Newsletter']['id'])));
		if($this->request->data['Newsletter']['send_to'] == 0){
			$userData = $this->User->find('list',array('conditions'=>array('User.status'=>1,'User.is_deleted'=>0),'fields'=>array('User.id','User.email')));
		}
		$userData = $this->request->data['Newsletter']['user_id'];
			$this->CakeEmail = new CakeEmail('smtp');
			foreach($userData as $uData){
				$to = $uData;
				$data1 = $data['Newsletter']['description'];					
					
				$subject = ucfirst(str_replace('_', ' ',$data['Newsletter']['title']));
				$this->CakeEmail->from(array("info@test.com"));
				$this->CakeEmail->to($to);
				$this->CakeEmail->subject($subject);
				$this->CakeEmail->emailFormat('both');
				$this->CakeEmail->send($data1);
				
			}
			
			$this->Newsletter->id = $this->request->data['Newsletter']['id'];
			$this->Newsletter->saveField('is_sent', 1);
			$this->Session->setFlash("Newsletter sent sucessfully.",'default',array('class'=>'alert alert-success'));	
			$this->redirect(array('Newsletters','action'=>'index','plugin'=>'newsletters'));
		}
		$this->set('breadcrumb','newsletters/Send Newsletter');
	}

	
	// To get newsletter template
	function getNewletterTemplate($templateId=null){
		if($templateId !=""){
		$this->loadModel('NewsletterTemplate');
		$data = $this->NewsletterTemplate->find('first',array('conditions'=>array('NewsletterTemplate.id'=>$templateId)));
		if(!empty($data)){
			$content = $data['NewsletterTemplate']['template'];
		}else{
			$content = "";
		}
		}else{
			$content = "";
		}
		echo  $content; exit;
	}
	/*
		* delete function
		* Functionality - delete newsletter
		* Developer - Navdeep kaur
		* Created date - 16-Apr-2014
		* Modified date - 
	*/       
	function deleteNewsletter($id = null)
	{
		$this->loadModel('Newsletter');		
		$id = base64_decode($id);
		if($this->Newsletter->updateAll(array('Newsletter.is_deleted'=>'1'),array('Newsletter.id'=>$id))){
			$this->Session->setFlash("Newsletter has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
			$this->redirect(array('Newsletters','action'=>'index','plugin'=>'newsletters'));
		}
	}
	/*
		* delete function
		* Functionality - delete newsletter
		* Developer - Navdeep kaur
		* Created date - 16-Apr-2014
		* Modified date - 
	*/       
	function deleteNlTemplate($id = null)
	{
		$this->loadModel('NewsletterTemplate');		
		$id = base64_decode($id);
		if($this->NewsletterTemplate->updateAll(array('NewsletterTemplate.is_deleted'=>'1'),array('NewsletterTemplate.id'=>$id))){
			$this->Session->setFlash("Newsletter Template has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
			
			$this->redirect(array('Newsletters','action'=>'newsletterTemplate','plugin'=>'newsletters'));
		}
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
?>