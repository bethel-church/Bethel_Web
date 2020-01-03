<?php
    /*
        * RolesPermissions Controller class
        * Functionality -  Manage the admin login,listing,add 
        * Developer - Navdeep
        * Created date - 11-Feb-2014
        * Modified date - 
    */
    App::uses('Sanitize', 'Utility'); 
    class RolesPermissionsController extends RolesPermissionsAppController {
	
        var $name = 'RolesPermissions';        
        var $components = array('Email','Cookie','Paginator');
		   
        function beforeFilter(){
            parent::beforeFilter();    
            
        }        
        
	
		 /*
            * addrole function
            * Functionality -  Add & edit the admin profile
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */
        function addRole($id = null)
        {
			
			$this->loadModel('AdminRole');
			if(isset($this->request->data) && !empty($this->request->data)) 
			{  
				$this->request->data['AdminRole']['id'] = base64_decode($this->request->data['AdminRole']['id']);
				
				#sanitize data (remove tags)
				$this->request->data = $this->sanitizeData($this->request->data);
				
				$this->AdminRole->set($this->request->data);	
				if($this->AdminRole->validates()) 				
				{ 
					if($id){
                        
                        $msz= "Role updated sucessfully.";
                    }else{
                        $msz= "Role saved sucessfully.";
                    }
					
					if($this->AdminRole->save($this->request->data))
					{						
						$this->Session->setFlash($msz,'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'listRoles'));
					}
				}    
			}else{
                
                $this->request->data = $this->AdminRole->read(null, base64_decode($id));
            }
			$textAction = ($id == null) ? 'Add' : 'Edit';			
					
			$this->set('action',$textAction);			
			$this->set('breadcrumb','Roles And Permissions/'.$textAction);
			$buttonText = ($id == null) ? 'Submit' : 'Update';	
			$this->set('buttonText',$buttonText);
        }
		 function listRoles()
        {
			
        $this->loadModel('AdminRole');
			/* Active/Inactive/Delete functionality */
			if((isset($this->data["AdminRole"]["setStatus"])))
			{
				if(!empty($this->request->data['AdminRole']['status'])){
					$status = $this->request->data['AdminRole']['status'];
				}else
				{
					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
					$this->redirect(array('action' => 'adminRoles'));
					
				}
				$CheckedList = $this->request->data['checkboxes'];
				$model='AdminRole';				
				$controller = $this->params['controller'];				
				$action = $this->params['action'];				
				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
			}
			/* Active/Inactive/Delete functionality */			
			$value ="";
			$value1= "";
			$show ="";
            $account_type ="";
			$criteria="AdminRole.is_deleted = 0 AND AdminRole.id != 1";
			if(!empty($this->request->data['Search'])){ 
                if(isset($this->request->data['Search']['keyword']) && !empty($this->request->data['Search']['keyword'])){		   
					$value = $this->request->data['Search']['keyword'];				  
				}

				
				if($value !="") {
						$criteria .= " AND (AdminRole.role_name LIKE '%".$value."%')";						
				}
                            
              				
				
			}			
            $this->Paginator->settings = array('conditions' => array($criteria),
				'limit' => 10,
					'fields' => array('AdminRole.id',
									  'AdminRole.role_name', 
									  'AdminRole.status',
									  'AdminRole.created'                                 
								  ),
				'order' => array(
					'AdminRole.id' => 'DESC'
				)
            );
			$getData= $this->Paginator->paginate('AdminRole');
			
			// Used to show count of data in breadcrum
			$getrecCount = $this->AdminRole->find('count',array('conditions'=>array('is_deleted'=>0,'AdminRole.id !='=> 1)));
			$this->set('getrecCount',$getrecCount);
			
			$this->set('getData',$getData);
			$this->set('keyword', $value);
			
			if($value == "" && empty($getData)){
				$this->redirect(array('controller'=>'RolesPermissions','action' => 'addRole'));
			}
			$this->set('show', $show);
			
					
			$this->set('breadcrumb','Roles And Permissions');
			
			
        }
	function deleteRole($id = null)
	{
		$this->loadModel('AdminRole');
		if(!empty($id))
		{
			$id = base64_decode($id);
			if($this->AdminRole->updateAll(array('AdminRole.is_deleted'=>'1'),array('AdminRole.id'=>$id))){
				$this->Session->setFlash("Role has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));	
				$this->redirect('listRoles');
			}				
		}
	}
	function viewRole($id = null){
		$this->loadModel('AdminRole');
		if(!empty($id))
		{
			$id = base64_decode($id);
			$getData = $this->AdminRole->find('first',array('conditions'=>array('AdminRole.id'=>$id)));
			$this->set('getData',$getData);
							
		}
	}
	function permissions($id = null){
		
		$this->set('id',$id);
		$this->layout = 'admin';		
		$this->loadModel("Module");
		$this->loadModel("RolePermission");
		if(!empty($this->request->data)){
			$this->request->data['RolePermission']['role_id']= base64_decode($this->request->data['RolePermission']['role_id']);
			$permissionarr= '';
			foreach($this->request->data['RolePermission']['permission_id'] as $key =>$val ){
				if($val != 0){
				$permissionarr[]  = $key;
				}
			}
			
			if(!empty($permissionarr)){
				$this->request->data['RolePermission']['permission_ids'] = implode(',',$permissionarr);
			}else{
				$this->request->data['RolePermission']['permission_ids'] = "";
			}
		 
			$this->RolePermission->save($this->request->data);
			$this->redirect(array("controller" => "RolesPermissions","action" => "listRoles"));
		}
		$role_data = $this->RolePermission->find('first',array('conditions'=>array('RolePermission.role_id'=>base64_decode($id))));
		$this->set('role_data',$role_data);
		$permissions = $this->Module->find('all');
		$this->set('permissions',$permissions);
			
		$this->set('breadcrumb','Roles And Permissions/Assign Permissions');
    }
	//function admin_getAllLinks(){
	//	$this->loadModel('RolePermission');
	//	$this->loadModel('Module');
	//	
	//	$id = $this->Session->read('loggedUserInfo.admin_role_id');
	//	if($id != ""){
	//		$data = $this->Module->find('all',array('order'=>'module_name ASC'));
	//		
	//		$rolData = $this->RolePermission->find('first',array('conditions'=>array('role_id'=>$id)));
	//		if(!empty($rolData)){
	//			$rolePermArr = explode(',',$rolData['RolePermission']['permission_ids']);
	//		}else{
	//			$rolePermArr[] ="";
	//		}
	//		$i =0 ;
	//		$links[] ="";
	//		foreach($data as $module){
	//			if(in_array($module['Module']['id'],$rolePermArr)){
	//				$links[$i]['Controller'] =  $module['Module']['controller'];
	//				$links[$i]['Action'] =  $module['Module']['action'];
	//				$links[$i]['Name'] =  $module['Module']['module_name'];
	//				$i++;
	//			}
	//			
	//		}
	//		
	//		return $links; 
	//	}else{
	//		$links[] ="";
	//		return $links; 
	//	}
	//	
	//
	//}
	
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