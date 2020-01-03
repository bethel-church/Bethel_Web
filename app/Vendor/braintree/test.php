<?php  
    require_once '/viren/webroot/restaurantorder/app/webroot/braintree/example_2/lib/Braintree.php';

    Braintree_Configuration::environment('sandbox');
    Braintree_Configuration::merchantId('zcbgv6pp6kgpy5nr');
    Braintree_Configuration::publicKey('z3s6y475x72t5c4c');
    Braintree_Configuration::privateKey('hmbdrz8nj552cdtc');
    /* Step1:Do Transaction & save credit card into the braintree vault and receive a token for the credit card*/
   /*
    $customer = Braintree_Customer::create(array(
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ))->customer;

        $transaction = Braintree_Transaction::sale(array(
            'amount' => '100.00',
            'customerId' => $customer->id,
            'creditCard' => array(
                'cardholderName' => 'The Cardholder',
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ),
            'options' => array(
                'storeInVault' => true
            )
        ))->transaction;
    echo "<pre>";
    print_r($transaction);
    echo $token  = $transaction->creditCard['token'];
    exit;
     */
    /* Step2: Get the Credit card details based on the token received in Step1 */
    /*
    $token  = '6wz6y';
    $creditCardDetails  = Braintree_CreditCard::find($token);
   echo "<pre>";
   print_r($creditCardDetails);
   exit;*/
    
    /* Step3: To Transaction / sale with a existing token received in the step1*/
    
    $token  = '6wz6y';    
   
   $result = Braintree_CreditCard::sale($token, array(
            'amount' => '200.00'
        ));
    
    echo "<pre>";
    print_r($result);
    
    exit;/**/
    if ($result->success) {
        print_r("success!: " . $result->transaction->id);
    } else if ($result->transaction) {
        print_r("Error processing transaction:");
        print_r("\n  code: " . $result->transaction->processorResponseCode);
        print_r("\n  text: " . $result->transaction->processorResponseText);
    } else {
        print_r("Validation errors: \n");
        print_r($result->errors->deepAll());
    }

?>