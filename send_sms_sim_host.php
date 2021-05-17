<?php
/**
 * How to use this script:
 * - This script is designed to help you code-in sending SMS to you PHP application.
 * - The functions can be copied and inserted in your existing script. You can use either of the two methods.
 * - The script implements POST via cUrl.
 * - If so desire, you can run script from your server after you have added all the needed parameters to ensure that you get the right result.
 */




//Use the variables below to hold your values. 
$message = 'Hello Adewale';
$senderid = 'AZEEZ';
$recipients = '08023130565';
$token = 'DyrX8D7cfdotvRREIW5ONUa5IVUoWqd5QNk6i6ZRAZS7c0KX7dbJKMXtTAyHYZUbPCiYw5uVrtwg2IgFgJFWIqBxGVK4wWBCHJjo';        //The generated code from api-x token page
$url = 'https://smartsmssolutions.com/api/';


$sms_array = array (
                'sender'    => $senderid,
                'to' => $recipients,
                'message'   => $message,
                'type'  => '0',          //This can be set as desired. 0 = Plain text ie the normal SMS
                'routing' => '3',         //This can be set as desired. 3 = Deliver message to DND phone numbers via the corporate route
                'token' => $token
            );

echo $message."<BR>";
echo $senderid."<BR>";
echo $recipients."<BR>";
echo $token."<BR>";
//Call sendsms_post function to send SMS        
$response = sendsms_post($url, $sms_array);

//Echo the reply
echo $response;

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