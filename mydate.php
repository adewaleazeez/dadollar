<?php
include("data.php");
/*$datetoday = date("Y-m-d");
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
echo $lastDateOfMonth;

$query = "SELECT max(cardno) as cardno FROM customers";
					$result = mysql_query($query, $connection);
					$row = mysql_fetch_array($result);
					extract ($row);
					echo $row[0];
return true;*/
//$query = "select cardno from transactions_archive where cardno>='81961' group by cardno union select cardno from transactions where cardno>='81961' group by cardno order by cardno";
$query = "select cardno from transactions_archive where cardno>='10002' group by cardno union select cardno from transactions where cardno>='10002' group by cardno order by cardno";
$result = mysql_query($query, $connection);
echo "Started............<br>";
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_row($result)) {
		extract($row);
		//echo $row[0]."<br>";
		//if ($row[0] <> "10002") break;
		$firstDateString = '2015-05-09';
		$lastDateString = '2019-01-31';
		$lastDateOfMonth = date("Y-m-t", strtotime($firstDateString));
		while ($lastDateOfMonth <= $lastDateString){
			$table = "";
			$table2 = "";
			if ($lastDateOfMonth < "2018-06-30"){
				$table = "transactions_archive";
			}else if ($lastDateOfMonth == "2018-06-30"){
				$table = "transactions_archive";
				$table2 = "transactions";
			}else{
				$table = "transactions";
			}
			
			$query1 = "SELECT * FROM $table  where cardno='{$row[0]}' and transdate<='{$lastDateOfMonth}' order by concat(transdate, serialno) desc limit 1";
			$result1 = mysql_query($query1, $connection);
			if (mysql_num_rows($result1) > 0) {
				$query2 = "SELECT * FROM $table  where cardno='{$row[0]}' and month(transdate)=month('{$lastDateOfMonth}') and year(transdate)=year('{$lastDateOfMonth}') and narration='Balance B/F' ";
				$result2 = mysql_query($query2, $connection);
				$row1 = mysql_fetch_row($result1);
				extract($row1);
				
				if (mysql_num_rows($result2) <= 0) {
					$query3 = "insert into $table (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) ";
					$query3 .= "values ('{$row1[1]}', '{$lastDateOfMonth}', 'Balance B/F', 0, 0, '{$row1[6]}', 'B/F', 'Admin', 'B/F', 1, '{$row1[11]}', '0', '', '')";
					mysql_query($query3, $connection);
				}else{
					$query3 = "update $table set balance='{$row1[11]}' where cardno='{$row1[1]}' and transdate='{$lastDateOfMonth}' and narration='B/F' ";
					mysql_query($query3, $connection);
				}				
				
				$qry="UPDATE currentrecord set currentrecordprocessing = '".$row1[1]."   ".$lastDateOfMonth."   ".$row1[11]."' where currentuser='Admin'";
				mysql_query($qry, $connection);
					
			}
			if($table2 == "transactions"){
				
				$query1 = "SELECT * FROM $table  where cardno='{$row[0]}' and transdate<='{$lastDateOfMonth}' order by concat(transdate, serialno) desc limit 1";
				$result1 = mysql_query($query1, $connection);
				//echo mysql_num_rows($result1)."<br>";
				if (mysql_num_rows($result1) > 0) {
					$query2 = "SELECT * FROM $table2  where cardno='{$row[0]}' and month(transdate)=month('{$lastDateOfMonth}') and year(transdate)=year('{$lastDateOfMonth}') and narration='Balance B/F' ";
					$result2 = mysql_query($query2, $connection);
					$row1 = mysql_fetch_row($result1);
					extract($row1);
					//echo mysql_num_rows($result2)."<br>";
					if (mysql_num_rows($result2) <= 0) {
						$query3 = "insert into transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) ";
						$query3 .= "values ('{$row1[1]}', '{$lastDateOfMonth}', 'Balance B/F', 0, 0, '{$row1[6]}', 'B/F', 'Admin', 'B/F', 1, '{$row1[11]}', '0', '', '')";
						mysql_query($query3, $connection);
					}else{
						$query3 = "update transactions set balance='{$row1[11]}' where cardno='{$row1[1]}' and transdate='{$lastDateOfMonth}' and narration='B/F' ";
						mysql_query($query3, $connection);
					}
				}
				
				$qry="UPDATE currentrecord set currentrecordprocessing = '".$row1[1]."   ".$lastDateOfMonth."   ".$row1[11]."' where currentuser='Admin'";
				mysql_query($qry, $connection);
				$table2="";
			}
			
			$lastDateOfMonth =  date('Y-m-d', strtotime($lastDateOfMonth. ' + 5 days'));
			$lastDateOfMonth = date("Y-m-t", strtotime($lastDateOfMonth));
		}
	}
}
echo "Finished............<br>";
?>