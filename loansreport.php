<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	global $startdate;
	$startdate = trim($_GET['startdate']);
	if($startdate == null) $startdate = "";

	global $startdate2;
	$startdate2 = trim($_GET['startdate2']);
	if($startdate2 == null) $startdate2 = "";

	global $enddate;
	$enddate = trim($_GET['enddate']);
	if($enddate == null) $enddate = "";

	global $enddate2;
	$enddate2 = trim($_GET['enddate2']);
	if($enddate2 == null) $enddate2 = "";

	global $cardno1;
	$cardno1 = trim($_GET['cardno1']);
	if($cardno1 == null) $cardno1 = "";

	global $cardno2;
	$cardno2 = trim($_GET['cardno2']);
	if($cardno2 == null) $cardno2 = "";


	include("data.php");

	$currentusers = $_COOKIE['currentuser'];

	require('fpdf.php');

	class PDF extends FPDF {

		var $B;
		var $I;
		var $U;
		var $HREF;

		function PDF($orientation ='L', $unit='mm', $size='A4'){
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
			include("data.php");

			$startdate = $GLOBALS['startdate'];
			$startdate2 = $GLOBALS['startdate2'];
			$enddate = $GLOBALS['enddate'];
			$enddate2 = $GLOBALS['enddate2'];
			$cardno1 = $GLOBALS['cardno1'];
			$cardno2 = $GLOBALS['cardno2'];

			include("data.php");

			//$this -> Image('images\Schoologo.png',10,10,20,20);

			$this -> Ln(2);
			$this -> SetY($currentY);
			//$this->SetX($this->GetX()+95);
			$this -> SetFont('Times','B',12);
			$this -> Cell(255, 7, "DA-DOLLAR GLOBAL RESOURCES LIMITED", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(255, 7, "Inside Irepodun Market, Beside Main Street Microfinance Bank ", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(255, 7, "(TARMAK) Ikotun, Lagos", 0, 0, 'C');
			$this -> Ln(10);
			$this -> Cell(255, 7, "LOANS TRANSACTION LISTING", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(255, 7, "REPORT For Card No: ".$cardno1."   To Card No: ".$cardno2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(255, 7, "Start Date: ".$startdate2."   To End Date: ".$enddate2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(10, 5, "S/No", 1, 0, 'R');
			$this -> Cell(35, 5, "Card No", 1, 0, 'L');
			$this -> Cell(65, 5, "Names", 1, 0, 'L');
			$this -> Cell(25, 5, "Tran. Date", 1, 0, 'R');
			$this -> Cell(30, 5, "Deposits", 1, 0, 'R');
			$this -> Cell(30, 5, "Witdrawals", 1, 0, 'R');
			$this -> Cell(30, 5, "Interests", 1, 0, 'R');
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

	$startdate = $GLOBALS['startdate'];
	$startdate2 = $GLOBALS['startdate2'];
	$enddate = $GLOBALS['enddate'];
	$enddate2 = $GLOBALS['enddate2'];
	$cardno1 = $GLOBALS['cardno1'];
	$cardno2 = $GLOBALS['cardno2'];
	$count=0;
	$deposits=0;
	$withdrawals=0;
	$interests=0;
	$totaldeposits=0;
	$totalwithdrawals=0;
	$totalinterests=0;
	$totalbalances=0;
	$cardnos="";

	$query = "SELECT a.cardno, (select concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.credit, a.debit, a.balance, a.transtype FROM transactions a where a.cardno>='{$cardno1}' and a.cardno<='{$cardno2}' and a.transdate>='{$startdate}' and a.transdate<='{$enddate}' and a.transgroup='loan' order by a.cardno, a.transdate, a.serialno ";

	$result = mysql_query($query, $connection);
	while ($row = mysql_fetch_array($result)) {
		extract ($row);
		if($cardno>$cardnos){
			if($cardnos!=""){
				$pdf -> Cell(10, 5, "", 1, 0, 'R');
				$pdf -> Cell(35, 5, "", 1, 0, 'L');
				$pdf -> Cell(65, 5, "", 1, 0, 'R');
				$pdf -> SetTextColor(0,100, 0);
				$pdf -> Cell(25, 5, "Total:", 1, 0, 'R');
				$pdf -> Cell(30, 5, number_format($deposits,2), 1, 0, 'R');
				$pdf -> Cell(30, 5, number_format($withdrawals,2), 1, 0, 'R');
				$pdf -> Cell(30, 5, number_format($interests,2), 1, 0, 'R');
				if(($deposits - $withdrawals - $interests)<0) $pdf -> SetTextColor(255, 0, 0);
				$pdf -> Cell(30, 5, number_format($deposits - $withdrawals - $interests,2), 1, 0, 'R');
				$pdf -> SetTextColor(0,0,0);
				$pdf -> Ln();
				$totalbalances += $deposits - $withdrawals - $interests;
				$deposits=0;
				$withdrawals=0;
				$interests=0;
			}
			$pdf -> Cell(10, 5, ++$count, "LR", 0, 'R');
			$pdf -> Cell(35, 5, $cardno, "LR", 0, 'L');
			$pdf -> Cell(65, 5, $row[1], "LR", 0, 'L');
			$date = new DateTime($transdate);
			$pdf -> Cell(25, 5, $date->format('d/m/Y'), "LR", 0, 'L');
			if($transtype=="deposit"){ 
				$deposits+= $credit;
				$totaldeposits+= $credit;
				$pdf -> Cell(30, 5, number_format($credit,2), "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			}else if($transtype=="withdrawal"){ 
				$withdrawals+= $debit;
				$totalwithdrawals+= $debit;
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, number_format($debit,2), "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			}else if($transtype=="interest"){ 
				$interests+= $debit;
				$totalinterests+= $debit;
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, number_format($debit,2), "LR", 0, 'R');
			}
			$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			$pdf -> Ln();
			$cardnos=$cardno;
		}else{
			$pdf -> Cell(10, 5, ++$count, "LR", 0, 'R');
			$pdf -> Cell(35, 5, $cardno, "LR", 0, 'L');
			$pdf -> Cell(65, 5, $row[1], "LR", 0, 'L');
			$date = new DateTime($transdate);
			$pdf -> Cell(25, 5, $date->format('d/m/Y'), "LR", 0, 'L');
			if($transtype=="deposit"){ 
				$deposits+= $credit;
				$totaldeposits+= $credit;
				$pdf -> Cell(30, 5, number_format($credit,2), "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			}else if($transtype=="withdrawal"){ 
				$withdrawals+= $debit;
				$totalwithdrawals+= $debit;
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, number_format($debit,2), "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			}else if($transtype=="interest"){ 
				$interests+= $debit;
				$totalinterests+= $debit;
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, "", "LR", 0, 'R');
				$pdf -> Cell(30, 5, number_format($debit,2), "LR", 0, 'R');
			}
			$pdf -> Cell(30, 5, "", "LR", 0, 'R');
			$pdf -> Ln();
		}
	}

	$pdf -> Cell(10, 5, "", 1, 0, 'R');
	$pdf -> Cell(35, 5, "", 1, 0, 'L');
	$pdf -> Cell(65, 5, "", 1, 0, 'R');
	$pdf -> SetTextColor(0,100, 0);
	$pdf -> Cell(25, 5, "Total:", 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($deposits,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($withdrawals,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($interests,2), 1, 0, 'R');
	if(($deposits - $withdrawals - $interests)<0) $pdf -> SetTextColor(255, 0, 0);
	$pdf -> Cell(30, 5, number_format($deposits - $withdrawals - $interests,2), 1, 0, 'R');
	$pdf -> SetTextColor(0,0,0);
	$pdf -> Ln();
	$totalbalances += $deposits - $withdrawals - $interests;

	$pdf -> Cell(10, 5, "", 1, 0, 'R');
	$pdf -> Cell(35, 5, "", 1, 0, 'L');
	$pdf -> Cell(65, 5, "", 1, 0, 'R');
	$pdf -> SetTextColor(0, 0,255);
	$pdf -> Cell(25, 5, "Grand Total:", 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($totaldeposits,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($totalwithdrawals,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, number_format($totalinterests,2), 1, 0, 'R');
	if($totalbalances<0) $pdf -> SetTextColor(255, 0, 0);
	$pdf -> Cell(30, 5, number_format($totalbalances,2), 1, 0, 'R');
	$pdf -> SetTextColor(0,0,0);
	$pdf->Output();
?>
