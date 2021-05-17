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
//echo $startdate." A <br>";
	global $enddate;
	$enddate = trim($_GET['enddate']);
	if($enddate == null) $enddate = "";

	global $enddate2;
	$enddate2 = trim($_GET['enddate2']);
	if($enddate2 == null) $enddate2 = "";
    
	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
	
	$query = "call dailySummary('$startdate')";
	mysqli_query($connection, $query);
	
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
			
			$startdate = $GLOBALS['startdate'];
			$startdate2 = $GLOBALS['startdate2'];
//echo $startdate." B <br>";
			$enddate = $GLOBALS['enddate'];
			$enddate2 = $GLOBALS['enddate2'];

			include("data.php");

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
			$this -> Cell(150, 7, "REPORT SUMMARY BY LINE NO From: ".$startdate2."   To: ".$enddate2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(12, 5, "S/No", 1, 0, 'R');
			$this -> Cell(16, 5, "Line No", 1, 0, 'L');
			$this -> Cell(22, 5, "Dates", 1, 0, 'L');
			$this -> Cell(25, 5, "Deposits", 1, 0, 'R');
			$this -> Cell(30, 5, "Withdrawals", 1, 0, 'R');
			$this -> Cell(30, 5, "Commission", 1, 0, 'R');
			$this -> Cell(30, 5, "Balances", 1, 0, 'R');
			$this -> Cell(30, 5, "Total Balances", 1, 0, 'R');
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
//echo $startdate." C <br>";
	$enddate = $GLOBALS['enddate'];
	$enddate2 = $GLOBALS['enddate2'];

	//$startdate = substr($startdate, 0, 8) ."01";
	//$enddate = substr($enddate, 0, 8) ."01";
	//serialno, lineno, trans_date, deposit, withdrawal, commission, balance, total_balance
	$query = "SELECT * from daily_summary where concat(trans_date,lineno) =(select max(concat(trans_date,lineno)) from daily_summary where trans_date<'{$startdate}') ";
    $result = mysqli_query($connection, $query);
    $total_balance=0;
    $trans_date='2015-05-01';
    if (mysqli_num_rows($result) > 0) {
	    $row = mysqli_fetch_array($result, MYSQLI_NUM);
        extract($row);
    }

    $query = "SELECT 0 as serialno,'B/F' as lineno,'{$row[2]}' as trans_date,'0' as deposit,'0' as withdrawal,'0' as commission,'0' as balance,'{$row[7]}' as total_balance UNION SELECT * from daily_summary where trans_date >='{$startdate}' and trans_date<='{$enddate}' order by trans_date, lineno ";
    if($startdate=="2015-04-01"){
        $query = "SELECT * from daily_summary where trans_date >='{$startdate}' and trans_date<='{$enddate}' order by trans_date, lineno";
    } 
	$result = mysqli_query($connection, $query);
	//echo $query;
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		extract ($row);
		$trans_date = $row[2];
		$deposit = $row[3];
		$withdrawal = $row[4];
		$commission = $row[5];
		$balance = $row[6];
		$total_balance = $row[7];
		
        $pdf -> Cell(12, 5, ++$count, 1, 0, 'R');
        $pdf -> Cell(16, 5, $lineno, 1, 0, 'L');
        $date = new DateTime($trans_date);
        $pdf -> Cell(22, 5, $date->format('d/m/Y'), 1, 0, 'L');
        $pdf -> Cell(25, 5, number_format($deposit), 1, 0, 'R');
        $pdf -> SetTextColor(194,8,8);
        $pdf -> Cell(30, 5, number_format($withdrawal), 1, 0, 'R');
        $pdf -> Cell(30, 5, number_format($commission), 1, 0, 'R');
        $pdf -> SetTextColor(0,0,0);
        if($balance<0) $pdf -> SetTextColor(194,8,8);
        $pdf -> Cell(30, 5, number_format($balance), 1, 0, 'R');
        $pdf -> SetTextColor(0,0,0);
        if($total_balance<0) $pdf -> SetTextColor(194,8,8);
        $pdf -> Cell(30, 5, number_format($total_balance), 1, 0, 'R');
        $pdf -> SetTextColor(0,0,0);
        $pdf -> Ln();
	}
	$pdf->Output();
	mysqli_close($connection);
?>
