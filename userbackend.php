<?php
	header("content-type: Access-Control-Allow-Origin: *");
	header("content-type: Access-Control-Allow-Methods: POST");
	header("Content-Type: application/json; charset=UTF-8");
 
	if (isset($_GET['option'])) {
		$option = str_replace("'", "`", trim($_GET['option']));
	}
	if (isset($_GET['userName'])) {
		$userNames = str_replace("'", "`", trim($_GET['userName']));
	} 
	if (isset($_GET['serialno'])) {
		$serialnos = str_replace("'", "`", trim($_GET['serialno']));
	}
	if (isset($_GET['userPassword'])) {
		$userPasswords = str_replace("'", "`", trim($_GET['userPassword']));
	}
	if (isset($_GET['firstName'])) {
		$firstNames = str_replace("'", "`", trim($_GET['firstName']));
	} 
	if (isset($_GET['lastName'])) {
		$lastNames = str_replace("'", "`", trim($_GET['lastName']));
	}
	if (isset($_GET['userType'])) {
		$userTypes = str_replace("'", "`", trim($_GET['userType']));
	}
	if (isset($_GET['access'])) {
		$accesss = str_replace("'", "`", trim($_GET['access']));
	}
	if (isset($_GET['archivedate'])) {
		$archivedate = str_replace("'", "`", trim($_GET['archivedate']));
	}
	if (isset($_GET['page'])) {
		$pages = str_replace("'", "`", trim($_GET['page']));
	}
	if (isset($_GET['menu'])) {
		$menus = str_replace("'", "`", trim($_GET['menu']));
	}
	if (isset($_GET['active'])) {
		$actives = str_replace("'", "`", trim($_GET['active']));
	}
	if (isset($_GET['login'])) {
		$logins = str_replace("'", "`", trim($_GET['login']));
	}
	if (isset($_GET['currentuser'])) {
		$currentusers = str_replace("'", "`", trim($_GET['currentuser']));
	}
	if($currentusers==null || $currentusers=="") {
		$currentusers = $_COOKIE['currentuser'];
	}
	
	//include("data.php");

	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");

	if($option == "getUser" || $option == "getPassword"){
		$query = "SELECT * FROM users where userName = '".$userNames."'";
		$result = mysqli_query($connection, $query);
		$resp = "invalidusername";
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
            extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
			$userType=$row[5]; $active=$row[6];
			if($userNames == $userName){
				$resp = $option . $userName . $option . $firstName . $option . $lastName;
			}
		}

		echo $resp;
	}

	if($option == "archive"){
		$query = "call archive('".$archivedate."')";
		mysqli_query($connection, $query);
		
		$query = "SELECT count(*) FROM transactions_archive where year(transdate)=year('".$archivedate."')";
		$result = mysqli_query($connection, $query);

		if(mysqli_num_rows($result) > 0){
			echo 'archived';
		}else{
			echo 'failed';
		}
	}
	
	if($option == "resetPassword"){
		$query = "SELECT * FROM users where userName = '".$userNames."'";
		$resp = "invalidlogin";
		$result = mysqli_query($connection, $query);

		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
			$userType=$row[5]; $active=$row[6];

			if($userName == $userNames){
				$userPasswords = strtolower($row['firstName']);
				$query = "update users set userPassword='{$userPasswords}' where userName='{$userNames}' ";
				//$quer=$query;
				mysqli_query($connection, $query);
				$resp = "passwordchanged".$active;
				$usernames = $userName;
				$activitydescriptions = $userNames." changed passwoword ";
				$activitydates = date("Y-m-d");
				$activitytimes = date("H:i:s");
				$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
				mysqli_query($connection, $query);
			}
		}
		echo $resp; //.$quer;
	}
		
	// Getting the received JSON into $json variable.
	$json = file_get_contents('php://input');

	// decoding the received JSON and store into $obj variable.
	$obj = json_decode($json,true); 
		
	if (isset($obj['user_platform'])) {
		$platform = str_replace("'", "`", trim($obj['user_platform']));
		$option = str_replace("'", "`", trim($obj['user_option']));
		$userNames = str_replace("'", "`", trim($obj['userName']));
		$userPasswords = str_replace("'", "`", trim($obj['userPassword']));

		if($option == "getServerDate" && $platform == 'mobile'){
			$serverdate = date("d/m/Y");
			echo json_encode(array("result" => $serverdate));
		}
		
		if($option == "checkLineNo" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$query = "SELECT * FROM customers where lineno = '".$lineNo."' and lineno<>'' limit 1";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				echo json_encode(array("result" => $lineNo));
			}else{
				echo json_encode(array("result" => "invalidlineno"));
			}
		}
		
		if($option == "getCustomerName" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$query = "SELECT * FROM customers where cardno = '".$cardNo."' limit 1"; //lineno = '".$lineNo."' and
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				extract ($row);
				//serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, openingbalance, recordlock, lockwithdrawal, commission, lineno, cardserial
				if($lineNo != $row[12]){
					echo json_encode(array("result" => 'invalidlineno'));
				}else{
					echo json_encode(array("result" => $row[2].' '.$row[3]));
				}
			}else{
				echo json_encode(array("result" => 'invalidcardno'));
			}
		}
		
		if($option == "checkDuplicateTrans" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$transDate = str_replace("'", "`", trim($obj['transDate']));
			$transDate = substr($transDate,6,4) . "-" . substr($transDate,3,2) . "-" .substr($transDate,0,2);
			$userName = str_replace("'", "`", trim($obj['userName']));
			$depositAmount = str_replace("'", "`", trim($obj['depositAmount']));
			
			$query = "SELECT a.* FROM transactions a where (a.transdate='{$transDate}' and a.cardno='{$cardNo}' and a.lineno='{$lineNo}' and a.username='{$userName}' and a.credit='{$depositAmount}' ) ";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				echo json_encode(array("result" => 'duplicateexists'));
			}else{
				echo json_encode(array("result" => 'noduplicate'));
			}
//mysqli_query($connection, "UPDATE currentrecord2 set report='".mysqli_num_rows($result)."', tmp='".$lineNo.$cardNo.$transDate.$userName.$depositAmount.trim($obj['transDate'])."', currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'");
		}
		
		if($option == "depositPosting" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$transDate = str_replace("'", "`", trim($obj['transDate']));
			$transDate = substr($transDate,6,4) . "-" . substr($transDate,3,2) . "-" .substr($transDate,0,2);
			$userName = str_replace("'", "`", trim($obj['userName']));
			$depositAmount = str_replace("'", "`", trim($obj['depositAmount']));
			
			$balance_b = 0;
			$query = "SELECT a.balance_a FROM transactionlist a where concat(a.transdate, a.serialno)=(select max(concat(transdate, serialno)) from transactionlist where cardno='{$cardNo}') ";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			if ($row != null) {
				$balance_b = $row[0];
			}
			$balance_a = floatval($balance_b."") + floatval($depositAmount."");
			
			$query = "INSERT INTO transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, ";
			$query .= "recordlock, post, smsstatus, cardserial) ";
			$query .= "values ('{$lineNo}', '{$cardNo}', '{$transDate}', '{$balance_b}', '{$depositAmount}', '{$balance_a}', 'deposit', 'contribution', ";
			$query .= "'{$userName}', '1', '1', '', '' )";
			mysqli_query($connection, $query);
			
			$query = "SELECT a.* FROM transactionlist a where concat(a.transdate, a.serialno)=(select max(concat(transdate, serialno)) from transactionlist where cardno='{$cardNo}') ";
			$result = mysqli_query($connection, $query);
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
					extract($row);
			
					$credit = "0";
					$debit = "0";
					$balance = "0";
					if ($row[7] == "deposit") {
						$credit = $row[5];
					} else {
						$debit = $row[5];
					}
					$narration = "The sum of " . $row[5] . " being " . $row[7] . " by " . $row[2];
					//$queryTrans1 = "SELECT a.* FROM transactions a where (a.transdate='{$transDate}' and a.cardno='{$row[2]}' and a.narration='{$narration}' ) or (a.transno='{$row[0]}') ";
					//$queryTrans1 .= "or (a.transtype='{$row[7]}' and a.cardno='{$row[2]}' and year(a.transdate)=year('{$row[3]}') and month(a.transdate)=month('{$row[3]}'))";
					//$resultTrans1 = mysqli_query($connection, $queryTrans1);
					//if (mysqli_num_rows($resultTrans1) == 0) {
						$queryTrans = "insert into transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ";
						$queryTrans .= " ('{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}') ";
			//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $queryTrans)."' where currentuser='Admin'";
			//$result = mysqli_query($connection, $qry);
							
						mysqli_query($connection, $queryTrans);
						updateTransBalances($row[2], $row[3]);
					//}
				}
				echo json_encode(array("result" => 'successful'));
			}else{
				echo json_encode(array("result" => 'invalidtransaction'));
			}
			
		}
		
		if($option == "listDepositTrans" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$transDate = str_replace("'", "`", trim($obj['transDate']));
			$transDate = substr($transDate,6,4) . "-" . substr($transDate,3,2) . "-" .substr($transDate,0,2);
			$userName = str_replace("'", "`", trim($obj['userName']));
			//$query = "SELECT a.*, b.lastname FROM transactions a, customers b where a.username = '".$userNames."' and a.lineno = '".$lineNo."' and a.transdate = '".$transDate."' and a.cardno=b.cardno ";
			//$query = "select 'S/No' as serialno, 'Card No' as cardno, 'Card Name' as lastname, 'Amount' as credit union ";
			$query = "SELECT @a:=@a+1 serial_number, a.cardno, b.lastname, FORMAT(a.credit,2) FROM transactions a, customers b, (SELECT @a:= 0) AS a where a.username = '".$userNames."' and a.lineno = '".$lineNo."' and a.transdate = '".$transDate."' and a.cardno=b.cardno ";
			$query .= " union select ' ' as serial_number, 'TOTAL :' as cardno, ' ' as lastname, (select FORMAT(sum(a.credit),2) FROM transactions a where a.username = '".$userNames."' and a.lineno = '".$lineNo."' and a.transdate = '".$transDate."') as credit ";
			$resp = array("records" => 'notransaction');
			$result = mysqli_query($connection, $query);
			$trans_arr=array();
			//$trans_arr["records"]=array();
			if(mysqli_num_rows($result) > 0){
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
					extract($row);
					$sno=$row[0]; $cardno=$row[1]; $credit=$row[2]; $lastname=$row[3]; 
					$trans_item=array(
						"sno" => $sno,
						"cardno" => $cardno,
						"name" => $lastname,
						"amount" => $credit
					);
			  
					//array_push($trans_arr["records"], $trans_item);
					array_push($trans_arr, $trans_item);
				}
				$resp = $trans_arr;
			}
//mysqli_query($connection, "UPDATE currentrecord2 set tmp='".$resp.trim($obj['transDate'])."', currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'");
			echo json_encode($resp);
		}
	
		if($option == "checkLogin" && $platform == 'mobile'){
			$query = "SELECT * FROM users where userName = '".$userNames."'";
			$resp = "invalidlogin";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				extract ($row);
				$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
				$userType=$row[5]; $active=$row[6];
				$samePassword = "";
				if($userName == $userNames && $userPassword == $userPasswords){
					if($userPassword == strtolower($row['firstName'])){
						$resp = "changepassword";
					} else if($active != 'Yes'){
						$resp = "inactive";
					} else {
						$resp = "validlogin";
					}
				}
			}
			echo json_encode(array("result" => $resp));
		}
	}

	function updateTransBalances($cardno, $date) {
		$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
		$balances = 0;
		$query = "SELECT max(transdate) as transdates FROM transactions where cardno='{$cardno}' and transdate<'{$date}' ";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		extract($row);
		$transdates = $row[0];
	//mysqli_query($connection, "UPDATE currentrecord2 set report='{$row[0]}', tmp='{$transdates}', currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'");
		
		if ($transdates == null || $transdates == "") {
			$query = "SELECT balance as balances FROM transactions_archive where concat(transdate, serialno)=(select max(concat(transdate, serialno)) from transactions_archive where cardno='{$cardno}')";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract($row);
			$balances = $row[0];
		}	
		
		if ($transdates != null && $transdates != "" && ($balances == null || $balances == "" || $balances == 0)) {
			$query = "SELECT balance FROM transactions where cardno='{$cardno}' and  transdate='{$transdates}' ";
			$result = mysqli_query($connection, $query);
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
					extract($row);
					$balances = $row[0];
				}
			}
		}
		$query = "SELECT * FROM transactions where cardno='{$cardno}' and transdate>='{$date}' order by transdate, serialno";
		$result = mysqli_query($connection, $query);
	//setcookie("myresponse", $query."     ".$balances, false);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract($row);
				$balances = $balances + $row[4] - $row[5];
				$queryBal = "update transactions set balance='{$balances}' where serialno='{$row[0]}' ";
				mysqli_query($connection, $queryBal);
			}
		}
		//return true;
		mysqli_close($connection);
	}
		
	if($option == "loginUser"){
		$query = "SELECT * FROM users where userName = '".$userNames."'";
		$resp = "invalidlogin";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
			$userType=$row[5]; $active=$row[6];
			$samePassword = "";
			if($userName == $userNames && $userPassword == $userPasswords){
				if($userPassword == strtolower($firstName)){
					echo "changepassword";
					return true;
				}
				if($loggedIn == "Yes" && $userNames!="Admin"){
					echo "userloggedin";
					return true;
				}

				$query = "update users set loggedIn='Yes' where userName = '".$userNames."'";
				mysqli_query($connection, $query);
				$resp = "validlogin".$active;
				setcookie("currentuserfullname", $firstName." ".$lastName, false);
				setcookie("currentuser", $userName, false);
				setcookie("currentusertype", $userType, false);

				$usernames = $userName;
				$activitydescriptions = $usernames." logged in";
				$activitydates = date("Y-m-d");
				$activitytimes = date("H:i:s");
				$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
				mysqli_query($connection, $query);
				
				$query = "SELECT * FROM currentrecord where currentuser = '".$userNames."'";
				$result = mysqli_query($connection, $query);
				if(mysqli_num_rows($result) == 0){
					$query = "INSERT INTO currentrecord (currentuser, currentrecordprocessing, report) VALUES ('{$userNames}', '', '')";
					mysqli_query($connection, $query);
				}else{
					$query = "update currentrecord set currentrecordprocessing='', report='' where currentuser='{$userNames}'";
					mysqli_query($connection, $query);
				}
			}
		}
		$datetoday = date("Y-m-d");
		$current_month=date('m');
		$current_year=date('Y');
		$lastDateMonth=0;
		$lastDateYear=0;
		if($current_month==1){
			$lastDateMonth=12;
			$lastDateYear=$current_year-1;
		} else {
			$lastDateMonth=$current_month-1;
			$lastDateYear=$current_year;
		}
		$lastDateOfMonth = date("Y-m-t", strtotime($lastDateYear."-".$lastDateMonth."-01"));
		echo $resp.$lastDateOfMonth;
	}
	
	if($option == "checkBalanceBF"){
		$datetoday = date("Y-m-d");
		$current_month=date('m');
		$current_year=date('Y');
		$lastDateMonth=0;
		$lastDateYear=0;
		if($current_month==1){
			$lastDateMonth=12;
			$lastDateYear=$current_year-1;
		} else {
			$lastDateMonth=$current_month-1;
			$lastDateYear=$current_year;
		}
		$lastDateOfMonth = date("Y-m-t", strtotime($lastDateYear."-".$lastDateMonth."-01"));
		
		$query = "SELECT max(cardno) as cardno FROM customers";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		extract ($row);
			
		$query1 = "SELECT * FROM transactions where cardno='{$row[0]}' and month(transdate)=month('{$lastDateOfMonth}') and year(transdate)=year('{$lastDateOfMonth}') and narration='Balance B/F' ";
		$result = mysqli_query($connection, $query1);
		if (mysqli_num_rows($result) <= 0) {
			$query = "select cardno from transactions group by cardno order by cardno";
			$result = mysqli_query($connection, $query);
			
			if (mysqli_num_rows($result) > 0) {
				$nunmber_of_customers = mysqli_num_rows($result);
				$customer_counter = 0;
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
					extract($row);
					
					$query1 = "SELECT * FROM transactions  where cardno='{$row[0]}' and transdate<='{$lastDateOfMonth}' order by concat(transdate, serialno) desc limit 1";
					$result1 = mysqli_query($connection, $query1);
					if (mysqli_num_rows($result1) > 0) {
						$query2 = "SELECT * FROM transactions  where cardno='{$row[0]}' and month(transdate)=month('{$lastDateOfMonth}') and year(transdate)=year('{$lastDateOfMonth}') and narration='Balance B/F' ";
						$result2 = mysqli_query($connection, $query2);
						$row1 = mysqli_fetch_array($result1, MYSQLI_NUM);
						extract($row1);
						
						if (mysqli_num_rows($result2) <= 0) {
							$query3 = "insert into transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) ";
							$query3 .= "values ('{$row1[1]}', '{$lastDateOfMonth}', 'Balance B/F', 0, 0, '{$row1[6]}', 'B/F', 'Admin', 'B/F', 1, '{$row1[11]}', '0', '', '')";
							mysqli_query($connection, $query3);
						}else{
							$query3 = "update transactions set balance='{$row1[11]}' where cardno='{$row1[1]}' and transdate='{$lastDateOfMonth}' and narration='B/F' ";
							mysqli_query($connection, $query3);
						}				
						
						$qry="UPDATE currentrecord set report = 'Card No: ".$row1[1]."   <br>Amount: ".number_format($row1[6],2)."   <br>Post Date: ".$lastDateOfMonth."  <br>Current Record: ".number_format((++$customer_counter))."/".number_format($nunmber_of_customers)."' where currentuser='Admin'";
						mysqli_query($connection, $qry);
					}
				}
			}
		}
		$qry="UPDATE currentrecord set report = '' where currentuser='Admin'";
		mysqli_query($connection, $qry);
		echo "BalanceBF";
	}
	
	if($option == "updateUserTime"){
		$datetoday = date("Y-m-d");
		$userdates = date("YmdHis");
		$query = "update users set friends='{$userdates}' where userName ='{$currentusers}' ";
		mysqli_query($connection, $query);
	}

	if($option == "logoutUser"){
		$usernames = $_COOKIE['currentuser'];
		$query = "update users set loggedIn='No' where userName = '".$usernames."'";
		mysqli_query($connection, $query);
		$activitydescriptions = $usernames." logged out";
		$activitydates = date("Y-m-d");
		$activitytimes = date("H:i:s");
		$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
		mysqli_query($connection, $query);
					
		$query = "update users set friends='' where userName ='{$currentusers}' ";
		mysqli_query($connection, $query);

		setcookie("currentuserfullname", null, false);
		setcookie("currentuser", null, false);
		setcookie("currentusertype", null, false);
		echo $option;
	}

	if($option == "getAllUsers" || $option == "getRecordlist"){
		$query = "SELECT * FROM users order by userName";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$flg = 1;
			$resp = "";
			$counter = 0;
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
				$userType=$row[5]; $active=$row[6];
				$resp .= $userName . "~_~" . $firstName . "~_~" . $lastName . "~_~" . $loggedIn . "~_~" . $active . $option;
			}

			$usernames = $_COOKIE['currentuser'];
			$activitydescriptions = $usernames." selected all users";
			$activitydates = date("Y-m-d");
			$activitytimes = date("H:i:s");
			$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
			mysqli_query($connection, $query);
		}
		echo $resp;
	}

	if($option == "insertUser"){
		$query = "SELECT * FROM users where  userName = '{$userNames}'";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) == 0){
			$userPasswords = strtolower($firstNames);
			$query = "INSERT INTO users (firstName, lastName, userName, userPassword, loggedin, active) VALUES ('{$firstNames}', '{$lastNames}', '{$userNames}', '{$userPasswords}', '{$logins}', '{$actives}')";
			$result = mysqli_query($connection, $query);

			$usernames = $_COOKIE['currentuser'];
			$activitydescriptions = $usernames." inserted user: ".$userNames;
			$activitydates = date("Y-m-d");
			$activitytimes = date("H:i:s");
			$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
			mysqli_query($connection, $query);

			//$query = "select max(userName) as id from users";
			//$result = mysql_query($query, $connection);
			//$row = mysqli_fetch_array($result, MYSQLI_NUM);
			//extract ($row);
			echo "inserted";
		} else {
			echo "recordexists";
		}
	}

	if($option == "updateUser"){
		$query = "SELECT * FROM users where userName ='{$userNames}'";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		extract ($row);
		$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
		$userType=$row[5]; $active=$row[6];
		$record="";
		//foreach($row as $i => $value){
		//	$meta = mysql_fetch_field($result, $i);
		//	$record .= "[".$meta->name. " - " . $value . "] ";
		//}
		//updateRecycleBin("[Table: users] ".$record,"Update",$currentusers);

		if(mysqli_num_rows($result) > 0){
			$query = "UPDATE users set firstName='{$firstNames}',  lastName='{$lastNames}', active='{$actives}', loggedin='{$logins}' where userName = '{$userNames}'";
			$result = mysqli_query($connection, $query);

			$usernames = $_COOKIE['currentuser'];
			$activitydescriptions = $usernames." updated user: ".$userNames;
			$activitydates = date("Y-m-d");
			$activitytimes = date("H:i:s");
			$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
			mysqli_query($connection, $query);

			echo "updated";
		} else {
			echo "recordnotexist";
		}
	}

	if($option == "changePass"){
		$query = "SELECT * FROM users where userName = '".$userNames."'";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
            extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $userPassword=$row[2]; $firstName=$row[3]; $lastName=$row[4]; 
			$userType=$row[5]; $active=$row[6];
			$split_userPasswords = explode("][", $userPasswords);
			if($split_userPasswords[0] == $userPassword){
				$query = "UPDATE users set userPassword='{$split_userPasswords[1]}' where userName = '".$userNames."'";
				$result = mysqli_query($connection, $query);

				$usernames = $_COOKIE['currentuser'];
				$activitydescriptions = $usernames." changed password for user: ".$userNames;
				$activitydates = date("Y-m-d");
				$activitytimes = date("H:i:s");
				$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
				mysqli_query($connection, $query);

				echo "changePass";
			}else{
				echo "invalidpassword";
			}
		} else {
			echo "invalidusername";
		}
	}

	if($option == "getAllMenus"){
		$query = "select max(serialno) as id from usersmenu";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		extract ($row);
		$serialnos = $row[0];

		$query="SELECT * FROM usersmenu where userName = ''";
		$result = mysqli_query($connection, $query);

		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $menuOption=$row[2]; $accessibility=$row[3];
			$query2="SELECT * FROM usersmenu where userName = '".$userNames."' and menuOption = '{$menuOption}' order by serialno";
			$result2 = mysqli_query($connection, $query2);
			if(mysqli_num_rows($result2) == 0){
				++$serialnos;
				$query3="insert into usersmenu values ({$serialnos}, '{$userNames}', '{$menuOption}', '{$accessibility}')";
				mysqli_query($connection, $query3);

				$usernames = $_COOKIE['currentuser'];
				$activitydescriptions = $usernames." created menu ".$menuOption." for user: ".$userNames;
				$activitydates = date("Y-m-d");
				$activitytimes = date("H:i:s");
				$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
				mysqli_query($connection, $query);

			}
		}

		$resp = "getAllMenus";
		$query="SELECT * FROM usersmenu where userName = '".$userNames."'";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				$serialno=$row[0]; $userName=$row[1]; $menuOption=$row[2]; $accessibility=$row[3];
				$resp .= $serialno . "_~_" . $menuOption . "_~_" . $accessibility . "row_separator";
			}
		}
		if($userNames == "")  $resp = "getAllMenus";
		echo $resp;
	}

	if($option == "changeAccess"){
		$query = "SELECT * FROM usersmenu where serialno = '{$serialnos}'";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $menuOption=$row[2]; $accessibility=$row[3];
			$query = "update usersmenu set accessibility = '{$accesss}' where serialno = '{$serialnos}'";
			mysqli_query($connection, $query);
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);

			$usernames = $_COOKIE['currentuser'];
			$activitydescriptions = $usernames." granted access to menu ".$menuOption." to user: ".$userNames;
			if($accesss=='No') $activitydescriptions = $usernames." revoked access to menu ".$menuOption." from user: ".$userNames;
			$activitydates = date("Y-m-d");
			$activitytimes = date("H:i:s");
			$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
			mysqli_query($connection, $query);

		} else {
			echo "accessupdatefailed";
		}
	}

	if($option == "checkAccess"){
		$query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = '{$menus}'";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$serialno=$row[0]; $userName=$row[1]; $menuOption=$row[2]; $accessibility=$row[3];
			if($accessibility == "Yes"){
				$resp="checkAccessSuccess";
			}else{
				$resp="checkAccessFailed".$menus;
			}
		}else{
			$resp="checkAccessFailed".$menus;
		}
		echo $resp;
	}
	mysqli_close($connection);
?>
