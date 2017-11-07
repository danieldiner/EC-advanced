<?php

// Import file
require_once("functions.php");

// get params
$transactions = $_POST["transactions"];

// get access token using get_access_token() function in functions.php
$access_token = get_access_token();

// get payment response by executing create_payment() function declared in functions.php
$payment = create_payment( $access_token, $transactions );

$pay_id = $payment['id'];

header('Content-Type: application/json');
echo '{"paymentID":"'.$pay_id.'"} ';

?>