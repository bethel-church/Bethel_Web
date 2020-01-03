<?php
    /*
        * Admins Controller class
        * Functionality -  Manage the admin login,listing,add 
        
        * Modified date - 
    */
    	App::uses('Sanitize', 'Utility'); 
    class AdminsController extends AppController {
	
        var $name = 'Admins';        
        var $components = array('Email','Cookie','Common','Paginator');
		   
        function beforeFilter(){
        
            parent::beforeFilter();    
            
        }        
       
        /*
            * admin_addedit function
            * Functionality -  Add & edit the admin profile
            * Developer - Navdeep
            * Created date - 11-Feb-2014
            * Modified date - 
        */		
        function admin_changepassword()
        {
			if(isset($this->request->data) && !empty($this->request->data))
			{	
				$id = $this->Session->read('loggedUserInfo.id');				
				$userInfo = $this->Admin->find('first',array('fields'=>array('id','password','email','first_name','last_name'),'conditions'=>array("Admin.id" => $id)));				
				$Oldassword  = md5(trim($this->request->data['Admin']['old_password']));
				
				if(!empty($userInfo['Admin']['password']) && ($userInfo['Admin']['password'] == $Oldassword) ) {
					unset($this->request->data['Admin']['old_password']);
					unset($this->request->data['Admin']['confirm_password']);
					$this->request->data['Admin']['id'] = $id;
					$this->request->data['Admin']['password'] = md5(trim($this->request->data['Admin']['password']));
					if($this->Admin->save($this->request->data))
					{
						# cakehphp email code, if you have set up of emailtemplates {
						//App::import('Model','Emailtemplate');
						//$this->Emailtemplate = new Emailtemplate;
						//$SITE_URL = Configure::read('BASE_URL');
						//
						////$active =  '<a href = "' .$SITE_URL. '/admin/admins/verify/'.sha1($hashCode).'">Link </a>'; 
						//$template = $this->Emailtemplate->getEmailTemplate('change_admin_password');
						//$to = $userInfo['Admin']['email'];
						//$data1 = $template['Emailtemplate']['template'];					
						//
						//$data1 = str_replace('{FirstName}',ucfirst($userInfo['Admin']['first_name']),$data1);
						//$data1 = str_replace('{LastName}',ucfirst($userInfo['Admin']['last_name']),$data1);
						//$data1 = str_replace('{Email}',$userInfo['Admin']['email'],$data1);			
						//$subject = ucfirst(str_replace('_', ' ', $template['Emailtemplate']['name']));
						//$send_mail = $this->sendEmail($to,$subject,$data1);
					//}
						$this->Session->setFlash("Password has been updated successfully.",'default',array('class'=>'alert alert-success'));
						}
				}else{
					$this->Session->setFlash("Entered old password does not match.Please try again.",'default',array('class'=>'alert alert-danger'));
					}
					
					$this->redirect(array('action' => 'changepassword'));
			}
			
			
		
			$this->set('breadcrumb','Change Password');
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
			//print "<pre>";print_r($data);
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
	
	
	/*
		* admin_delete function
		* Functionality - delete newsletter
		* Developer - Navdeep kaur
		* Created date - 16-Apr-2014
		* Modified date - 
	*/       
	function admin_settings()
	{
		$this->loadModel('Setting');
		$id = 1;
			if(empty($this->request->data)){
				$this->request->data = $this->Setting->read(null, 1);			
			}else
			if(isset($this->request->data) && !empty($this->request->data))
			{  $this->request->data['Setting']['id'] = base64_decode($this->request->data['Setting']['id']);
				$this->Setting->set($this->request->data);	
				if($this->Setting->validates()) 				
				{ //pr($this->request->data); die;
										
					if($this->Setting->save($this->request->data))
					{ 	
						$this->Session->setFlash("Settings saved sucessfully.",'default',array('class'=>'alert alert-success'));
						
						$this->redirect($this->referer());
					}
				}    
			}
			$textAction = ($id == null) ? 'Add' : 'Edit';			
			$this->set('navcategory','class = "active"');			
			$this->set('action',$textAction);			
			$this->set('breadcrumb','Settings/'.$textAction);
	}
	
		function admin_test_page(){
			$this->set('breadcrumb','Test Payment Page');
			$this->loadModel('Order');
			$this->loadModel('OrderDetail');
			 $flag=0;
			// Calling arrays
			$this->getCountries();
			if(!empty($this->request->data)){
                    
                    if(isset($this->request->data['Order']) && !empty($this->request->data['Order']))
                    {
						if( isset($this->request->data['Order']['payment_method']) && $this->request->data['Order']['payment_method'] == 1){
							$cardInfo = $this->request->data['Order'];
						
							$result= $this->Braintree->addCreditCard($cardInfo);
							//pr($result); die;
							if($result['status'] ==1){
								$token = $result['token'];
								$tokendetails['token'] = $token;
								$tokendetails['amount'] = $cardInfo['amount'];
								$status = $this->Braintree->payByToken($tokendetails);
								if($status['status'] == 1){
									$flag = 1;
									$this->request->data['Order']['transaction_id'] = $status['transaction_id'];
									$transId = $status['transaction_id'];
								}
							}else{
								$flag = 0;
								$this->Session->setFlash($result['error_message'],'default',array('class'=>'alert alert-danger'));
							}
							
							//pr($status); die;
							
						}else{
						
                        $paymentType = urlencode('Sale');				// or 'Sale'
                        $firstName = urlencode($this->request->data['Order']['billing_name']);
                        $lastName = ""; //urlencode($this->request->data['User']['lastname']);
                        $creditCardType = urlencode($this->request->data['Order']['card_type']);
                        $creditCardNumber = urlencode($this->request->data['Order']['cc_number']);
                        //$creditCardNumber = urlencode('4716796482327012');
                        
                        $expDateMonth = $this->request->data['Order']['exp_month'];
                        // Month must be padded with leading zero
                        $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
                        
                        $expDateYear = urlencode($this->request->data['Order']['exp_year']);
                        $cvv2Number = urlencode($this->request->data['Order']['cvv']);
                        //$cvv2Number = urlencode('000');
                        $address1 = urlencode($this->request->data['Order']['billing_street_1']);
                        $address2 = urlencode($this->request->data['Order']['billing_street_2']);
                        $city = urlencode($this->request->data['Order']['billing_city']);
                        $state = ""; // urlencode($this->request->data['OrderDetail']['billing_state']);
                        $zip = urlencode($this->request->data['Order']['billing_zip']);
                        $country = urlencode('US');// US or other valid country code
                        $amount = urlencode($this->request->data['Order']['amount']);
						
                        //$amount = urlencode(1);
                        $currencyID = urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
                        
                        // Add request-specific fields to the request string.
						if(isset($this->request->data['Order']['is_recurring']) && $this->request->data['Order']['is_recurring'] == 1){
							$date = $this->convertDataFormat($this->request->data['Order']['payment_date']);
							$newdate= $date."T00:00:00Z";
							$startDate =urlencode($newdate);
							$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
                                "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
                                "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate&DESC=test&BILLINGPERIOD=Month&BILLINGFREQUENCY=1";
							/*******request to api for recurring payment**********/							
							$httpParsedResponseAr = $this->Paypal->PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);
							
						}else{ 
							$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
                                "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
                                "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
								/*******request to api for recurring payment**********/							
							$httpParsedResponseAr = $this->Paypal->PPHttpPost('DoDirectPayment', $nvpStr);
							
						}
                        
                        
                    
					 
						
                         
                        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                       
							if(isset($this->request->data['Order']['is_recurring']) && $this->request->data['Order']['is_recurring'] == 1){
								$this->request->data['Order']['profile_id'] = urldecode($httpParsedResponseAr['PROFILEID']);
							}else{
								$this->request->data['Order']['transaction_id'] = $httpParsedResponseAr['TRANSACTIONID'];
							}
							$transId = $httpParsedResponseAr['TRANSACTIONID'];
                             $flag = 1;
                        }else{
                            
                            $msg = str_replace("%20", " ",$httpParsedResponseAr['L_LONGMESSAGE0']);
                            $msg = str_replace("%2e", " ",$msg);
                            $this->request->data['Order']['payment_date']="";
                            
							$this->Session->setFlash($msg,'default',array('class'=>'alert alert-danger'));
							//$this->redirect(array("controller" => "admins", "action" => "test_page"));
                        }
						}
                    }else{
                        $flag=0;
                    }
                    //	die;
                    if($flag == 1){
//                                $ccNumber = str_repeat('x', (strlen($this->request->data['Order']['cc_number']) - 4)) . substr($this->request->data['Order']['cc_number'],-4,4);
//                                $this->request->data['Order']['cc_number'] = $ccNumber;                               
//								$this->request->data['Order']['order_no'] = '130';
//								$this->request->data['Order']['payment_status'] = 1;
//								$this->request->data['Order']['order_status'] = 0;
//								//Default company id
//								$this->request->data['Order']['company_id'] = 1;
//                        if($this->Order->save($this->request->data)){ 
//                            $orderId = $this->Order->id;
//                            if(isset($this->request->data['OrderDetail']) && !empty($this->request->data['OrderDetail'])){
//                                /*-------------------------------for save payment deatail----------------------------*/
//                                $this->request->data['OrderDetail']['order_id'] = $orderId;
//                                
//                                $this->OrderDetail->save($this->request->data);
//                                 /*-------------------------------end save payment deatail----------------------------*/
//                            }
//                           		
//                   
//                            
//                        }
						$this->Session->setFlash('Order proccesed successfully. Transaction id is '. $transId,'default',array('class'=>'alert alert-success'));	
                        $this->redirect(array('controller'=>'admins','action'=>'test_page'));
                    }
                } 
		}
		/* Convert date 01/02/2014 to 2014-02-01 Format */
        function convertDataFormat($date = null)
		{
			$dateArray =  explode('/', $date);			
			return $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0] ;			
		}
		function admin_setnewStatus($id,$status,$model){
			$this->loadModel($model);
		
			$this->request->data[$model]['id'] = $id;
			$this->request->data[$model]['status'] = $status;
			if($this->$model->save($this->request->data,false)){
				echo $status; exit;
			}
			
			
		}
		function admin_braintree_payment(){
			$this->layout ='';
			$this->set('breadcrumb','Braintree Payment');
			if(!empty($this->request->data)){
				
				
				$cardInfo = $this->request->data['Order'];
				
				$result= $this->Braintree->addCreditCard($cardInfo);
				//pr($result); die;
				if($result['status'] ==1){
					$token = "6gybv6";
					$amt = 1;
				}
				$tokendetails['token'] = $token;
				$tokendetails['amount'] = 1;
				$status = $this->Braintree->payByToken($tokendetails);
				pr($status); die;
				
			}
			
		}
		function payment_test(){
			$this->layout ='';
			$this->autoRender=false;
		   $this->Braintree= & new BraintreeComponent();
		
		//code to create Custome account and save Vault
						
		/* 
		$result = Braintree_Customer::create(array(
			"firstName" => 'Shubham',
			"lastName" => 'Monga',
			"creditCard" => array(
				"number" => '5105105105105100',
				"expirationMonth" => '12',
				"expirationYear" => '2020',
				"cvv" => '123',
				"billingAddress" => array(
					"postalCode" => '213654'
				)
			)
		));
		
		if ($result->success) {
			echo("Success! Customer ID: " . $result->customer->id . "<br/>");
			echo("<a href='./subscription.php?customer_id=" . $result->customer->id . "'>Create subscription for this customer</a>");
		} else {
			echo("Validation errors:<br/>");
			foreach (($result->errors->deepAll()) as $error) {
				echo("- " . $error->message . "<br/>");
			}
		}*/
		
		$token ='bxybs6';   $subscription = 'gvcp7r';
		$cust_id = '78616836';
		//Braintree_Customer::delete($cust_id);   echo 'deleted';die;
		//code to create subscription plan with help of cust ID 
		
		try {
			$customer_id = $cust_id;
			$customer = Braintree_Customer::find($customer_id);
			$payment_method_token = $customer->creditCards[0]->token;
		
		   $result = Braintree_Subscription::create(array(
				'paymentMethodToken' => $payment_method_token,
				'planId' => 'pt'
			));   
			
			/*     $result = Braintree_Subscription::create(array(
					'paymentMethodToken' => $payment_method_token,
					'price'=> '200',
					'planId' => 'gym',
					// addone code start
					'addOns' => array(
							'add' => array(
								array(
									 'inheritedFromId' =>'additional_pt',
									'amount' => $add_on_price,
									'quantity' => $quantity
								)
							)
						)
				   // addon code ends here.
				));    */
					
		
			if ($result->success) {
				echo("Success! Subscription " . $result->subscription->id . " is " . $result->subscription->status);
			} else {
				echo("Validation errors:<br/>");
				foreach (($result->errors->deepAll()) as $error) {
					echo("- " . $error->message . "<br/>");
				}
			}
		} catch (Braintree_Exception_NotFound $e) {
			echo("Failure: no customer found with ID " . $cust_id);
		}

}


		function admin_sendMessage(){
			if(!empty($this->request->data) && isset($this->request->data)){		
	//echo "<pre>"; print_r($this->request->data); die;
				$from = "+15413726045";
				
				
				$message = $this->request->data['SendMessage']['message'];
				$to = $this->request->data['SendMessage']['message_to'];
				
				$this->sms($from, $to, $message);
	
			}
			$this->set('breadcrumb','Settings');
		}
		function sms($from, $to, $message)
		{
			$response = $this->Twilio->sms($from, $to, $message);
			
			if($response->IsError){
				$this->Session->setFlash($response->ErrorMessage,'default',array('class'=>'alert alert-danger'));
				//echo 'Error: ' . $response->ErrorMessage;
			} else {
				$this->Session->setFlash('Message sent successfully','success');
				//echo 'Sent message to ' . $to;
			}
		}
		function admin_twilio_settings(){
			$this->loadModel('Setting');
			$id = 1;
			if(empty($this->request->data)){
				$this->request->data = $this->Setting->read(null, 1);			
			}
			$this->set('breadcrumb','Settings');
		}
		function no_access(){
		
		}
		

}
?>