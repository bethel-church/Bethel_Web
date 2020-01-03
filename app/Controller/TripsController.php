<?php
    /*
        * Users Controller class
        * Functionality -  Manage the Users Management
        * Modified date - 
    */
    class TripsController extends AppController {
        var $name = 'Trips';
        var $uses = array('Trip','User','Transaction');
        public $components = array('Paginator','Image','Email', 'Aws');
        
        function beforeFilter(){
            //parent::beforeFilter();   
            $this->layout='admin';
            
            $l_id = $this->Session->read('trip_leader_info.id');
            if($l_id !=""){
              $this->set("l_id",$l_id);
            }
            $admin_id = $this->Session->read('loggedUserInfo.id');
            if($l_id == "" && $admin_id ==""){
              $this->redirect("/");
            }
            //for csv files uploaded through OSX or Leopard
            ini_set("auto_detect_line_endings", "1");
        }  
        function check_leader_login(){
          $l_id = $this->Session->read('trip_leader_info.id');
          if($l_id !=""){
            //leaders should be redirected to view trip detail page.
            $this->redirect("/leaders/login");
          }  
        }
        function set_trip_params(){
          if($this->params['named']['y'] != ""){
            $this->Session->write("year",$this->params['named']['y']);
          }else{
            $y = $this->Session->read("year");
            if($y == ""){
              //set current year for the trips listing page filter
              $this->Session->write("year",Date('Y'));
            }
          }
          if($this->params['named']['a'] != ""){
            $this->Session->write("a",$this->params['named']['a']);
          }else{
            $y = $this->Session->read("a");
            if($y == ""){
              //set current ACTIVE TRIPS for the trips listing page filter '0' means active trip
              $this->Session->write("a","0");
            }
          }
		  if($this->params['named']['b'] != ""){
            $this->Session->write("b",$this->params['named']['b']);
          }else{
            $y = $this->Session->read("b");
            if($y == ""){
              //set current ACTIVE TRIPS for the trips listing page filter '0' means active trip
              $this->Session->write("b","0");
            }
          }	

        }
        
        function find_trip_years(){
          $group = "EXTRACT(YEAR from `Trip`.`created`)";
          $field = "EXTRACT(YEAR from `Trip`.`created`) year";
          $this->Trip->unbindModel(
           array('hasMany' => array('User'))
          );
          //$this->Trip->virtualFields['Trip.year'] = "EXTRACT(YEAR from `Trip`.`created`) ";
          $trip_years= $this->Trip->find('all',array('fields'=>array($field),'group' => array($group)));
          //configure::write("debug","2");
         // print "<pre>";
         // pr($trip_years);
          $years = array();
          $c_year = Date("Y");
          $flag = false;
          $i=1;
          if(is_array($trip_years) && count($trip_years[0])>0){
            foreach ($trip_years as $key=>$value){
              $years[] = $value[0]['year'];
             //pr($value);
              if($c_year == $value[0]['year']){
                $flag = true;
              }
            }
          }
          if($flag == false){
            $years[] = $c_year;
          }
          //pr($years);
          return $years;
        } 
        
        function archived_trips($year){
          $b = $this->Session->read("b");
		  if($b == "1"){
		    $trip_organization = "2nd Year Travel";
		  }else{
		  	$trip_organization = "Mission Trips";
		  }
          //$query = "select count(archived) as total, archived as type from trips where trip_organization='$trip_organization' AND EXTRACT(YEAR from created)=$year group by archived";
          $query = "select count(archived) as total, archived as type from trips where trip_organization='$trip_organization' AND year=$year group by archived";
          $result = $this->Trip->query($query);
          if(is_array($result)){
            if($result[0]['trips']['type'] == "0"){
              //active trip
              $trips['active'] = $result[0][0]['total'];
              if($result[1][0]['total'] != ""){
                $trips['archived'] = $result[1][0]['total'];
              }else{
                $trips['archived'] = "0";
              }
            }elseif($result[0]['trips']['type'] == "1"){
              $trips['archived'] = $result[0][0]['total'];
              if($result[1][0]['total'] != ""){
                $trips['active'] = $result[1][0]['total'];
              }else{
                $trips['active'] = "0";
              }
            }else{
              $trips['archived'] = "0";
              $trips['active'] = "0";
            }
            
          }else{
            $trips['archived'] = "0";
            $trips['active'] = "0";
          }
          return $trips;
          //print_r($results);
        } 
        /* Trip Functionality start */
        
        /*
            * index function
            * Functionality -  Trips Listing
            * Developer -
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function index()
        {
          $this->check_leader_login();
          $this->set_trip_params();
          //configure::write("debug","2");
  			
          /* Active/Inactive/Delete functionality */
    			if((isset($this->data["Trip"]["setStatus"])))
    			{
    				if(!empty($this->request->data['Trip']['status'])){
    					$status = $this->request->data['Trip']['status'];
    				}else
    				{
  					$this->Session->setFlash("Please select the action.",'default',array('class'=>'alert alert-danger'));	
  					$this->redirect(array('action' => 'index'));
  					
  				}
  				$CheckedList = $this->request->data['checkboxes'];
  				$model='Trip';
  				$controller = $this->params['controller'];				
  				$action = $this->params['action'];				
  				$this->setStatus($status,$CheckedList,$model,$controller,$action); 			 
  			}
  			/* Active/Inactive/Delete functionality */			
  			$value ="";
  			$value1= "";
  			$show ="";
        $account_type ="";
  		//	$criteria="Trip.is_deleted =0 ";
        $criteria="  1=1 ";
        //print "<br /> \t\t\t\b\b                       ";
        //pr($this->params);
        //exit();
        $trip_years = $this->find_trip_years();
        //pr($trip_years);
        $y = $this->Session->read("year");
        $a = $this->Session->read("a");
		    $b = $this->Session->read("b");
		    if($b == "1"){
			     $trip_category = "2nd Year Travel";
		    }else{
			     $trip_category = "Mission Trips";
		    }

        
        //$criteria .=" AND EXTRACT(YEAR FROM Trip.created) ='$y'";
        $criteria .=" AND Trip.year ='$y'";
        $criteria .=" AND (Trip.archived ='$a' AND (Trip.trip_organization='$trip_category')) ";
  			if(!empty($this->params)){ 
  					if(!empty($this->params->query['keyword'])){
  						$value = trim($this->params->query['keyword']);	
  					}
  					
  					if($value !="") {
  						$criteria .= " AND (Trip.name LIKE '%".$value."%' )";
  					}
  					if(!empty($this->params->query['alphabet_letter'])){
  						$value1 = trim($this->params->query['alphabet_letter']);	
  					}
  					if($value1 !="") {
  						$criteria .= " AND (Trip.name LIKE '".$value1."%') ";
  					}
  			}
  			//echo $criteria;exit();
  			$this->Trip->unbindModel(
          array('hasMany' => array('User'))
      );
  			$join = array(
                    array('table' => 'users',
                        'alias' => 'User',
                        'type' => 'LEFT',
                        
                        'conditions' => array(
                            ' Trip.main_leader = User.id' ,
                        )
                    )
                );
  			
          $this->Paginator->settings = array('Trip'=> array('conditions' => array($criteria),
  				'limit' =>100,
  				'fields' => array('Trip.id',
                                    'Trip.name',
                                    'Trip.user_passcode',
                                    'Trip.leader_passcode',
                                    'Trip.created',
                                    'Trip.archived',
                                    'Trip.edit_blocked',
                                    'Trip.main_leader',
                                    'User.first_name',
                                    'User.middle_name',
                                    'User.last_name'
                                    //'TripProfile.id'
                                    
  								  ),
  				'joins'	=> $join,
  				'order' => array(
  					'Trip.name' => 'ASC'
  				)
           ) 
              );
              
  		
  			$this->set('getData',$this->Paginator->paginate('Trip'));
  			$t = $this->archived_trips($y);
  			$this->set('active_trips', $t);
  			$this->set('keyword', $value);
  			$this->set('alphakeyword', $value1);
  			$this->set('show', $show);
  			$this->set('alphabetArray',$alphabetArray);
  			$this->set('navTrips','class = "active"');
  			$this->set('breadcrumb','Trips');
  			$this->set('trip_years',$trip_years);
  			$this->set('selected_year',$y);
  			$this->set('selected_trip_type',$a);
  			
  			//pr($trip_years);
  			$this->Session->write("csv_data","");
                        $this->Session->write("leaders","");
                        $this->Session->write("csv_file","");
			$this->set("a",$this->Session->read("a"));
			$this->set("b",$this->Session->read("b"));
      		//	pr($this->Paginator->paginate('Trip'));exit();
     }        
        /*
            * addedit function
            * Functionality -  Add & edit the Trips
            * Developer -
            * Created date - 12-Feb-2014
            * Modified date - 
        */
        function addedit($id = null)
        {
			      $this->check_leader_login();
			
            if(empty($this->request->data)){				
					     $this->request->data = $this->Trip->read(null, base64_decode($id));
					     //print_r($this->request->data);
      			}elseif(isset($this->request->data) && !empty($this->request->data))
      			{
      			   //pr($this->request->data);exit();
      			    $this->request->data['Trip']['id'] = base64_decode($this->request->data['Trip']['id']);
      			    
      			    #sanitize data (remove tags)
      			    $this->request->data = $this->sanitizeData($this->request->data);
      			    
      				
                            $this->Trip->set($this->request->data);
                            //pr($_FILES);
                
        				if($this->Trip->validates())
        				{ 		
              				  //echo $this->request->data['Trip']['user_passcode'];
                                            $csv_data = $this->Session->read("csv_data"); 
        				  
                							
        					if($this->Trip->save($this->request->data))
        					{ 	
        					  if($csv_data != ""){
                                                    $this->Trip->saveMembers($this->Trip->id, $csv_data);
                                                    $tt = $this->Trip->id;
                                                    $nameInfo = explode("__",$this->request->data['Trip']['main_leader']);
                                                    $first_name = $nameInfo[0];
                                                    $middle_name = $nameInfo[1];
                                                    $last_name = $nameInfo[2];
                                                     //pr($nameInfo);
                                                     //pr($this->request->data);
                                                    $UserInfo = $this->User->find('first',array('fields'=>array('id','first_name','last_name'),'conditions'=>array("User.first_name" => $first_name,"User.middle_name" => $middle_name,"User.last_name"=>$last_name,"User.trip_id"=>$tt)));
                                                               // pr($UserInfo);exit();
                                                    $main_trip_leader = $UserInfo['User']['id'];

                                                    $tripInfoData['Trip']['id'] = $this->Trip->id;
                                                    $tripInfoData['Trip']['main_leader'] = $main_trip_leader;
                                                    $csv_f = $this->Session->read("csv_file");
                                                    $tripInfoData['Trip']['csv_file'] = $csv_f['Trip']['csv']['name'];
                                                    //pr($tripInfoData);
                                                    //exit();
                                                    $this->Trip->save($tripInfoData);
                      
                     }
                    
                      if($this->request->data['Trip']['id'] != ""){
                        $msg = "Trip updated sucessfully.";
                      }else{
                        $msg = "Trip added sucessfully.";
                      }
                      $this->Session->setFlash($msg,'default',array('class'=>'alert alert-success'));
                      $this->Session->write("csv_data","");
                      $this->Session->write("leaders","");
                      $this->Session->write("csv_file","");
                      
                      $this->redirect(array('controller' => 'trips', 'action' => 'index'));
                    
        					}else{
        					  
                  }
      				}else{
                //exit();
                  $csv_data = $this->Session->read("csv_data");
                  if($csv_data != ""){
                    //pr($csv_data);
                    //pr($this->Session->read("csv_file"));
                    $fData = $this->Session->read("csv_file");
                    $this->request->data['Trip']['csv_file'] = $fData['Trip']['csv']['name'];
                    $this->set("main_leader",$this->Session->read("leaders"));
                    $this->set("trip_members",$this->Session->read("leaders"));
                  }
                  $dd = $this->Trip->validationErrors;
                  $this->set("errors",$dd);
              }    
			     }
      			// Calling arrays
      			if($this->Trip->id != null){
              //$this->Trip->id = base64_decode($id);
              $id = base64_encode($this->request->data['Trip']['id']);
              
              $this->request->data['Trip']['id'] = $this->Trip->id;
              $conditions = "trip_id = '".$this->Trip->id."' AND type='1'";
              $this->User->virtualFields = array(
              'name' => 'CONCAT(User.first_name, " ",User.middle_name, " ", User.last_name)'
              );
              $trip_members = $this->User->find('list',array('fields'=>array('id','name'),'conditions' => array($conditions)));
              $this->set('trip_members', $trip_members);
            }
            //echo $id."::Trip ID".$this->Trip->id;
            
            if($csv_data != ""){
                    //pr($csv_data);
                    //pr($this->Session->read("csv_file"));
                    $fData = $this->Session->read("csv_file");
                    $this->request->data['Trip']['csv_file'] = $fData['Trip']['csv']['name'];
                    $this->set("main_leader",$this->Session->read("leaders"));
                    $this->set("trip_members",$this->Session->read("leaders"));
            }
            
            //pr($trip_members);
      			$textAction = ($id == null) ? 'New' : 'Edit';			
      			$this->set('heading', $textAction);
            $this->set('action',$textAction);			
      			$this->set('breadcrumb','Trips/'.$textAction);
      			$buttonText = ($id == null) ? 'CREATE TRIP' : 'UPDATE TRIP';	
      			$this->set('buttonText',$buttonText);
      			
        }
        
        function updatecheck(){
          if(isset($this->request->data) && !empty($this->request->data)){
      			if($this->request->data['Trip']['budget']<=0){
              $msg = "Please enter valid trip budget.";
              $this->Session->setFlash($msg,'default',array('class'=>'alert alert-success'));
              $this->redirect('/trips/view/'.$this->request->data['Trip']['id']);
            }
      			$this->trip_data = $this->Trip->read(null, base64_decode($this->request->data['Trip']['id']));
      			$this->trip_data['Trip']['budget'] = $this->request->data['Trip']['budget'];
      			
      			if($this->Trip->save($this->trip_data)){
        		    $msg = "Budget updated sucessfully.";
                $this->Session->setFlash($msg,'default',array('class'=>'alert alert-success'));
                $this->redirect('/trips/view/'.$this->request->data['Trip']['id']);
                //$this->redirect(array('controller' => 'trips', 'action' => 'view'));
            }
      			
          }
        }
        
        /* 
        Edit function in case a transaction has been already added to a trip
        This function doesn't allow updating anything apart from the Trip name
        */
         function addedit1($id = null)
        {
			
			
            if(empty($this->request->data)){				
					     $this->request->data = $this->Trip->read(null, base64_decode($id));
      			}elseif(isset($this->request->data) && !empty($this->request->data))
      			{
      			   //pr($this->request->data);exit();
      			    $this->request->data['Trip']['id'] = base64_decode($this->request->data['Trip']['id']);
      			    
      			    #sanitize data (remove tags)
      			    $this->request->data = $this->sanitizeData($this->request->data);
      			    
      				
                $this->Trip->set($this->request->data);
                //pr($_FILES);
                //Remove some validation rules
                //print "<pre>";
                //print_r($this->Trip);exit();
                $this->Trip->remove_validation();
        				if($this->Trip->validates())
        				{ 		
              				  //echo $this->request->data['Trip']['user_passcode'];
              		//$csv_data = $this->Session->read("csv_data"); 
        				  
                							
        					if($this->Trip->save($this->request->data))
        					{ 	
        					  
                    
                      $msg = "Trip updated sucessfully.";
                      $this->Session->setFlash($msg,'default',array('class'=>'alert alert-success'));
                      $this->Session->write("csv_data","");
                      $this->Session->write("leaders","");
                      $this->Session->write("csv_file","");
                      $this->redirect(array('controller' => 'trips', 'action' => 'index'));
                    
        					}else{
        					  
                  }
      				}else{
                //exit();
                  $this->set("main_leader",$this->Session->read("leaders"));
                  $this->set("trip_members",$this->Session->read("leaders"));
                  $dd = $this->Trip->validationErrors;
                  $this->set("errors",$dd);
              }    
			     }
      			// Calling arrays
      			if($this->Trip->id != null){
              //$this->Trip->id = base64_decode($id);
              $id = base64_encode($this->request->data['Trip']['id']);
              
              $this->request->data['Trip']['id'] = $this->Trip->id;
              $conditions = "trip_id = '".$this->Trip->id."' AND type='1'";
              $this->User->virtualFields = array(
              'name' => 'CONCAT(User.first_name, " ",User.middle_name, " ", User.last_name)'
              );
              $trip_members = $this->User->find('list',array('fields'=>array('id','name'),'conditions' => array($conditions)));
              $this->set('trip_members', $trip_members);
            }
            //echo $id."::Trip ID".$this->Trip->id;
            //pr($trip_members);
      			$textAction = ($id == null) ? 'New' : 'Edit';			
      			$this->set('heading', $textAction);
            $this->set('action',$textAction);			
      			$this->set('breadcrumb','Trips/'.$textAction);
      			$buttonText = ($id == null) ? 'CREATE TRIP' : 'UPDATE TRIP';	
      			$this->set('buttonText',$buttonText);
      			
        }
        
        function add($id = null)
        {
			
			      $this->check_leader_login();
            if(empty($this->request->data)){				
					     $this->request->data = $this->Trip->read(null, base64_decode($id));
      			}elseif(isset($this->request->data) && !empty($this->request->data))
      			{
      			   //configure::write("debug",0);
      			   //pr($this->request->data);exit();
      			    $this->request->data['Trip']['id'] = base64_decode($this->request->data['Trip']['id']);
      			    
      			    #sanitize data (remove tags)
      			    $this->request->data = $this->sanitizeData($this->request->data);
      			    
      				
                $this->Trip->set($this->request->data);
                //pr($_FILES);
                
        				if($this->Trip->validates())
        				{ 		
              				  //echo $this->request->data['Trip']['user_passcode'];
              		$csv_data = $this->Session->read("csv_data");
        				  //print "<pre>";
                  //print_r($csv_data);exit();
                							
        					if($this->Trip->save($this->request->data))
        					{ 	
        					  if($csv_data != ""){
                      $this->Trip->saveMembers($this->Trip->id, $csv_data);
                      
                     }
                     $tt = $this->Trip->id;
                     $nameInfo = explode("__",$this->request->data['Trip']['main_leader']);
                     $first_name = $nameInfo[0];
                     $middle_name = $nameInfo[1];
                     $last_name = $nameInfo[2];
                     //pr($nameInfo);
                     //print_r($this->request->data);
                     $UserInfo = $this->User->find('first',array('fields'=>array('id','first_name','last_name'),'conditions'=>array("User.first_name" => $first_name,"User.middle_name" => $middle_name,"User.last_name"=>$last_name,"User.trip_id"=>$tt)));
		                 // print_r($UserInfo);exit();
		                 $main_trip_leader = $UserInfo['User']['id'];
		                 
		                 $tripInfoData['Trip']['id'] = $this->Trip->id;
		                 $tripInfoData['Trip']['main_leader'] = $main_trip_leader;
		                 $tripInfoData['Trip']['year'] = $this->request->data['Trip']['year'];
		                 $tripInfoData['Trip']['trip_organization'] = $this->request->data['Trip']['trip_organization'];
		                 $csv_f = $this->Session->read("csv_file");
		                 //print_r($this->request->data);
  		               $tripInfoData['Trip']['csv_file'] = $csv_f['Trip']['csv']['name'];
		                 //$tripInfoData['Trip']['csv_file'] = $this->request->data['Trip']['csv']['name'];
		                 //print_r($validationErrors);
                     $pp = $this->Trip->save($tripInfoData);
		                 //print_r($this->Trip->validationErrors);
		                // exit();
                      if($this->request->data['Trip']['id'] != ""){
                        $msg = "Trip updated sucessfully.";
                      }else{
                        $msg = "Trip added sucessfully.";
                      }
                      $this->Session->setFlash($msg,'default',array('class'=>'alert alert-success'));
                      $this->Session->write("csv_data","");
                      $this->Session->write("leaders","");
                      $this->Session->write("csv_file","");
                      $this->redirect(array('controller' => 'trips', 'action' => 'index'));
                    
        					}else{
        					  
                  }
      				}else{
                //exit();
                  //pr($this->Session->read("leaders"));
                  $this->set("main_leader",$this->Session->read("leaders"));
                  $this->set("trip_members",$this->Session->read("leaders"));
                  $this->request->data['Trip']['csv_file'] = $this->Session->read("csv_file_name");
                  $dd = $this->Trip->validationErrors;
                  $this->set("errors",$dd);
              }    
			     }
      			// Calling arrays
      			if($this->Trip->id != null){
              //$this->Trip->id = base64_decode($id);
              $id = base64_encode($this->request->data['Trip']['id']);
              
              $this->request->data['Trip']['id'] = $this->Trip->id;
              $conditions = "trip_id = '".$this->Trip->id."' AND type='1'";
              $this->User->virtualFields = array(
              'name' => 'CONCAT(User.first_name, " ",User.middle_name, " ", User.last_name)'
              );
              $trip_members = $this->User->find('list',array('fields'=>array('id','name'),'conditions' => array($conditions)));
              $this->set('trip_members', $trip_members);
            }
            //echo $id."::Trip ID".$this->Trip->id;
            
            //pr($trip_members);
      			$textAction = ($id == null) ? 'New' : 'Edit';			
      			$this->set('heading', $textAction);
            $this->set('action',$textAction);			
      			$this->set('breadcrumb','Trips/'.$textAction);
      			$buttonText = ($id == null) ? 'CREATE TRIP' : 'UPDATE TRIP';	
      			$this->set('buttonText',$buttonText);
      			
        }
        
        
        function validateCSV($data){
          //pr($data);
          $validfiles = array('application/csv','application/octet-stream','text/plain','text/csv','text/comma-separated-values');
          $type = $data['Trip']['csv']['type'];
          if($data['Trip']['csv']['error'] == 0){
            if(in_array($type, $validfiles)){
              $name = $data['Trip']['csv']['name'];
              $dd = explode(".",$name);
              $ext = end($dd);
              if($ext == "csv"){
                return true;
              }else{
                //echo "not valid file";exit();
                return false;
              }
            }
            return false;
          }else{
            return false;
          }
            
          
        }
        
        function checkCSVdata(){
          $csv_data = "";
          if($this->request->data['Trip']['csv']['error'] =="0"){
              $csv_data = $this->uploadCSV();
          }	
          if($csv_data['error'] == TRUE){
             //$dd = $this->Trip->validationErrors;
             $dd = $csv_data['message']; 
             $this->set("errors",$dd);
          }	
          return $csv_data;
        }
        
        /*
            * view function
            * Functionality -  Trip detail view
            * Developer -
            * Created date - 24-Feb-2014
            * Modified date - 
        */
        function view($id = null)
        {
            $getData =  array();
            if($id == ""){
              //pr($this->request->data);
              $id = $this->request->data['Trip']['id'];
              //$all_receipts = "All Receipts";
              
            }else{
              //echo "id".$id;exit();
              
              if($this->request->data['Trip']['id'] == ""){
                $all_receipts = "All Receipts";
                $date_range = "1";
              }else{
                $id =  $this->request->data['Trip']['id'];
              }
            }
            if(!empty($id))
            {
                $id = base64_decode($id);
                $cond = "id=".$id;
                $getTripData = $this->Trip->find('first',array('conditions' => array($cond)));
                $filelist = $this->Aws->getlistObjects("receipts/".$id);
                if(count($filelist) > 1)
                  $getTripData['Trip']['file_exists'] = 1;
                else
                  $getTripData['Trip']['file_exists'] = 0;
                $response = $this->get_budget_details($id);
                $total_budget = $getTripData['Trip']['budget'];
                $total_spent = $response['total_spent'];
                $total_left = $total_budget - $total_spent;
                $len = count($this->request->data['receiptType']);
                $conditions = "";
                if($len>0){
                  $receipt_type =  $this->request->data['receiptType'][0];
                  if($receipt_type == "All Receipts"){
                    $all_receipts = "All Receipts";
                  }else{
                    $st = implode("','",$this->request->data['receiptType']);
                    $conditions .= "`Transaction`.`type` IN ('".$st."') AND ";
                    //echo $st;exit();
                  }
                }
                
                if($date_range == "")
                $date_range = $this->request->data['dateR'];
                $startDate = $this->request->data['startDate'];
                $endDate = $this->request->data['endDate'];
                $memberS = $this->request->data['memberS'];
                
                //echo "After Range".$date_range;
                
                  if($startDate == ""){
                    //$startDate = Date("Y-01-01");
                    //use Trip creation date as start date
                    $startDate = Date("Y-m-d",strtotime($getTripData['Trip']['created']));
                  }
                  if($endDate == ""){
                    $endDate = Date("Y-m-d");
                  }
                  $s = strtotime($startDate);
                  $e = strtotime($endDate);
                  $st = Date("Y-m-d H:i:s",$s);
                  $en = Date("Y-m-d 23:59:00",$e);
                  //echo "start".$st;
                  //echo "end".$en;
                if($date_range == "")
                $conditions .= " (`Transaction`.`created` >='$st' AND `Transaction`.`created` <= '$en') AND ";
                $conditions .= "`Transaction`.`trip_id` = ".$id;
                if($memberS != ""){
                  //do nothing fetch data for all users
                }else{
                  $trip_u = implode("','", $this->request->data['selMembers']);
                  if($trip_u !="")
                  $conditions .= " AND `Transaction`.`user_id` IN ('".$trip_u."') ";
                  //echo "conditions.".$memberS;
                }
                $this->loadModel('Transaction');
                $this->loadModel('User');
               /*
                $conditions .= "trip_id = ".$id;
                //echo "conditions".$conditions;
                $getData = $this->Transaction->find('all',array('conditions' => array($conditions)));
                */
                $join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      
                      'conditions' => array(
                          'Transaction.user_id = User.id' ,
                      )
                  )
              );
              $fields = array('Transaction.id','Transaction.user_id','Transaction.description','Transaction.type','Transaction.trip_id','Transaction.receipt','Transaction.usd','Transaction.foreign_currency_amount','Transaction.foreign_currency','Transaction.created','User.id','User.trip_id','User.first_name','User.middle_name','User.last_name');        
              $getData = $this->Transaction->find('all',array('fields'=>$fields,'conditions' => array($conditions),'joins'=>$join, 'order'=>array('Transaction.created DESC')));
              $conditions1 = "trip_id=".$id;
              $students = $this->User->find("count",array('conditions'=>array($conditions1)));
              //find filter specific total
              if($receipt_type != ""){ 
                $res = $this->get_budget_details($id, $conditions);
                $filtered_spent =  $res['total_spent'];
              }
            } 
            $conditions = "`User`.`trip_id` = ".$id;
            //configure::write("debug","0");
            
            $trip_members = $this->User->find("all", array('conditions'=>array($conditions),'order'=>array('User.first_name ASC')));
            //print_r($trip_members);
            
            $this->set("trip_id",$id);
            $this->set("total_budget",$total_budget);
            $this->set("total_spent",$total_spent);
            if($total_left<0){
              $total_left = 0;
            }
            if($memberS == ""){
              if($trip_u == ""){
                $memberS = "1";
              }
            }
            //filtered spent
            $this->set("filtered_spent",$filtered_spent);
            $this->set("trip_members",$trip_members);
            $this->set("memberS",$memberS);
            $this->set("receipt_type",$receipt_type);
            $this->set("total_left",$total_left);
            $this->set("arr",$this->request->data['receiptType']);
            $this->set("selMembers",$this->request->data['selMembers']);
            $this->set("all_receipts",$all_receipts);
            $this->set(compact('getData'));
            $this->set(compact('getTripData'));
            $this->set(compact('students'));
            $this->set("startDate",$startDate);
            $this->set("endDate",$endDate);
            //echo "DR".$date_range;
            $this->set("date_range",$date_range);
            //$this->set("checked",)
        }
         function get_budget_details($trip_id, $conditions=""){
          if($conditions == ""){
            $conditions = array('Transaction.trip_id'=>$trip_id);
          }
          $virtualFields = array('total' => 'SUM(Transaction.usd) AS total');
          $total = $this->Transaction->find('first', array('fields' => $virtualFields, 'conditions'=>$conditions));
          //pr($total);
          if($total[0]['total'] != ""){
            $usd = round($total[0]['total'],2);
          }else{
            $usd = "0.00";
          }
          
          $response['total_spent'] = $usd;
          return $response;
        }
        function uploadCSV(){
          $file = $this->Session->read("csv_file");
          $file = $file['Trip']['csv'];
          //$file = $this->request->data['Trip']['csv'];
          //pr($file);
          $err = array();
          $data = array();
          $i = 0;
          if($file['tmp_name'] != "" && $file['error'] == 0){
            $handle = fopen($file['tmp_name'], "r");
            $row = fgetcsv($handle);
            
            if(count($row) == 4){
              while ($row = fgetcsv($handle)){
                
                $data[$i]['first_name'] = htmlentities($row[0]);
                $data[$i]['middle_name'] = htmlentities($row[1]);
                $data[$i]['last_name'] = htmlentities($row[2]);
                if (preg_match("/\\s/", trim($data[$i]['first_name']))) {
                    // there are spaces
                    $err[] = $i+2;
                }else if(preg_match("/\\s/", trim($data[$i]['middle_name']))){
                    $err[] = $i+2;
                }else if(preg_match("/\\s/", trim($data[$i]['last_name']))){
                    $err[] = $i+2;
                }
                
                $data[$i]['temp_id'] = $row[0]."__".$row[1]."__".$row[2];
                if(strtolower($row[3]) == "leader"){
                  $data[$i]['type'] = "leader";
                 
                }else{
                  
                  $data[$i]['type'] = 'member';
                }
                $i++;
                
              }
              
            }else{
              $this->Trip->validationErrors['count_mismatch'][0] = "Column count in the csv file didn't match with the expected format.";
              //return false;
              $ret['error'] = true;
              $ret['message'] = "Column count in the csv file didn't match with the expected format.";
              return $ret;
            }
          }
          $total_errors = count($err);
          if($total_errors>0){
              $final_string = implode(", ",$err);
              $msg = "No whitespace allowed in first/middle/last name. Invalid names found in ";
              if($total_errors >1){
                  $msg .= "rows ".$final_string.".";
              }else{
                  $msg .= "row ".$final_string.".";
              }
              $this->Trip->validationErrors['count_mismatch'][0] = $msg;
              
              $ret['error'] = true;
              $ret['message'] = $msg;
              return $ret;
          }
          //pr($data);exit();
          $ret['error'] = false;
          $ret['data'] = $data;
          return $ret;
        }
        
        /*
            * delete function
            * Functionality -  Add & edit the Trips
            * Developer -
            * Created date - 12-Feb-2014
            * Modified date - 
        */       
	function delete($id = null)
	{
		if(!empty($id))
		{
			//$id = base64_decode($id);
			if($this->Trip->delete($id)){
			  //$this->Transaction->deleteAll
			  $this->loadModel('Transaction');
        $this->loadModel('User');
        $query = "DELETE FROM transactions where trip_id='$id'";
        $this->Transaction->query($query);
        $query = "DELETE FROM users where trip_id='$id'";
        $this->User->query($query);
			  
			  //$this->Transaction->deleteAll(array('Transaction.trip_id' => $id));
			  //$this->User->deleteAll(array('User.trip_id' => $id));
				$this->Session->setFlash("Trip has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));
				//$this->redirect($this->referer());
				$this->redirect(array('controller'=>'trips','action'=>'index'));
			}	
			$this->redirect(array('controller'=>'trips','action'=>'index'));
			/*if($this->Trip->updateAll(array('Trip.is_deleted'=>'1'),array('Trip.id'=>$id))){
				$this->Session->setFlash("Trip has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));
				$this->redirect($this->referer());
			}	*/			
		}
	}
	
	function archive($id = null)
	{
		if(!empty($id))
		{
			//$id = base64_decode($id);
			if($this->Trip->archive($id)){
				$this->Session->setFlash("Trip archived sucessfully.",'default',array('class'=>'alert alert-success'));
				$this->redirect(array('controller'=>'trips','action'=>'index'));
			}	
			
						
		}
	}
	
	function set_trip_leaders($data){
    $leaders = array();
    $i = 0;
    foreach($data as $leader){
      if($leader['type'] == "leader"){
        $name = $leader['first_name']." ";
        if($leader['middle_name'] != ""){
          $name .= $leader['middle_name']. " ";
        }
        $name .= $leader['last_name'];
        $leaders[$leader['temp_id']] = $name;
        //$leaders[$i]['id'] = ;
        $i++;
        //$leaders[$leader['temp_id']] = $name;
      }
    }
    return $leaders;
  }
	
    function csv(){
    //echo "reached";
    //pr($_FILES);
    if($_FILES != ""){
      $data['Trip'] = $_FILES;
      //pr($data);
      $dd = $this->validateCSV($data);
      if($dd){
        $this->Session->write("csv_file",$data);
        $ret = $this->uploadCSV();
        $flg = $ret['error'];
        if($flg==FALSE){
          //pr($data);
          $data =  $ret['data'];
          $leaders = $this->set_trip_leaders($data);
          //$leaders[] = "Rahul";
          $this->Session->write("csv_data",$data);
          $this->Session->write("leaders", $leaders);
          
          //pr($leaders);exit();
          $result = array("status"=>"success", 'data'=>$leaders);
          echo json_encode($result);exit();
        }else{
          //$result = array("status"=>"failure", 'data'=>"Column count in the csv file didn't match with the expected format.");
            $result = array("status"=>"failure", 'data'=>$ret['message']);
            echo json_encode($result);exit();
        
          //error something went wrong
        }
        //$ss = $this->Session->read("csv_file");
        //pr($ss);
      }else{
        $result = array("status"=>"failure", 'data'=>"Please upload csv file only.");
        echo json_encode($result);exit();
        //show error message
      }
      //echo var_dump($dd);
    }
    exit();
  }
	
	function unarchive($id = null)
	{
		if(!empty($id))
		{
			$id = base64_decode($id);
			if($this->Trip->unarchive($id)){
				$this->Session->setFlash("Trip un-archived sucessfully.",'default',array('class'=>'alert alert-success'));
				$this->redirect(array('controller'=>'trips','action'=>'index'));
			}else{
        //echo "unarchive";
      }	
			
			/*if($this->Trip->updateAll(array('Trip.is_deleted'=>'1'),array('Trip.id'=>$id))){
				$this->Session->setFlash("Trip has been deleted sucessfully.",'default',array('class'=>'alert alert-success'));
				$this->redirect($this->referer());
			}	*/			
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
        /* Trip Functionality end */
        
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
	    $this->loadModel('Trip');
		//Code for webservice
			//if($this->request->accepts('application/json')) {
		//	print_r($this->request->accepts()); die();
		
			if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
        
				$input = json_decode(file_get_contents('php://input'), true);
				//pr($input); die;
				if(!empty($input)){
		    
				$this->request->data['Trip'] = $input;
       				
  				if($this->request->data['Trip']['email'] ==""){
  					$finalData['message'] = "Email is missing";
  					$finalData['messageId'] = 300;
  					echo json_encode($finalData); exit;
  				}
  				if($this->request->data['Trip']['password'] ==""){
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
     
				$TripId = $this->Session->read('loggedTripInfo.id');
				if(!empty($TripId)) {
				    $this->redirect(array('controller'=>'Trips','action'=>'dashboard','plugin'=>'login'));
				}
				
			}
		
	    if(isset($this->request->data) && (!empty($this->request->data)))
      {	
		    $this->Trip->set($this->request->data);
		      //$this->Trip->validator()->remove('email', 'rule1');
		
		if($this->Trip->validates(array('fieldList' => array('email', 'password'))))
		{	
			 
			$email = $this->request->data['Trip']['email'];
			$Trip_password  = md5($this->request->data['Trip']['password']);
			$TripInfo = $this->Trip->find('first',array('fields'=>array('id','first_name','last_name','email','password'),'conditions'=>array("Trip.email" => $email,"Trip.password" => $Trip_password,"Trip.status"=>1,"Trip.is_deleted"=>0)));
		
			if(count($TripInfo) > 0 ) {
				if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
					$messageId= 200 ;
					$message = "Success";
					$finalData['result'] = array("TripID"=>$TripInfo['Trip']['id'],"first_name"=>$TripInfo['Trip']['first_name'],"last_name"=>$TripInfo['Trip']['last_name']);
					$finalData['message'] = $message;
					$finalData['messageId'] = $messageId;
					echo json_encode($finalData); exit;
				}
				$this->Session->write('loggedTripInfo', $TripInfo['Trip']);
				$this->Session->write('ADMIN_SESSION', $TripInfo['Trip']['id']);
				
				if(!empty($this->request->data['Trip']['remember_me'])) {
				$email = $this->Cookie->read('TripEmail');
				$password = base64_decode($this->Cookie->read('TripPass'));
				if(!empty($email) && !empty($password)) {
					$this->Cookie->delete('TripEmail');
					$this->Cookie->delete('TripPass');
				} 						
					$cookie_email = $this->request->data['Trip']['email'];
					$this->Cookie->write('TripEmail', $cookie_email, false, '+2 weeks');
					$cookie_pass = $this->request->data['Trip']['password'];
					$this->Cookie->write('TripPass', base64_encode($cookie_pass), false, '+2 weeks');
				}else {
					$email = $this->Cookie->read('TripEmail');
					$password = base64_decode($this->Cookie->read('TripPass'));
					if(!empty($email) && !empty($password)) {
							$this->Cookie->delete('TripEmail');
							$this->Cookie->delete('TripPass');
					}
				}
				
				$this->redirect(array('controller'=>'Trips','action'=>'dashboard','plugin'=>'login'));
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
				
				$email = $this->Cookie->read('TripEmail');
				$password = base64_decode($this->Cookie->read('TripPass'));
				if(!empty($email) && !empty($password)) {
					$remember_me  = true;
					$this->request->data['Trip']['email']  = $email;
					$this->request->data['Trip']['password']  = $password;
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
        
     function download($id=null){
      
      ini_set('max_execution_time',0);
      ini_set('memory_limit', '2500M');
      //$st = ini_set('memory_limit', '256M');
      //echo $ss." sdfsdfs ".$st;exit;
      if($id == ""){
        exit("Not a valid resource");
      }
      //pr($id);exit("sdfd");
      $id = base64_decode($id);
      
      if(LIVE){
        //$path = WWW_ROOT."receipts/".$id."/";
        $path = AWS_S3_BUCKET_PATH."/";
        $zipname = WWW_ROOT.'zz/Trip.zip';
        /*$sample_xls = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.xlsx';
        $sample_pdf = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.pdf';*/
        $sample_xls = WWW_ROOT.'receipts/Trip.xlsx';
        $sample_pdf = WWW_ROOT.'receipts/Trip.pdf';
        $rendererLibraryPath = WWW_ROOT."../Vendor/mpdf60";
        $inputFileName = WWW_ROOT.'receipts/sample_r.xlsx';
      }else{
        //$path = WWW_ROOT."receipts\\".$id."\\";
        $path = AWS_S3_BUCKET_PATH."\\";
        $zipname = WWW_ROOT.'zz\Trip.zip';
        /*$sample_xls = AWS_S3_BUCKET_PATH.'\\receipts\\'.$id.'\\Trip.xlsx';
        $sample_pdf = AWS_S3_BUCKET_PATH.'\\receipts\\'.$id.'\\Trip.pdf';*/
        $rendererLibraryPath = WWW_ROOT."..\Vendor\mpdf60";
        $inputFileName = WWW_ROOT.'receipts\sample_r.xlsx';
      }
      if(file_exists($sample_xls)){
        unlink($sample_xls);
      }
      $trip_name = $this->generateXLS($id, $sample_xls, $sample_pdf, $inputFileName, $rendererLibraryPath, $type);
      if(LIVE){
        $zipname = WWW_ROOT.'zz/'.$trip_name.'.zip';
        
      }else{
        $zipname = WWW_ROOT.'zz\\'.$trip_name.'.zip';
        
      }
      $zip = new ZipArchive;
      //if ($zip->open($zipname, ZIPARCHIVE::OVERWRITE)!==TRUE) {
      if ($zip->open($zipname, ZIPARCHIVE::CREATE)!==TRUE) {      
        exit("cannot open <$zipname>\n");
      }

      $filelist = $this->Aws->getlistObjects("receipts/".$id);
      /*if ($handle = opendir($path)){
          while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
                $zip->addFile($path.$entry, $entry) or die("Could not add file to zip archive");
            }
          }
        closedir($handle);
        $zip->close();
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename='".$trip_name.".zip'");
        header('Content-Length: ' . filesize($zipname));
        header("Location: /zz/$trip_name.zip");
      }*/
      foreach ($filelist as $key => $value) {
        # code...
        if($key==0)
          continue;
        $entry = $value['Key'];
        if ($entry != ".." && !strstr($entry,'.php')) {
            $content = file_get_contents($path.$entry);
            $zip->addFromString(pathinfo ( $path.$entry, PATHINFO_BASENAME), $content);
        }
      }
      $zip->close();
      header('Content-Type: application/zip');
      header("Content-Disposition: attachment; filename='".$trip_name.".zip'");
      header('Content-Length: ' . filesize($zipname));
      header("Location: /zz/$trip_name.zip");
      exit();
    }
    
    function generateXLS($id, $output, $outputFileName, $inputFileName, $rendererLibraryPath, $type){
    
      $this->loadModel('Transaction');
      $conditions = "`Transaction`.`trip_id` = ".$id;
      $join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      
                      'conditions' => array(
                          'Transaction.user_id = User.id' ,
                      )
                  )
              );
      $fields = array('Transaction.id','Transaction.user_id','Transaction.description','Transaction.type','Transaction.usd','Transaction.foreign_currency_amount','Transaction.foreign_currency','Transaction.created','User.id','User.trip_id','User.first_name','User.middle_name','User.last_name');        
      $getData = $this->Transaction->find('all',array('fields'=>$fields,'conditions' => array($conditions),'joins'=>$join));
      $conditions = "Trip.id=".$id;
      $this->Trip->unbindModel(
        array('hasMany' => array('User'))
      );
      $fields = array('Trip.id','Trip.name','Trip.main_leader','Trip.budget','Trip.created','User.id','User.trip_id','User.first_name','User.middle_name','User.last_name');
			$join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      
                      'conditions' => array(
                          ' Trip.main_leader = User.id' ,
                      )
                  )
              );
      $getTripData = $this->Trip->find('first',array('fields'=>$fields,'conditions' => array($conditions),'joins'=>$join));
      
      $trip = $getTripData['Trip']['name'];
      $r_array = array("/","&","\"","'");
      $trip = str_replace($r_array," ",$trip);
      $trip_budget = $getTripData['Trip']['budget'];
      $dd = strtotime($getTripData['Trip']['created']);
      $year = Date("Y", $dd);
      $name = $getTripData['User']['first_name'];
      if($getTripData['User']['middle_name'] != ""){
        $name .= " ".$getTripData['User']['middle_name'];
      }
      $name .= " ".$getTripData['User']['last_name'];
      
      App::import('Vendor', 'PHPExcel', array('file' => 'phpexcel/PHPExcel.php'));
      $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
      $rendererLibrary = 'tcpdf';
      //$rendererLibrary = 'mpdf60';
      //$inputFileName = WWW_ROOT.'receipts\sample.xlsx';
      //$output = WWW_ROOT.'receipts\output.xlsx';
      //$outputFileName = WWW_ROOT.'receipts\output.pdf';
      //$rendererLibraryPath = WWW_ROOT."..\Vendor\\".$rendererLibrary;
      if (!PHPExcel_Settings::setPdfRenderer(
    		$rendererName,
    		$rendererLibraryPath
    	)) {
    	die(
    		'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
    		EOL .
    		'at the top of this script as appropriate for your directory structure'
    	);
      }
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($inputFileName); // Empty Sheet 
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $sheet = $objPHPExcel->getActiveSheet();
      $sheet->mergeCells('E15:H15');
      //$objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
      $sheet->setCellValue('C3', $trip);
      $sheet->setCellValue('G3', $name);
      $sheet->setCellValue('A1', $year. " Bethel Trips Expense List");
      $sheet->setCellValue('H6', $trip_budget);
      $sheet->setCellValue('E20', "Remaining Amount in USD");
      $styleArray = array('borders' => array(
         'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
         ),
      ));
      $styleArray3 = array(
      'font'  => array(
          'bold'  => true,
          'size'  => 11,
          
      ),
      'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      
      );
      $currency_f = array();
      foreach ($getData as $transaction){
        if($transaction['Transaction']['foreign_currency_amount']>0){
          $currency_f[$transaction['Transaction']['foreign_currency']]['total'] += $transaction['Transaction']['foreign_currency_amount'];
        }
      }
      $total_f = count($currency_f);
      $counter = 0;
      $baseR = 18;
      $total =0;
      foreach ($currency_f as $key => $val){
        if($counter ==0){
          $sheet->setCellValue('E18', "Total $key Amount of Expenses:");
          $sheet->setCellValue('H18', round($val['total'],2));
        }else if($total_f > 1){
          
          //Total Foreign Amount of Expenses:
          $sheet->insertNewRowBefore($baseR,1);
          $sheet->setCellValue('E'.$baseR, "Total $key Amount of Expenses:");
          $sheet->mergeCells('E'.$baseR.':G'.$baseR);
          $sheet->setCellValue('H'.$baseR, round($val['total'],2));
        }
        $baseR += 1;
        $counter++;
      }
      if($baseR == 18){
        $baseR =  19;//in case no foreign currency found the total cell should still show all USD
        $counter =1;
      }
      $tt_f = 0;
      foreach ($getData as $transaction){
        $total += $transaction['Transaction']['usd'];
        if($transaction['Transaction']['foreign_currency_amount']>0){
        }else{
          $totalUSD += $transaction['Transaction']['usd'];
        }
        $tt_f +=1; 
      }
      $usdS = $baseR;
      
      
      $sheet->setCellValue('H'.$usdS,$total);
      $sheet->setCellValue('H17', $totalUSD);
      if($trip_budget >0){
        $money_leftover = $trip_budget - $total;
      }else{
        $money_leftover = 0;
      }
      if($money_leftover <0)
        $money_leftover = 0;
      $base_money_remaining_row = $usdS+1;//after $counter number of times the foreign currencies  
      $sheet->setCellValue('H'.$base_money_remaining_row, $money_leftover);
      //echo "M row is ".$base_money_remaining_row;exit();
      $baserow = 22+$counter;//row from where transactions needs to be inserted.
      $r =1;
      $totalUSD = 0;
      $totalForeign = 0;
      $currency_f = array();
      $tt_c =0;
      $message = "Generating xls file";
      $sheet->insertNewRowBefore($baserow+1,$tt_f);
      foreach ($getData as $transaction){
        $tt_c +=1;
        if($type == "all"){
          $progress = round(($tt_c/$tt_f)*50,0);
        }else{
          //no images only xls file
          $progress = round(($tt_c/$tt_f)*100,0);
        }
        $this->send_message($id, $message, $progress);
        $row = $baserow + $r;
        //$sheet->insertNewRowBefore($row,1);
        $time = strtotime($transaction['Transaction']['created']);
        $date = Date("m/d/y", $time);
        if($transaction['Transaction']['foreign_currency_amount']>0){
          //$totalForeign += $transaction['Transaction']['foreign_currency_amount'];
          $foreign_currency = $transaction['Transaction']['foreign_currency']." ".$transaction['Transaction']['foreign_currency_amount'];
          $currency_f[$transaction['Transaction']['foreign_currency']]['total'] += $transaction['Transaction']['foreign_currency_amount'];
          
        }else{
          
          $foreign_currency = "";
        }
        $m_name = $transaction['User']['first_name'];
        if($transaction['User']['middle_name'] != "")
        $m_name .= " ".$transaction['User']['middle_name'];
        $m_name .= " ".$transaction['User']['last_name'];
        //continue;
        $sheet->mergeCells('C'.$row.':D'.$row);
        $sheet->mergeCells('E'.$row.':F'.$row);
        $sheet->setCellValue('A'.$row, $date)
	                              ->setCellValue('B'.$row, $transaction['Transaction']['type'])
	                              ->setCellValue('C'.$row, $transaction['Transaction']['description'])
	                              ->setCellValue('E'.$row, $m_name)
	                              ->setCellValue('G'.$row, "$".$transaction['Transaction']['usd'])
	                              ->setCellValue('H'.$row, $foreign_currency);
        $sheet->getStyle("A".$baserow.":H".$row)->applyFromArray($styleArray3);
        $r++;
      }
      
      $sheet->getStyle("I1:I".$row)->applyFromArray($styleArray);
      $styleArray2 = array(
        'borders' => array(
        'left' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
        )
      );
      
      $row = $row;
      $sheet->getStyle("H".$baserow.":I".$row)->applyFromArray($styleArray2);
      $sheet->removeRow($baserow,1);
      //exit;
      $objWriter->save($output);
      //$message = "Generating PDF file";
      //$progress = "51";
      //$this->send_message($id, $message, $progress);
      //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
      //$message = "Generating PDF file";
      //$progress = "52";
      //$this->send_message($id, $message, $progress);
      //$objWriter->save($outputFileName);
      unset($objWriter);
      unset($objPHPExcel);

      $destination_dir = "receipts/".$id."/";
      $destination = $destination_dir . pathinfo ( $output, PATHINFO_BASENAME);
      $file_moved = $this->Aws->uploadimg($output, $destination);
      return $trip; 
    }
  public function generate_data($id,$type){
      //ini_set('log_errors',1);
      ini_set('max_execution_time',0);
      ini_set('memory_limit', '2500M');
      if($id == ""){
        exit("Not a valid resource");
      }
      $id = base64_decode($id);
      
      if(LIVE){
        $path = AWS_S3_BUCKET_PATH."/";
        $zipname = WWW_ROOT.'zz/Trip.zip';
        /*$sample_xls = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.xlsx';
        $sample_pdf = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.pdf';*/
        $sample_xls = WWW_ROOT.'receipts/Trip.xlsx';
        $sample_pdf = WWW_ROOT.'receipts/Trip.pdf';
        $rendererLibraryPath = WWW_ROOT."../Vendor/mpdf60";
        $inputFileName = WWW_ROOT.'receipts/sample_r.xlsx';
      }else{
        $path = AWS_S3_BUCKET_PATH."\\";
        $zipname = WWW_ROOT.'zz\Trip.zip';
        $sample_xls = AWS_S3_BUCKET_PATH.'\\receipts\\'.$id.'\\Trip.xlsx';
        $sample_pdf = AWS_S3_BUCKET_PATH.'\\receipts\\'.$id.'\\Trip.pdf';
        $rendererLibraryPath = WWW_ROOT."..\Vendor\mpdf60";
        $inputFileName = WWW_ROOT.'receipts\sample_r.xlsx';
      }
      $message = "Generating xls file";
      $progress = "0";
      $this->send_message($id, $message, $progress);
      //sleep(1);
      if(file_exists($sample_xls)){
        unlink($sample_xls);
      }
      $trip_name = $this->generateXLS($id, $sample_xls, $sample_pdf, $inputFileName, $rendererLibraryPath, $type);
      if(LIVE){
        $zipname = WWW_ROOT.'zz/'.$trip_name.'.zip';
        
      }else{
        $zipname = WWW_ROOT.'zz\\'.$trip_name.'.zip';
        
      }
      $zip = new ZipArchive;
      if ($zip->open($zipname, ZipArchive::CREATE|ZipArchive::OVERWRITE)!==TRUE) {      
        exit("cannot open <$zipname>\n");
      }
      $filelist = $this->Aws->getlistObjects("receipts/".$id);
      /*if(file_exists($path)){
      }else{
        exit("No such directory");
      }*/
      $progress = 0;
      $message = "Archiving files";
      if($type != "xls"){
        $needle = ".php";
      }else{
        $needle = ".jpg";
      }
      //$total_files =0;
      foreach ($filelist as $key => $value) {
        # code...
        if($key==0)
          continue;
        $entry = $value['Key'];
        if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
            $total_files +=1;
        }
      }
        /*if ($handle = opendir($path)){
            while (false !== ($entry = readdir($handle))) {
              if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
                  $total_files +=1;
              }
            }
            closedir($handle);
        }*/
        $counter =0;
        foreach ($filelist as $key => $value) {
          # code...
          if($key==0)
            continue;
          $entry = $value['Key'];
          if ($entry != "." && $entry != ".." && !strstr($entry,$needle)) {
              $counter +=1;
              $content = file_get_contents($path.$entry);
              $zip->addFromString(pathinfo ( $path.$entry, PATHINFO_BASENAME), $content);
              //$zip->addFile($path.$entry, $entry) or die("Could not add file to zip archive");

              //the earlier 50% has been calculated while generating the xls and pdf file
              $progress = 50+round(($counter/$total_files)*50,0);
              $message = "Archiving receipt $counter of $total_files";
              $this->send_message($id, $message, $progress);
              //sleep(1);
          }
        }
        $message = 'TERMINATE';
        $progress = '100';
        $this->send_message($id, $message, $progress);
        /*if ($handle = opendir($path)){
          
            while (false !== ($entry = readdir($handle))) {
              if ($entry != "." && $entry != ".." && !strstr($entry,$needle)) {
                  $counter +=1;
                  $zip->addFile($path.$entry, $entry) or die("Could not add file to zip archive");
                  //the earlier 50% has been calculated while generating the xls and pdf file
                  $progress = 50+round(($counter/$total_files)*50,0);
                  $message = "Archiving receipt $counter of $total_files";
                  $this->send_message($id, $message, $progress);
                  //sleep(1);
              }
            }
          closedir($handle);
          $message = 'TERMINATE';
          $progress = '100';
          $this->send_message($id, $message, $progress);
        }*/
        
      $zip->close();
  
  }  
    /**
    Constructs the SSE data format and flushes that data to the client.
    */
   public function send_message($id, $message, $progress) {
        $d = array('message' => $message , 'progress' => $progress);
         
        echo "id: $id" . PHP_EOL;
        echo "data: " . json_encode($d) . PHP_EOL;
        echo PHP_EOL;
         
        //PUSH THE data out by all FORCE POSSIBLE
        ob_flush();
        flush();
        //sleep(1);
    }
    
    public function sse($id,$type){
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
    $this->generate_data($id,$type);
    exit();
  }
  
  public function dd($id,$type="all"){
      if($id == ""){
        exit();
      }else{
        $id = base64_decode($id);
      }
      $conditions = "Trip.id=".$id;
      $this->Trip->unbindModel(
        array('hasMany' => array('User'))
      );
      $fields = array('Trip.id','Trip.name','Trip.created');
			
      $getTripData = $this->Trip->find('first',array('fields'=>$fields,'conditions' => array($conditions),'joins'=>$join));
      //print_r($getTripData);
      $trip_name = $getTripData['Trip']['name'];
      //update at other place as well
      $r_array = array("/","&","\"","'");
      $trip_name = str_replace($r_array," ",$trip_name);
      if($type == "xls"){
        $t = " xls";
      }
      $zipname = WWW_ROOT.'zz/'.$trip_name.'.zip';
      //exit();
      header('Content-Type: application/zip');
      header("Content-Disposition: attachment; filename='".$trip_name.$t.".zip'");
      header('Content-Length: ' . filesize($zipname));
      header("Location: /zz/$trip_name.zip");
      exit();
  } 
  function kt(){
  $id = "2";
    $this->loadModel('Transaction');
      $conditions = "`Transaction`.`trip_id` = ".$id;
      $join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      
                      'conditions' => array(
                          'Transaction.user_id = User.id' ,
                      )
                  )
              );
      $fields = array('Transaction.id','Transaction.user_id','Transaction.description','Transaction.type','Transaction.usd','Transaction.foreign_currency_amount','Transaction.foreign_currency','Transaction.created','User.id','User.trip_id','User.first_name','User.middle_name','User.last_name');        
      $getData = $this->Transaction->find('all',array('fields'=>$fields,'conditions' => array($conditions),'joins'=>$join));
      $conditions = "Trip.id=".$id;
      print "<pre>";
      print_r($getData);
      exit();
  }
  
}
?>