<?php
    /*
        * Settings Controller class
        * Functionality -  Manage the Settings Management
        * Developer -Navdeep kaur
        * Created date - 12-Feb-2014
        * Modified date - 
    */
    class SettingsController extends AppController {
        var $name = 'Settings';
        
        public $components = array('Paginator','Image','Common','Email');        
        
        function beforeFilter(){
            parent::beforeFilter();    
            
        }
		/*
            * admin_css function
            * Functionality -  To update css files
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */
		function admin_css(){
		//	$this->loadModel('UiSetting');
			//$files = $this->UiSetting->find('list',array('fields'=>array('id','file_name')));
		//	$this->set('files',$files);
			$this->set('breadcrumb','UI Settings/CSS');
			
			//Import Folder and  File Utilities
			App::uses('Folder', 'Utility');
			App::uses('File', 'Utility');
			// Folder path whose files needed
			$dir = new Folder('css');
			//Get file names using regular expression
			$files = $dir->find('.*\.css');
			//To make keys same as value
			$files = array_combine($files, $files);
			
			$this->set('files',$files);
			//foreach ($files as $file) {
			//	$file = new File($dir->pwd() . DS . $file);
			//	$contents = $file->read();
			//	
			//	// $file->write('I am overwriting the contents of this file');
			//	// $file->append('I am adding to the bottom of this file.');
			//	// $file->delete(); // I am deleting this file
			//	$file->close(); // Be sure to close the file when you're done
			//}
			if(!empty($this->request->data)){
			//	pr($this->request->data); die;
				$file= $this->request->data['UiSetting']['file_name'];
				
				$file = new File($dir->pwd() . DS . $file);
				$contents = $file->writable();
				if($contents == 1){
					chmod($file, 0777); 
					if($file->write($this->request->data['UiSetting']['content'],'w',true)){ 
					$this->Session->setFlash("File updated successfully",'default',array('class'=>'alert alert-success'));
					}
				}else{
					$this->Session->setFlash("File is not writable.",'default',array('class'=>'alert alert-success'));	
				}
				$file->close();
				$this->redirect(array('action' => 'css'));
				//$this->request->data['UiSetting']['file_type'] = 0;
				//if($this->UiSetting->save($this->request->data))
				//{ 	
				//	$this->Session->setFlash("Css has been saved sucessfully.",'default',array('class'=>'alert alert-success'));	
				//	$this->redirect(array('action' => 'css'));
				//}
			}
			
		}
		/*
            * admin_css function
            * Functionality -  To update css files
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */
		function admin_basicSetting(){
			
			$this->loadModel('Company');
			#To get company Id
			$id = $this->Session->read('loggedUserInfo.company_id');
			
			if(isset($this->request->data) && !empty($this->request->data))
			{
			//	pr($this->request->data); die;
				$this->request->data['Company']['id'] = base64_decode($this->request->data['Company']['id']);
						
					if($id){
                        
                        $msz= "Data updated sucessfully.";
                    }else{
                        $msz= "Data saved sucessfully.";
                    }
					/*****************image upload*****************/
						if(!empty($this->request->data['Company']['logo']['name'])){
							
						
							if(isset($this->request->data['Company']['logo']['name']) && $this->request->data['Company']['logo']['name'] != ""){
							// Variable declaration
								$file =$this->request->data['Company']['logo'];
								$path = 'img/logo';					
								$folder_name = 'original';
								$multiple[] = array('folder_name'=>'thumb','height'=>'100','width'=>'100'); 
								
								// Code to upload the image
								$response = $this->Common->upload_image($file, $path, $folder_name, true, $multiple);
								
								// check if filename return or error return
								$is_image_error = $this->Common->is_image_error($response);
								
								if($response == 'exist_error'){
									$this->Session->setFlash($is_image_error,'error');
								}elseif($response == 'size_mb_error'){
									$this->Session->setFlash($is_image_error,'error');
								}elseif($response == 'type_error'){
									$this->Session->setFlash($is_image_error,'error');
								}else{							
								
									$flag = true;
									$filename = $response;
									$this->request->data['Company']['logo'] = $filename;
								
								}
								//DElete Old Pic
								$oldImg = 'logo/thumb/'.$this->request->data['Company']['old_pic'];                
								if(file_exists(WWW_ROOT.'/img/'.$oldImg))
								{				
									unlink(WWW_ROOT.'/img/logo/original/'.$oldImg);
								}
								
							} 
							 
						}else{
							$this->request->data['Company']['logo'] = $this->request->data['Company']['old_pic'];
						}
					
					if($this->Company->save($this->request->data,false))
					{
						
						$this->Session->setFlash($msz,'default',array('class'=>'alert alert-success'));	
						$this->redirect(array('action' => 'basicSetting'));
					}
				  
			}else{
                
                $this->request->data = $this->Company->read(null,$id);
            }
			$this->set('breadcrumb','Site Setting');
		}
		function admin_getlogodata(){
			#To get company Id
			$companyId = $this->Session->read('loggedUserInfo.company_id');
			if($companyId == ""){
				$companyId = 1; //DEfault compnay id OR SUPER ADMin Company LOGO
			}
			$this->loadModel('Company');
			$data = $this->Company->find('first',array('conditions'=>array('Company.id'=>$companyId)));
			if(!empty($data)){
				return $data;
			}else{
				return "No Logo";
			}
		}
		function getContent($selectedFile){
			
			//Import Folder and  File Utilities
			App::uses('Folder', 'Utility');
			App::uses('File', 'Utility');
			
			$dir = new Folder('css');
			$file = new File($dir->pwd() . DS . $selectedFile);
			$contents = $file->read();
			echo $contents; exit;
			
		}
        /*
            * admin_index function
            * Functionality -  Settings Listing
            * Developer -Navdeep kaur
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function admin_index()
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
			$criteria='User.is_deleted =0'; 

			if(!empty($this->params)){ 
					if(!empty($this->params->query['keyword'])){
						$value = trim($this->params->query['keyword']);	
					}
					
					if($value !="") {
						$criteria .= " AND (User.first_name LIKE '%".$value."%' OR User.last_name LIKE '%".$value."%' OR User.email LIKE '%".$value."%')";						
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
                                  'User.email',
                                  'User.gender',
								  'User.status',
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
			$this->set('breadcrumb','Settings');			
        }        
 
        
        
}
?>
