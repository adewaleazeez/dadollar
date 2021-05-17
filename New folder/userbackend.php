<?php
	header("content-type: Access-Control-Allow-Origin: *");
	header("content-type: Access-Control-Allow-Methods: POST");
	header("Content-Type: application/json; charset=UTF-8");
 
	include "config.php";

	if(isset($_POST['platform'])){
		$platform = $_POST['platform'];
		$option = $_POST['options'];
		$userNames = $_POST['userName'];
		$userPasswords = $_POST['userPassword'];
	}

	if($optiono == 'loginUser'){
		$resp = 'invalidlogin';
		if($userNames == 'Admin' && $userPasswords == 'admins'){
			$resp = 'validlogin';
		}
		
		if(isset($_POST['platform'])){
			echo json_encode('resp: '.$resp.'      platform: '.$platform.'       option: '.$option.'     userNames: '.$userNames.'     userPasswords: '.$userPasswords);
		}else{
			echo $resp;
		}
	}

	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
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
			echo json_encode($serverdate);
		}
		
		if($option == "checkLineNo" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$query = "SELECT * FROM customers where lineno = '".$lineNo."' limit 1";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				echo json_encode(array("result" => $lineNo));
			}else{
				echo json_encode(array("result" => "invalidlineno"));
			}
		}
		
		if($option == "getUserName" && $platform == 'mobile'){
			$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$query = "SELECT * FROM customers where cardno = '".$cardNo."' limit 1";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				extract ($row);
				echo json_encode(array("result" => $row[2].' '.$row[3]));
			}else{
				echo json_encode(array("result" => 'invalidcardno'));
			}
		}
		
		if($option == "depositPosting" && $platform == 'mobile'){
			$lineNo = str_replace("'", "`", trim($obj['lineNo']));
			$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$transDate = str_replace("'", "`", trim($obj['transDate']));
			$transDate = substr($transDate,6,4) . "-" . substr($transDate,3,2) . "-" .substr($transDate,0,2);
			$userName = str_replace("'", "`", trim($obj['userName']));
			$depositAmount = str_replace("'", "`", trim($obj['depositAmount']));
			
			$query = "SELECT a.balance_a FROM transactionlist a where concat(a.transdate, a.serialno)=(select max(concat(transdate, serialno)) from transactionlist where cardno='{$cardNo}') ";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract($row);
			$balance_b = $row[0];
			$balance_a = floatval($balance_b."") + floatval($depositAmount."");
			
			$query = "INSERT INTO transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, ";
			$query .= "recordlock, post, smsstatus, cardserial) ";
			$query .= "values ('{$lineNo}', '{$cardNo}', '{$transDate}', '{$balance_b}', '{$depositAmount}', '{$balance_a}', 'deposit', 'contribution', ";
			$query .= "'{$userName}', '1', '1', '', '' ";
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
					$queryTrans1 = "SELECT a.* FROM transactions a where (a.transdate='{$transDate}' and a.cardno='{$row[2]}' and a.narration='{$narration}' ) or (a.transno='{$row[0]}') ";
					$queryTrans1 .= "or (a.transtype='{$row[7]}' and a.cardno='{$row[2]}' and year(a.transdate)=year('{$row[3]}') and month(a.transdate)=month('{$row[3]}'))";
					$resultTrans1 = mysqli_query($connection, $queryTrans1);
					if (mysqli_num_rows($resultTrans1) == 0) {
						$queryTrans = "insert into transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ";
						$queryTrans .= " ('{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}') ";
			//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $queryTrans)."' where currentuser='Admin'";
			//$result = mysqli_query($connection, $qry);
							
						mysqli_query($connection, $queryTrans);
						updateTransBalances($row[2], $row[3]);
					}
				}
				echo json_encode(array("result" => 'successful'));
			}else{
				echo json_encode(array("result" => 'invalidtransaction'));
			}
			
			
			/*$cardNo = str_replace("'", "`", trim($obj['cardNo']));
			$query = "SELECT * FROM customers where cardno = '".$cardNo."' limit 1";
			$result = mysqli_query($connection, $query);
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result, MYSQLI_NUM);
				extract ($row);
				echo json_encode(array("result" => $row[2].' '.$row[3]));
			}else{
				
			}*/
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
		include("data.php");
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
	}
?>
