<?php

$paypalMode = "sandbox";

$host = "";

$api_clientId = "";
$api_secret = "";

$return_url = "";
$cancel_url = "";

if ($paypalMode == "sandbox") {

    $host = 'https://api.sandbox.paypal.com';

    $api_clientId = "AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v";
    $api_secret = "EBoFU50fW9Bd0-VM0eDMihyDMlt-fyxrGzOcjKspOF_dcYYz2DwJYoStqM8mCcA1yESJjZSB5il4WwWl";

    $return_url = "http://www.example.com/index.html";
    $cancel_url = "http://www.example.com/index.html";

}

if ($paypalMode == "production") {

    $host = 'https://api.paypal.com';

    $api_clientId = ""; 
    $api_secret = ""; 

    $return_url = "";
    $cancel_url = "";

}








