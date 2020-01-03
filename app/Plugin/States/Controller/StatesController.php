<?php
    /*
        * State Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class StatesController extends StatesAppController {
	
        var $name = 'States';        
        var $components = array('Email','Cookie','Paginator');
		   
        function beforeFilter(){
            parent::beforeFilter();    
            
        }        
       
		/*
            * index function
            * Functionality -  State Listing
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function index()
        {            
			
			/* Active/Inactive/Delete functionality */
			if((isset($this->data["State"]["setStatus"])))
			{
				if(!empty($this->request->data['State']['status'])){
					$status = $this->request->data['State']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'index'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='State';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */			
			$value ="";
			$value1= "";
			$show ="";
            $account_type ="";
			
			$criteria="State.is_deleted =0"; 
			
			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					if($value !="") {
						$criteria .= " AND (State.name LIKE '%".$value."%' )";												
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
            $this->Paginator->settings = array('conditions' => array($criteria),
				'limit' => $limit,
				'page'=>$pagination_start,
				'fields' => array('State.id',
                                  'State.name',
								  'State.status'
                                  
								  ),
				'order' => array(
					'State.id' => 'DESC'
				)
            );
			$data = $this->Paginator->paginate('State');
			
			//Code for webservice
			//if($this->request->accepts('application/json')) {
			if(isset($_SERVER["CONTENT_TYPE"]) &&  $_SERVER["CONTENT_TYPE"] == "application/json"){
				$messageId= 200 ;
			    $message = "Success";
				$finalData['result'] = $data;
				$finalData['message'] = $message;
				$finalData['messageId'] = $messageId;	
				$finalData['total_records'] = $this->params['paging']['State']['count'];
				$finalData['total_pages'] = $this->params['paging']['State']['pageCount'];
				echo json_encode($finalData); exit;
			}else{
			//Code for webservice
			// Used to show count of data in breadcrum
			$getrecCount = $this->State->find('count',array('conditions'=>array('is_deleted'=>0)));
			$this->set('getrecCount',$getrecCount);
			
			$this->set('getData',$data);
			$this->set('keyword', $value);
			
			if($value == "" && empty($data)){
				$this->redirect(array('controller'=>'State','action' => 'addedit'));
			}
			$this->set('show', $show);
			
			
			$this->set('breadcrumb','State');
			}
			
        }
		
        /*
            * addedit function
            * Functionality -  Add & edit the admin profile
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function addedit($id = null)
        {			
			$this->loadModel('Country');
			$countryList = $this->Country->find('list',array('conditions'=>array('Country.is_deleted'=>0),'fields'=>array('id','name')));
			if(empty($this->request->data)){
				$this->request->data = $this->State->read(null, base64_decode($id));
				
				
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{    
				
				#sanitize data (remove tags)
				$this->request->data = $this->sanitizeData($this->request->data);
				
				$this->State->set($this->request->data);				
				
				if ($this->State->validates(array('fieldList' => array('name')))) 				
				{ 
										
					if($this->State->save($this->request->data))
					{ 						
						$this->Session->setFlash("State has been updated successfully.",'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'index'));
					}
				}    
			}
			
			$textAction = ($id == null) ? 'Add' : 'Edit';
			$buttonText = ($id == null) ? 'Submit' : 'Update';		
			$this->set('countryList',$countryList);			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','states/'.$textAction);
			$this->set('buttonText',$buttonText);
        }
		
    
	
	
        
		
	
	function delete($id = null)
        {				
			$id = base64_decode($id);
			if($this->State->updateAll(array('State.is_deleted'=>'1'),array('State.id'=>$id))){
				$this->Session->setFlash("State has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
				$this->redirect('index');
			}
		}
			function admin_getAllLinks(){ 
    $links = array();
  
		$this->loadModel('RolePermission');
		$this->loadModel('Module');
		      
		$id = $this->Session->read('loggedUserInfo.admin_role_id');
  
		if($id != ""){
			$data = $this->Module->find('all',array('order'=>'module_name ASC'));
			
			$rolData = $this->RolePermission->find('first',array('conditions'=>array('role_id'=>$id)));
			if(!empty($rolData)){
				$rolePermArr = explode(',',$rolData['RolePermission']['permission_ids']);
			}else{
				$rolePermArr[] ="";
			}
			$i =0 ;
			//$links[] ="";
			foreach($data as $module){
				if(in_array($module['Module']['id'],$rolePermArr)){
					$links[$i]['Controller'] =  $module['Module']['controller'];
					$links[$i]['Action'] =  $module['Module']['action'];
					$links[$i]['Name'] =  $module['Module']['module_name'];
          $links[$i]['Plugin'] = $module['Module']['plugin'];
					$i++;
				}
				
			}
		}
			return $links; 
		/*}else{
			
      
			return $links; 
		} */
		

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