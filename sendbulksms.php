<?php
	header("content-type: Access-Control-Allow-Origin: *");
	header("content-type: Access-Control-Allow-Methods: POST");
	header("Content-Type: application/json; charset=UTF-8");
 
	if (isset($_POST['selectedIdsArray'])) {
		$selectedIdsArray = $_POST['selectedIdsArray'];
	}
	if (isset($_POST['option'])) {
		$option = str_replace("'", "`", trim($_POST['option']));
	}
	if (isset($_POST['selectedids'])) {
		$selectedids = str_replace("'", "`", trim($_POST['selectedids']));
	}
	if (isset($_POST['lineno'])) {
		$lineno = str_replace("'", "`", trim($_POST['lineno']));
	}
	if (isset($_POST['transdate'])) {
		$transdate = str_replace("'", "`", trim($_POST['transdate']));
	}
	if (isset($_POST['username'])) {
		$username = str_replace("'", "`", trim($_POST['username']));
	}
	if (isset($_POST['textmsg'])) {
		$textmsg = str_replace("'", "`", trim($_POST['textmsg']));
	}
	//smstranslist&selectedids="+selectedids+"&lineno="+lineno+"&transdate="+transdate+"&username="+username
	//include("data.php");
	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");

	// Getting the received JSON into $json variable.
	$json = file_get_contents('php://input');

	// decoding the received JSON and store into $obj variable.
	$obj = json_decode($json,true); 
	
	$platform = "";
	if (isset($obj['user_platform'])) {
		$platform = str_replace("'", "`", trim($obj['user_platform']));
		$option = str_replace("'", "`", trim($obj['user_option']));
		$lineno = str_replace("'", "`", trim($obj['lineNo']));
		$cardno = str_replace("'", "`", trim($obj['cardNo']));
		$username = str_replace("'", "`", trim($obj['username']));
		$transdate = str_replace("'", "`", trim($obj['transdate']));
		$transdate = substr($transdate,6,4) . "-" . substr($transdate,3,2) . "-" .substr($transdate,0,2);
		$query = "SELECT * FROM transactions a where concat(a.transdate, a.serialno)=(select max(concat(transdate, serialno)) from transactions where cardno='{$cardno}') ";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$selectedids = $row[0];
		}
	}

	if ($option == "smstranslist") {
//mysqli_query($connection, "UPDATE currentrecord set currentrecordprocessing ='".$platform." - ".$option." - ".$lineno." - ".$cardno." - ".$transdate." - ".$username." - ".$selectedids."' where currentuser='Admin'");
		$selectedids = str_replace("_~_", ", ", $selectedids);
		$query = "SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance+a.debit-a.credit as balance_b, a.credit+a.debit as amount, a.balance as balance_a, a.transgroup, a.recordlock, 1, (select distinct b.telephone from customers b where a.cardno=b.cardno) as phone, a.smsstatus FROM transactions a where a.transdate='{$transdate}' "; // and a.post='1'
		if ($lineno != "") {
			$query .= " and a.lineno='{$lineno}'  ";
		}
		if ($username != "") {
			$query .= " and a.username='{$username}' ";
		}
		$query .= " and a.serialno in (" . $selectedids . ") ";
		$query .= " order by a.transdate, a.username, a.transtype, a.lineno, a.serialno ";
		$result = mysqli_query($connection, $query);
//mysqli_query($connection, "UPDATE currentrecord set tmp ='".str_replace("'", "`", $query)."' where currentuser='Admin'");
		$resp = "listSMSTransaction";
		$msg = "";
		$row[4] = "";
		$response = "";
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
				extract($row);
	//serialno, username, transtype, lineno, cardno, names, transdate, balance_b, amount, balance_a, transgroup, recordlock, post, phone, smsstatus            
				$recipientno = $row[13];
				$originaldate = $row[6];
				$row[6] = substr($row[6], 8, 2) . "/" . substr($row[6], 5, 2) . "/" . substr($row[6], 0, 4);
				$msg = "";
				//$msg = "Transaction Details!!!%0a%0a";ucfirst(
				//$msg .= "Trans. Type:%20%20" . $row[2] . "%0a";
				$msg .="\n".ucfirst($row[2]) . " Alert\n";
				//$msg .= "Amount:%20%20%20%20%20" . $row[8] . "%0a";
				$msg .= "Amount: " . $row[8] . "\n";
				//$msg .= "Bal. B/F:%20%20%20%20%20%20" . $row[7] . "%0a";
				//$msg .= "New Bal.:%20%20%20" . $row[9] . "%0a";
				$msg .= "A/C Name: " . $row[5] . "\n";
				//$msg .= "A/C NO:%20%20%20%20%20%20%20%20%20" . $row[4] . "%0a";
				$msg .= "Trans Date: " . $row[6] . "\n";
				$msg .= "Send Date: " . date("d/m/Y H:i:s") . "\n\n";
				$msg .= "Pls call DADOLLAR on 08029035517 for complaints.";
//mysqli_query($connection, "UPDATE currentrecord set report ='".$recipientno." - ".$msg." - ".$row[13]." - ".$row[6]." - ".$row[2]."' where currentuser='Admin'");
				//Sendsms($recipientno, $msg);
				$response = Sendsms($recipientno, $msg);
				//$response1 = strpos($response, '1000');
				//$response2 = strpos($response, '1002');
//mysqli_query($connection, "UPDATE currentrecord set tmp ='".$msg." - ".$recipientno." - ".$response."' where currentuser='Admin'");
//$qry="UPDATE currentrecord set currentrecordprocessing =concat(currentrecordprocessing,'{$recipientno}~','1000: {$response1}~','1002: {$response2}~~~') where currentuser='Admin'";
//mysqli_query($connection, $qry);
				if(strpos($response, '1000') !== false){
						$resp = 'listSMSTransaction';
						$query = "update transactions set smsstatus='SMS Sent' where transdate='{$originaldate}' and lineno='{$row[3]}'   and cardno='{$row[4]}' ";
						mysqli_query($connection, $query);
						
				}else if(strpos($response, '1001') !== false){
						$resp = 'Invalid Token';
						break;
					
				}else if(strpos($response, '1002') !== false){
						$resp = 'listSMSTransactionfail';
						$query = "update transactions set smsstatus='Invalid Phone No' where transdate='{$originaldate}' and lineno='{$row[3]}' and cardno='{$row[4]}' ";
						mysqli_query($connection, $query);

				}else if(strpos($response, '1003') !== false){
						$resp = 'Insufficient Balance';
						break;
						
				}else if(strpos($response, '1004') !== false){
						$resp = 'Error Sending SMS';
						break;

				}else if(strpos($response, '1005') !== false){
						$resp = 'Application Error';
						break;

				}else if(strpos($response, '1006') !== false){
						$resp = 'Error retrieving balance';
						break;

				}else if(strpos($response, '1007') !== false){
						$resp = 'Message Schedule Error';
						break;

				}else if(strpos($response, '1008') !== false){
						$resp = 'Unregistered Bank Sender ID';
						break;

				}else if(strpos($response, '1009') !== false){
						$resp = 'Phone numbers on DND';
						break;
					// Replace $msg values to what you want accordingly
				}

				//Y:::POSTED:FALSE ERROR:No outbound route found for destination. MESSAGE-ID: NUMBER-OF-PARTS:
				//Y:::POSTED:TRUE ERROR: MESSAGE-ID:1553820313 NUMBER-OF-PARTS:1

				/*if (substr($response, 0, 16) == "Y:::POSTED:FALSE" || strlen($recipientno)!=11) {
					$query = "update transactions set smsstatus='Invalid Phone No' where transdate='{$originaldate}' and lineno='{$row[3]}'  ";
					$query .= " and cardno='{$row[4]}' ";
					mysqli_query($connection, $query);
					$resp = "listSMSTransaction";
				} else if (substr($response, 0, 15) == "Y:::POSTED:TRUE") {
					$query = "update transactions set smsstatus='SMS Sent' where transdate='{$originaldate}' and lineno='{$row[3]}'  ";
					$query .= " and cardno='{$row[4]}' ";
					mysqli_query($connection, $query);
					$resp = "listSMSTransaction";
				} else if ($response == "Y:::") {
					$resp = "nointernet";
					break;
				}else{
					$resp = "myresponse".$response;
				}*/
				//$msg2 = $row[4];
//$qry="UPDATE currentrecord set currentrecordprocessing = CONCAT('". $resp."','  ','".$msg2."') where currentuser='Admin'";
//mysqli_query($connection, $qry);
			}
			
		}else{
			$resp = "listSMSTransaction";
		}
		if($platform == "mobile"){
			$resp = json_encode(array("result" => 'smssent   '.$response));
		}
		echo $resp;
	}

	if ($option == "smsmsg") {
		//$qry="UPDATE currentrecord2 set currentrecordprocessing = '' where currentuser='Admin'";
		//mysqli_query($connection, $qry);

		$resp = "";
		$resp2 = "";
		$selectedids = "";
		$counter=0;
		/*$qry = "DROP TABLE IF EXISTS tmp_ids ";
		mysqli_query($connection, $qry);
		$qry="CREATE TABLE tmp_ids (serialno int(11) NOT NULL DEFAULT 0)"; //TEMPORARY
		mysqli_query($connection, $qry);*/
		$qry = "delete from tmp_ids ";
		mysqli_query($connection, $qry);
		foreach($selectedIdsArray as $value) {
			if(strlen($value)>0){
				/*if(($counter % 100)==0){
					$response = CallSendsms($textmsg);
					$responses = explode("-", $response);
					$resp = $responses[0];
					$resp2 .= $responses[1];
					$qry = "delete from tmp_ids ";
					mysqli_query($connection, $qry);
				}*/
				//$selectedids .= ", ";
				$qry = "insert into tmp_ids (serialno) values ({$value}) ";
				mysqli_query($connection, $qry);
				/*++$counter;
				$str = $counter."/".count($selectedIdsArray);
				$_SESSION["currentid"] = $str;
				$qry="UPDATE currentrecord2 set tmp = '{$str}' where currentuser='Admin'";
				mysqli_query($connection, $qry);*/
			}
		   //$selectedids .= $value;
		}
		$response = CallSendsms($textmsg);
		$responses = explode("-", $response);
		$resp = $responses[0];
		$resp2 .= $responses[1];
		//$qry = "DROP TABLE IF EXISTS tmp_ids";
		//mysqli_query($connection, $qry);
		echo  $resp.$option.$textmsg.$option.$resp2;
	}

	function CallSendsms($textmsg) {
		//include("data.php");
		$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
		//$query = "SELECT b.lastname, b.telephone, b.cardno from customers b where b.serialno in (" . $selectedids . ") order by b.cardno ";
		$query = "SELECT b.lastname, b.telephone, b.cardno, b.serialno from customers b where b.serialno in (select serialno from tmp_ids) order by b.cardno ";
		$result = mysqli_query($connection, $query);

		$resp = "";
		$resp2 = "";
		$msg = "";
		if (mysqli_num_rows($result) > 0) {
			$response = "";
			$recipients_list = "";
			while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
				extract($row);
				$recipientno = $row[1];
				if(!strpos($recipients_list,$recipientno)){
					$recipients_list .= $recipientno." ";
	//				sleep(10);
					$response = Sendsms($recipientno, $textmsg);
//$qry="UPDATE currentrecord set currentrecordprocessing =concat(currentrecordprocessing,'    {$recipientno}','~      ','{$response}') where currentuser='Admin'";
//mysqli_query($connection, $qry);
					if(strpos($response, '1000') !== false){
							$resp = 'show_excel';
							$resp2 .= $row[0]."~".$recipientno."~SENT_";

					}else if(strpos($response, '1001') !== false){
							$resp = 'Invalid Token';
							break;
						
					}else if(strpos($response, '1002') !== false){
							$resp = 'show_excel';
							$resp2 .= $row[0]."~".$recipientno."~Invalid Phone No_";

					}else if(strpos($response, '1003') !== false){
							$resp = 'Insufficient Balance';
							break;
							
					}else if(strpos($response, '1004') !== false){
							$resp = 'Error Sending SMS';
							break;
							
					}else if(strpos($response, '1005') !== false){
							$resp = 'Application Error';
							break;

					}else if(strpos($response, '1006') !== false){
							$resp = 'Error retrieving balance';
							break;

					}else if(strpos($response, '1007') !== false){
							$resp = 'Message Schedule Error';
							break;

					}else if(strpos($response, '1008') !== false){
							$resp = 'Unregistered Bank Sender ID';
							break;

					}else if(strpos($response, '1009') !== false){
							$resp = 'Phone numbers on DND';
							break;
						// Replace $msg values to what you want accordingly
					}
						//$qry="UPDATE currentrecord2 set currentrecordprocessing = CONCAT(currentrecordprocessing,'".$recipientno."','  ','".$row[2]."','  ',now(),'\n') where currentuser='Admin'";
					$qry = "update tmp_ids set cardno={$row[2]}, telephone={$row[1]} where serialno={$row[3]} ";
					mysqli_query($connection, $qry);
					/*if (strpos($response,"Insufficient")) {
						$resp = "success";
						$resp2 .= $row[0]."~".$recipientno."~Insufficient";
						break;
					} else if (substr($response, 0, 16) == "Y:::POSTED:FALSE" || strlen($recipientno)!=11) {
						$resp = "fail";
						$resp2 .= $row[0]."~".$recipientno."~FAILED_";
					} else if (substr($response, 0, 15) == "Y:::POSTED:TRUE") {
						$resp = "success";
						$resp2 .= $row[0]."~".$recipientno."~SENT_";
						//break;
					} else if ($response == "Y:::") {
						$resp = "nointernet";
						break;
					}else{
						$resp = "myresponse".$response;
						$resp2 .= $row[0]."~".$recipientno."~FAILED_";
						//$resp = "success";
						//$resp2 .= $row[0]."~".$recipientno."~SENT_";
					}*/
				}
			}
		}else{
			$resp = "nocustomer";
		}
		mysqli_close($connection);
		return $resp."-".$resp2;
	}

	function Sendsms($_mobileno, $msg, $is_MSGTools = 'N') {
		//include("data.php");
		$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
//$qry="UPDATE currentrecord2 set report = concat(currentrecordprocessing,'{$_mobileno}','   ','{$msg}') where currentuser='Admin'";
//mysqli_query($connection, $qry);
		$_mobileno = PrefixPhone_nos($_mobileno);
		$userid = "dadollar87@yahoo.com"; //"Immaculate";
		$password = "aliyah"; //"@dm1ns";
		$sender = "Dadollar";
		$SendNow = ":";
		$Reply = "N:::";
		if (isset($_mobileno)) {
			//$API_MSG = str_replace(' ', '+', $msg);
			$API_MSG = $msg;
			
			//Use the variables below to hold your values. 
			//$message = '';
			//$senderid = '';
			//$recipients = '';
			$token = 'DyrX8D7cfdotvRREIW5ONUa5IVUoWqd5QNk6i6ZRAZS7c0KX7dbJKMXtTAyHYZUbPCiYw5uVrtwg2IgFgJFWIqBxGVK4wWBCHJjo';        //The generated code from api-x token page
			$url = 'https://smartsmssolutions.com/api/';

			$sms_array = array (
							'sender'    => $sender,
							'to' => $_mobileno,
							'message'   => $API_MSG,
							'type'  => 0,          //This can be set as desired. 0 = Plain text ie the normal SMS
							'routing' => '3',         //This can be set as desired. 3 = Deliver message to DND phone numbers via the corporate route
							'token' => $token
						);
			//Call sendsms_post function to send SMS        
			$Reply = sendsms_post($url, $sms_array);
//$qry="UPDATE currentrecord set report = CONCAT(report,'  ','".$_mobileno."','~','".$Reply."|') where currentuser='Admin'";
//mysqli_query($connection, $qry);

			//$url = 'http://api.smartsmssolutions.com/smsapi.php?username='.$userid.'&password='.$password.'&sender='.$sender.'&recipient='.$_mobileno.'&message='.$API_MSG.'&';
			//$Reply = file_get_contents($url);
			//return $send;
			 
			/*$url = "http://test.ipisms.com/HTTPIntegrator_SendSMS_1?u=$userid&p=$password&s=$sender&r=t&f=f&d=$_mobileno&t=$API_MSG";
			$SendNow = file_get_contents($url);
			//$SendNow = @fopen($url, 'r');Sendsms(
			$Reply = "Y:::$SendNow";*/
		}
		//$hh=SaveSMS($_mobileno,$msg,$SendNow);  /You may want to store in your own DB to monitor usage
		mysqli_close($connection);
		return $Reply;
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

	function PrefixPhone_nos($to) {

		$to = @preg_replace("[^0-9]", "", $to);
		$to = str_replace("+", "", $to);

		if (!is_numeric($to)) {
			$to = preg_replace('/[^0-9]/Uis', '', $to);
		}

		if (strlen($to) > 13) {
			return;
		}
		$first = substr($to, 0, 1);

		if (strlen($to) == 10 or ( $first == "8" or $first == "7" or $first == "9")) {

			$to = "234" . $to;
		}

		$prefix = substr($to, 0, 3);
		//For International Numbers
		if (($prefix != "234") and ( $first != "0")) {
			$num = $to;
		} else {
			if ($prefix == "234") {
				$num = $to;
			} else {
				$num = substr_replace($to, '234', 0, 1);
			}
		}
		return $num;
	}
	
	mysqli_close($connection);
?>


