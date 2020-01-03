<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class AdminsController extends AdminUsersAppController {
	
        var $name = 'Admins';        
        var $components = array('Email','Cookie','Paginator');
		   
        function beforeFilter(){
            parent::beforeFilter();    
            
        }        
       
		/*
            * index function
            * Functionality -  Admins Listing
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function index()
        {            
			
			/* Active/Inactive/Delete functionality */
			if((isset($this->data["Admin"]["setStatus"])))
			{
				if(!empty($this->request->data['Admin']['status'])){
					$status = $this->request->data['Admin']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'index'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='Admin';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */			
			$value ="";
			$value1= "";
			$show ="";
            $account_type ="";
			
			$criteria="Admin.is_deleted =0 AND Admin.id != 1"; 
			
			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					if($value !="") {
						$criteria .= " AND (Admin.first_name LIKE '%".$value."%' OR Admin.last_name LIKE '%".$value."%' OR Admin.email LIKE '%".$value."%')";												
					}
			}
			
			$limit= 10;
			$pagination_start='';
			//Code for webservice
			if($this->request->accepts('application/json')) {
				$input = json_decode(file_get_contents('php://input'), true);
				$page_num=isset($input['page_num'])?$input['page_num']:'';
				$limit=isset($input['limit'])?$input['limit']:'';
				
				if(!empty($page_num))
				{
					$pagination_start=($page_num-1)*$limit;
				}
				
			}
			//Code for webservice (ends here)
			
            $this->Paginator->settings = array('conditions' => array($criteria),
				'limit' => $limit,
				'page'=>$pagination_start,
				'fields' => array('Admin.id',
                                  'Admin.first_name',
                                  'Admin.last_name',
                                  'Admin.email',
								  'Admin.phone',
								  'Admin.status',
								  'Admin.created',
                                  //'AdminProfile.id'
                                  
								  ),
				'order' => array(
					'Admin.id' => 'DESC'
				)
            );
			$data = $this->Paginator->paginate('Admin');
			//Code for webservice
			if($this->request->accepts('application/json')) {
				$messageId= 200 ;
			    $message = "Success";
				$finalData['result'] = $data;
				$finalData['message'] = $message;
				$finalData['messageId'] = $messageId;	
				$finalData['total_records'] = $this->params['paging']['Admin']['count'];
				$finalData['total_pages'] = $this->params['paging']['Admin']['pageCount'];
				echo json_encode($finalData); exit;
			}else{
			//Code for webservice
			// Used to show count of data in breadcrum
			$getrecCount = $this->Admin->find('count',array('conditions'=>array($criteria)));
			$this->set('getrecCount',$getrecCount);			
			
			$this->set('getData',$data);
			$this->set('keyword', $value);
			
			if($value == "" && empty($data)){
				$this->redirect(array('controller'=>'Admins','action' => 'addedit'));
			}
			$this->set('show', $show);
			
			
			$this->set('breadcrumb','Admins');
			
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
			
			$this->loadModel('AdminRole');
			
			if($id =="me"){
				$id = base64_encode($this->Session->read('loggedUserInfo.id'));
			}
			if(empty($this->request->data)){
				$this->request->data = $this->Admin->read(null, base64_decode($id));
				
				
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{
			    #sanitize data (remove tags)
			    $this->request->data = $this->sanitizeData($this->request->data);
			    
			    $this->Admin->set($this->request->data);
			    
			    if(!empty($this->request->data['Admin']['admin_role_id'])){
				$admin_role_id = $this->request->data['Admin']['admin_role_id'];
				$chkRole = $this->AdminRole->find('count',array('conditions'=>array('AdminRole.id'=>$admin_role_id,'is_deleted'=>0,'status'=>1)));
				if($chkRole == 0){
				    $this->Session->setFlash("Something went wrong. Please try again later.",'default',array('class'=>'alert alert-danger'));
				}else{				
			    
				    if ($this->Admin->validates(array('fieldList' => array('first_name','email')))) 				
				    { 
					if($id == ""){
						$this->request->data['Admin']['password'] = md5($this->request->data['Admin']['password']);	
					}
					
					if($this->Admin->save($this->request->data))
					{ 				
						$this->Session->setFlash("The Profile has been updated successfully.",'default',array('class'=>'alert alert-success'));
						if($id =="1"){ 
							$this->redirect(array('action' => 'addedit','me'));
						}
						$this->redirect(array('action' => 'index'));
					}
				    }
				}
			    }
			}
			$roleData = $this->AdminRole->find('list',array('conditions'=>array('is_deleted'=>0,'status'=>1),'fields'=>array('id','role_name')));
			$this->set('roles',$roleData);
			$textAction = ($id == null) ? 'Add' : 'Edit';
			$buttonText = ($id == null) ? 'Submit' : 'Update';		
					
			$this->set('action',$textAction);			
			$this->set('breadcrumb','admins/'.$textAction);
			$this->set('buttonText',$buttonText);
        }	
	
	function delete($id = null)
        {				
			$id = base64_decode($id);
			if($this->Admin->updateAll(array('Admin.is_deleted'=>'1'),array('Admin.id'=>$id))){
				$this->Session->setFlash("Admin has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
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