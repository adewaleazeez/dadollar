<?php
	//header("Content-type: application/pdf"); 
	header("Content-type: application/x-msdownload"); 
	//header("Content-type: application/msword"); 
	header("Content-Disposition: attachment; filename=excel.xls"); 
	//header("Content-Disposition: attachment; filename=msword.doc"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	echo "$header"; 

	$resp = trim($_GET['resp']);
	if($resp == null) $resp = "";

	echo "<table border='1'>";
	
	$resp = explode("smsmsg", $resp);
	$sms = $resp[1];
	$resp = $resp[2];
	$resp = explode("_", $resp);

	echo "<tr><td colspan='3'>The Message: ".$sms."</td></tr>";
	echo "<tr><td colspan='3'>&nbsp;</td></tr>";
	echo "<tr><td>Name</td><td>Phone</td><td>Status</td></tr>";

	for($k=0; $k<count($resp); $k++){
		$cols = explode("~",$resp[$k]);
		echo "<tr><td>".$cols[0]."</td><td>".$cols[1]."</td><td>".$cols[2]."</td></tr>";
	}
	echo "</table>";
?>






