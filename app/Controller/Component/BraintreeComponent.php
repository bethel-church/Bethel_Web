<?php
App::import('Vendor','braintree' ,array('file'=>'braintree/lib/Braintree.php')); 
    
class BraintreeComponent extends Component {

     public function __construct() {
		/************************************************************/
	# call model for getting dynamic values
		$setting = ClassRegistry::init('Setting');
		$settingData = $setting->find('first',array('conditions'=>array('id'=>1)));

	/************************************************************/
	if(!empty($settingData)){
	if($settingData['Setting']['braintree_environment'] == 0){ $mode = "sandbox"; }else{ $mode = "live"; }
	
         Braintree_Configuration::environment($mode);
         Braintree_Configuration::merchantId($settingData['Setting']['braintree_merchant_id']);
         Braintree_Configuration::publicKey($settingData['Setting']['braintree_public_key']);
         Braintree_Configuration::privateKey($settingData['Setting']['braintree_private_key']);
      }
	 }
      /*
        Function Name:  doDirectPayment
        Functionality:  do direct payment using BrainTree Payment gateway
        @params:        
      */
     function doDirectPayment($order_data=array()){

        $customer_details  =  $order_data['customer_details'];
        $card_details      =  $order_data['card_details'];
        $amount            =  $order_data['amount'];
        $storeInVault      =  $order_data['storeInVault'];
	$billing_address      =  $order_data['billing_address'];
        if(empty($storeInVault)){
          $storeInVault = true;
        }

       $customer   =   Braintree_Customer::create($customer_details)->customer;

        //Verify and check if the credit card is expired        

        $card_result = Braintree_CreditCard::create(array(
                        'customerId' => $customer->id,
                        'cardholderName' => $card_details['cardholderName'],
			'cvv' => $card_details['cvv'],
                        'number' => $card_details['number'],
                        'expirationDate' => $card_details['expirationDate'],
			'billingAddress' => $billing_address ,
                        'options' => array('verifyCard' => true
                                    )
                        ));
    
	//pr($card_result);
	if (empty($card_result->success) && $card_result->success!=1)
        {
	    $result['status']         =  0;
	    $result['error_message']  =  $card_result->_attributes['message'];
	    return $result;
        }
 
        $currentYear = date('Y');
        $currentMonth = date('m');
        $customerdatedata = explode('/',$card_details['expirationDate']);

        if (($customerdatedata[1] == $currentYear) && ($customerdatedata[0] < $currentMonth) )
        {
       
              $result['status']         =  0;
              $result['error_message']  =  'Your credit card is expired.';
        }
	   
        else {

		$transaction = Braintree_Transaction::sale(array(
		'amount' => $amount,
		'customerId' => $customer->id,
		'creditCard' => $card_details,
		'billing' => $billing_address ,
		'options' => array(
		'storeInVault' => $storeInVault,
		'addBillingAddressToPaymentMethod' => false,
		'submitForSettlement' => true
		)
		))->transaction;
	    //pr($transaction);die;
            if(isset($transaction) && !empty($transaction)){
              $result['token'] = $transaction->creditCard['token'];
            }
               
	    if(isset($transaction) && !empty($transaction) && isset($transaction->status) && $transaction->status=='submitted_for_settlement'){
                $result['status'] = 1;
	    } else {
               $result['status'] = 0;
	       $result['error_message'] = 'Please enter valid credit card details.';
	    }
        }
      
        return $result;
        /*
        echo "Token Here:";
        echo $token      =   $transaction->creditCard['token'];
        echo $transaction->status;
        echo "<pre>";
        print_r($transaction);
        exit;
        */
     }
     /*
      Function Name:  fetchCardDetails
      Functionality:  to fetch the Card Detauls from Braintree based on the token no.
      @params:        $token
    */    
     function fetchCardDetails($token=''){
        if(!empty($token)){
            $creditCardDetails    = Braintree_CreditCard::find($token);                        
            if(!$creditCardDetails){
                $result['status']        = 0;
                $result['error_message'] = 'Credit card with provided token does not exists.';
            }
            else{                               
                $arrCredeitCardDetails   = array();
                $arrCredeitCardDetails['cardType']        = $creditCardDetails->cardType;
                $arrCredeitCardDetails['maskedNumber']    = $creditCardDetails->maskedNumber;
                $arrCredeitCardDetails['expirationMonth'] = $creditCardDetails->expirationMonth;
                $arrCredeitCardDetails['expirationYear']  = $creditCardDetails->expirationYear;
                $arrCredeitCardDetails['token']  = $token;
                $result['status']        = 1;
                $result['arrCredeitCardDetails'] = $arrCredeitCardDetails;
            }    
            return $result;                        
        }
        else{
            $result['status']        = 0;
            $result['error_message'] = 'Please enter the Credit card token no.';
        }
     }
     /*
      Function Name:  payByToken
      Functionality:  to do payment based on the token no. of the saved credit card on Braintree
      @params:        $orderDetails
    */
     function payByToken($orderDetails=array()){        
          $token       =  $orderDetails['token'];
          $amount      =  $orderDetails['amount'];
          $transaction =  Braintree_CreditCard::sale($token, array('amount' =>$amount,'options'=>array('submitForSettlement' => true)));
          
          if($transaction->success==1){
              $result['status'] = 1;
			  $result['transaction_id'] = $transaction->transaction->_attributes['id'];
          }
          else{
              $result['status']        = 0;
              $result['error_message'] = $transaction->message;              
          }
          return $result;
          /*
          print_r($transaction);
          echo "<pre>";
          print_r($result);
          exit;
          */
          /**/
    }
    
    
     /*
      Function Name:  addCreditCard
      Functionality:  add credit card for the existing customer using braintree
      @params:        customerCreditcardData
    */
     function addCreditCard($customerCreditcardData=array()){        
	
	$transaction = Braintree_Customer::create(array(
			    'firstName' => $customerCreditcardData['billing_name'],
			    'lastName' => $customerCreditcardData['last_name'],
			    'creditCard' => array(
				'cardholderName' => $customerCreditcardData['name_on_card'],
				'number' => $customerCreditcardData['cc_number'],
				'cvv' => $customerCreditcardData['cvv'],
				'expirationDate' => $customerCreditcardData['exp_month'].'/'.$customerCreditcardData['exp_year'],
				'billingAddress' => array(
				    'postalCode' => $customerCreditcardData['billing_zip']
				),
				'options' => array(
				    'verifyCard' => true
				)
			    )
			));
	
	    if($transaction->success==1){
              $result['status'] = 1;
				$result['token'] = $transaction->customer->creditCards[0]->_attributes['token'];
	      
	      
          }
          else{
              $result['status']        = 0;
              $result['error_message'] = $transaction->message;              
          }
	 
          return $result;
          
    }    
     /*
      Function Name:  updateCreditCard
      Functionality:  update the existing credit card for the existing customer using braintree
      @params:        customerCreditcardData
    */
    function updateCreditCard($token=NULL, $customerCreditcardData=array()){
	
	$transaction = Braintree_CreditCard::update($token,$customerCreditcardData);
	
	if($transaction->success==1){
              $result['status'] = 1;
	} else {
            $result['status']        = 0;
            $result['error_message'] = $transaction->message;
	    //prx($transaction->message);
        }
	return $result;	
    }        
    
    /*
    Function Name:  deleteCreditCard
    Functionality:  delete the credit card of customer using braintree
    Author:        Inderjit Singh
    Date:          20 Dec 2013
    @params:        $token
    */
    function deleteCreditCard($token=array()){
	
	$transaction = Braintree_CreditCard::delete($token);

	if($transaction->success==1){
	
	    $result['status'] = 1;
	    $result['message'] = 'Card has been deteled';
	    
	} else {
	    $result['status']        = 0;
            $result['error_message'] = $transaction->message;
	}
	
	return $result;
    }
    
}