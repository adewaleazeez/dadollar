<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	global $cardno;
	$cardno = trim($_GET['cardno']);
	if($cardno == null) $cardno = "";

	global $name;
	$name = trim($_GET['name']);
	if($name == null) $name = "";

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


	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");

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
			$connection = mysqli_connect("localhost", "root", "admins", "dadollar");

			$cardno = $GLOBALS['cardno'];
			$name = $GLOBALS['name'];
			$startdate = $GLOBALS['startdate'];
			$startdate2 = $GLOBALS['startdate2'];
			$enddate = $GLOBALS['enddate'];
			$enddate2 = $GLOBALS['enddate2'];

			include("data.php");

			//$this -> Image('images\Schoologo.png',10,10,20,20);
			$query = "SELECT passportpicture FROM customers where cardno='{$cardno}' ";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			if($passportpicture!=null && $row[0]!="" && file_exists ("photo/".$passportpicture)){
				$this -> Image("photo/".$passportpicture,260,5,30,30);
			}else{
				$this -> Image("photo/silhouette.jpg",260,5,30,30);
			}
			$this -> Ln(2);
			$this -> SetY($currentY);
			//$this->SetX($this->GetX()+95);
			$this -> SetFont('Times','B',12);
			$this -> Cell(270, 7, "DA-DOLLAR GLOBAL RESOURCES LIMITED", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(270, 7, "Inside Irepodun Market, Beside Main Street Microfinance Bank ", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(270, 7, "(TARMAK) Ikotun, Lagos", 0, 0, 'C');
			$this -> Ln(10);
			$this -> Cell(270, 7, "CUSTOMER'S STATEMENTS OF ACCOUNT", 0, 0, 'C');
			$this -> Ln();
			$this -> Cell(270, 7, " For: (".$cardno.") ".$name."     From Start Date: ".$startdate2."   To End Date: ".$enddate2, 0, 0, 'L');
			$this -> Ln();
			$this -> Cell(10, 5, "S/No", 1, 0, 'R');
			$this -> Cell(25, 5, "Trans Date", 1, 0, 'L');
			$this -> Cell(170, 5, "Narration", 1, 0, 'L');
			$this -> Cell(25, 5, "Credit", 1, 0, 'R');
			$this -> Cell(25, 5, "Debit", 1, 0, 'R');
			$this -> Cell(25, 5, "Balance", 1, 0, 'R');
			$this -> Ln();
			mysqli_close($connection);
		}

		// Page footer
		function Footer() {
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

	$count=0;
	$query = "SELECT * FROM transactions_archive where cardno='{$cardno}' and transdate>='{$startdate}' and transdate<='{$enddate}'  ";
	$query .= " UNION SELECT * FROM transactions where cardno='{$cardno}' and transdate>='{$startdate}' and transdate<='{$enddate}' order by transdate, serialno ";
	$result = mysqli_query($connection, $query);
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		extract ($row);
		$pdf -> Cell(10, 5, ++$count, 1, 0, 'R');
		$date = new DateTime($row[2]);
		$pdf -> Cell(25, 5, $date->format('d/m/Y'), 1, 0, 'L');
		$splitnarration=explode(" - ", $row[3]);
		$pdf -> Cell(170, 5, substr($splitnarration[0],0,86), 1, 0, 'L');
		$pdf -> Cell(25, 5, number_format($row[4]), 1, 0, 'R');
		$pdf -> Cell(25, 5, number_format($row[5]), 1, 0, 'R');
		if($row[6]<0) $pdf -> SetTextColor(194,8,8);
		$pdf -> Cell(25, 5, number_format($row[6]), 1, 0, 'R');
		$pdf -> SetTextColor(0,0,0);
		$pdf -> Ln();
	} 
	$pdf->Output();
	mysqli_close($connection);
?>
