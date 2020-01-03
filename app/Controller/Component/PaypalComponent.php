<?php

/** DoDirectPayment NVP example; last modified 08MAY23.
 *
 *  Process a credit card payment. 
*/
class PaypalComponent extends Component {

	function PPHttpPost($methodName_, $nvpStr_) {
	//global $environment;

	// Set up your API credentials, PayPal end point, and API version.
	/*$API_UserName = urlencode('tbomann_api1.new.rr.com');
	$API_Password = urlencode('3ZXW7DVYZG4AAAVU');
	$API_Signature = urlencode('A98Wj2hFFEXXhaHm6RcWlTxHt4JaAJ8ULwTdwh5vgZ5Vo9NJDbqEkzWI');*/
	/************************************************************/
	# call model for getting dynamic values
		$setting = ClassRegistry::init('Setting');
		$settingData = $setting->find('first',array('conditions'=>array('id'=>1)));

	/************************************************************/
	if($settingData['Setting']['enviroment'] == 0){ $mode = "sandbox"; }else{ $mode = "live"; }
	$environment = $mode;	// or 'beta-sandbox' or 'live'
	$API_UserName = urlencode($settingData['Setting']['api_user']);
	$API_Password = urlencode($settingData['Setting']['api_pass']);
	$API_Signature = urlencode($settingData['Setting']['api_sign']);
	//echo $environment; die;
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
	
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('56.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}
}
?>