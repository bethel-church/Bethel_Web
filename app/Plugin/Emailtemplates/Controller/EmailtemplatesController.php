<?php
    /*
        * Email Template Controller class
        * Functionality -  Manage the Email templates Management
        * Developer - Gurpreet Singh Ahhluwalia
        * Created date - 12-Feb-2014
        * Modified date - 
    */
    class EmailtemplatesController extends EmailtemplatesAppController {        
        var $name = 'Emailtemplates';                
		public $components = array('Paginator');
		public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Emailtemplate.id' => 'Asc'
				)
			);
        function beforeFilter(){
            parent::beforeFilter();    
            
        }     
	/*
            * index function
            * Functionality -  Emailtemplates Listing
            * Developer - Gurpreet Singh Ahhluwalia
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function index()
        {           
           
	    
	    $value ="";
	    $show ="";
	    $criteria=  "1"; 
	    
	    if(!empty($this->params)){ 
		if(!empty($this->params->query['keyword'])){
		    $value = trim($this->params->query['keyword']);	
		}
		if($value !="") {
		    $criteria .= " AND Emailtemplate.name LIKE '%".$value."%'";						
		}
	    }
	    $this->Paginator->settings = array('conditions' => array($criteria),'limit'=>10,'order'=>'id DESC');
		$data = $this->Paginator->paginate('Emailtemplate');
		
		// Used to show count of data in breadcrum
		$getrecCount = $this->Emailtemplate->find('count');
		$this->set('getrecCount',$getrecCount);
		
	    $this->set('getData',$data);
	    $this->set('keyword', $value);
		if($value == "" && empty($data)){
				$this->redirect(array('controller'=>'Emailtemplates','action' => 'addedit'));
		}
	    $this->set('navemailtemplate','class = "active"');			
	    $this->set('breadcrumb','emailtemplates');
			
        }
		
	
		
        /*
            * addedit function
            * Functionality -  Add & edit the Emailtemplates
            * Developer - Gurpreet Singh Ahhluwalia
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function addedit($id = null)
        {
			
			
			if(empty($this->request->data)){
				$this->request->data = $this->Emailtemplate->read(null, base64_decode($id));			
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
				$this->request->data['Emailtemplate']['id'] = base64_decode($this->request->data['Emailtemplate']['id']);
				
				#sanitize data (remove tags)
				$temp = $this->request->data['Emailtemplate']['template'];
				$this->request->data = $this->sanitizeData($this->request->data);
				$this->request->data['Emailtemplate']['template'] = $temp;				
				
				$this->Emailtemplate->set($this->request->data);	
				if($this->Emailtemplate->validates()) 				
				{
					$templateContent =  strip_tags(trim($this->request->data['Emailtemplate']['template']));
					$templateContent = str_replace('&nbsp;', '', $templateContent);
					
					
					if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$templateContent))) == 0) {
						$templateContent = "";
					}
					
					if($templateContent != ""){	
						$this->request->data['Emailtemplate']['name'] = trim($this->request->data['Emailtemplate']['name']);					
						if($this->Emailtemplate->save($this->request->data,false))
						{ 	
							$this->Session->setFlash("Emailtemplate has been saved sucessfully.",'default',array('class'=>'alert alert-success'));	
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->set("editorError",1);
					}
				}    
			}
			$textAction = ($id == null) ? 'Add' : 'Update';			
			$this->set('navemailtemplate','class = "active"');			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','emailtemplates/'.$textAction);
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
			
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