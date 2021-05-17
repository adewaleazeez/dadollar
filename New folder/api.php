<?php
ini_set('max_execution_time', -1);
include "config.php";
if(isset($_POST['but_submit'])){
	$sql_query = mysqli_real_escape_string($con,$_POST['txt_query']);

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
	
	//echo "Attack Type: ".$return_message;
	header('Location: home.php?retval=~_~'."Attack Type: ".$return_message."~_~".$sql_query);
}

?>
