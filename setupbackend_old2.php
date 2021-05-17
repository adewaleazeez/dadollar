<?php
//session_start();
$option = str_replace("'", "`", trim($_GET['option']));
$table = str_replace("'", "`", trim($_GET['table']));
$userNames = str_replace("'", "`", trim($_GET['userName']));
$serialnos = str_replace("'", "`", trim($_GET['serialno']));
$accesss = str_replace("'", "`", trim($_GET['access']));
$pages = str_replace("'", "`", trim($_GET['page']));
$menus = str_replace("'", "`", trim($_GET['menu']));
$actives = str_replace("'", "`", trim($_GET['active']));
$currentusers = str_replace("'", "`", trim($_GET['currentuser']));
if ($currentusers == null || $currentusers == "") {
    $currentusers = $_COOKIE['currentuser'];
}
$menuoption = str_replace("'", "`", trim($_GET['menuoption']));
$access = str_replace("'", "`", trim($_GET['access']));
$a_param1 = str_replace("'", "`", trim($_GET['a_param1']));
$a_param2 = str_replace("'", "`", trim($_GET['a_param2']));
$currentobject = str_replace("'", "`", trim($_GET['currentobject']));
include("data.php");

/* if($option == "checkAccess"){
  $query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = '{$menuoption}'";
  $result = mysql_query($query, $connection);
  if(mysql_num_rows($result) > 0){
  $row = mysql_fetch_array($result);
  extract ($row);

  if($accessibility == "Yes"){
  $resp = $menuoption."checkAccessSuccess".$access."checkAccessSuccess".$a_param1;
  }else{
  $resp="checkAccessFailed".$menuoption;
  }
  }else{
  $resp="checkAccessFailed".$menuoption;
  }
  echo $resp;
  } */

if ($option == "getAllMenus") {
    $query = "select max(serialno) as id from usersmenu";
    $result = mysql_query($query, $connection);
    $row = mysql_fetch_array($result);
    extract($row);
    $serialnos = $id;

    $query = "SELECT * FROM usersmenu where userName = ''";
    $result = mysql_query($query, $connection);

    while ($row = mysql_fetch_array($result)) {
        extract($row);

        $query2 = "SELECT * FROM usersmenu where userName = '" . $userNames . "' and menuOption = '{$menuOption}' order by serialno";
        $result2 = mysql_query($query2, $connection);
        if (mysql_num_rows($result2) == 0) {
            ++$serialnos;
            $query3 = "insert into usersmenu values ({$serialnos}, '{$userNames}', '{$menuOption}', '{$accessibility}')";
            mysql_query($query3, $connection);

            $usernames = $_COOKIE['currentuser'];
            $activitydescriptions = $usernames . " created menu " . $menuOption . " for user: " . $userNames;
            $activitydates = date("Y-m-d");
            $activitytimes = date("H:i:s");
            $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
            mysql_query($query, $connection);
        }
    }

    $resp = "getAllMenus";
    $query = "SELECT * FROM usersmenu where userName = '" . $userNames . "'";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            extract($row);
            $resp .= $serialno . "_~_" . $menuOption . "_~_" . $accessibility . "row_separator";
        }
    }
    if ($userNames == "") {
        $resp = "getAllMenus";
    }
    echo $resp;
}

if ($option == "changeAccess") {
    $query = "SELECT * FROM usersmenu where serialno = '{$serialnos}'";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        extract($row);
        $query = "update usersmenu set accessibility = '{$accesss}' where serialno = '{$serialnos}'";
        mysql_query($query, $connection);

        $usernames = $_COOKIE['currentuser'];
        $activitydescriptions = $usernames . " granted access to menu " . $menuOption . " to user: " . $userNames;
        if ($accesss == 'No') {
            $activitydescriptions = $usernames . " revoked access to menu " . $menuOption . " from user: " . $userNames;
        }
        $activitydates = date("Y-m-d");
        $activitytimes = date("H:i:s");
        $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
        mysql_query($query, $connection);
    } else {
        echo "accessupdatefailed";
    }
}

if ($option == "checkAccess") {
    $query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = '{$menuoption}'";
    $result = mysql_query($query, $connection);
    $resp = "checkAccess";
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        extract($row);

        if ($accessibility == "Yes") {
            $resp = "checkAccessSuccess";
        } else {
            $resp = "checkAccessFailed" . $menuoption;
        }
    } else {
        $resp = "checkAccessFailed" . $menuoption;
    }
    echo $resp;
}

if ($option == "updateLocks") {
    $currentuser = $currentusers;
    if ($table == 'customers') {
        $query = "select * from usersmenu  where userName='{$currentuser}' and menuOption='Lock Customers'";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            extract($row);
            $accessibility = $row[3];
            if ($accessibility == 'No') {
                echo "locknotallowed";
                return true;
            }
        }
    }
    $parameter = explode("][", $a_param1);
    for ($count = 0; $count < count($parameter); $count++) {
        $parameter[$count] = trim($parameter[$count]);
    }
    $query = "update {$table} set recordlock='{$parameter[1]}'  where serialno={$parameter[0]} ";
    mysql_query($query, $connection);
    if ($table == 'transactions') {
        $query = "select cardno, transdate, transtype, transgroup, lineno from transactions where serialno={$parameter[0]} ";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            extract($row);
            $query = "update transactionlist set recordlock='{$parameter[1]}' where cardno='{$row[0]}' and transdate='{$row[1]}' and transtype='{$row[2]}' and transgroup='{$row[3]}' and lineno='{$row[4]}' and serialno='{$row[12]}' ";
            mysql_query($query, $connection);
        }
    }
    $usernames = $_COOKIE['currentuser'];
    if ($parameter[1] == "1") {
        $activitydescriptions = $usernames . " locked Record No: " . $parameter[0];
    } else {
        $activitydescriptions = $usernames . " unlocked Record No: " . $parameter[0];
    }
    $activitydates = date("Y-m-d");
    $activitytimes = date("H:i:s");
    $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
    mysql_query($query, $connection);

    $option = "getAllRecs";
    if ($table == 'transactions') {
        $table = "lockrecords";
    } else if ($table == 'customers') {
        $table = "customers4";
    }
}

if ($option == "lockrecords") {
    $currentuser = $currentusers;
    if ($table == 'customers') {
        $query = "select * from usersmenu  where userName='{$currentuser}' and menuOption='Lock Customers'";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            extract($row);
            $accessibility = $row[3];
            if ($accessibility == 'No') {
                echo "locknotallowed";
                return true;
            }
        }
    }
    /* if($table="customers"){
      $query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = 'Lock Customers'";
      $result = mysql_query($query, $connection);
      $resp="checkAccess";
      if(mysql_num_rows($result) > 0){
      $row = mysql_fetch_array($result);
      extract ($row);

      if($accessibility == "Yes"){
      $resp="checkAccessSuccess";
      }else{
      $resp="checkAccessFailedLock Customers";
      echo $resp;
      return true;
      }
      }else{
      $resp="checkAccessFailedLock Customers";
      echo $resp;
      return true;
      }
      }

      $a_param2=substr($a_param2, 0, strlen($a_param2)-3);
      $parameter2 = explode("_~_", $a_param2);
      for($count=0; $count<count($parameter2); $count++)	$parameter2[$count]=trim($parameter2[$count]);
      $recordlist="";
      foreach($parameter2 as $code){
      $parameter3 = explode("!!!", $code);
      for($count=0; $count<count($parameter3); $count++)	$parameter3[$count]=trim($parameter3[$count]);

      $query = "SELECT * FROM {$table} where serialno='{$parameter3[0]}' ";
      //and groupsession='{$parameter1[6]}'
      $result = mysql_query($query, $connection);
      if(mysql_num_rows($result) == 1){
      $query="update {$table} set recordlock='{$parameter3[1]}' where serialno='{$parameter3[0]}' ";
      mysql_query($query, $connection);
      $lockststus=(($parameter3[1]=="1") ? "Locked" : "Unlocked");
      $recordlist.="[".$parameter3[0].": ".$lockststus."] ";
      }
      } */
    $query = "update {$table} set recordlock='{$access}' where serialno<>0 ";
    mysql_query($query, $connection);

    $usernames = $_COOKIE['currentuser'];
    $activitydescriptions = "";
    //$activitydescriptions = $usernames." locked/unlocked Record List: ".$recordlist;
    if ($access == "1") {
        $activitydescriptions = $usernames . " locked all records for customers";
    }
    if ($access == "1") {
        $activitydescriptions = $usernames . " unlocked all records for customers";
    }
    $activitydates = date("Y-m-d");
    $activitytimes = date("H:i:s");
    $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
    mysql_query($query, $connection);

    $option = "getAllRecs";
    if ($table == 'transactions') {
        $table = "lockrecords";
    } else if ($table == 'customers') {
        $table = "customers4";
    }
}

if ($option == "checkCourseLock") {
    $parameter = explode("][", $a_param1);
    $query = "select * FROM coursestable where serialno<>0 and coursecode = '{$parameter[0]}' and facultycode='{$parameter[1]}' and departmentcode='{$parameter[2]}' and programmecode='{$parameter[3]}' and studentlevel='{$parameter[4]}' and sessiondescription='{$parameter[5]}' and semesterdescription='{$parameter[6]}' and recordlock='1' ";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        echo "recordlocked for $parameter[0] in $parameter[5] $parameter[6] semester";
        return true;
    }
    echo "editCourseCode";
}

if ($option == "lockWithdrawal") {
    $query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = 'Lock Withdrawal'";
    $result = mysql_query($query, $connection);
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
    $resp = "checkAccess";
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        extract($row);

        if ($accessibility == "Yes") {
            $resp = "checkAccessSuccess";
        } else {
            $resp = "checkAccessFailedLock Withdrawal";
            echo $resp;
            return true;
        }
    } else {
        $resp = "checkAccessFailedLock Withdrawal";
        echo $resp;
        return true;
    }
    $query = "update customers set lockwithdrawal='{$a_param1}' where cardno = '{$accesss}' ";
    $result = mysql_query($query, $connection);
    $resp = "lockWithdrawal" . $a_param1;
    echo $resp;
}

/* if($option == "lockWithdrawal"){
  $query = "SELECT * FROM usersmenu where userName = '{$currentusers}' and menuOption = 'Lock Withdrawal'";
  $result = mysql_query($query, $connection);
  //$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
  //$result = mysql_query($qry, $connection);
  $resp="checkAccess";
  if(mysql_num_rows($result) > 0){
  $row = mysql_fetch_array($result);
  extract ($row);

  if($accessibility == "Yes"){
  $resp="checkAccessSuccess";
  }else{
  $resp="checkAccessFailedLock Withdrawal";
  echo $resp;
  return true;
  }
  }else{
  $resp="checkAccessFailedLock Withdrawal";
  echo $resp;
  return true;
  }
  $query = "update customers set lockwithdrawal='{$a_param1}' where cardno = '{$accesss}' ";
  $result = mysql_query($query, $connection);
  $resp="lockWithdrawal".$a_param1;
  echo $resp;
  } */

if ($option == "updateCardNo") {
    $a_param1 = explode("][", $a_param1);
    for ($count = 0; $count < count($a_param1); $count++) {
        $a_param1[$count] = trim($a_param1[$count]);
    }
    $oldcardnumber = $a_param1[0];
    $newcardnumber = $a_param1[1];
    $query = "SELECT cardno FROM customers where cardno='{$newcardnumber}'";
    $result = mysql_query($query, $connection);
    if ($newcardnumber == null || $newcardnumber == "") {
        echo "cardblank";
    } else if (mysql_num_rows($result) == 0) {
        $query = "update customers set cardno='{$newcardnumber}' where cardno ='{$oldcardnumber}' ";
        mysql_query($query, $connection);

        $query = "update loancustomers set cardno='{$newcardnumber}' where cardno ='{$oldcardnumber}' ";
        mysql_query($query, $connection);

        $query = "update transactions set cardno='{$newcardnumber}' where cardno ='{$oldcardnumber}' ";
        mysql_query($query, $connection);

        $query = "update transactionlist set cardno='{$newcardnumber}' where cardno ='{$oldcardnumber}' ";
        mysql_query($query, $connection);

        echo "cardupdated";
    } else {
        echo "cardexists";
    }
}
//$table="transactionlist2"; $option = "getAllRecs"; $serialnos='2015-04-17';
if ($option == "getAllRecs" || $option == "getRecordlist" || $option == "getARecord") {
	$query = "delete FROM customers where cardno is null";
	mysql_query($query, $connection);

    $query = "SELECT * FROM {$table} ";

    if ($table == "customers" && $option == "getAllRecs") {
		$query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers  order by cardno desc";
	}

    if ($table == "listCustomers" && $option == "getAllRecs") {
		if($accesss==""){
			$query = "SELECT serialno, lineno, cardno, concat(lastname, ' ', othernames) as names, telephone FROM customers where telephone<>'' order by cardno";
		}else{
			$query = "SELECT serialno, lineno, cardno, concat(lastname, ' ', othernames) as names, telephone FROM customers where telephone<>'' and lineno='".$accesss."'  order by cardno";
		}
    }

    if (($table == "customers2" && $_COOKIE['tabletype'] == "customers2") && $option == "getAllRecs") {
        $table = "customers";
        $query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where serialno like '%{$serialnos}%' or cardno like '%{$serialnos}%' or lastname like '%{$serialnos}%' or othernames like '%{$serialnos}%' or sex like '%{$serialnos}%' or telephone like '%{$serialnos}%' or address like '%{$serialnos}%' or passportpicture like '%{$serialnos}%' order by cardno desc";
    }

    if (($table == "customers3" && $_COOKIE['tabletype'] == "customers3") && $option == "getAllRecs") {
        $table = "customers";
        $query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where sex='{$serialnos}' order by cardno desc";
        if ($serialnos == "") {
            $query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where sex<>'' order by cardno desc";
        }
    }

    if ($table == "customers4" && $option == "getAllRecs") {
        $table = "customers";
        $sexid = $_COOKIE['sexid'];
        $search = $_COOKIE['searchid'];
        $query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where serialno>0 ";
        if ($sexid != null && $sexid != "") {
            $query .= " and sex='{$sexid}' ";
        }
        if ($search != null && $search != "") {
            $query .= " and (serialno like '%{$search}%' or cardno like '%{$search}%' or lastname like '%{$search}%' or othernames like '%{$search}%' or sex like '%{$search}%' or telephone like '%{$search}%' or address like '%{$search}%' or passportpicture like '%{$search}%') ";
        }
        $query .= " order by cardno desc";
    }

    if ($table == "customers5" && $option == "getAllRecs") {
        $table = "customers";
        $query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where cardno like '%{$serialnos}%' order by cardno desc";
    }

    if ($table == "deposits" && $option == "getAllRecs") {
        $today = $_COOKIE['today'];
        $query = "SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.narration, a.credit, a.debit, a.balance, a.transtype, a.recordlock FROM transactions_archive a where a.transdate='{$today}' and a.transgroup='contribution' and a.transtype='deposit'";
        $query .= " UNION SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.narration, a.credit, a.debit, a.balance, a.transtype, a.recordlock FROM transactions a where a.transdate='{$today}' and a.transgroup='contribution' and a.transtype='deposit' order by serialno desc";
    }

    if ($table == "withdrawals" && $option == "getAllRecs") {
        $cardnumber = $_COOKIE['cardnumber'];
        $date1 = $_COOKIE['date1'];
        $date2 = $_COOKIE['date2'];

        $query = "SELECT max(transdate) as transdates FROM transactions where cardno='{$cardnumber}' and transdate<'{$date1}' ";
        $result = mysql_query($query, $connection);
        $row = mysql_fetch_array($result);
        extract($row);
        $openbalance = 0;
        $query = "SELECT balance FROM transactions where cardno='{$cardnumber}' and  transdate='{$transdates}' ";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_row($result)) {
                extract($row);
                $openbalance = $row[0];
            }
        }
        $closebalance = 0;
        $query = "SELECT balance FROM transactions where cardno='{$cardnumber}' and  transdate>='{$date1}' and  transdate<='{$date2}' order by transdate, serialno";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_row($result)) {
                extract($row);
                $closebalance = $row[0];
            }
        }
        setcookie("thebalances", $openbalance . "k" . $closebalance, false);
        //$qry="UPDATE currentrecord set report = '".$openbalance."][".$closebalance."' where currentuser='{$currentusers}' ";
        //mysql_query($qry, $connection);

		if(date("Y") == date('Y', strtotime($date1))){
			$query = "SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.credit, a.debit, a.balance, a.transtype, a.recordlock, a.cardserial FROM transactions a where a.cardno='{$cardnumber}' and a.transdate>='{$date1}' and a.transdate<='{$date2}' and a.transgroup='contribution' order by transdate, serialno";
		}else{
			$query = "SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.credit, a.debit, a.balance, a.transtype, a.recordlock, a.cardserial FROM transactions_archive a where a.cardno='{$cardnumber}' and a.transdate>='{$date1}' and a.transdate<='{$date2}' and a.transgroup='contribution' ";
			$query .= " UNION SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.credit, a.debit, a.balance, a.transtype, a.recordlock, a.cardserial FROM transactions a where a.cardno='{$cardnumber}' and a.transdate>='{$date1}' and a.transdate<='{$date2}' and a.transgroup='contribution' order by transdate, serialno";
		}
    }

    if ($table == "loandeposits" && $option == "getAllRecs") {
        $cardnumber = $_COOKIE['cardnumber'];
        $date1 = $_COOKIE['date1'];
        $date2 = $_COOKIE['date2'];
        $query = "SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.narration, a.credit, a.debit, a.balance, a.transtype, a.recordlock FROM transactions_archive a where a.cardno='{$cardnumber}' and a.transdate>='{$date1}' and a.transdate<='{$date2}' and a.transgroup='loan' ";
        $query .= " UNION SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), a.transdate, a.narration, a.credit, a.debit, a.balance, a.transtype, a.recordlock FROM transactions a where a.cardno='{$cardnumber}' and a.transdate>='{$date1}' and a.transdate<='{$date2}' and a.transgroup='loan' order by transdate, serialno";
    }

    if ($table == "lockrecords" && $option == "getAllRecs") {
        $lineno = $_COOKIE['lineno'];
        $owner = $_COOKIE['owner'];
        $searchstr = $_COOKIE['searchstr'];
        $transdate = $_COOKIE['transdate'];
        //$date1 = $_COOKIE['date1'];
        //$date2 = $_COOKIE['date2'];
        $query = "SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.narration, a.credit, a.debit, a.balance, a.recordlock, a.transtype FROM transactions_archive a where a.transdate='{$transdate}'  and (a.cardno like '%{$searchstr}%' or (select concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno) like '%{$searchstr}%') ";
        if ($lineno != "") {
            $query .= " and a.lineno = '{$lineno}' ";
        }
        if ($owner != "") {
            $query .= " and a.username = '{$owner}' ";
        }
        $query .= " UNION SELECT a.serialno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.narration, a.credit, a.debit, a.balance, a.recordlock, a.transtype FROM transactions a where a.transdate='{$transdate}'  and (a.cardno like '%{$searchstr}%' or (select concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno) like '%{$searchstr}%') ";
        if ($lineno != "") {
            $query .= " and a.lineno = '{$lineno}' ";
        }
        if ($owner != "") {
            $query .= " and a.username = '{$owner}' ";
        }
        $query .= "order by transdate, serialno";
    }

    if ($table == "transactionlist" && $option == "getAllRecs") {
		if(date("Y") == date('Y', strtotime($serialnos))){
			$query = " SELECT a.serialno, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transtype, a.transgroup, (select b.passportpicture from customers b where a.cardno=b.cardno) as passport, a.username, a.recordlock, a.post FROM transactionlist a where a.transdate='{$serialnos}' and a.lineno='{$accesss}' and a.username='{$currentusers}' and a.transtype='" . $_COOKIE['currentform'] . "'  order by transdate, serialno";
		}else{
			$query = "SELECT a.serialno, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transtype, a.transgroup, (select b.passportpicture from customers b where a.cardno=b.cardno) as passport, a.username, a.recordlock, a.post FROM transactionlist_archive a where a.transdate='{$serialnos}' and a.lineno='{$accesss}' and a.username='{$currentusers}' and a.transtype='" . $_COOKIE['currentform'] . "' ";
			$query .= " UNION SELECT a.serialno, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transtype, a.transgroup, (select b.passportpicture from customers b where a.cardno=b.cardno) as passport, a.username, a.recordlock, a.post FROM transactionlist a where a.transdate='{$serialnos}' and a.lineno='{$accesss}' and a.username='{$currentusers}' and a.transtype='" . $_COOKIE['currentform'] . "'  order by transdate, serialno";
		}
	}
//serialno, lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post
    if ($table == "transactionlist2" && $option == "getAllRecs") {
		
		$query = "SELECT a.* FROM transactionlist a where a.transdate='{$serialnos}' and a.post='1' and a.serialno not in (SELECT b.transno ";
        $query .= " FROM transactions b where b.transdate='{$serialnos}') order by transdate, username, transtype, lineno, serialno";
		$result = mysql_query($query, $connection);
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_row($result)) {
				extract($row);
				//serialno, lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post, smsstatus, cardserial
				/*$queryTrans = "select max(serialno) as id from transactions ";
                $resultTrans = mysql_query($queryTrans, $connection);
                $rowTrans = mysql_fetch_array($resultTrans);
                extract($rowTrans);
                $serialnos = intval($rowTrans[0]) + 1;*/
				$credit = "0";
				$debit = "0";
				$balance = "0";
				if ($row[7] == "deposit") {
					$credit = $row[5];
				} else {
					$debit = $row[5];
				}
				$narration = "The sum of " . $row[5] . " being " . $row[7] . " by " . $row[2];
                //$queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ";
				//$queryTrans = " ('{$serialnos}', '{$parameters[2]}', '{$parameters[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$parameters[7]}', '{$parameters[9]}', '{$parameters[8]}', '1', '{$parameters[1]}', '{$serialnos2}') ";
                $queryTrans = "insert into transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ";
				$queryTrans .= " ('{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}') ";
$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $queryTrans)."' where currentuser='Admin'";
$result = mysql_query($qry, $connection);
                
				mysql_query($queryTrans, $connection);
                updateTransBalances($row[2], $row[3]);
			}
		}
        $query = "SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transgroup, a.recordlock, a.post FROM transactionlist_archive a where a.transdate='{$serialnos}' and a.post='1' ";

        if ($accesss != "") {
            $query .= " and a.lineno='{$accesss}'  ";
        }
        if ($userNames != "") {
            $query .= " and a.username='{$userNames}' ";
        }
        $query .= " UNION SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance_b, a.amount, a.balance_a, a.transgroup, a.recordlock, a.post FROM transactionlist a where a.transdate='{$serialnos}' and a.post='1' ";

        if ($accesss != "") {
            $query .= " and a.lineno='{$accesss}'  ";
        }
        if ($userNames != "") {
            $query .= " and a.username='{$userNames}' ";
        }
        $query .= " order by transdate, username, transtype, lineno, serialno ";
//echo $query;
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
    }
	//serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno

    if ($table == "smstransactionlist" && $option == "getAllRecs") {
        $query = "SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance+a.debit-a.credit as balance_b, a.credit+a.debit as amount, a.balance as balance_a, a.transgroup, a.recordlock, 1, (select distinct b.telephone from customers b where a.cardno=b.cardno) as phone, a.smsstatus FROM transactions_archive a where a.transdate='{$serialnos}'  "; //and a.post='1'
        if ($accesss != "") {
            $query .= " and a.lineno='{$accesss}'  ";
        }
        if ($userNames != "") {
            $query .= " and a.username='{$userNames}' ";
        }
        $query .= " UNION SELECT a.serialno, a.username, a.transtype, a.lineno, a.cardno, (select distinct concat(b.lastname, ' ', b.othernames) from customers b where a.cardno=b.cardno) as names, a.transdate, a.balance+a.debit-a.credit as balance_b, a.credit+a.debit as amount, a.balance as balance_a, a.transgroup, a.recordlock, 1, (select distinct b.telephone from customers b where a.cardno=b.cardno) as phone, a.smsstatus FROM transactions a where a.transdate='{$serialnos}'  "; //and a.post='1'
        if ($accesss != "") {
            $query .= " and a.lineno='{$accesss}'  ";
        }
        if ($userNames != "") {
            $query .= " and a.username='{$userNames}' ";
        }
        $query .= " order by transdate, username, transtype, lineno, serialno ";
//echo $query;
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
    }

    if ($option == "getRecordlist") {
        if (substr($currentobject, 0, 6) == "cardno") {
            $query = "SELECT DISTINCT serialno, cardno, lastname, othernames FROM {$table} where cardno like '{$serialnos}%' or lastname like '{$serialnos}%' or othernames like '{$serialnos}%' order by cardno";
        }
        //$query = "SELECT serialno, cardno, lastname, othernames, sex, telephone, address, passportpicture, recordlock FROM customers where cardno like '{$serialnos}%' order by cardno desc";
        //echo $option.$query;
        //return true;
        if ($table == "lineno" || $currentobject == "lineno") {
            $query = "SELECT DISTINCT lineno, lineno, lineno FROM customers  order by lineno ";
        }

        if ($table == "users" || $currentobject == "username") {
            $query = "SELECT serialno, userName, firstName FROM users  order by userName ";
        }

        if ($table == "transtype" || $currentobject == "transtype") {
            $query = '1_~_Deposits_~_getRecordlist2_~_Withdrawals_~_getRecordlist3_~_Commissions_~_getRecordlist4_~_Interests_~_getRecordlist';
        }
    }

    if ($option == "getARecord") {
        $query = "SELECT * FROM {$table} where serialno='{$serialnos}' ";
    }
    if ($option == "getARecord" && $table == "customers" && $accesss == null) {
        $query = "select a.cardno from loancustomers a where a.cardno=(select b.cardno from customers b where b.serialno='{$serialnos}' )";
        $result = mysql_query($query, $connection);
        //$row = mysql_fetch_array($result);
        //extract ($row);
        if (mysql_num_rows($result) > 0) {
            $query = "SELECT a.*, b.datedisbursed, b.loanamount, b.loaninterest, b.loanstartdate, b.loanenddate, b.repayoption, b.amountperrepay from customers a, loancustomers b where a.cardno=b.cardno and a.serialno='{$serialnos}' ";
        } else {
            $query = "SELECT * from customers where serialno='{$serialnos}' ";
        }
    }
    $qry = "";
    if ($option == "getARecord" && $table == "customers" && $accesss != null) {
        /*$query = "select a.cardno from loancustomers a where a.cardno=(select b.cardno from customers b where b.cardno='{$accesss}' )";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            $query = "SELECT a.*, b.datedisbursed, b.loanamount, b.loaninterest, b.loanstartdate, b.loanenddate, b.repayoption, b.amountperrepay from customers a, loancustomers b where a.cardno=b.cardno and a.cardno='{$accesss}' ";
        } else {*/
		
			$queryTempTrans = "delete from cardnotrans  where cardno='{$accesss}' ";
			mysql_query($queryTempTrans, $connection);

			$queryTempTrans = "INSERT INTO cardnotrans (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) SELECT * FROM transactions  where cardno='{$accesss}' order by concat(transdate, serialno) desc limit 1";
			mysql_query($queryTempTrans, $connection);

            $query = "select max(concat(transdate, serialno)) as sno from cardnotrans where cardno='{$accesss}' ";
            $result = mysql_query($query, $connection);
            $row = mysql_fetch_array($result);
            extract($row);

            if ($sno == null) {
				$queryTempTrans = "delete from cardnotrans  where cardno='{$accesss}' ";
				mysql_query($queryTempTrans, $connection);

				$queryTempTrans = "INSERT INTO cardnotrans (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) SELECT * FROM transactions_archive  where cardno='{$accesss}' order by concat(transdate, serialno) desc limit 1";
				mysql_query($queryTempTrans, $connection);

				$query = "select max(concat(transdate, serialno)) as sno from cardnotrans where cardno='{$accesss}' ";
				$result = mysql_query($query, $connection);
				$row = mysql_fetch_array($result);
				extract($row);
				if ($sno == null) {
					$query = "SELECT a.*, 0 as bal from customers a where a.cardno='{$accesss}' ";
				} else {
					$query = "SELECT a.*, (select b.balance from cardnotrans b where concat(b.transdate, b.serialno)='{$sno}') as bal from customers a where a.cardno='{$accesss}' ";
				}
            } else {
                $query = "SELECT a.*, (select b.balance from cardnotrans b where concat(b.transdate, b.serialno)='{$sno}') as bal from customers a where a.cardno='{$accesss}' ";
            }
        //}
    //echo $query;
	}
    if ($option == "getARecord" && $table == "withdrawals") {
        $query = "SELECT * FROM transactions where serialno='{$serialnos}'";
    }
    if ($option == "getARecord" && $table == "loandeposits") {
        $query = "SELECT * FROM transactions where serialno='{$serialnos}'";
    }
    if ($option == "getARecord" && $table == "deposits") {
        $query = "SELECT a.*, (select concat(b.lastname, ' ', b.othernames) as names from customers b where a.cardno=b.cardno), (select b.passportpicture from customers b where a.cardno=b.cardno) FROM transactions a where a.serialno='{$serialnos}'";
	}
    $resp = $option;

    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        $resp = "";
        while ($row = mysql_fetch_row($result)) {
            extract($row);
            foreach ($row as $i => $value) {
                if ($option == "getAllRecs" || $option == "getRecordlist") {
                    $resp .= $value . "_~_";
                } else {
                    $resp .= "getARecord" . $value;
                }
            }
            if ($option == "getAllRecs" || $option == "getRecordlist") {
                $resp .= $option;
            }
        }
        if ($option == "getAllRecs") {
            $resp = $table . $option . $resp;
        }
        if ($option == "getARecord") {
            $resp = $table . $resp . $option;
        }
    } else if ($table == "transtype") {
        $resp = $query . $option;
    } else {
        if ($option == "getAllRecs") {
            $resp = $table . $option;
        }
    }
    $queryTempTrans = "delete from cardnotrans  where cardno='{$accesss}' ";
	mysql_query($queryTempTrans, $connection);
    echo $resp;
}

if ($option == "addRecord") {
	$parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    if ($table == "customers") {
        $query = "SELECT * FROM {$table} where cardno ='{$parameters[1]}'";
    }

    if ($table == "transactions") {
        $query = "SELECT * FROM {$table} where serialno ='{$serialnos}'";
    }

    if ($table == "transactionlist") {

        //SELECT min(serialno), lineno, transdate FROM transactionlist where post<>'1' and transdate<>'{$parameters[3]}' and username='{$parameters[9]}' and transtype in ('commission','deposit','withdrawal')
        $query = "SELECT serialno, lineno, transdate FROM transactionlist where serialno=(select min(serialno) from transactionlist where post<>'1' and transdate<>'{$parameters[3]}' and username='{$parameters[9]}') and transtype in ('commission','deposit','withdrawal') ";

		$result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
            extract($row);
            $linenos = $row[1];
			$transdates = $row[2];
			$transdates = new DateTime(substr($transdates, 0, 10));
			//$transdates->sub(new DateInterval('P1D'));
			$transdates = $transdates->format('d-m-Y');
			if($linenos!=null && $linenos!=""){
				echo "unposted".$parameters[9]." must post line ".$linenos." of ".$transdates." before another line can be created.";
				return true;
			}
        }

        $query = "SELECT max(transdate) FROM transactionlist ";
        $result = mysql_query($query, $connection);
        $minimaldate = "";
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            extract($row);
            $minimaldate = $row[0];
        }

        $minimaldate = new DateTime(substr($minimaldate, 0, 10));
        $minimaldate->sub(new DateInterval('P1D'));
        $minimaldate = $minimaldate->format('Y-m-d');
        if ($parameters[3] < $minimaldate && $_COOKIE['currentuser'] != "Admin") {
            echo "minimaldate" . $minimaldate;
            return true;
        }

        if ($parameters[7] == "withdrawal") {
            $query = "SELECT lockwithdrawal FROM customers where cardno ='{$parameters[2]}'";
            $result = mysql_query($query, $connection);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                extract($row);
                if ($row[0] == "1") {
                    echo "withdrawallocked" . $parameters[2];
                    return true;
                }
            }
        }
        $query = "SELECT * FROM {$table} where serialno ='{$parameters[0]}'";
        $serialnos = $parameters[0];
    }

    $originaltrans = "transactionlist";
    if ($table == "transactionlist2") {
        $originaltrans = "transactionlist2";
        $table = "transactionlist";
        $query = "SELECT * FROM transactionlist where serialno ='{$parameters[0]}' ";
    }

    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) == 0) {
        $query = "select max(serialno) as id from {$table}";
        $result = mysql_query($query, $connection);
        $row = mysql_fetch_array($result);
        extract($row);
        $serialnos = intval($id) + 1;
        $serialnos2 = $serialnos;

        $query = "INSERT INTO {$table} (serialno) VALUES ('{$serialnos}')";
        $result = mysql_query($query, $connection);

        $query = "SELECT * FROM {$table} where serialno ='{$serialnos}'";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            $record = "";
            $count = 0;
            while ($row = mysql_fetch_row($result)) {
                extract($row);
                foreach ($row as $i => $value) {
                    $meta = mysql_fetch_field($result, $i);
                    if ($count > 0) {
                        $record .= $meta->name . "='" . $parameters[$count++] . "', ";
                    } else {
                        $count++;
                    }
                }
            }
			
            $record = substr($record, 0, strlen($record) - 2);
            $query = "UPDATE {$table} set " . $record . " where serialno ='{$serialnos}'";
			
            $result = mysql_query($query, $connection);
			if ($table == "transactionlist") {
				$query = "SELECT cardserial from customers where cardno='{$parameters[2]}'";
				$result = mysql_query($query, $connection);
				$row = mysql_fetch_array($result);
				extract($row);
				$query = "UPDATE {$table} set cardserial='{$row[0]}' where serialno ='{$serialnos}'";
				mysql_query($query, $connection);
			}

//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
        }
        if ($table == "transactionlist" && $originaltrans == "transactionlist2") {
//		var a_param1 = "&a_param1="+serialno+"]["+lineno+"]["+cardno+"]["+transdate+"]["+balance_b+"]["+amount+"]["+balance_a+"]["+transtype+"]["+username;

            $queryTrans = "SELECT * FROM transactions where cardno='{$parameters[2]}' and transdate='{$parameters[3]}' and lineno='{$parameters[1]}' and transtype='{$parameters[7]}' and transgroup='{$parameters[8]}' and transno='{$serialnos2}' ";
            $resultTrans = mysql_query($queryTrans, $connection);
            $credit = "0";
            $debit = "0";
            $balance = "0";
            if ($parameters[7] == "deposit" || $parameters[3] == "loandeposits") {
                $credit = $parameters[5];
            } else {
                $debit = $parameters[5];
            }
            $narration = "The sum of " . $parameters[5] . " being " . $parameters[7] . " by " . $parameters[2];
            $username = $_COOKIE['currentuser'];
            if (mysql_num_rows($resultTrans) > 0) {
                $queryTrans = "update transactions  set credit='{$credit}', debit='{$debit}', balance='{$balance}', username='{$row[9]}',  narration='{$narration}' where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}'  ";
                mysql_query($queryTrans, $connection);
                $serialnos = $serialno;
                updateTransBalances($parameters[2], $parameters[3]);
            } else {
                $queryTrans = "select max(serialno) as id from transactions ";
                $resultTrans = mysql_query($queryTrans, $connection);
                $rowTrans = mysql_fetch_array($resultTrans);
                extract($rowTrans);
                $serialnos = intval($rowTrans[0]) + 1;

                $queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ('{$serialnos}', '{$parameters[2]}', '{$parameters[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$parameters[7]}', '{$parameters[9]}', '{$parameters[8]}', '1', '{$parameters[1]}', '{$serialnos2}') ";
                mysql_query($queryTrans, $connection);
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $queryTrans)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
                updateTransBalances($parameters[2], $parameters[3]);
            }
            $table = $originaltrans;

            updateTransBalances($parameters[2], $parameters[3]);
            //echo 'listTransaction';
        }

        if ($table == "customers") {
            if ($parameters[8] != null && $parameters[8] != "") {
                $parameters[2] = date("Y-m-d");
                $parameters[3] = "Opening Balance";
                if ($parameters[8] > "0") {
                    $parameters[4] = $parameters[8];
                    $parameters[5] = "0";
                    $parameters[7] = "deposit";
                } else {
                    $parameters[4] = "0";
                    $parameters[5] = $parameters[8];
                    $parameters[7] = "withdrawal";
                }
                $parameters[6] = "0";
                $parameters[8] = $_COOKIE['currentuser'];
                $parameters[9] = "contribution";

                /* $query = "SELECT * FROM transactions where cardno ='{$parameters[1]}' and narration='Opening Balance' ";
                  $result = mysql_query($query, $connection);
                  if(mysql_num_rows($result) == 0){
                  $query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup) VALUES ('{$parameters[1]}', '{$parameters[2]}', '{$parameters[3]}', '{$parameters[4]}', '{$parameters[5]}', '{$parameters[6]}', '{$parameters[7]}', '{$parameters[8]}', '{$parameters[9]}') ";
                  $result = mysql_query($query, $connection);
                  updateTransBalances($parameters[1],$parameters[2]);
                  } */
            }
            if ($a_param2 != "][][][][][][][") {
                $parameters2 = explode("][", $a_param2);
                for ($count = 0; $count < count($parameters2); $count++) {
                    $parameters2[$count] = trim($parameters2[$count]);
                }

                $query = "SELECT * FROM loancustomers where cardno='{$parameters2[1]}' and datedisbursed='{$parameters2[2]}'";
                $result = mysql_query($query, $connection);
                if (mysql_num_rows($result) == 0) {
                    $query = "INSERT INTO loancustomers (cardno, datedisbursed, loanamount, loaninterest, loanstartdate, loanenddate, repayoption, amountperrepay) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$parameters2[3]}', '{$parameters2[4]}', '{$parameters2[5]}', '{$parameters2[6]}', '{$parameters2[7]}','{$parameters2[8]}')";
                    mysql_query($query, $connection);

                    $narration = "The sum of " . $parameters2[10] . " being loan amount paid to (" . $parameters[1] . ")";
                    $query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$narration}', '0', '{$parameters2[3]}', '0',  'withdrawal', '{$_COOKIE[currentuser]}', 'loan', '1')";
                    mysql_query($query, $connection);

                    $narration = "The sum of " . $parameters2[11] . "being loan interest charged to (" . $parameters[1] . ")";
                    $query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$narration}', '0', '{$parameters2[4]}', '0', 'interest', '{$_COOKIE[currentuser]}', 'loan', '1')";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transdate='{$parameters2[2]}' and transtype='withdrawal' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);
                    updateTransBalances($parameters2[1], $parameters2[2]);
                } else {
                    $query = "UPDATE loancustomers set datedisbursed='{$parameters2[2]}', loanamount='{$parameters2[3]}', loaninterest='{$parameters2[4]}', loanstartdate='{$parameters2[5]}', loanenddate='{$parameters2[6]}', repayoption='{$parameters2[7]}', amountperrepay='{$parameters2[8]}' where cardno ='{$parameters2[1]}' ";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transtype='withdrawal' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);

                    $narration = "The sum of " . $parameters2[10] . " being loan amount paid to (" . $parameters[1] . ")";
                    $query = "update transactions set cardno='{$parameters2[1]}', transdate='{$parameters2[2]}', narration='{$narration}', credit='0', debit='{$parameters2[3]}', balance='0', transtype='withdrawal', username='{$_COOKIE[currentuser]}', transgroup='loan', recordlock='1' where serialno='{$serialno}' ";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transtype='interest' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);

                    $narration = "The sum of " . $parameters2[11] . "being loan interest charged to (" . $parameters[1] . ")";
                    $query = "update transactions set cardno='{$parameters2[1]}', transdate='{$parameters2[2]}', narration='{$narration}', credit='0', debit='{$parameters2[4]}', balance='0', transtype='interest', username='{$_COOKIE[currentuser]}', transgroup='loan', recordlock='1' where serialno='{$serialno}' ";
                    mysql_query($query, $connection);

                    //$query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transdate='{$parameters2[2]}' and transtype='withdrawal' and transgroup='loan' ";
                    //$result = mysql_query($query, $connection);
                    //$row = mysql_fetch_array($result);
                    extract($row);
                    updateTransBalances($parameters2[1], $parameters2[2]);
                }
            }
        }
        $usernames = $_COOKIE['currentuser'];
        $activitydescriptions = $usernames . " inserted record into table: " . $table . " Record: " . str_replace("'", "", trim($record));
        $activitydates = date("Y-m-d");
        $activitytimes = date("H:i:s");
        $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
        mysql_query($query, $connection);

        echo $table . "inserted";
    } else {
        echo "recordexists";
    }
}

if ($option == "checkCommission") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }
    $query = "SELECT * FROM transactions where cardno='{$parameters[0]}' and transtype='commission' and (transdate>='{$parameters[1]}' and transdate<='{$parameters[2]}') ";
    $result = mysql_query($query, $connection);
    echo "commissionexists";
    if (mysql_num_rows($result) == 0) {
        echo "enablecommission";
    } else {
        echo "disablecommission";
    }
}

if ($option == "showCurrentCardno") {
    $result = mysql_query("select report from currentrecord where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);
    $row = mysql_fetch_array($result);
    extract($row);
    echo "showCurrentCardno" . $row[0];
}

if ($option == "showCurrentId") {
	$result = mysql_query("select tmp from currentrecord2 where currentuser='Admin'", $connection);
	$row = mysql_fetch_array($result);
	extract($row);
	echo "showCurrentId" . $row[0];
}


if ($option == "calculateCommission") {
    $parameters = explode("][", $a_param1);
    $username = $parameters[0];
    $transdate = $parameters[1];
    //71 41 100
    //$result=mysql_query($connect, "CALL calculateCommissions('$username','$transdate')");
    //$query = "delete FROM transactionlist where lineno is null";
    //mysql_query($query, $connection);
    mysql_query("update currentrecord set report='' where currentuser='" . $username . "'", $connection);

    //$query = "select max(cardno) from transactions ";
    //$result = mysql_query($query, $connection);
    //$row = mysql_fetch_array($result);
    //extract ($row);
    //$maxcardno = $row[0];

    $query = "SELECT * FROM customers where cardno NOT LIKE '41%' and cardno NOT LIKE '71%' and cardno NOT LIKE '100%'  and commission is not null and commission > 0 order by cardno";
    $result = mysql_query($query, $connection);
    $counter = 0;
    if (mysql_num_rows($result) > 0) {

        $queryTempTrans = "delete from temptrans ";
        mysql_query($queryTempTrans, $connection);

        $queryTempTrans = "INSERT INTO temptrans (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, smsstatus, cardserial) SELECT * FROM transactions where month(transdate)=month('{$transdate}') and year(transdate)=year('{$transdate}')";
        mysql_query($queryTempTrans, $connection);

        /* $queryLineno = "SELECT distinct lineno FROM transactionlist where lineno<>'' and cardno NOT LIKE '41%' and cardno NOT LIKE '71%' and cardno NOT LIKE '100%'";
          $resultLineno = mysql_query($queryLineno, $connection);
          $mylinenos='';
          if(mysql_num_rows($resultLineno) > 0){
          while ($rowLineno = mysql_fetch_row($resultLineno)) {
          extract ($rowLineno);
          $mylinenos .= $rowLineno[0].",";
          }
          }
          $mylinenos = explode(",",$mylinenos); */
        while ($row = mysql_fetch_row($result)) {
            extract($row);
            //setcookie("processingcommission", $row[1],false, '/');
            $counter++;
            $repot = $row[1] . " [" . $counter . " / " . mysql_num_rows($result) . "]";
            mysql_query("update currentrecord set report='" . $repot . "' where currentuser='" . $username . "'", $connection);

            /*$queryLineno = "SELECT distinct lineno FROM temptrans where cardno='" . $row[1] . "'  and cardno like concat(lineno,'%')";
            $resultLineno = mysql_query($queryLineno, $connection);
            $rowLineno = mysql_fetch_row($resultLineno);
            extract($rowLineno);
            $mylineno = $rowLineno[0];*/
            $mylineno = $row[12];
            /* for($count=0; $count<count($mylinenos); $count++){
              $mylinenos[$count]=trim($mylinenos[$count]);
              if(substr($row[1], 0, 2)==$mylinenos[$count] || substr($row[1], 0, 3)==$mylinenos[$count]){
              $mylineno = $mylinenos[$count];
              break;
              }
              } */


            $queryDeposit = "SELECT * FROM temptrans where cardno='{$row[1]}' and transtype='deposit' and month(transdate)=month('{$transdate}') and year(transdate)=year('{$transdate}')";
            $resultDeposit = mysql_query($queryDeposit, $connection);

            $queryCommission = "SELECT * FROM temptrans where cardno='{$row[1]}' and transtype='commission' and month(transdate)=month('{$transdate}') and year(transdate)=year('{$transdate}')";
            $resultCommission = mysql_query($queryCommission, $connection);
//setcookie("myquery",$queryTransaction);
            if (mysql_num_rows($resultCommission) == 0 && mysql_num_rows($resultDeposit) > 0) {
                $queryBal = "select balance from temptrans where serialno=(select max(serialno) from temptrans where cardno='{$row[1]}')";
                $resultBal = mysql_query($queryBal, $connection);
                $rowBal = mysql_fetch_array($resultBal);
                extract($rowBal);

                $balanceA = $rowBal[0] - $row[11];
                $queryIns = "insert into transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post) values ('{$mylineno}', '{$row[1]}', '{$transdate}', '{$rowBal[0]}', '{$row[11]}', '{$balanceA}', 'commission', 'contribution', '{$username}', '1', '1') ";
                mysql_query($queryIns, $connection);

                $querySerialno = "select max(serialno) from transactionlist ";
                $resultSerialno = mysql_query($querySerialno, $connection);
                $rowSerialno = mysql_fetch_array($resultSerialno);
                extract($rowSerialno);

                $queryTrans = "SELECT * FROM temptrans where cardno='{$row[1]}' and transdate='{$transdate}' and lineno='{$mylineno}' and transtype='commission' and transgroup='contribution' ";
                $resultTrans = mysql_query($queryTrans, $connection);
                $credit = "0";
                $balance = "0";
                $debit = $row[11];
                $narration = "The sum of " . $row[11] . " being commission paid by " . $row[1];
                $username = $_COOKIE['currentuser'];
                if (mysql_num_rows($resultTrans) > 0) {
                    $queryTrans = "update transactions  set credit='{$credit}', debit='{$debit}', balance='{$balance}', username='{$username}', transno='{$rowSerialno[0]}',  narration='{$narration}' where cardno='{$row[1]}' and transdate='{$transdate}' and lineno='{$mylineno}' and transtype='commission' and transgroup='contribution'  and transno='{$rowSerialno[0]}'  ";
                    mysql_query($queryTrans, $connection);
                    $serialnos = $serialno;
                    updateTransBalances($row[1], $transdate);
                } else {
                    $queryTrans = "select max(serialno) as id from transactions ";
                    $resultTrans = mysql_query($queryTrans, $connection);
                    $rowTrans = mysql_fetch_array($resultTrans);
                    extract($rowTrans);
                    $serialnos = intval($rowTrans[0]) + 1;

                    $queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ('{$serialnos}', '{$row[1]}', '{$transdate}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', 'commission', '{$username}', 'contribution', '1', '{$mylineno}', '{$rowSerialno[0]}') ";
                    mysql_query($queryTrans, $connection);
                    updateTransBalances($row[1], $transdate);
                }
            }
            //if($row[1]==$maxcardno){
            //}
        }
    }
    mysql_query("update currentrecord set report='' where currentuser='" . $username . "'", $connection);
    echo "commissioncompleted";
}

function updateTransBalances($cardno, $date) {
    include("data.php");
	$balances = 0;
    $query = "SELECT max(transdate) as transdates FROM transactions where cardno='{$cardno}' and transdate<'{$date}' ";
    $result = mysql_query($query, $connection);
    $row = mysql_fetch_array($result);
    extract($row);
	
    if ($transdates == null || $transdates == "") {
		$query = "SELECT balance as balances FROM transactions_archive where concat(transdate, serialno)=(select max(concat(transdate, serialno)) from transactions_archive where cardno='{$cardno}')";
		$result = mysql_query($query, $connection);
		$row = mysql_fetch_array($result);
		extract($row);
	}	
	
    if ($transdates != null && $transdates != "" && ($balances == null || $balances == "" || $balances == 0)) {
        $query = "SELECT balance FROM transactions where cardno='{$cardno}' and  transdate='{$transdates}' ";
        $result = mysql_query($query, $connection);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_row($result)) {
                extract($row);
                $balances = $row[0];
            }
        }
    }
    $query = "SELECT * FROM transactions where cardno='{$cardno}' and transdate>='{$date}' order by transdate, serialno";
    $result = mysql_query($query, $connection);
//setcookie("myresponse", $query."     ".$balances, false);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);
            $balances = $balances + $row[4] - $row[5];
            $queryBal = "update transactions set balance='{$balances}' where serialno='{$row[0]}' ";
            mysql_query($queryBal, $connection);
        }
    }
    return true;
}

if ($option == "changeDate") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++){
        $parameters[$count] = trim($parameters[$count]);
    }
    /*$query = "select serialno from transactionlist where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
	$result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);
			$queryTrans = "update transactionlist set transdate='{$parameters[0]}' where serialno='{$row[0]}' ";
			mysql_query($queryTrans, $connection);
		}
	}*/
    $queryTrans = "update transactionlist set transdate='{$parameters[0]}' where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
	mysql_query($queryTrans, $connection);

    /*$query = "select serialno from transactions where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
	$result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);
			$queryTrans = "update transactions set transdate='{$parameters[0]}' where serialno='{$row[0]}' ";
			mysql_query($queryTrans, $connection);
		}
	}*/
    $queryTrans = "update transactions set transdate='{$parameters[0]}' where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
    mysql_query($queryTrans, $connection);

    //$query = "SELECT distinct cardno FROM transactions where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
    $query = "SELECT distinct cardno FROM transactions where lineno='{$parameters[1]}' and transdate='{$parameters[0]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
    $result = mysql_query($query, $connection);
    $cardnos = "";
    $mindate = $parameters[0];
    if ($parameters[2] < $mindate) {
        $mindate = $parameters[2];
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);

            /* $queryTrans = "update transactionlist set transdate='{$parameters[0]}' where cardno='{$row[0]}' and lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
              mysql_query($queryTrans, $connection);

              $queryTrans = "update transactions set transdate='{$parameters[0]}' where cardno='{$row[0]}' and lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
              mysql_query($queryTrans, $connection); */
            updateTransBalances($row[0], $mindate);
        }
    }
    echo "changeDate";
}

/*



  $query = "select * from transactions where lineno is not null and transno=0 order by transdate, username, lineno, serialno";
  echo $query."<br><br>";
  $result = mysql_query($query, $connection);
  if(mysql_num_rows($result) > 0){
  $count=0;
  while ($row = mysql_fetch_row($result)) {
  extract ($row);

  $amount=$row[4];
  if($row[5]>0) $amount=$row[5];
  $balanceA=$row[6];
  $balanceB=$row[6] - $row[4] + $row[5];
  $queryIns = "insert into transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post) values ('{$row[11]}', '{$row[1]}', '{$row[2]}', '{$balanceB}', '{$amount}', '{$balanceA}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '{$row[10]}', '1') ";
  mysql_query($queryIns, $connection);
  echo (++$count)."   ".$queryIns."<br>";
  }
  }
  echo "<br><br><br><br>";


  $query = "select * from transactionlist where serialno not in (select distinct transno from transactions) order by transdate, username, lineno, serialno";
  echo $query."<br><br>";
  $result = mysql_query($query, $connection);
  if(mysql_num_rows($result) > 0){
  $count=0;
  while ($row = mysql_fetch_row($result)) {
  extract ($row);

  $queryTrans = "update transactionlist  set recordlock='1', post='1' where serialno='{$row[0]}' ";
  mysql_query($queryTrans, $connection);

  $queryTrans = "SELECT * FROM transactions where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}' and transno='{$row[0]}' ";
  $resultTrans = mysql_query($queryTrans, $connection);
  $credit="0"; $debit="0"; $balance="0";
  if($row[7]=="deposit" || $row[7]=="loandeposits"){
  $credit=$row[5];
  }else{
  $debit=$row[5];
  }
  $narration="The sum of ".$row[5]." being ".$row[7]." by ".$row[2];
  $username=$_COOKIE['currentuser'];
  if(mysql_num_rows($resultTrans) > 0){
  $queryTrans = "update transactions  set credit='{$credit}', debit='{$debit}', balance='{$balance}', username='{$row[9]}', transno='{$row[0]}',  narration='{$narration}' where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}'  and transno='{$row[0]}'  ";
  mysql_query($queryTrans, $connection);
  $serialnos = $serialno;
  updateTransBalances($row[2],$row[3]);
  }else{
  $queryTrans = "select max(serialno) as id from transactions ";
  $resultTrans = mysql_query($queryTrans, $connection);
  $rowTrans = mysql_fetch_array($resultTrans);
  extract ($rowTrans);
  $serialnos = intval($rowTrans[0])+1;

  $queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ('{$serialnos}', '{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}') ";
  mysql_query($queryTrans, $connection);
  updateTransBalances($row[2],$row[3]);
  }
  echo (++$count)."   ".$queryIns."<br>";
  }
  }

  $query = "SELECT * FROM transactions where transno=0 and lineno is not null ";
  $result = mysql_query($query, $connection);
  $count=0;
  while ($row = mysql_fetch_row($result)) {
  extract ($row);

  $myquery = "select * from transactionlist where cardno ='{$row[1]}' and transdate='{$row[2]}' and transtype='{$row[7]}' and lineno='{$row[11]}' and transgroup='{$row[9]}' and (amount='{$row[4]}' or amount='{$row[5]}') ";
  $myresult = mysql_query($myquery, $connection);
  if(mysql_num_rows($myresult) > 0){
  $myrow = mysql_fetch_array($myresult);
  extract ($myrow);

  $dequery = "select transno from transactions where transno ='{$myrow[0]}' ";
  echo (++$count)."   ".$dequery."<br>";
  $deresult = mysql_query($dequery, $connection);
  if(mysql_num_rows($deresult) > 0){
  $myquery="update transactions set transno ='{$myrow[0]}' where serialno='{$row[0]}' and (credit='{$myrow[5]}' or debit='{$myrow[5]}')";
  mysql_query($myquery, $connection);
  }
  echo ($count)."   ".$myquery."<br>";
  }
  }
  //serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno

  $query = "select count(transno) as ccc, serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno from transactions where lineno is not null group by transno order by ccc;";
  echo $query."<br><br>";
  $result = mysql_query($query, $connection);
  if(mysql_num_rows($result) > 0){
  $count=0;
  while ($row = mysql_fetch_row($result)) {
  extract ($row);

  if($row[0]>1){
  $querydel = "delete from transactions where transno='{$row[13]}'";
  echo $querydel."<br><br>";
  mysql_query($querydel, $connection);

  $queryIns = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno) values ('{$row[1]}', '{$row[2]}', '{$row[3]}', '{$row[4]}', '{$row[5]}', '{$row[6]}', '{$row[7]}', '{$row[8]}', '{$row[9]}', '{$row[10]}', '{$row[11]}', '{$row[12]}', '{$row[13]}') ";
  mysql_query($queryIns, $connection);
  echo (++$count)."   ".$queryIns."<br>";
  }
  }
  } */


if ($option == "calculateBalances") {
    updateTransBalances($access, $serialnos);

    //$queryDel = "delete FROM transactionlist where cardno ='{$access}' and post='1' and transdate>='{$serialnos}' ";
    //mysql_query($queryDel, $connection);

    $query = "SELECT * FROM transactions where cardno ='{$access}' and transdate>='{$serialnos}' and lineno is not null";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);

            $amount = $row[4];
            if ($row[5] > 0){
                $amount = $row[5];
            }
            $balanceA = $row[6];
            $balanceB = $row[6] - $row[4] + $row[5];

            $queryIns = "select * from transactionlist where transdate='{$row[2]}' and lineno='{$row[11]}' and username='{$row[8]}' and cardno ='{$access}' and post='1' and transdate>='{$serialnos}' and amount in ('{$row[4]}','{$row[5]}')";
            if (mysql_num_rows($result) == 0) {
                $queryIns = "insert into transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post) values ('{$row[11]}', '{$row[1]}', '{$row[2]}', '{$balanceB}', '{$amount}', '{$balanceA}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '{$row[10]}', '1') ";
            } else {
                $queryIns = "update transactionlist set lineno='{$row[11]}', cardno='{$row[1]}', transdate='{$row[2]}', balance_b='{$balanceB}', amount='{$amount}', balance_a='{$balanceA}', transtype='{$row[7]}', transgroup='{$row[9]}', username='{$row[8]}', recordlock='{$row[10]}', post='1' where transdate='{$row[2]}' and lineno='{$row[11]}' and username='{$row[8]}' and cardno ='{$access}' and post='1' and transdate>='{$serialnos}' and amount in ('{$row[4]}','{$row[5]}') ";
            }
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $queryIns)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
            mysql_query($queryIns, $connection);
        }
    }

    echo "calculateBalances";
}

if ($option == "changeLineno") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    $query = "SELECT distinct cardno FROM transactions where lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
    $result = mysql_query($query, $connection);
    $cardnos = "";
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);

            $queryTrans = "update transactionlist set lineno='{$parameters[0]}' where cardno='{$row[0]}' and lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
            mysql_query($queryTrans, $connection);

            $queryTrans = "update transactions set lineno='{$parameters[0]}' where cardno='{$row[0]}' and lineno='{$parameters[1]}' and transdate='{$parameters[2]}' and username='{$parameters[3]}' and transtype='{$parameters[4]}' ";
            mysql_query($queryTrans, $connection);
        }
    }
    echo "changeDate";
}

if ($option == "deleteLine") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    $query = "SELECT * FROM transactions where lineno='{$parameters[0]}' and transdate='{$parameters[1]}' and username='{$parameters[2]}' and transtype='{$parameters[3]}' ";
    $result = mysql_query($query, $connection);
    $cardnos = "";
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);

            $queryTrans = "delete from transactionlist where cardno='{$row[1]}' and lineno='{$parameters[0]}' and transdate='{$parameters[1]}' and username='{$parameters[2]}' and transtype='{$parameters[3]}' and serialno='{$row[12]}' ";
            mysql_query($queryTrans, $connection);

            $queryTrans = "delete from transactions where cardno='{$row[1]}' and lineno='{$parameters[0]}' and transdate='{$parameters[1]}' and username='{$parameters[2]}' and transtype='{$parameters[3]}' and transno='{$row[12]}' ";
            mysql_query($queryTrans, $connection);
            updateTransBalances($row[1], $parameters[1]);
        }
    }
    echo "deleteLine";
}


if ($option == "deleteTransaction") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    $query = "delete FROM transactionlist where lineno='{$parameters[0]}' and transdate='{$parameters[1]}' and username='{$parameters[2]}' and transtype='{$parameters[3]}' and transgroup='{$parameters[4]}' ";
    $result = mysql_query($query, $connection);
    echo "deltedTransaction";
}

if ($option == "postTransaction") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    $query = "SELECT * FROM transactionlist where transdate>'{$parameters[1]}' ";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
		echo "deletePost";
		return true;
	}

	$query = "SELECT currentrecordprocessing FROM currentrecord where currentuser='" . $_COOKIE['currentuser'] . "'";
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result);
		extract($row);
		$timenow = date('YmdHis');
		$timestores = explode('~',$row[0]);
		$timestores[0] = floatval($timestores[0]);
		//mysql_query("update currentrecord set tmp='" . $timestores[0]." > 0 && ".(floatval($timenow) - $timestores[0])." < ".(20 * 1000 * 60)." && ".$timestores[1]."==".$parameters[0]." && ".$timestores[2]."==".$parameters[1] . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);
		if($timestores[0] > 0 && (floatval($timenow) - $timestores[0]) < (10 * 1000 * 60) && $timestores[1]==$parameters[0] &&  $timestores[2]==$parameters[1]){
			mysql_query("update currentrecord set tmp='  true  " . $timestores[0]." > 0 && ".(floatval($timenow) - $timestores[0])." < ".(20 * 1000 * 60)." && ".$timestores[1]."==".$parameters[0]." && ".$timestores[2]."==".$parameters[1] . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);
			echo "runningPost";
			return true;
		}else{
			mysql_query("update currentrecord set tmp=' false  " . $timestores[0]." > 0 && ".(floatval($timenow) - $timestores[0])." < ".(20 * 1000 * 60)." && ".$timestores[1]."==".$parameters[0]." && ".$timestores[2]."==".$parameters[1] . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);
			$timenow = $timenow."~".$parameters[0]."~".$parameters[1];
			mysql_query("update currentrecord set currentrecordprocessing='" . $timenow . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);
			//mysql_query("INSERT INTO currentrecord (currentuser, currentrecordprocessing) VALUES ('" . $_COOKIE['currentuser'] . "', '" . $timenow . "') ON DUPLICATE KEY UPDATE currentrecordprocessing='" . $timenow . "'");

		}
	}

	mysql_query("update currentrecord set report='" . $repot . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);

    $query = "SELECT * FROM transactionlist where lineno='{$parameters[0]}' and transdate='{$parameters[1]}' and username='{$parameters[2]}' and transtype='{$parameters[3]}' and transgroup='{$parameters[4]}' ";
    $result = mysql_query($query, $connection);

    $counter = 0;
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_row($result)) {
            extract($row);

            $counter++;
            $repot = $row[2] . " [" . $counter . " / " . mysql_num_rows($result) . "]";
            mysql_query("update currentrecord set report='" . $repot . "' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);

            $queryTrans = "update transactionlist  set recordlock='1', post='1' where serialno='{$row[0]}' ";
            mysql_query($queryTrans, $connection);

            $queryTrans = "SELECT * FROM transactions where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}' and transno='{$row[0]}' ";
            $resultTrans = mysql_query($queryTrans, $connection);
            $credit = "0";
            $debit = "0";
            $balance = "0";
            if ($parameters[3] == "deposit" || $parameters[3] == "loandeposits") {
                $credit = $row[5];
            } else {
                $debit = $row[5];
            }
            $narration = "The sum of " . $row[5] . " being " . $parameters[3] . " by " . $row[2];
            $username = $_COOKIE['currentuser'];
            if (mysql_num_rows($resultTrans) > 0) {
                $queryTrans = "update transactions  set credit='{$credit}', debit='{$debit}', balance='{$balance}', username='{$row[9]}', transno='{$row[0]}',  narration='{$narration}' where cardno='{$row[2]}' and transdate='{$row[3]}' and lineno='{$row[1]}' and transtype='{$row[7]}' and transgroup='{$row[8]}'  and transno='{$row[0]}'  ";
                mysql_query($queryTrans, $connection);
                $serialnos = $serialno;
                updateTransBalances($row[2], $row[3]);
            } else {
                $queryTrans = "select max(serialno) as id from transactions ";
                $resultTrans = mysql_query($queryTrans, $connection);
                $rowTrans = mysql_fetch_array($resultTrans);
                extract($rowTrans);
                $serialnos = intval($rowTrans[0]) + 1;

                $queryTrans = "insert into transactions (serialno, cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock, lineno, transno, cardserial) values ('{$serialnos}', '{$row[2]}', '{$row[3]}', '{$narration}', '{$credit}', '{$debit}', '{$balance}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '1', '{$row[1]}', '{$row[0]}', '{$row[13]}') ";
                mysql_query($queryTrans, $connection);
                updateTransBalances($row[2], $row[3]);
            }
        }
    }

	mysql_query("update currentrecord set report='', currentrecordprocessing='' where currentuser='" . $_COOKIE['currentuser'] . "'", $connection);

	echo "postedTransactions";
}

if ($option == "updateRecord") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    $query = "SELECT * FROM {$table} where serialno ='{$serialnos}'";
    if ($table == "customers") {
        $query = "SELECT * FROM {$table} where cardno ='{$parameters[1]}'";
    }

    if ($table == "transactions") {
        $query = "SELECT * FROM {$table} where serialno ='{$serialnos}'";
    }

    if ($table == "transactionlist") {
        $query = "SELECT * FROM {$table} where serialno ='{$parameters[0]}'";
        $serialnos = $parameters[0];
    }

    if ($table == "transactionlist2") {
        $query = "SELECT * FROM transactionlist where serialno ='{$parameters[0]}'";
        $serialnos = $parameters[0];
    }
    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        $record = "";
        $count = 0;
        while ($row = mysql_fetch_row($result)) {
            extract($row);
            foreach ($row as $i => $value) {
                $meta = mysql_fetch_field($result, $i);
                if ($count > 0) {
                    $record .= $meta->name . "='" . $parameters[$count++] . "', ";
                } else {
                    $count++;
                }
            }
        }

        $record = substr($record, 0, strlen($record) - 2);
        $originaltrans = "transactionlist";
        if ($table == "transactionlist2") {
            $originaltrans = "transactionlist2";
            $table = "transactionlist";
        }

        $query = "UPDATE {$table} set " . $record . " where serialno ='{$serialnos}'";
        $result = mysql_query($query, $connection);

        if ($table == "transactions") {
            updateTransBalances($parameters[1], $parameters[2]);
            $query = "SELECT * FROM transactions where serialno ='{$parameters[0]}' ";
            $result = mysql_query($query, $connection);
            $row = mysql_fetch_array($result);
            extract($row);
            if ($row[12] != '0' && $row[7] != 'Open. Bal.') {
                $amount = $row[4];
                if ($row[5] > 0) {
                    $amount = $row[5];
                }
                $balanceA = $row[6];
                $balanceB = $row[6] - $row[4] + $row[5];
                $query = "SELECT * FROM transactionlist where cardno ='{$row[1]}' and transdate='{$row[2]}' and transtype='{$row[7]}' and lineno='{$row[11]}' and serialno='{$row[12]}' ";
//$qry="UPDATE currentrecord set report = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
                $result = mysql_query($query, $connection);
                if (mysql_num_rows($result) > 0) {
                    $query = "update transactionlist set amount='{$amount}', balance_b='{$balanceB}', balance_a='{$balanceA}' where cardno ='{$row[1]}' and transdate='{$row[2]}' and transtype='{$row[7]}' and lineno='{$row[11]}' and serialno='{$row[12]}' ";
                } else {
                    $query = "insert into transactionlist (lineno, cardno, transdate, balance_b, amount, balance_a, transtype, transgroup, username, recordlock, post, serialno) values ('{$row[11]}', '{$row[1]}', '{$row[2]}', '{$balanceB}', '{$amount}', '{$balanceA}', '{$row[7]}', '{$row[9]}', '{$row[8]}', '{$row[10]}', '1', '{$row[12]}') ";
                }
                mysql_query($query, $connection);
				if($row[7]=="commission"){
					$query = "update customers set commission='{$amount}' where cardno ='{$row[1]}' ";
					mysql_query($query, $connection);
				}
            }
        }

        if ($table == "transactionlist") {
            $query = "update transactions set credit='{$parameters[5]}' where cardno ='{$parameters[2]}' and transdate='{$parameters[3]}' and transtype='{$parameters[7]}' and lineno='{$parameters[1]}' and transno='{$parameters[0]}' ";
            if ($parameters[7] != 'deposit') {
                $query = "update transactions set debit='{$parameters[5]}' where cardno ='{$parameters[2]}' and transdate='{$parameters[3]}' and transtype='{$parameters[7]}' and lineno='{$parameters[1]}' and transno='{$parameters[0]}' ";
            }
            mysql_query($query, $connection);
            $query = "SELECT serialno FROM transactions where cardno ='{$parameters[2]}' and transdate='{$parameters[3]}' and transtype='{$parameters[7]}' and lineno='{$parameters[1]}' and transno='{$parameters[0]}' ";
//echo $query;
            $result = mysql_query($query, $connection);
			if (mysql_num_rows($result) > 0){
				$row = mysql_fetch_array($result);
				extract($row);
				if($serialno != null && $serialno !=""){
					updateTransBalances($parameters[2], $parameters[3]);
				}
			}
            $table = $originaltrans;
        }

        if ($table == "customers") {
            if ($parameters[8] != null && $parameters[8] != "") {
                $parameters[2] = date("Y-m-d");
                $parameters[3] = "Opening Balance";
                if ($parameters[8] > "0") {
                    $parameters[4] = $parameters[8];
                    $parameters[5] = "0";
                    $parameters[7] = "deposit";
                } else {
                    $parameters[4] = "0";
                    $parameters[5] = $parameters[8];
                    $parameters[7] = "withdrawal";
                }
                $parameters[6] = "0";
                $parameters[8] = $_COOKIE['currentuser'];
                $parameters[9] = "contribution";
            }
            if ($a_param2 != "][][][][][][][") {
                $parameters2 = explode("][", $a_param2);
                for ($count = 0; $count < count($parameters2); $count++) {
                    $parameters2[$count] = trim($parameters2[$count]);
                }

                /*$query = "SELECT * FROM loancustomers where cardno ='{$parameters2[1]}' ";
                $result = mysql_query($query, $connection);
                if (mysql_num_rows($result) == 0) {
                    $query = "INSERT INTO loancustomers (cardno, datedisbursed, loanamount, loaninterest, loanstartdate, loanenddate, repayoption, amountperrepay) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$parameters2[3]}', '{$parameters2[4]}', '{$parameters2[5]}', '{$parameters2[6]}', '{$parameters2[7]}','{$parameters2[8]}')";
                    mysql_query($query, $connection);

                    $narration = "The sum of " . $parameters2[10] . " being loan amount paid to (" . $parameters[1] . ")";
                    $query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$narration}', '0', '{$parameters2[3]}', '0',  'withdrawal', '{$_COOKIE[currentuser]}', 'loan', '1')";
                    mysql_query($query, $connection);

                    $narration = "The sum of " . $parameters2[11] . "being loan interest charged to (" . $parameters[1] . ")";
                    $query = "INSERT INTO transactions (cardno, transdate, narration, credit, debit, balance, transtype, username, transgroup, recordlock) VALUES ('{$parameters2[1]}', '{$parameters2[2]}', '{$narration}', '0', '{$parameters2[4]}', '0', 'interest', '{$_COOKIE[currentuser]}', 'loan', '1')";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transdate='{$parameters2[2]}' and transtype='withdrawal' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);
                    updateTransBalances($parameters2[1], $parameters2[2]);
                } else {
                    $query = "UPDATE loancustomers set datedisbursed='{$parameters2[2]}', loanamount='{$parameters2[3]}', loaninterest='{$parameters2[4]}', loanstartdate='{$parameters2[5]}', loanenddate='{$parameters2[6]}', repayoption='{$parameters2[7]}', amountperrepay='{$parameters2[8]}' where cardno ='{$parameters2[1]}' ";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transtype='withdrawal' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);

                    $narration = "The sum of " . $parameters2[10] . " being loan amount paid to (" . $parameters[1] . ")";
                    $query = "update transactions set cardno='{$parameters2[1]}', transdate='{$parameters2[2]}', narration='{$narration}', credit='0', debit='{$parameters2[3]}', balance='0', transtype='withdrawal', username='{$_COOKIE[currentuser]}', transgroup='loan', recordlock='1' where serialno='{$serialno}' ";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transtype='interest' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);

                    $narration = "The sum of " . $parameters2[11] . "being loan interest charged to (" . $parameters[1] . ")";
                    $query = "update transactions set cardno='{$parameters2[1]}', transdate='{$parameters2[2]}', narration='{$narration}', credit='0', debit='{$parameters2[4]}', balance='0', transtype='interest', username='{$_COOKIE[currentuser]}', transgroup='loan', recordlock='1' where serialno='{$serialno}' ";
                    mysql_query($query, $connection);

                    $query = "SELECT serialno FROM transactions where cardno ='{$parameters2[1]}' and transdate='{$parameters2[2]}' and transtype='withdrawal' and transgroup='loan' ";
                    $result = mysql_query($query, $connection);
                    $row = mysql_fetch_array($result);
                    extract($row);
                    updateTransBalances($parameters2[1], $parameters2[2]);
                }*/
            }
        }
        $usernames = $_COOKIE['currentuser'];
        $activitydescriptions = $usernames . " updated record into table: " . $table . " Record: " . str_replace("'", "", trim($record));
        $activitydates = date("Y-m-d");
        $activitytimes = date("H:i:s");
        $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
        mysql_query($query, $connection);
        //updateTransBalances($parameters[1],$parameters[0]);


        echo $table . "updated";
    } else {
        echo "recordnotexist";
    }
}

if ($option == "deleteRecord") {
    $parameters = explode("][", $a_param1);
    for ($count = 0; $count < count($parameters); $count++) {
        $parameters[$count] = trim($parameters[$count]);
    }

    if ($table == "customers") {
        $query = "SELECT * FROM transactionlist where cardno ='{$parameters[1]}'";
    }

    if ($table == "transactionlist") {
        $query = "SELECT * FROM transactionlist where serialno ='{$parameters[0]}'";
    }

    if ($table == "transactionlist2") {
        $query = "SELECT * FROM transactionlist where serialno ='{$parameters[0]}'";
    }
    //$query = "SELECT * FROM {$table} where serialno ='{$parameters[0]}'";		

    if ($table == "transactions") {
        $query = "SELECT * FROM transactions where serialno ='{$parameters[0]}'";
    }

    $result = mysql_query($query, $connection);
    if (mysql_num_rows($result) > 0) {
        if ($table == "customers") {
            $query = "SELECT * FROM customers where cardno ='{$parameters[1]}'";
            $result = mysql_query($query, $connection);
            if (mysql_num_rows($result) > 1) {
                $query = "DELETE FROM customers  where serialno ='{$parameters[0]}'";
                $result = mysql_query($query, $connection);
                echo $table . "deleted";
                return true;
            } else {
                echo "recordused";
                return true;
            }
//$qry="UPDATE currentrecord set currentrecordprocessing = '".str_replace("'", "`", $query)."' where currentuser='Admin'";
//$result = mysql_query($qry, $connection);
        }


        if ($table == "transactions") {
            $row = mysql_fetch_array($result);
            extract($row);
            $query = "delete FROM transactions where serialno ='{$row[0]}' ";
            mysql_query($query, $connection);
            updateTransBalances($parameters[1], $parameters[2]);

            $query = "delete FROM transactionlist where serialno ='{$row[12]}' ";
            mysql_query($query, $connection);

            $usernames = $_COOKIE['currentuser'];
            $activitydescriptions = $usernames . " deleted record from table: " . $table . " Query: " . str_replace("'", "", trim($query));
            $activitydates = date("Y-m-d");
            $activitytimes = date("H:i:s");
            $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
            mysql_query($query, $connection);

            echo $table . "deleted";
        }

        if ($table == "transactionlist" || $table == "transactionlist2") {
            $row = mysql_fetch_array($result);
            extract($row);
            if ($row['recordlock'] == "" || $_COOKIE['currentuser'] == 'Admin') {
                $query = "DELETE FROM transactionlist where serialno ='{$row[0]}'";
                $result = mysql_query($query, $connection);

                $query = "SELECT transno FROM transactions where transno ='{$row[0]}' ";
                $result = mysql_query($query, $connection);
				if (mysql_num_rows($result) > 0) {
					$query = "DELETE FROM transactions where transno ='{$row[0]}' ";
					$result = mysql_query($query, $connection);
					updateTransBalances($parameters[2], $parameters[3]);
				}

                $usernames = $_COOKIE['currentuser'];
                $activitydescriptions = $usernames . " deleted record from table: " . $table . " Query: " . str_replace("'", "", trim($query));
                $activitydates = date("Y-m-d");
                $activitytimes = date("H:i:s");
                $query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
                mysql_query($query, $connection);

                echo $table . "deleted";
            } else {
                echo $table . "recordlocked2";
            }
        }
    } else {
        if ($table == "customers") {
            $query = "DELETE FROM customers  where cardno ='{$parameters[1]}'";
            $result = mysql_query($query, $connection);
            echo $table . "deleted";
        } else {
            echo "recordnotexist";
        }
    }
}
?>
