<?php 
	//$connection = mysql_pconnect('localhost','root','admins'); 
	$connection = mysqli_connect('localhost','root','admins'); 
	mysqli_select_db($connection, "dadollar");
?>