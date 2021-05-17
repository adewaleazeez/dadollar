<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	global $transdate;
	$transdate = trim($_GET['transdate']);
	if($transdate == null || $transdate == "--") $transdate = "0000-00-00";

	global $transdate2;
	$transdate2 = trim($_GET['transdate2']);
	if($transdate2 == null) $transdate2 = "";

	global $lineno;
	$lineno = trim($_GET['lineno']);
	if($lineno == null) $lineno = "";

	global $username;
	$username = trim($_GET['username']);
	if($username == null) $username = "";

	global $transtype;
	$transtype = trim($_GET['transtype']);
	if($transtype == null) $transtype = "";

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

			$transdate = $GLOBALS['transdate'];
			$transdate2 = $GLOBALS['transdate2'];
			$username = $GLOBALS['username'];
			$transtype = $GLOBALS['transtype'];

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
			$this -> Cell(255, 7, "CUSTOMERS TRANSACTION LISTING", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(255, 7, "Transaction Date: ".$transdate2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(10, 5, "S/No", 1, 0, 'R');
			$this -> Cell(35, 5, "User Name", 1, 0, 'L');
			$this -> Cell(25, 5, "Trans Type", 1, 0, 'L');
			$this -> Cell(15, 5, "Line No", 1, 0, 'L');
			$this -> Cell(25, 5, "Tran. Date", 1, 0, 'R');
			$this -> Cell(25, 5, "Card No", 1, 0, 'L');
			$this -> Cell(45, 5, "Customer Name", 1, 0, 'L');
			$this -> Cell(30, 5, "Balance B/F", 1, 0, 'R');
			$this -> Cell(30, 5, "Amount", 1, 0, 'R');
			$this -> Cell(30, 5, "New Balance", 1, 0, 'R');
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

	$transdate = $GLOBALS['transdate'];
	$transdate2 = $GLOBALS['transdate2'];
	$lineno = $GLOBALS['lineno'];
	$username = $GLOBALS['username'];
	$transtype = $GLOBALS['transtype'];
	$count=0;
	$deposits=0;
	$withdrawals=0;
	$commissions=0;
	$interests=0;
	$totaldeposits=0;
	$totalwithdrawals=0;
	$totalcommissions=0;
	$totalinterests=0;
	$totalbalances=0;
	$cardnos="";
	$dtranstype="";
	$dlineno="";
	$duser="";
	$tttype=0;	$tdeposit=0;	$tcommission=0;	$twithdrawal=0;

	$query = "SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transgroup, a.recordlock, a.post FROM transactionlist a where a.transdate='{$transdate}' and a.post='1' ";
	
	if($lineno!="") $query .= " and a.lineno='{$accesss}'  ";
	if($username!="") $query .= " and a.username='{$userNames}' ";
	$query .= " order by a.transdate, a.username, a.transtype, a.lineno, a.cardno ";

	$result = mysql_query($query, $connection);
	while ($row = mysql_fetch_array($result)) {
		extract ($row);
		if($dtranstype!=$transtype || $dlineno!=$lineno || $duser!=$username){
			if(($dtranstype!='' && $dtranstype!=$transtype) || ($dlineno!='' && $dlineno!=$lineno) || ($duser!='' && $duser!=$username)){
				$pdf -> Cell(240, 5, "Total ".$dtranstype." for Line No ".$dlineno.":  ".number_format($tttype,2), 1, 0, 'R');
				$pdf -> Cell(30, 5, "", 1, 0, 'C');
				$pdf -> Ln();

				$pdf -> Cell(270, 5, "", 1, 0, 'C');
				$pdf -> Ln();

				$pdf -> Cell(10, 5, "S/No", 1, 0, 'R');
				$pdf -> Cell(35, 5, "User Name", 1, 0, 'L');
				$pdf -> Cell(25, 5, "Trans Type", 1, 0, 'L');
				$pdf -> Cell(15, 5, "Line No", 1, 0, 'L');
				$pdf -> Cell(25, 5, "Tran. Date", 1, 0, 'C');
				$pdf -> Cell(25, 5, "Card No", 1, 0, 'L');
				$pdf -> Cell(45, 5, "Customer Name", 1, 0, 'L');
				$pdf -> Cell(30, 5, "Balance B/F", 1, 0, 'R');
				$pdf -> Cell(30, 5, "Amount", 1, 0, 'R');
				$pdf -> Cell(30, 5, "New Balance", 1, 0, 'R');
				$pdf -> Ln();
				$tttype=0; $count=0;
			}
			$dtranstype=$transtype;
			$duser=$username;
			$dlineno=$lineno;
		}

		if($transtype=='deposit') $tdeposit += ($amount);
		if($transtype=='commision') $tcommission += ($amount);
		if($transtype=='withdrawal') $twithdrawal += ($amount); 
		$tttype += ($amount); 
		
		$pdf -> Cell(10, 5, ++$count, 1, 0, 'R');
		$pdf -> Cell(35, 5, $username, 1, 0, 'L');
		$pdf -> Cell(25, 5, $transtype, 1, 0, 'L');
		$pdf -> Cell(15, 5, $lineno, 1, 0, 'L');
		$date = new DateTime($transdate);
		$pdf -> Cell(25, 5, $date->format('d/m/Y'), 1, 0, 'L');
		//$pdf -> Cell(25, 5, $transdate, 1, 0, 'C');
		$pdf -> Cell(25, 5, $cardno, 1, 0, 'L');
		$pdf -> Cell(45, 5, $names, 1, 0, 'L');
		$pdf -> Cell(30, 5, $balance_b, 1, 0, 'R');
		$pdf -> Cell(30, 5, $amount, 1, 0, 'R');
		$pdf -> Cell(30, 5, $balance_a, 1, 0, 'R');
		$pdf -> Ln();
    }
	$pdf -> Cell(240, 5, "Total ".$dtranstype." for Line No ".$dlineno.":  ".number_format($tttype,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, "", 1, 0, 'C');
	$pdf -> Ln();

	$pdf -> Cell(270, 5, "", 1, 0, 'C');
	$pdf -> Ln();

	$pdf -> Cell(240, 5, "Total for Deposit:  ".number_format($tdeposit,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, "", 1, 0, 'C');
	$pdf -> Ln();

	$pdf -> Cell(240, 5, "Total for Withdrawal:  ".number_format($twithdrawal,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, "", 1, 0, 'C');
	$pdf -> Ln();

	$pdf -> Cell(240, 5, "Total for Commission:  ".number_format($tcommission,2), 1, 0, 'R');
	$pdf -> Cell(30, 5, "", 1, 0, 'C');
	$pdf -> Ln();

	$pdf->Output();
?>
