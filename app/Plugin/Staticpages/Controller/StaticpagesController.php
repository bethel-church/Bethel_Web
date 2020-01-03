<?php
    /*
        * Staticpages Controller class
        * Functionality -  Manage the Staticpages Management
        * Developer - Navdeepk
        * Created date - 21-Apr-2014
        * Modified date - 
    */
    class StaticpagesController extends StaticpagesAppController {        
        var $name = 'Staticpages';                
		
		public $components = array('Paginator');
		
		
		public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Staticpage.title' => 'asc'
				)
			);
        
        function beforeFilter(){
            parent::beforeFilter();    
            
        }
        
       
		/*
            * admin_index function
            * Functionality -  Staticpages Listing
            * Developer - Navdeepk
            * Created date - 21-Apr-2014
            * Modified date - 
        */
        function index()
        {
			
			/* Active/Inactive/Delete functionality */
			if((isset($this->request->data["Staticpage"]["setStatus"])))
			{
				if(!empty($this->request->data['Staticpage']['status'])){
					$status = $this->request->data['Staticpage']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'index'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='Staticpage';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */
           $value ="";
			$show ="";
			$criteria="is_deleted = 0"; 
			
			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					if($value !="") {
						$criteria .= " AND Staticpage.title LIKE '%".$value."%'";				
					}
			}
			// Used to show count of data in breadcrum
			$getrecCount = $this->Staticpage->find('count',array('conditions'=>array('is_deleted'=>0)));
			$this->set('getrecCount',$getrecCount);
			
			$this->Paginator->settings = array('conditions' => array($criteria),'limit'=>10,'order'=>'id DESC');
			$getData = $this->Paginator->paginate('Staticpage');
            $this->set('getData',$getData);			
            $this->set('keyword', $value);
			if($value == "" && empty($getData)){
				$this->redirect(array('controller'=>'Staticpages','action' => 'addedit'));
			}
			$this->set('show', $show);
			
			$this->set('breadcrumb','Staticpages');			
        }
		
        /*
            * addedit function
            * Functionality -  Add & edit the Staticpages
            * Developer - Navdeepk
            * Created date - 21-Apr-2014
            * Modified date - 
        */
        function addedit($id = null)
        {
			
			if(empty($this->request->data)){				
					$this->request->data = $this->Staticpage->read(null, base64_decode($id));				
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
				#sanitize data (remove tags)
				$desp = $this->request->data['Staticpage']['description'];
				$this->request->data = $this->sanitizeData($this->request->data);
				$this->request->data['Staticpage']['description'] = $desp;
				
				$this->Staticpage->set($this->request->data);	
				if($this->Staticpage->validates()) 				
				{
				
					$this->request->data['Staticpage']['id'] = base64_decode($this->request->data['Staticpage']['id']);					
					$this->request->data['Staticpage']['title'] = trim($this->request->data['Staticpage']['title']);					
					if($this->Staticpage->save($this->request->data))
					{ 	
						$this->Session->setFlash("Page has been saved sucessfully.",'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'index'));
					}
				}    
			}
			$textAction = ($id == null) ? 'Add' : 'Edit';
			$buttonText = ($id == null) ? 'Submit' : 'Update';
				
			$this->set('action',$textAction);			
			$this->set('breadcrumb','staticpages/'.$textAction);
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