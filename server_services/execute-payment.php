<?php

// Import file
require_once("functions.php");

// get params
$paymentID = $_POST["paymentID"];
$payerID = $_POST["payerID"];

$accessToken = get_access_token();
$response = execute_payment($accessToken, $payerID,  $paymentID);

header('Content-Type: application/json');
echo $response;

?>