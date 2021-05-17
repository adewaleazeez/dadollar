<?php
function sendsms($senderid,$message,$to,$token,$routing,$type) {
    $baseurl = 'https://smartsmssolutions.com/api/json.php';
    $payloads = array ('sender' => $senderid, 'to' => $to, 'message' => $message, 'type' => $type, 'routing' => $routing, 'token' => $token);

    $params = http_build_query($payloads);
    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL,$baseurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    $response = curl_exec($ch);
if(curl_errno($ch)){
	echo 'Curl error: ' . curl_error($ch);
}

//echo $response."   response<br>";
    curl_close($ch);

    $response = json_decode($response);
    $msg = '';
//echo $response."   response<br>";

    switch ($response->code) {
        case '1000':
            $msg = 'Sent Successfully';
            break;

        case '1001':
            $msg = 'Invalid Token';
            break;
        
        case '1002':
            $msg = 'Error Sending SMS';
            break;

        case '1003':
            $msg = 'Insufficient Balance';
            break;
            
        case '1004':
            $msg = 'No valid phone number found';
            break;

        case '1005':
            $msg = 'Application Error';
            break;

        case '1006':
            $msg = 'Error retrieving balance';
            break;

        case '1007':
            $msg = 'Message Schedule Error';
            break;

        case '1008':
            $msg = 'Unregistered Bank Sender ID';
            break;

        case '1009':
            $msg = 'Phone numbers on DND';
            break;
        // Replace $msg values to what you want accordingly
    }
//echo $msg."msg<br>";

    return $msg;
}

$senderid = 'AZEEZ';
$message = 'Welcome to my world!';
$to = '08023130565';
$routing = '3';
$type = 0;
$token = 'SkHSPkY3qC1qraiM17pyBA1cgd36PCXNGoNO7mhQYOcePrjSWKrwH8S0DXJvTeGfxvNIbGUrBoycwSQ7OYHkqJPsyhKnCfg4GLkQ';

$response= sendsms($senderid,$message,$to,$token,$routing,$type);
echo $response;
/*var_dump(validate_sendsms($response));





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
}*/

?>