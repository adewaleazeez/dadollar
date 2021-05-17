<?php
	include("data.php");
    $query = "SELECT cardno, balance FROM transactions_archive where transdate>='2016-12-01' order by cardno, transdate, serialno";
    $result = mysql_query($query, $connection);
	//echo mysql_num_rows($result);
	if (mysql_num_rows($result) > 0) {
		//echo $query."<br>";
    	$mycardno='';
        $oldcardno='';
        $oldbalance=0.0;
        while ($row = mysql_fetch_row($result)) {
            extract($row);
			$cardno=$row[0];
			$archive_balance=$row[1];
			
			if($cardno!=$mycardno){
			//echo "cardno: ".$cardno."       mycardno: $mycardno<br>";
				if($mycardno!=''){
					echo $oldcardno;
					$queryBal = "select balance, lineno from transactions where cardno='{$oldcardno}' and transdate='2016-12-31 00:00:00' and transtype='b/f'";
					$resultbal = mysql_query($queryBal, $connection);
					$rowBal = mysql_fetch_array($resultbal);
					$transbalance=0.0;
					$translineno='';
					if (mysql_num_rows($resultbal) > 0) {
						extract($rowBal);
						$transbalance=$rowBal[0];
						$translineno=$rowBal[1];
					}else{
						$transbalance=$rowBal[0];
						$translineno=$rowBal[1];
					}
					if($oldbalance!=$transbalance){
						if($transbalance==null){
							$transbalance=0.0;
							$queryBal = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus) VALUES ('{$oldcardno}', '2016-12-31', concat('Balance B/F ','{$oldbalance}'), 0.0, 0.0, {$oldbalance}, 'b/f', 'Admin', 'contribution', '1', '{$translineno}', 0, 'Pending')";
							mysql_query($queryBal, $connection);
						}
						
						echo "  D   ".$oldbalance." - ".$transbalance;
						
						$queryBal = "update transactions set balance={$oldbalance}, narration=concat('Balance B/F ','{$oldbalance}') where cardno='{$oldcardno}' and transdate='2016-12-31 00:00:00' and transtype='b/f'";
						mysql_query($queryBal, $connection);
						updateTransBalances($oldcardno, '2016-12-31 00:00:00');
					}//else{
					//	echo "  S   ".$oldbalance."  ".$transbalance;
					//	$queryBal = "update transactions set narration=concat('Balance B/F ','{$oldbalance}') where cardno='{$oldcardno}' and transdate='2016-12-31 00:00:00' and transtype='b/f'";
					//	mysql_query($queryBal, $connection);
					//}
					
					echo "  ____<br>";
				}
				$mycardno=$cardno;
			}
			$oldcardno=$cardno;
			$oldbalance=$archive_balance;
        }
    }
	
	
	
	
	function updateTransBalances($cardno, $date) {
		include("data.php");
		$balances = 0;
		$query = "SELECT max(transdate) as transdates FROM transactions where cardno='{$cardno}' and transdate<'{$date}' ";
		$result = mysql_query($query, $connection);
		$row = mysql_fetch_array($result);
		extract($row);
		
		if ($transdates == null || $transdates == "") {
			$query = "SELECT balance as balances FROM transactions_archive where concat(transdate, serialno)=(select max(concat(transdate, serialno)) from transactions_archive where cardno='{$cardno}')";
			$result = mysql_query($query, $connection);
			$row = mysql_fetch_array($result);
			extract($row);
		}	
		
		if ($transdates != null && $transdates != "" && ($balances == null || $balances == "" || $balances == 0)) {
			$query = "SELECT balance FROM transactions where cardno='{$cardno}' and  transdate='{$transdates}' ";
			$result = mysql_query($query, $connection);
			if (mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_row($result)) {
					extract($row);
					$balances = $row[0];
				}
			}
		}
		$query = "SELECT * FROM transactions where cardno='{$cardno}' and transdate>='{$date}' order by transdate, serialno";
		$result = mysql_query($query, $connection);
	//setcookie("myresponse", $query."     ".$balances, false);
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_row($result)) {
				extract($row);
				$balances = $balances + $row[4] - $row[5];
				$queryBal = "update transactions set balance='{$balances}' where serialno='{$row[0]}' ";
				mysql_query($queryBal, $connection);
			}
		}
		return true;
	}
	
?>