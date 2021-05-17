<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	global $balancedate;
	$balancedate = trim($_GET['balancedate']);
	if($balancedate == null) $balancedate = "";

	global $balancedate2;
	$balancedate2 = trim($_GET['balancedate2']);
	if($balancedate2 == null) $balancedate2 = "";

	global $cardno1;
	$cardno1 = trim($_GET['cardno1']);
	if($cardno1 == null) $cardno1 = "";

	global $cardno2;
	$cardno2 = trim($_GET['cardno2']);
	if($cardno2 == null) $cardno2 = "";

	global $baltype;
	$baltype = trim($_GET['baltype']);
	if($baltype == null) $baltype = "";


	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");

	$currentusers = $_COOKIE['currentuser'];

	require('fpdf.php');

	class PDF extends FPDF {

		var $B;
		var $I;
		var $U;
		var $HREF;

		function PDF($orientation ='P', $unit='mm', $size='A4'){
			// Call parent constructor
			$this -> FPDF($orientation, $unit, $size);
			// Initialization
			$this -> B = 0;
			$this -> I = 0;
			$this -> U = 0;
			$this -> HREF = '';
		}

			// Page header
		function Header() {
			// Logo
			$balancedate = $GLOBALS['balancedate'];
			$balancedate2 = $GLOBALS['balancedate2'];
			$cardno1 = $GLOBALS['cardno1'];
			$cardno2 = $GLOBALS['cardno2'];
			$baltype = $GLOBALS['baltype'];

			//$this -> Image('images\Schoologo.png',10,10,20,20);

			$this -> Ln(2);
			$this -> SetY($currentY);
			//$this->SetX($this->GetX()+95);
			$this -> SetFont('Times','B',12);
			$this -> Cell(150, 7, "DA-DOLLAR GLOBAL RESOURCES LIMITED", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(150, 7, "Inside Irepodun Market, Beside Main Street Microfinance Bank ", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(150, 7, "(TARMAK) Ikotun, Lagos", 0, 0, 'C');
			$this -> Ln(10);
			$reptype="";
			if($baltype=="Credit") $reptype=" (Credit Balances)";
			if($baltype=="Debit") $reptype=" (Debit Balances)";
			if($baltype=="Zero") $reptype=" (Zero Balances)";
			$this -> Cell(150, 7, "CUSTOMER BALANCES".$reptype, 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(150, 7, "BALANCES AS AT ".$balancedate2."     From Card No: ".$cardno1."   To Card No: ".$cardno2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(10, 5, "S/No", 1, 0, 'R');
			$this -> Cell(35, 5, "Card No", 1, 0, 'L');
			$this -> Cell(120, 5, "Names", 1, 0, 'L');
			$this -> Cell(30, 5, "Balances", 1, 0, 'R');
			$this -> Ln();

		}

		// Page footer
		function Footer() {
			include("data.php");
			// Position at 1.5 cm from bottom
			$this -> SetY(-10);
			$this -> SetFont('Times','B',7.5);
			$this -> SetFont('Times','B',10);
			$this -> Cell(0, 5,'Page '.$this -> PageNo().'/{nb}',0,0,'C');
		}

	}

	function str_in_str($str,$token){
		$retunrtype=false;
		for($k=0; $k<=(strlen($str)-strlen($token)); $k++){
			if(substr($str, $k, strlen($token))==$token){
				$retunrtype=true;
				break;
			}
		}
		return $retunrtype;
	}

	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','B',12);

	$balancedate = $GLOBALS['balancedate'];
	$balancedate2 = $GLOBALS['balancedate2'];
	$cardno1 = $GLOBALS['cardno1'];
	$cardno2 = $GLOBALS['cardno2'];
	$baltype = $GLOBALS['baltype'];
	$count=0;
	$balances=0;
	$cardnos="";

	$query = "delete FROM customers where cardno is null";
	mysqli_query($connection, $query);

	/*$query = "SELECT cardno, count(cardno) as counts FROM customers group by cardno";
	$result = mysql_query($query, $connection);
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		extract ($row);
		if($counts>1){
			$query1 = "SELECT max(serialno) as maxno FROM customers where cardno='".$cardno."'";
			$result1 = mysql_query($query1, $connection);
			$row1 = mysqli_fetch_array($result1, MYSQLI_NUM);
			extract ($row1);

			$query2 = "DELETE FROM customers where cardno='".$cardno."' and serialno<>'".$maxno."'";
			$result2 = mysql_query($query2, $connection);
//echo $query1."<br>".$query2."<br><br>";

		}
	}*/
	$startdate =  date('Y-m-d', strtotime($balancedate. ' - 29 days'));
	$startdate = substr($startdate, 0, 8) ."01";
	//echo $startdate;
	
	$query = "DELETE FROM trans_temp";
	mysqli_query($connection, $query);
	
	$query = "INSERT INTO trans_temp (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus)";
	//$query .= "UNION SELECT cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus FROM transactions_archive transactions";
	$query .= "  SELECT cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus FROM ";
	$query .= " (SELECT * FROM transactions_archive where transdate >='{$startdate}' and transdate<='{$balancedate}' ";
	$query .= " UNION SELECT * FROM transactions where transdate >='{$startdate}' and transdate<='{$balancedate}') as a";
	mysqli_query($connection, $query);
//echo $query."<br><br>";

	$mycardnos = "";
	$query = "select max(concat(transdate,serialno)) as tdate FROM trans_temp where cardno>='{$cardno1}' and cardno<='{$cardno2}' and transdate<='{$balancedate}' group by cardno";
//echo $query."<br><br>";
	$result = mysqli_query($connection, $query);
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		extract ($row);
		$tdate = $row[0];
		//$mycardnos .= $row['0'] . ",";
		$mycardnos .= substr($tdate, 19, strlen($tdate)). ",";
//echo $row['0']."<br>";
	}
//echo $mycardnos;
	$mycardnos =  substr($mycardnos,0,strlen($mycardnos)-1);
	$query = "SELECT a.cardno, (select concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance FROM trans_temp a where a.serialno in (".$mycardnos.") order by a.cardno, a.transdate";
//echo $query;
//$negativebalance=0;
//$positivebalance=0;
	$result = mysqli_query($connection, $query);
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		extract ($row);
		$cardno = $row[0];
		$names = $row[1];
		$transdate = $row[2];
		$balance = $row[3];
		
		if($cardno!=$cardnos){
			$cardnos=$cardno;
			if($baltype=="Credit" && $balance<=0) continue;
			if($baltype=="Debit" && $balance>=0) continue;
			if($baltype=="Zero" && $balance<>0) continue;
			$pdf -> Cell(10, 5, ++$count, 1, 0, 'R');
			$pdf -> Cell(35, 5, substr($cardno,0,18), 1, 0, 'L');
			$pdf -> Cell(120, 5, substr($row[1],0,44), 1, 0, 'L');
			if($balance<0) $pdf -> SetTextColor(194,8,8);
			$pdf -> Cell(30, 5, number_format($balance), 1, 0, 'R');
			$pdf -> SetTextColor(0,0,0);
			$pdf -> Ln();
			//if($balance<0) $negativebalance = $negativebalance + $balance;
			//if($balance>0) $positivebalance = $positivebalance + $balance;
			$balances = $balances + $balance;
		}
	}
	$pdf -> Cell(10, 5, "", 1, 0, 'R');
	$pdf -> Cell(35, 5, "", 1, 0, 'L');
	$pdf -> Cell(120, 5, "Total:", 1, 0, 'R');
	if($balances<0) $pdf -> SetTextColor(194,8,8);
	$pdf -> Cell(30, 5, number_format($balances), 1, 0, 'R');
	$pdf -> SetTextColor(0,0,0);
	/*$pdf -> Ln();
	$pdf -> Cell(10, 5, "", 1, 0, 'R');
	$pdf -> Cell(35, 5, "Totals:", 1, 0, 'L');
	$pdf -> Cell(120, 5, number_format($negativebalance), 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($positivebalance), 1, 0, 'R');
	$pdf -> SetTextColor(0,0,0);*/
	$pdf->Output();
	mysqli_close($connection);
?>
