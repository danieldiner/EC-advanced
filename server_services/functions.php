<?php

require_once("config.php");

#This function gets the OAuth2 Access Token which will be valid for 28800 seconds
function get_access_token() {

  global $api_clientId, $api_secret, $host;

  $postFields = 'grant_type=client_credentials';
  $url = $host.'/v1/oauth2/token';

  // curl documentation -> http://php.net/manual/en/book.curl.php

  $curl = curl_init($url); // Initializes a new session and return a cURL handle for use with the curl_setopt(), curl_exec(), and curl_close() functions.

  // curl_setopt documentation -> http://php.net/manual/en/function.curl-setopt.php

  curl_setopt($curl, CURLOPT_POST, true); // TRUE to do a regular HTTP POST.
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FALSE to stop cURL from verifying the peer's certificate.
  curl_setopt($curl, CURLOPT_USERPWD, $api_clientId . ":" . $api_secret); // A username and password formatted as "[username]:[password]" to use for the connection.
  curl_setopt($curl, CURLOPT_HEADER, false); // TRUE to include the header in the output.
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
  curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields); //The full data to post in a HTTP "POST" operation.
  #curl_setopt($curl, CURLOPT_VERBOSE, TRUE); // TRUE to output verbose information. Writes output to STDERR, or the file specified using CURLOPT_STDERR.

  $response = curl_exec( $curl ); // Returns TRUE on success or FALSE on failure. However, if the CURLOPT_RETURNTRANSFER option is set, it will return the result on success, FALSE on failure.

  if (empty($response)) {
    // Some kind of an error happened
    die(curl_error($curl)); // The die() function prints a message and exits the current script. This function is an alias of the exit() function.
    curl_close($curl); // Closes a cURL session and frees all resources. The cURL handle, $curl, is also deleted.
  } else {
    $info = curl_getinfo($curl); // Gets information about the last transfer.
    curl_close($curl); // Closes a cURL session and frees all resources. The cURL handle, $curl, is also deleted.
    
    if ($info['http_code'] != 200 && $info['http_code'] != 201 ) {
      echo "Received error: " . $info['http_code']. "\n";
      echo "Raw response:".$response."\n";
      die();
    }
    
  }

  // Convert the result from JSON format to a PHP array
  $jsonResponse = json_decode( $response );
  return $jsonResponse->access_token;

}

#This function creates a payment using data provided
function create_payment( $access_token, $c_transactions ) {

  global $host;

  $postdata = get_json_payment( $c_transactions );

  $url = $host.'/v1/payments/payment';
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true); // TRUE to do a regular HTTP POST.
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FALSE to stop cURL from verifying the peer's certificate.
  curl_setopt($curl, CURLOPT_HEADER, false); // TRUE to include the header in the output.
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer '.$access_token,
    'Accept: application/json',
    'Content-Type: application/json'
  )); // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
  curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
  #curl_setopt($curl, CURLOPT_VERBOSE, TRUE);

  $response = curl_exec( $curl );

  if (empty($response)) {
    // Some kind of an error happened
    die(curl_error($curl));
    curl_close($curl);
  } else {

    $info = curl_getinfo($curl);
    curl_close($curl); // close cURL handler
    
    if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
      echo "Received error: " . $info['http_code']. "\n";
      echo "Raw response:".$response."\n";
      die();
    }

  }

  // Convert the result from JSON format to a PHP array
  $jsonResponse = json_decode($response, TRUE);
  return $jsonResponse;

}

#This function executes a payment once the client has accepted and payer id is available
function execute_payment($access_token, $payerId,  $paymentID) {

  global $host;
  $url = $host.'/v1/payments/payment/'.$paymentID.'/execute/';

  $postdata= '{"payer_id" : "'.$payerId.'"}';

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer '.$access_token,
    'Content-Type: application/json'
  )); // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
  curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
  #curl_setopt($curl, CURLOPT_VERBOSE, TRUE);

  $response = curl_exec( $curl );

  if (empty($response)) {
    // Some kind of an error happened
    die(curl_error($curl));
    curl_close($curl);
  } else {
    $info = curl_getinfo($curl);
    curl_close($curl); 

    if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
      return $response;
    }

  }

  return $response;

}

#Function help  to create a json of the payment info
function get_json_payment( $c_transactions) {

  global $return_url, $cancel_url;

  $payment = '{
                "intent": "sale",
                "redirect_urls":
                {
                  "return_url": "'.$return_url.'",
                  "cancel_url": "'.$cancel_url.'"
                },
                "payer":
                {
                  "payment_method": "paypal"
                },
                "transactions": ['.$c_transactions.']   
              }';

  return $payment;

}