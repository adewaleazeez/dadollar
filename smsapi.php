<?php

$message = 'Test message';
$senderid = 'AZEEZ';
$to = '08023130565';
$token = 'DyrX8D7cfdotvRREIW5ONUa5IVUoWqd5QNk6i6ZRAZS7c0KX7dbJKMXtTAyHYZUbPCiYw5uVrtwg2IgFgJFWIqBxGVK4wWBCHJjo';
$baseurl = 'https://smartsmssolutions.com/api/json.php?';

$sms_array = array 
  (
  'sender' => $senderid,
  'to' => $to,
  'message' => $message,
  'type' => '0',
  'routing' => 3,
  'token' => $token
);

$params = http_build_query($sms_array);
$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL,$baseurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

$response = curl_exec($ch);

curl_close($ch);

echo $response; // response code

//Or to validate by calling the validate_sendsms function
var_dump(validate_sendsms($response));





//VALIDATION: If you need to validate if the message is send successfully you call the function below.
function validate_sendsms ($response) {
    $validate = explode('||', $response);
    if ($validate[0] == '1000') {
        return TRUE;
        //return custom response here instead.
    } else {
        return FALSE;
        //return custom response here instead.
    }
}


function sendsms_post ($url, array $params) {
    $params = http_build_query($params);
    $ch = curl_init(); 
    
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);   
 
    $output=curl_exec($ch);
 
    curl_close($ch);

    return $output;        
}
?>