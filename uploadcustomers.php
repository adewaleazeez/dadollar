<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	$results = 0;
	setcookie("resp", "", false);
	$resp = "";
	$currentusers = $_COOKIE['currentuser'];
	if (isset($_GET['ftype'])) {
		$ftype = $_GET['ftype'];
	}
	//include("data.php");
	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
	
	//$query = "INSERT INTO activities (userEmail, descriptions, activityDate, activityTime) VALUES ('{$currentusers}', '{$_FILES['txtFile']['type']}', '{$ftype}', '{$results}')";
	//mysqli_query($connection, $query);

	//if(ereg("application/vnd.ms-excel", $_FILES['txtFile']['type']) || ereg("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", $_FILES['txtFile']['type'])) {
	//if(str_in_str($_FILES['txtFile']['type'], "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || str_in_str($_FILES['txtFile']['type'], "application/vnd.ms-excel") ){
	
	$target_path = "";
	if($ftype=="pic"){
		$target_path = "photo/" . basename( $_FILES['txtFile']['name']);

		if(@move_uploaded_file($_FILES['txtFile']['tmp_name'], $target_path)) {
			$results = 1;
		}
		sleep(1);
		setcookie("filetype", "pic", false);
	}

	if($ftype=="doc"){
		$target_path = "photo/" . basename( $_FILES['txtFile2']['name']);

		if(substr($target_path, strlen($target_path)-4, strlen($target_path))==".xls"){
			if(@move_uploaded_file($_FILES['txtFile2']['tmp_name'], $target_path)) {
				$results = 1;
			}
		}
		sleep(1);
		setcookie("filetype", "doc", false);
	}

	if($ftype=="excel"){
		$resp ="blankfile";
		if(strlen(basename( $_FILES['txtFile3']['name']))>0) $resp ="wrongformat".basename( $_FILES['txtFile3']['name']);
		if(str_in_str($_FILES['txtFile3']['type'],"sheet") || str_in_str($_FILES['txtFile3']['type'],"excel") ){
			$target_path = "excelfiles/" . basename( $_FILES['txtFile3']['name']);

			if(substr($target_path, strlen($target_path)-4, strlen($target_path))==".xls"){
				if(@move_uploaded_file($_FILES['txtFile3']['tmp_name'], $target_path)) {
					$results = 1;
					$resp = "successful";
				}
			}

			sleep(1);
			setcookie("filetype", "excel", false);

			if($results==1){
				//setcookie("resp", "file uploaded", false);
				require_once 'reader.php';

				$filename=$target_path;
				//parseExcel($filename);
				//$prod=parseExcel($filename);
				//echo"<pre>";
				//print_r($prod);

				//function parseExcel($excel_file_name_with_path){
				$data = new Spreadsheet_Excel_Reader();
				// Set output Encoding.
				$data->setOutputEncoding('CP1251');
				//$data->read($excel_file_name_with_path);
				$data->read($target_path);


				for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
						if($i>1){
							if($j==1) $sno = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==2) $cardno = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==3) $lastname = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==4) $othernames = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==5) $sex = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==6) $telephone = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==7) $address = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==8) $picture = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==9) $openingbalance = trim($data->sheets[0]['cells'][$i][$j])."";
							/*if($j==10) $recordlock= trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==11) $withdrawalock= trim($data->sheets[0]['cells'][$i][$j])."";

							if($j==12){ 
								$disbursedate = trim($data->sheets[0]['cells'][$i][$j])."";
								if($disbursedate==null || $disbursedate==""){
									$disbursedate='0000-00-00';
								}else{
									$disbursedate = new DateTime($disbursedate);
									$disbursedate = $disbursedate->format('Y-m-d');
								}
							}
							if($j==13){
								$loanamount = trim($data->sheets[0]['cells'][$i][$j])."";
								if($loanamount==null || $loanamount=="") $loanamount='0';
							}
							if($j==14){
								$loaninterest = trim($data->sheets[0]['cells'][$i][$j])."";
								if($loaninterest==null || $loaninterest=="") $loaninterest='0';
							}
							if($j==15){
								$loanstartdate = trim($data->sheets[0]['cells'][$i][$j])."";
								if($loanstartdate==null || $loanstartdate=="") {
									$loanstartdate='0000-00-00';
								}else{
									$loanstartdate = new DateTime($loanstartdate);
									$loanstartdate = $loanstartdate->format('Y-m-d');
								}
							}
							if($j==16){
								$loanenddate = trim($data->sheets[0]['cells'][$i][$j])."";
								if($loanenddate==null || $loanenddate=="") {
									$loanenddate='0000-00-00';
								}else{
									$loanenddate = new DateTime($loanenddate);
									$loanenddate = $loanenddate->format('Y-m-d');
								}
							}
							if($j==17) $repayoption = trim($data->sheets[0]['cells'][$i][$j])."";
							if($j==18){
								$amountrepay = trim($data->sheets[0]['cells'][$i][$j])."";
								if($amountrepay==null || $amountrepay=="") $amountrepay='0';
							}
							if($j==18){*/
							if($j==10){
								$sno = str_replace("'", "`", trim($sno));
								$lastname = str_replace("'", "`", trim($lastname));
								$othernames = str_replace("'", "`", trim($othernames));
								$sex = str_replace("'", "`", trim($sex));
								$telephone = str_replace("'", "`", trim($telephone));
								$address = str_replace("'", "`", trim($address));
								$picture = str_replace("'", "`", trim($picture));
								$openingbalance = str_replace("'", "`", trim($openingbalance));
								if($cardno=="" && $lastname=="") {
									break;
								}
								$query = "SELECT * FROM customers where cardno='{$cardno}'";
								$result = mysqli_query($connection, $query);
								if(mysqli_num_rows($result) == 0){

									$query = "INSERT INTO customers (cardno, lastname, othernames, sex, telephone, address, passportpicture, openingbalance, recordlock, lockwithdrawal) values ('{$cardno}', '{$lastname}', '{$othernames}', '{$sex}', '{$telephone}', '{$address}', '{$picture}', '{$openingbalance}', '1', '')";
									$result=mysqli_query($connection, $query);
//$qry="UPDATE currentrecord set report = concat(report, ' - ', '".str_replace("'", "`", $query)."') where currentuser='Admin'";
//$result = mysqli_query($connection, $qry);
									
									/*if($disbursedate!='0000-00-00' && $loanamount!='0'){
										$quer = "INSERT INTO loancustomers (cardno, datedisbursed, loanamount, loaninterest, loanstartdate, loanenddate, repayoption, amountperrepay) values ('{$cardno}', '{$disbursedate}', '{$loanamount}', '{$loaninterest}', '{$loanstartdate}', '{$loanenddate}', '{$repayoption}', '{$amountrepay}')";
										$result=mysqli_query($connection, $query);
									}*/
									
									$transdate = date("Y-m-d");
									$balance = "0";
									$username = $_COOKIE['currentuser'];
									$recordlock = "1";
									if($openingbalance!="0"){
										$narration = "The sum of ".number_format($openingbalance,2)." being Opening Balance";
										$transgroup = "contribution";
										if($openingbalance>"0"){
											$debit = "0";
											$credit = $openingbalance;
											$transtype = "deposit";
										}else{
											$debit = doubleval($openingbalance) * -1;
											$credit = "0";
											$transtype = "withdrawal";
										}

										$query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) values ('{$cardno}', '{$transdate}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$transtype}', '{$username}', '{$transgroup}', '{$recordlock}')";
										$result=mysqli_query($connection, $query);

										$query = "SELECT max(serialno) as sno FROM transactions where cardno ='{$cardno}' ";
										$result = mysqli_query($connection, $query);
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										updateTransBalances($cardno, $row[0]);
									}
									/*if($loanamount!="0"){
										$narration = "The sum of ".number_format($loanamount,2)." being loan amount ";
										$transtype = "withdrawal";
										$debit = $loanamount;
										$credit = "0";
										$transgroup = "loan";

										$query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) values ('{$cardno}', '{$transdate}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$transtype}', '{$username}', '{$transgroup}', '{$recordlock}')";
										$result=mysqli_query($connection, $query);

										$query = "SELECT max(serialno) as sno FROM transactions where cardno ='{$cardno}' ";
										$result = mysqli_query($connection, $query);
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										updateTransBalances($cardno, $row[0]);
									}
									if($loaninterest!="0"){
										$narration = "The sum of ".number_format($loaninterest,2)." being loan interest ";
										$transtype = "interest";
										$debit = $loaninterest;
										$credit = "0";
										$transgroup = "loan";

										$query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) values ('{$cardno}', '{$transdate}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$transtype}', '{$username}', '{$transgroup}', '{$recordlock}')";
										$result=mysqli_query($connection, $query);

										$query = "SELECT max(serialno) as sno FROM transactions where cardno ='{$cardno}' ";
										$result = mysqli_query($connection, $query);
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										updateTransBalances($cardno, $row[0]);
									}*/
								}
							break;
							}
						}
					}
					if($resp != "successful"){
						break;
					}
				}
			}else{
				$results = 0;
				//$resp = "file not uploaded";
			}
		}
		

		$usernames = $_COOKIE['currentuser'];
		$activitydescriptions = $usernames." uploaded student data for Department: ".$department.", Program: ".$programme.", Level: ".$level.", Session: ".$sessions.", Semester: ".$semester;
		$activitydates = date("Y-m-d");
		$activitytimes = date("H:i:s");
		$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
		mysqli_query($connection, $query);
					
		sleep(1);
		$resp = str_replace(" ", "_", $resp);
		setcookie("resp", $resp, false);
	}
	mysqli_close($connection);
	
	function updateTransBalances($cardno,$sno){
		//include("data.php"); 
		$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
	
		$query = "SELECT max(serialno) as serialnos FROM transactions where cardno='{$cardno}' and serialno<'{$sno}' ";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_array($result, MYSQLI_NUM);
		extract ($row);
		$serialnos = $row[0];
		$balances=0;
		if($serialnos!=null && $serialnos!=""){
			$query = "SELECT balance FROM transactions where serialno='{$serialnos}' ";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
			extract ($row);
			$balances=$row[0];
		}

		$query = "SELECT * FROM transactions where cardno='{$cardno}' and serialno>='{$sno}' order by transdate, serialno";
		$result = mysqli_query($connection, $query);
		if(mysqli_num_rows($result) > 0){
			$count=0;
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				$balances = $balances + $row[4] - $row[5];
				$queryBal = "update transactions set balance='{$balances}' where serialno='{$row[0]}' ";
				mysqli_query($connection, $queryBal);
			}
		}
		mysqli_close($connection);
		return true;
	}

	function IsNaN($phones){
		$resp=false;
		for($k=0; $k<strlen($phones); $k++)	{
			if(strpos("1234567890.",substr($phones,$k,1))===false) {
				$resp=true;
			}
		}
		return $resp;
	}

	function checkCode($query){
		//include("data.php"); 
		$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
	
		$result = mysqli_query($connection, $query);
		$resp="";
		if(mysql_num_rows($result)==0) $resp .= "notinsetup";
		mysqli_close($connection);
		return $resp;
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

?>

<script language="javascript" type="text/javascript">
	window.top.window.stopUpload3(<?php echo $results; ?>);
</script>
