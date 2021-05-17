<?php
include("data.php");

$query = "select count(cardno), cardno from transactions where transtype != 'Open. Bal.' and transno>0 group by cardno";
echo $query."<br><br>";
$result = mysql_query($query, $connection);
if(mysql_num_rows($result) > 0){
	$count=0;
	while ($row = mysql_fetch_row($result)) {
		extract ($row);
		$queryTrans = "select count(cardno), cardno from transactionlist where post='1' and lineno is not null and transtype != 'Open. Bal.' and cardno='{$row[1]}' ";
		$resultTrans = mysql_query($queryTrans, $connection);
		$rowTrans = mysql_fetch_array($resultTrans);
		if(mysql_num_rows($resultTrans) > 0){
			if($row[0]==$rowTrans[0]){
				//echo ++$coun." good match ".$row[0]." ".$row[1]."      ".$rowTrans[0]." ".$rowTrans[1]."<br>";
			}else{
				echo ++$coun." bad  match ".$row[0]." ".$row[1]."      ".$rowTrans[0]." ".$rowTrans[1]."<br>";
			}
		}else{
			echo ++$coun." not  found ".$row[0]." ".$row[1]."      ".$rowTrans[0]." ".$rowTrans[1]."<br>";
		}
	}
}



/*$query = "select * from transactionlist where post='1' and transtype != 'Open. Bal.' and serialno not in (select transno from transactions where transno>0) order by transdate, username, lineno, serialno";
echo $query."<br><br>";
$result = mysql_query($query, $connection);
if(mysql_num_rows($result) > 0){
	$count=0;
	while ($row = mysql_fetch_row($result)) {
		extract ($row);

		//$queryTrans = "update transactionlist  set recordlock='1', post='1' where serialno='{$row[0]}' ";
		//mysql_query($queryTrans, $connection);

		$queryTrans = "SELECT * FROM transactions where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}' and transno='{$row[0]}'  and (debit='{$row[5]}' or credit='{$row[5]}') ";
		$resultTrans = mysql_query($queryTrans, $connection);
		$credit="0"; $debit="0"; $balance="0";
		if($row[7]=="deposit" || $row[7]=="loandeposits"){
			$credit=$row[5];
		}else{
			$debit=$row[5];
		}
		$narration="The sum of ".$row[5]." being ".$row[7]." by ".$row[2];
		$username=$_COOKIE['currentuser'];

		if(mysql_num_rows($resultTrans) > 0){
			//credit='{$credit}', debit='{$debit}', balance='{$balance}', username='{$row[9]}',,  narration='{$narration}'
			$queryTrans = "update transactions  set  transno='{$row[0]}' where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}' and (debit='{$row[5]}' or credit='{$row[5]}') ";
echo (++$count)."   ".$queryTrans."<br>";
			mysql_query($queryTrans, $connection);
			//$serialnos = $serialno;
			//updateTransBalances($row[2],$row[3]);
		}else{
			$queryTrans = "select max(serialno) as id from transactions ";
			$resultTrans = mysql_query($queryTrans, $connection);
			$rowTrans = mysql_fetch_array($resultTrans);
			extract ($rowTrans);
			$serialnos = intval($rowTrans[0])+1;

			$queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ('{$serialnos}', '{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}') ";
echo (++$count)."   ".$queryTrans."<br>";
			mysql_query($queryTrans, $connection);
			//updateTransBalances($row[2],$row[3]);
		}
	}
}*/

?>