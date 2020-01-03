<?php
    /*
        * Faqs Controller class
        * Functionality -  Manage the Faqs Management
        * Developer - Navdeep kaur
        * Created date - 21-Apr-2014
        * Modified date - 
    */
    class FaqsController extends FaqsAppController {        
        var $name = 'Faqs';                
		
		public $components = array('Paginator');	
        
        function beforeFilter(){
            parent::beforeFilter();    
            
        }               
        
		/*
            * index function
            * Functionality -  Faqs Listing
            * Developer - Navdeep kaur
            * Created date - 21-Apr-2014
            * Modified date - 
        */
        function index()
        {
						
            /* Active/Inactive/Delete functionality */
			if((isset($this->data["Faq"]["setStatus"])))
			{
				if(!empty($this->request->data['Faq']['status'])){
					$status = $this->request->data['Faq']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'index'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='Faq';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */			
			$value ="";
			$show ="";
			$criteria="is_deleted =0"; 
			
			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					if($value !="") {
						$criteria .= " AND Faq.title LIKE '%".$value."%'";								
					}
			}
			$limit= 10;
			$pagination_start='';
			//Code for webservice
			//if($this->request->accepts('application/json')) {
			if(isset($_SERVER["CONTENT_TYPE"]) &&  $_SERVER["CONTENT_TYPE"] == "application/json"){
				$input = json_decode(file_get_contents('php://input'), true);
				$page_num=isset($input['page_num'])?$input['page_num']:'';
				$limit=isset($input['limit'])?$input['limit']:'';
				
				if(!empty($page_num))
				{
					$pagination_start=($page_num-1)*$limit;
				}
				
			}
			//Code for webservice (ends here)
			$this->Paginator->settings = array('conditions' => array($criteria),'limit' => $limit,
				'page'=>$pagination_start,'order'=>'id DESC');
			$data= $this->Paginator->paginate('Faq');
			//Code for webservice
			//if($this->request->accepts('application/json')) {
			if(isset($_SERVER["CONTENT_TYPE"]) &&  $_SERVER["CONTENT_TYPE"] == "application/json"){
				$messageId= 200 ;
			    $message = "Success";
				$finalData['result'] = $data;
				$finalData['message'] = $message;
				$finalData['messageId'] = $messageId;	
				$finalData['total_records'] = $this->params['paging']['Faq']['count'];
				$finalData['total_pages'] = $this->params['paging']['Faq']['pageCount'];
				echo json_encode($finalData); exit;
			}else{
			//Code for webservice
			// Used to show count of data in breadcrum
			$getrecCount = $this->Faq->find('count',array('conditions'=>array('is_deleted'=>0)));
			$this->set('getrecCount',$getrecCount);
		
            $this->set('getData',$data);
			if($value == "" && empty($data)){
				$this->redirect(array('controller'=>'Faqs','action' => 'addedit'));
			}
            $this->set('keyword', $value);
			$this->set('show', $show);
			$this->set('navfaqs','class = "active"');			
			$this->set('breadcrumb','Faqs');
			}
        }
		
        /*
            * addedit function
            * Functionality -  Add & edit the Faqs
            * Developer - Navdeep kaur
            * Created date - 21-Apr-2014
            * Modified date - 
        */
        function addedit($id = null)
        {
			
			
			if(empty($this->request->data)){				
					$this->request->data = $this->Faq->read(null, base64_decode($id));				
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
			
				$this->request->data['Faq']['id'] = base64_decode($this->request->data['Faq']['id']);
				
				#sanitize data (remove tags)
				$temp = $this->request->data['Faq']['description'];
				$this->request->data = $this->sanitizeData($this->request->data);
				$this->request->data['Faq']['description'] = $temp;
				
				$this->Faq->set($this->request->data);	
				if($this->Faq->validates()) 				
				{ 
					$templateContent =  strip_tags(trim($this->request->data['Faq']['description']));
					$templateContent = str_replace('&nbsp;', '', $templateContent);
					
					
					if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$templateContent))) == 0) {
						$templateContent = "";
					}
					
					if($templateContent != ""){	 			
						$this->request->data['Faq']['title'] = trim($this->request->data['Faq']['title']);					
						if($this->Faq->save($this->request->data))
						{ 	
							$this->Session->setFlash("Faq has been saved sucessfully.",'default',array('class'=>'alert alert-success'));	
							$this->redirect(array('action' => 'index'));
						}
					}else{
						$this->set("editorError",1);
					}
					
				}    
			}
			$textAction = ($id == null) ? 'Add' : 'Edit';			
			$this->set('navfaqs','class = "active"');			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','faqs/'.$textAction);
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
			
        }
		/*
            * delete function
            * Functionality -  Add & edit the Faqs
            * Developer - Navdeep kaur
            * Created date - 21-Apr-2014
            * Modified date - 
        */       
		function delete($id = null)
        {
			$id = base64_decode($id);
			$this->Faq->updateAll(array('Faq.is_deleted'=>'1'),array('Faq.id'=>$id));
			$this->Session->setFlash("Faq has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
			$this->redirect(array('action' => 'index'));
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
