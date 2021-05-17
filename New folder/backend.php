<?php
include "config.php";

if(isset($_POST['but_submit'])){

	$sql_query = mysqli_real_escape_string($con,$_POST['txt_uname']);

	$query = "SELECT * FROM sqlia_api order by importance_order";
	$result = mysqli_query($con, $query);

	$return_message = "";

	if(mysqli_num_rows($result) > 0){
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			extract ($row);
			$id=$row[0]; $symbol_and_keyword=$row[1]; $sqlia_description=$row[2]; $importance_order=$row[3]; $sqlia_type=$row[4];
			$pos = strpos(strtoupper($sql_query), strtoupper($symbol_and_keyword));
			if($pos === false){
				$return_message = "Benign";
			}else{
				$return_message = $sqlia_type;
				break;
			}
		}
	}
	if($return_message == "Benign"){
		



		$uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
		$password = mysqli_real_escape_string($con,$_POST['txt_pwd']);

		if ($uname != "" && $password != ""){

			$sql_query = "select count(*) as cntUser from users where username='".$uname."' and password='".$password."'";
			$result = mysqli_query($con,$sql_query);
			$row = mysqli_fetch_array($result);

			$count = $row['cntUser'];

			if($count > 0){
				$_SESSION['uname'] = $uname;
				setcookie("ret_val", "");
				setcookie("posted_val", "");
				header('Location: home.php');
			}else{
				setcookie("ret_val", "Invalid username and password");
				header('Location: login.php?error=Invalid username and password');
			}

		}
	}else{
		header('Location: login.php?retval=~_~'."Attack Type: ".$return_message."~_~".$sql_query);
	}

}