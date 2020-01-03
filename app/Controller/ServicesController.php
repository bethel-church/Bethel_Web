<?php
    /*
        * Services Controller class
        * Functionality -  Manage the Users Management
        * Modified date - 
    */
    class ServicesController extends AppController {
        var $uses = array('Trip','Currency', 'Transaction', 'User');
        var $components = array("Aws");
        function beforeFilter(){
          
        }   

        function index()
        {
          //echo "Invalid URL";
          echo "Testing...."."<br/>";
          $filelist = $this->Aws->getlistObjects("receipts/1");
          $path = AWS_S3_BUCKET_PATH."/";
          $zipname = WWW_ROOT.'zz/TestTest.zip';
          $sample_xls = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.xlsx';
          $sample_pdf = AWS_S3_BUCKET_PATH.'/receipts/'.$id.'/Trip.pdf';
          $rendererLibraryPath = WWW_ROOT."../Vendor/mpdf60";
          $inputFileName = WWW_ROOT.'receipts/sample_r.xlsx';
          $zip = new ZipArchive;
          //if ($zip->open($zipname, ZIPARCHIVE::CREATE)!==TRUE) {
          if ($zip->open($zipname, ZIPARCHIVE::OVERWRITE)!==TRUE) {
            exit("cannot open <$zipname>\n");
          }
          foreach ($filelist as $key => $value) {
            # code...
            $entry = $value['Key'];
            if ($entry != ".." && !strstr($entry,'.php')) {
                $zip->addFile($path.$entry, $entry) or die("Could not add file to zip archive");
                $content = file_get_contents($path.$entry);
                $zip->addFromString(pathinfo ( $path.$entry, PATHINFO_BASENAME), $content);
            }
          }
          $zip->close();
          header('Content-Type: application/zip');
          header("Content-Disposition: attachment; filename='TestTest.zip'");
          header('Content-Length: ' . filesize($zipname));
          header("Location: /zz/TestTest.zip");
          exit();
        }

          /*while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
                $zip->addFile($path.$entry, $entry) or die("Could not add file to zip archive");
                //echo "<br />here".$path.$entry;
            }
          }*/

        function get_trip_list(){
		  $data = json_decode( file_get_contents('php://input') );
		  $arr = array('Mission Trips','2nd Year Travel');
          if(is_object($data)){
            $trip_category = trim($data->trip_category);
            if(!in_array($trip_category,$arr)){
				$output = array('status'=>'failure','message'=>'Invalid organization category.');
				echo json_encode($output);
				exit();
			}
		  }else{
			$output = array('status'=>'failure','message'=>'Missing organization category.');
			echo json_encode($output);
			exit();

		  }	
          $conditions = array("Trip.archived='0' AND Trip.trip_organization='$trip_category' ");
          $fields = array('Trip.id', 'Trip.name', 'Trip.budget', 'Trip.created');
          $order = array('Trip.name ASC');
          $getData = $this->Trip->find('all',array('fields'=>$fields,'conditions' => $conditions,'order'=>$order,'recursive'=>0));
          $output = array('status'=>'success', 'trips'=>$getData) ;  
          echo json_encode($output);
          exit();
			
        }
        
        function login(){
          configure::write("debug",0);
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = trim($data->trip_id);
            $passcode = trim($data->passcode);
            $message = $this->validateLogin($trip_id , $passcode);
            if($message == ""){
              //check if the passcode is for users
              $conditions = "`Trip.id`='$trip_id' AND (`Trip.user_passcode`='$passcode')";
              $fields = array('Trip.id', 'Trip.name', 'Trip.budget', 'Trip.created');
              $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
              );
              $this->Trip->bindModel(array(
                  'hasMany' => array(
                      'User' => array(
                          'className' => 'User',
                          'conditions' => array('User.type' => 0),
                          'fields' => array('id', 'first_name', 'middle_name', 'last_name', 'type','created')
                        )
                  )
              ));
              
                   // pr($joins);exit();
              //$order = array('Trip.name ASC');
              $getData = $this->Trip->find('first',array('fields'=>$fields,'conditions' => $conditions,'recursive'=>1 ));
              //$log = $this->Trip->getDataSource()->getLog(false, false); 
              //pr($log);
              //debug($log);
              //pr($getData);
              
              if($getData['Trip']['id'] == ""){
                  //check if the passcode belongs to Trip leader
                  $conditions = "`Trip.id`='$trip_id' AND (`Trip.leader_passcode`='$passcode')";
                  $fields = array('Trip.id', 'Trip.name', 'Trip.budget', 'Trip.created');
                  $this->Trip->unbindModel(
                     array('hasMany' => array('User'))
                  );
                  $this->Trip->bindModel(array(
                      'hasMany' => array(
                          'User' => array(
                              'className' => 'User',
                              'conditions' => array('User.type' => 1),
                              'fields' => array('id', 'first_name', 'middle_name', 'last_name', 'type','created')
                            )
                      )
                  ));
                  
                   // pr($joins);exit();
                  //$order = array('Trip.name ASC');
                  $getData = $this->Trip->find('first',array('fields'=>$fields,'conditions' => $conditions,'recursive'=>1 ));
                  //$log = $this->Trip->getDataSource()->getLog(false, false); 
                  //pr($log);
                  //pr($getData);echo "here done";
                  if($getData['Trip']['id'] != ""){
                    $output = array('status'=>'success', 'trips'=>$getData);
                  }else{
                    $output = array('status'=>'failure', 'message'=>"Invalid Trip ID/passcode combination. No such matching record found.");
                  }
              }else{
                //share member data only
                $output = array('status'=>'success', 'trips'=>$getData); 
              }
               
            }else{
              $output = array('status'=>'failure', 'message'=>$message);
            }  
          }else{
            $output = array('status'=>'failure','message'=>'Please supply valid input data.');
          }
          //pr($output);echo "here";exit();
          echo json_encode($output);
          exit();
			
        }
        
        function set_trip_budget(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = trim($data->trip_id);
            $budget = trim($data->trip_budget);
            
          } 
          if($trip_id >0 AND $budget >0){
            $query = "UPDATE trips SET `budget`='$budget' WHERE `id`='$trip_id'";
            $this->Trip->query($query);
            if($this->Trip->getAffectedRows()){
              $output = array('status'=>'success', 'message'=>'Budget set successfully');
            }else{
              $output = array('status'=>'failure', 'message'=>'No trip was updated. Please try again');
            }
            
          }else{
            if($trip_id == "" OR $budget ==""){
              $message = "Missing Trip ID or budget.";
            }elseif(!($budget>0)){
              $message = "Incorrect budget amount.";
            }else{
              $message = "Invalid input data. Please try again.";
            }
            $output = array('status'=>'success', 'message'=>$message) ;
          }
            
          echo json_encode($output);
          exit();
			
        }
        
        
        
        function set_trip_currencies(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            
            $trip_id = trim($data->trip_id);
            $currencies = $data->currencies;
            $valid = true;
            if($trip_id ==""){
              $message = "Trip Id missing.";
              $output = array("status"=>"failure", 'message'=>$message);
              $valid = false;
            }elseif(!($trip_id>0)){
              $message = "Invalid trip ID.";
              $valid = false;
              $output = array("status"=>"failure", 'message'=>$message);
            }
            
            //$budget =$data
            
          }else{
            $valid = false;
            $message = "Invalid input data.";
            $output = array("status"=>"failure", 'message'=>$message);
          }
          
          if($valid){
            //$trip_id = $receipt['Transaction']['trip_id'];
            $this->Trip->unbindModel(
              array('hasMany' => array('User'))
            );
            $trip = $this->Trip->find('first', array( 'conditions'=>array('id'=>$trip_id,'archived'=>'0')));
            //pr($trip);
            //exit();
            if($trip['Trip']['archived'] != "0"){
              $output = array('status'=>'failure', 'message'=>"Invalid trip ID or trip has been archived.");
              echo json_encode($output);
              exit();
            }
          
            $query = "DELETE FROM currencies WHERE `trip_id`='$trip_id'";
            $date = Date("Y-m-d h:i:s");
            $this->Trip->query($query);
            foreach ($currencies as $currency){
              $query ="INSERT INTO currencies (`trip_id`, `currency`,`created`) VALUES ('$trip_id','$currency','$date')";
              $this->Trip->query($query);
            }
            $output = array("status"=>"success", 'message'=>"Currencies set successfully.");
            
          }
          echo json_encode($output);
          exit();
          
          
          
          echo json_encode($output);
          exit();
			
        }
        
        function get_trip_currencies(){
          //configure::write("debug",0);
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = $data->trip_id;
            if($trip_id !=""){
              $fields = array('Currency.id', 'Currency.currency', 'Currency.trip_id', 'Currency.created');
              
              $conditions = "`trip_id`='$trip_id'";
              $getData = $this->Currency->find('all',array('fields'=>$fields,'conditions'=>$conditions,'recursive'=>0));
              //pr($getData);
              //exit();
              $output = array('status'=>'success', 'trips'=>$getData) ; 
             }else{
              $output = array('status'=>'failure','message'=>"No trip ID found.");
             } 
          }else{
            $output = array('status'=>'success', 'message'=>"Invalid input data.");
          }
          echo json_encode($output);
          exit();
          
        }
        /**
        format date nicely for add_receipt and update_receipt functions
        */
        function form_date($date){
          $st = strtotime($date);
          $dd = Date("Y-m-d H:i:s",$st);
          return $dd;
        }
        
        
        function add_receipt(){
          $data = $this->request->data;
          //pr($_FILES);
          $file = $_FILES;
          $validation = $this->validateReceipt($data, $file);
          if($validation['status'] == "failure"){
            echo json_encode($validation);
            exit();
          }else{
            $user_id = $validation['user_id'];
            $trip_id = trim($data['trip_id']);
            $price_other_currency = trim($data['price_other_currency']);
            $price_usd = trim($data['price_usd']);
            $currency = trim($data['currency']);
            $type= htmlspecialchars(trim($data['type']));
            //$user_name = trim($data['user_name']);
            $description = trim($data['description']);
            $description = htmlspecialchars($description);
            $receipt_date = trim($data['receipt_date']);
            $receipt_date = $this->form_date($receipt_date);
            //change this value for windows system
            $destination_dir = "receipts/".$trip_id."/";

            //$date = Date("Y-m-d h:i:s");
            $date = gmdate("Y-m-d H:i:s");
            $date = $receipt_date;
            
            $receipt_name = $trip_id."_".time();
            $dd = explode(".",$file['receipt_image']['name']);
            $ext = end($dd);
            $file_ext = in_array($file['receipt_image']['type'],array('image/jpeg','image/jpg','image/pjpg'));
            $file2_ext = in_array($file['receipt_image']['type'],array('image/png'));
            
            if($file_ext){
            //jpg
              $receipt_name = $receipt_name.".jpg";
            }elseif($file2_ext){
              $receipt_name = $receipt_name.".png";
            }else{
              $receipt_name = $receipt_name.".".$ext;
            }
            $destination = $destination_dir . $receipt_name;
            //$file_moved = move_uploaded_file($file['receipt_image']['tmp_name'], $destination );
            $file_moved = $this->Aws->uploadimg($file['receipt_image']['tmp_name'], $destination);
            if($file_moved){
              $query = "INSERT INTO transactions (`trip_id`,`user_id`,`type`,`description`,`usd`,`foreign_currency_amount`,`foreign_currency`,`receipt`,`is_edited`,`created`,`modified`) VALUES ('$trip_id','$user_id','$type','$description','$price_usd','$price_other_currency','$currency','$receipt_name','0','$date','$date')";
              $this->Transaction->query($query);
              
              $query = "Update trips set `edit_blocked`='1' WHERE `id`='$trip_id'";
              $this->Trip->query($query);
              
              $messages[] = "Receipt added successfully";
              $response = array('status'=>'success','messages'=>$messages);
            }else{
              $messages[] = "There was an error in moving the receipt image to target directory.";
              $response = array('status'=>'failure','messages'=>$messages);
            }
            echo json_encode($response);
            exit();
          }
          exit();
        }
        
        function update_receipt(){
          $data = $this->request->data;
          //pr($_FILES);
          $file = $_FILES;
          $validation = $this->validateReceipt($data, $file);
          if($validation['status'] == "failure"){
            echo json_encode($validation);
            exit();
          }else{
            $user_id = $validation['user_id'];
            $existing_receipt = $validation['existing_receipt'];
            $receipt_id = trim($data['receipt_id']);
            $trip_id = trim($data['trip_id']);
            $price_other_currency = trim($data['price_other_currency']);
            $price_usd = trim($data['price_usd']);
            $currency = trim($data['currency']);
            $type= htmlspecialchars(trim($data['type']));
            //$user_name = trim($data['user_name']);
            $description = trim($data['description']);
            $description = htmlspecialchars($description);
            
            $receipt_date = trim($data['receipt_date']);
            $receipt_date = $this->form_date($receipt_date);
            //change this value for windows system

            //echo $path;exit();
            if(count($file)>0){
              $has_file = true;
            }else{
              $has_file = false;
            }
            if($has_file){
              $destination_dir = "receipts/".$trip_id."/";

              if($existing_receipt != ""){
                $existing_receipt = $destination_dir.$existing_receipt;
              }
              $flag = true;
            }else{
              $flag = true;
            } 
            if($flag){
            //either directory exists or successfully created
              //$date = Date("Y-m-d h:i:s");
              $date1 = gmdate("Y-m-d H:i:s");
              $date = $receipt_date;
              if($has_file){
                $receipt_name = $trip_id."_".time();
                $dd = explode(".",$file['receipt_image']['name']);
                $ext = end($dd);
                $file_ext = in_array($file['receipt_image']['type'],array('image/jpeg','image/jpg','image/pjpg'));
                $file2_ext = in_array($file['receipt_image']['type'],array('image/png'));
                
                if($file_ext){
                //jpg
                  $receipt_name = $receipt_name.".jpg";
                }elseif($file2_ext){
                  $receipt_name = $receipt_name.".png";
                }else{
                  $receipt_name = $receipt_name.".".$ext;
                }
                $destination = $destination_dir . $receipt_name;
                //$file_moved = move_uploaded_file($file['receipt_image']['tmp_name'], $destination );
                $file_moved = $this->Aws->uploadimg($file['receipt_image']['tmp_name'], $destination);
              }else{
                $file_moved = true;
              }  
              if($file_moved){
                if($has_file){
                  $query = "UPDATE transactions SET `type`='$type',`description`='$description',`usd`='$price_usd',`foreign_currency_amount`='$price_other_currency',`foreign_currency`='$currency',`receipt`='$receipt_name', `is_edited`='1', `created`='$date', `modified`='$date1' WHERE `id`='$receipt_id' ";
                }else{
                  $query = "UPDATE transactions SET `type`='$type',`description`='$description',`usd`='$price_usd',`foreign_currency_amount`='$price_other_currency',`foreign_currency`='$currency', `is_edited`='1', `created`='$date',`modified`='$date1' WHERE `id`='$receipt_id' ";
                }
                
                $this->Transaction->query($query);
                
                $query = "Update trips set `edit_blocked`='1' WHERE `id`='$trip_id'";
                $this->Trip->query($query);
                //echo $existing_receipt.
                if($existing_receipt != "" && $has_file==true){
                  $res_delete = $this->Aws->delete(array(array('Key' => $existing_receipt)));
                }
                
                $messages[] = "Receipt added successfully";
                $response = array('status'=>'success','messages'=>$messages);
              }else{
                $messages[] = "There was an error in moving the receipt image to target directory.";
                $response = array('status'=>'failure','messages'=>$messages);
              }
              echo json_encode($response);
              exit();
            }else{
              $messages[] = "There was an error in creating target directory for storing receipt images.";
              $out = array('status'=>'failure','messages'=>$messages);
              echo json_encode($out);
              exit();
            }
            //if(file_exists())
          }
          exit();
        }
        
        
        function validateReceipt($data, $file){
          $messages = array();
          $validfiles = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
          $trip_id = trim($data['trip_id']);
          $price_other_currency = trim($data['price_other_currency']);
          $price_usd = trim($data['price_usd']);
          $currency = trim($data['currency']);
          $type= trim($data['type']);
          //$user_name = trim($data['user_name']);
          $description = trim($data['description']);
          $first_name = htmlspecialchars(trim($data['first_name']));
          $middle_name = htmlspecialchars(trim($data['middle_name']));
          $last_name = htmlspecialchars(trim($data['last_name']));
          $receipt_id = htmlspecialchars(trim($data['receipt_id']));
          $receipt_date = trim($data['receipt_date']);
          //trip_id, price_other_currency, price_usd, currency, type, user_name, receipt_image, description.
          if($trip_id ==""){
           $messages[] = "Missing trip ID.";
          }else{
            $query = "SELECT * FROM trips WHERE `id`='$trip_id' AND `archived`='0'";
            
            $info = $this->Trip->query($query);
            //pr($info);exit();
            if($info[0]['trips']['id'] ==""){
              $messages[] = "Invalid trip ID or trip has been archived.";
            }
          }
          if($price_other_currency !="" && !($price_other_currency >0)){
            $messages[] = "Invalid other currency amount.";
          }
          if($price_usd == "" OR !($price_usd>0)){
            $messages[] = "Missing or Invalid USD price.";
          }
          if($type == ""){
            $messages[] = "Missing receipt type.";
          }
          if($receipt_id != ""){
              $query = "SELECT * FROM transactions WHERE `trip_id`='$trip_id' AND `id`= '$receipt_id'";
              $name = $this->Transaction->query($query);
              //pr($name);
              if($name[0]['transactions']['id'] !=""){
                $existing_receipt = $name[0]['transactions']['receipt'];
                
              }else{
                $messages[] = "No such receipt ID found.";
              }
              
          }else{
            if($first_name == "" OR $last_name == ""){
              $messages[] = "Missing first name or last name.";
            }else{
              $query = "SELECT * FROM users WHERE `trip_id`='$trip_id' AND (`first_name`= '$first_name' AND `middle_name` = '$middle_name' AND `last_name`= '$last_name') LIMIT 1";
              $name = $this->Trip->query($query);
              if($name[0]['users']['id'] !=""){
                $user_id = $name[0]['users']['id'];
              }else{
                $messages[] = "No such user found for this trip name. Input data, Trip ID:$trip_id First Name: $first_name Last Name: $last_name";
              }
              
            }
          
          } 
          if($description == ""){
            $messages[] = "Missing description.";
          }
          $len = strlen($receipt_date);
           
          if($receipt_date == ""){
            $messages[] = "Missing receipt date.";
          }elseif($len != "19"){
            $messages[] = "Receipt date should be in 'y-m-d hh:mm AM/PM' format only. Input date: $receipt_date";

          }
          if($receipt_id != "" && count($file['receipt_image'])>0){
            if($file['receipt_image']['error'] != 0){
              $messages[] = "Missing or invalid receipt image. Please try again.";
            }elseif(!(in_array($file['receipt_image']['type'], $validfiles))){
              $messages[] = "Invalid receipt file type. Please supply jpg or png files.";
            }
          }elseif($receipt_id == 0)  {
            if($file['receipt_image']['error'] != 0){
              $messages[] = "Missing or invalid receipt image. Please try again.";
            }elseif(!(in_array($file['receipt_image']['type'], $validfiles))){
              $messages[] = "Invalid receipt file type. Please supply jpg or png files.";
            }
          }
          if(count($messages)>0){
            $out = array('status'=>'failure', 'messages'=>$messages);
          }else{
          
            $out = array('status'=>'success','user_id'=>$user_id, 'existing_receipt'=>$existing_receipt);
          }
          return $out;
        }
        
        function get_budget_details(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            
            
            if($trip_id >0){
              $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
              );
              $fields = array('budget');
              $getData = $this->Trip->find('first', array('fields'=>$fields,'conditions'=>array('Trip.id'=>$trip_id)));
              if(count($getData)>0){
                //Finding total spent 
                $virtualFields = array('total' => 'SUM(Transaction.usd) AS total');
                $total = $this->Transaction->find('first', array('fields' => $virtualFields, 'conditions'=>array('Transaction.trip_id'=>$trip_id)));
                //pr($total);
                if($total[0]['total'] != ""){
                  $usd = round($total[0]['total'],2);
                }else{
                  $usd = "0.00";
                }
                $getData['Trip']['total_spent'] = $usd;
                $response['total_spent'] = $usd;
                $response['budget'] = $getData['Trip']['budget'];
                //$response = $getData;
                //pr($response);
                //exit();
              }else{
                $response = array('status'=>'failure','message'=>'No such trip found.');
                echo json_encode($response);
                exit();
              }
            }
            $response =  array('status'=>'success', 'trip_details'=>$response);
            echo json_encode($response);
            exit();
            
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
        }
        
        
        function get_trip_details(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            
            
            if($trip_id >0){
              $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
              );
              $this->Trip->bindModel(array(
                  'hasMany' => array(
                      'User' => array(
                          'className' => 'User',
                          /*'conditions' => array('User.type' => 0),*/
                          'fields' => array('id', 'first_name', 'middle_name', 'last_name', 'type','created')
                        )
                  )
              ));
              $getData = $this->Trip->find('first', array('conditions'=>array('Trip.id'=>$trip_id)));
              if(count($getData)>0){
                //Finding total spent 
                $virtualFields = array('total' => 'SUM(Transaction.usd) AS total');
                $total = $this->Transaction->find('first', array('fields' => $virtualFields, 'conditions'=>array('Transaction.trip_id'=>$trip_id)));
                //pr($total);
                if($total[0]['total'] != ""){
                  $usd = round($total[0]['total'],2);
                }else{
                  $usd = "0.00";
                }
                $getData['Trip']['total_spent'] = $usd;
                $response = $getData;
                //pr($response);
                //exit();
              }else{
                $response = array('status'=>'failure','message'=>'No such trip found.');
                echo json_encode($response);
                exit();
              }
            }
            $response =  array('status'=>'success', 'trip_details'=>$response);
            echo json_encode($response);
            exit();
            
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
        }
        
        function get_total_spent(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            if($trip_id >0){
              
              $virtualFields = array('total' => 'SUM(Transaction.usd) AS total');
              $total = $this->Transaction->find('first', array('fields' => $virtualFields, 'conditions'=>array('Transaction.trip_id'=>$trip_id)));
              //pr($total);
              if($total[0]['total'] != ""){
                $usd = round($total[0]['total'],2);
              }else{
                $usd = "0.00";
              }
              $response =  array('status'=>'success', 'total'=>"$usd");
              echo json_encode($response);
              exit();
            }
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
          echo json_encode($response);
          exit();
        
        
        }
        
        
        function delete_receipt(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $receipt_id = htmlspecialchars($data->receipt_id);
            if($receipt_id >0){
              //$virtualFields = array('receipt'=>'id');
              $receipt = $this->Transaction->find('first', array( 'conditions'=>array('Transaction.id'=>$receipt_id)));
              if(count($receipt)>0){
                $trip_id = $receipt['Transaction']['trip_id'];
                $existing_receipt = $receipt['Transaction']['receipt'];
                $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
                );
                $trip = $this->Trip->find('first', array( 'conditions'=>array('id'=>$trip_id,'archived'=>'0')));
                //pr($trip);
                //exit();
                if($trip['Trip']['archived'] == "0"){
                  $this->Transaction->delete($receipt_id);
                  $response =  array('status'=>'success', 'message'=>"Receipt deleted successfully");

                  $res_delete = $this->Aws->delete(array(array('Key' => "receipts/".$trip_id."/".$existing_receipt)));
                  //delete existing receipt Ids
                  /*$path = WWW_ROOT;
                  if(LIVE){
                  //for linux
                    $destination_dir = $path."receipts/".$trip_id;
                    
                    $destination_dir .= "/";
                    if($existing_receipt != ""){
                      $existing_receipt = $destination_dir.$existing_receipt;
                    }
                  }else{
                  //for windows
                    $destination_dir = $path."receipts\\".$trip_id;
                    
                    $destination_dir .= "\\";
                    if($existing_receipt != ""){
                      $existing_receipt = $destination_dir.$existing_receipt;
                    }
                  }
                  
                  if(file_exists($existing_receipt)){
                    unlink($existing_receipt);
                  }*/
                  
                }else{
                //Invalid trip ID or trip archived
                //"Invalid trip ID or trip has been archived.";
                  $response = array('status'=>'failure', 'message'=>"Invalid trip ID or trip has been archived.");
                }
                
                
              }else{
                $response = array('status'=>'failure', 'message'=>"No such receipt found");
              }
              echo json_encode($response);
              exit();
            }else{
              $response = array('status'=>'failure', 'message'=>'Invalid input data.');
            }
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
          echo json_encode($response);
          exit();
        
        
        }
        function clean_date(){
         //$dd = "2016-05-13 11:23:34";
         //2015-10-30 06:26:31
         $dd = "2015-10-30 06:26:31";
         $pp = strftime("%Y-%m-%d %I:%M %p", strtotime($dd));
         echo $pp; exit();
        }
        function get_all_receipts(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            if($trip_id >0){
              $join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      'conditions' => array(
                          'User.id = Transaction.user_id' ,
                      )
                  )
              );
              //$img_url = Router::fullBaseUrl()."/receipts/".$trip_id."/";
              $img_url = AWS_S3_BUCKET_PATH."/receipts/".$trip_id."/";

              //$this->Transaction->virtualFields['receipt_date'] = 'DATE(Transaction.created)';
              $fields = array('id','trip_id','user_id','User.first_name','User.middle_name','User.last_name','type','description','usd','foreign_currency_amount','foreign_currency','receipt','is_edited','created');
              $data = $this->Transaction->find('all', array('fields' => $fields,'joins'=>$join, 'conditions'=>array('Transaction.trip_id'=>$trip_id)));
              $total = count($data);
              //pr($data);
              for($i=0;$i<$total;$i++){
                $data[$i]['Transaction']['receipt'] = $img_url . $data[$i]['Transaction']['receipt'];
                $data[$i]['Transaction']['receipt_date'] = Date('Y-m-d h:i A',strtotime($data[$i]['Transaction']['created']));
              }
              
              $response =  array('status'=>'success', 'receipts'=>$data);
              echo json_encode($response);
              exit();
            }
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
          echo json_encode($response);
          exit();
        
        
        }
        
        function get_user_receipts(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            $first_name = htmlspecialchars($data->first_name);
            $middle_name = htmlspecialchars($data->middle_name);
            $last_name = htmlspecialchars($data->last_name);
            $message = "";
            if($trip_id >0){
              if($first_name == "" OR $last_name == ""){
                  $message = "Missing first name or last name.";
                }else{
                  $query = "SELECT * FROM users WHERE `trip_id`='$trip_id' AND (`first_name`= '$first_name' AND `middle_name` = '$middle_name' AND `last_name`= '$last_name') LIMIT 1";
                  $name = $this->Trip->query($query);
                  if($name[0]['users']['id'] !=""){
                    $user_id = $name[0]['users']['id'];
                  }else{
                    $message = "No such user found for this trip name";
                  }
              
              }
              if($message != ""){
                $response = array('status'=>'failure','message'=>$message);
                echo json_encode($response);
                exit();
              }
              //$img_url = Router::fullBaseUrl()."/receipts/".$trip_id."/";
              $img_url = AWS_S3_BUCKET_PATH."/receipts/".$trip_id."/";
              
              $join = array(
                  array('table' => 'users',
                      'alias' => 'User',
                      'type' => 'LEFT',
                      'conditions' => array(
                          'User.id = Transaction.user_id' ,
                      )
                  )
              );
              //$this->Transaction->virtualFields['receipt_date'] = 'DATE(Transaction.created)';
              
              $fields = array('id','trip_id','user_id','User.first_name','User.middle_name', 'User.last_name', 'type','description','usd','foreign_currency_amount','foreign_currency','receipt', 'is_edited','created');
              $data = $this->Transaction->find('all', array('fields' => $fields,'joins'=> $join, 'conditions'=>array('Transaction.trip_id'=>$trip_id, 'Transaction.user_id'=>$user_id)));
              $total = count($data);
              //pr($data);
              for($i=0;$i<$total;$i++){
                $data[$i]['Transaction']['receipt'] = $img_url . $data[$i]['Transaction']['receipt'];
                $data[$i]['Transaction']['receipt_date'] = Date('Y-m-d h:i A',strtotime($data[$i]['Transaction']['created']));
              }
              
              $response =  array('status'=>'success', 'receipts'=>$data);
              echo json_encode($response);
              exit();
            }
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
          echo json_encode($response);
          exit();
        
        
        }
        
        function get_user_details(){
        //configure::write("debug",2);
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            /*$join = array(
                  array('table' => 'transactions',
                      'alias' => 'Transaction',
                      'type' => 'LEFT',
                      'conditions' => array(
                          'Transaction.user_id = User.id'
                      )
                  )
              );
              $this->User->virtualFields['total_spent'] = 'sum(Transaction.usd)';
              //$trip_id = "1";
              $fields = array('User.id','User.first_name','User.middle_name', 'User.last_name', 'User.total_spent');
              $data = $this->User->find('all', array('fields'=>$fields, 'joins'=> $join, 'conditions'=>array('User.trip_id'=>$trip_id,'Transaction.trip_id'=>$trip_id),'group' => 'User.id'));
              */
              $query = "SELECT User.id, User.first_name, User.middle_name, User.last_name , (SELECT if( sum( t.usd ) >0, sum( t.usd ) , 0 ) FROM transactions t WHERE t.user_id = User.id) as `total_spent` FROM `users` User WHERE User.trip_id =$trip_id";
              $data = $this->User->query($query);
              //print "<pre>";
              //print_r($data);
              for ($i=0;$i<count($data);$i++){
                $dd[$i]['User'] = $data[$i]['User'];
                $dd[$i]['User']['total_spent'] = $data[$i][0]['total_spent'];
              }
              // print "<pre>";
              //print_r($dd);exit();
              $response =  array('status'=>'success', 'data'=>$dd);
              echo json_encode($response);
              exit();
              exit();
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
          echo json_encode($response);
          exit();
        }
        
        
        function validateLogin($trip_id, $passcode){
          $message = "";
          if($trip_id == "" && $passcode == ""){
            $message = "Missing trip_id & passcode.";
          }elseif($trip_id == ""){
            $message = "Missing trip_id.";
          }elseif($passcode == ""){
            $message = "Missing passcode.";
          }
          return $message;
        }
        
        function isArchived(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            
            
            if($trip_id >0){
              $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
              );
              $fields = array('archived');
              $getData = $this->Trip->find('first', array('fields'=>$fields,'conditions'=>array('Trip.id'=>$trip_id)));
              if(count($getData)>0){
                
                $response['archived'] = $getData['Trip']['archived'];
                //$response = $getData;
                //pr($response);
                //exit();
              }else{
                $response = array('status'=>'failure','message'=>'No such trip found.');
                echo json_encode($response);
                exit();
              }
            }else{
               $response = array('status'=>'failure','message'=>'No such trip found.');
               echo json_encode($response);
               exit();
            }
            $response =  array('status'=>'success', 'trip_details'=>$response);
            echo json_encode($response);
            exit();
            
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
          }
        }
        
       /**
       Total spent by a student for a given trip
       */
       function get_student_budget_details(){
          $data = json_decode( file_get_contents('php://input') );
          if(is_object($data)){
            $trip_id = htmlspecialchars($data->trip_id);
            $first_name = htmlspecialchars(trim($data->first_name));
            $middle_name = htmlspecialchars(trim($data->middle_name));
            $last_name = htmlspecialchars(trim($data->last_name));
            //print_r($_POST);exit();
            $messages = array();
            if($first_name == "" OR $last_name == ""){
              $messages[] = "Missing first name or last name.";
            }else{
              $query = "SELECT * FROM users WHERE `trip_id`='$trip_id' AND (`first_name`= '$first_name' AND `middle_name` = '$middle_name' AND `last_name`= '$last_name') LIMIT 1";
             
              $name = $this->Trip->query($query);
              if($name[0]['users']['id'] !=""){
                $user_id = $name[0]['users']['id'];
              }else{
                $messages[] = "No such user found for this trip name";
              }
              
            }
            if(count($messages)>0){
              $response = array('status'=>'failure', 'message'=>'No user found.');
              echo json_encode($response);
              exit();
            }
            if($trip_id >0){
              $this->Trip->unbindModel(
                 array('hasMany' => array('User'))
              );
              $fields = array('budget');
              $getData = $this->Trip->find('first', array('fields'=>$fields,'conditions'=>array('Trip.id'=>$trip_id)));
              if(count($getData)>0){
                //Finding total spent 
                $virtualFields = array('total' => 'SUM(Transaction.usd) AS total');
                $total = $this->Transaction->find('first', array('fields' => $virtualFields, 'conditions'=>array('Transaction.trip_id'=>$trip_id,'Transaction.user_id'=>$user_id)));
                //pr($total);
                if($total[0]['total'] != ""){
                  //$usd = round($total[0]['total'],2);
                  $usd = number_format($total[0]['total'], 2, ".","");
                }else{
                  $usd = "0.00";
                }
                $getData['Trip']['total_spent'] = $usd;
                $response['total_spent'] = $usd;
                //$response['budget'] = $getData['Trip']['budget'];
                //$response = $getData;
                //pr($response);
                //exit();
              }else{
                $response = array('status'=>'failure','message'=>'No such trip found.');
                echo json_encode($response);
                exit();
              }
            }
            $response =  array('status'=>'success', 'trip_details'=>$response);
            echo json_encode($response);
            exit();
            
          }else{
            $response = array('status'=>'failure', 'message'=>'Invalid input data.');
            echo json_encode($response);
            exit();
          }
          exit();
        }
        
        public function sk(){
          $receipt_date = "2016-03-04 12:03 AM";
          $len = strlen($receipt_date);
          
          if($receipt_date == ""){
            $messages[] = "Missing receipt date.";
          }elseif($len != "19"){
            $messages[] = "Receipt date should be in 'y-m-d hh:mm AM/PM' format only.";

          }
          
        }
        
        
}
?>