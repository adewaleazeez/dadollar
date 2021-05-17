<?php
	if($_COOKIE["currentuser"]==null || $_COOKIE["currentuser"]==""){
		echo '<script>alert("You must login to access this page!!!\n Click Ok to return to Login page.");</script>';
		echo '<script>window.location = "login.php";</script>';
		return true;
	}

	global $tmp_examresultstable;
	global $tmp_finalresultstable;
	global $tmp_currentrecord;
	global $tmp_coursestable;
	global $tmp_regularstudents;
	global $tmp_gradestable;
	global $firstregnumber;
	global $lastregnumber;

	$results = 0;
	$resp = "";
	setcookie("resp", "", false);
	$currentusers = $_COOKIE['currentuser'];
	if (isset($_GET['ftype'])) {
		$ftype = $_GET['ftype'];
	}
	//include("data.php");
	$connection = mysqli_connect("localhost", "root", "admins", "dadollar");
$query = "update currentrecord set currentrecordprocessing='{$ftype}' where currentuser ='{$currentusers}'";
mysqli_query($connection, $query);
echo $query.'<br>';
	//$query = "INSERT INTO activities (userEmail, descriptions, activityDate, activityTime) VALUES ('{$currentusers}', '{$_FILES['txtFile']['type']}', '{$ftype}', '{$results}')";
	//mysqli_query($connection, $query);

	//if(ereg("application/vnd.ms-excel", $_FILES['txtFile']['type']) || ereg("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", $_FILES['txtFile']['type'])) {
	//if(str_in_str($_FILES['txtFile']['type'], "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || str_in_str($_FILES['txtFile']['type'], "application/vnd.ms-excel") ){
	$target_path = "";

	if($ftype=="pic"){
		$target_path = "photo/" . basename( $_FILES['txtFile']['name']);
$query = "update currentrecord set report='{$target_path}' where currentuser ='{$currentusers}'";
mysqli_query($connection, $query);
echo $query.'<br>';
		if(@move_uploaded_file($_FILES['txtFile']['tmp_name'], $target_path)) {
			$results = 1;
		}
		sleep(1);
		setcookie("filetype", "pic", false);
	}

	if($ftype=="doc"){
		$target_path = "photo/" . basename( $_FILES['txtFile2']['name']);

		if(@move_uploaded_file($_FILES['txtFile2']['tmp_name'], $target_path)) {
			$results = 1;
		}
		sleep(1);
		setcookie("filetype", "doc", false);
	}

	if($ftype=="excel"){
		$resp ="blankfile";
		if(strlen(basename( $_FILES['txtFile']['name']))>0) $resp ="wrongformat".basename( $_FILES['txtFile']['name']);
		if(str_in_str($_FILES['txtFile']['type'],"sheet") || str_in_str($_FILES['txtFile']['type'],"excel") ){
			//setcookie("responses", null, false);
			$sno=0;
			$response="";
			$target_path = "excelfiles/" . basename( $_FILES['txtFile']['name']);

			if(substr($target_path, strlen($target_path)-4, strlen($target_path))==".xls"){
				if(@move_uploaded_file($_FILES['txtFile']['tmp_name'], $target_path)) {
					$results = 1;
					$resp = "successful";
				}
			}

			sleep(1);
			setcookie("filetype", "excel", false);

			if($results==1){
				//setcookie("resp", "file uploaded", false);
				require_once 'reader.php';

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

				//$colname=array('id','name');
				$faculty = "";
				$department = "";
				$programme = "";
				$level = "";
				$sesionss = "";
				$semesters = "";
				$groupsession = "";
				$coursecode = "";
				$marksdescription = $_COOKIE['_markdescription'];
				$marksobtainable = 100.0;
				$ca = 0;
				$exam = 0;
				$percentage = 100.0;
				$matricno = "";
				$marksobtained = "";
				$resp = "successful";
				$sheets=0;
				$thecoursecodes="";
				$process=true;
				for ($sheets = 0; isset($data->sheets[$sheets]['numRows']); $sheets++){
					$coursetitle = trim($data->sheets[$sheets]['cells'][5][1])."";
					if($coursetitle!="Course Code"){
						$resp ="wrongexcel".basename( $_FILES['txtFile']['name']);
						$results = 0;
						$process=false;
						//$resp = str_replace(" ", "_", $resp);
						setcookie("resp", $resp, false);
						$quit=true;
						break;
					}
					$cellvalue = trim($data->sheets[$sheets]['cells'][5][2])."";
					if(str_in_str($thecoursecodes,$cellvalue) && $thecoursecodes>"" && $cellvalue>""){
						$resp = $cellvalue."coursecodeduplicate ".($sheets+1);
						$process=false;
						//$resp = str_replace(" ", "_", $resp);
						setcookie("resp", $resp, false);
						$quit=true;
						break;
					}else{
						$thecoursecodes .= $cellvalue;
					}
				}

				$query = "update currentrecord set currentrecordprocessing='', report='Matric No~Last Name~Other Names~Course Code~Remarks~Comments_~_' where currentuser ='{$currentusers}'";
				mysqli_query($connection, $query);
				
				$cookiefacult=str_replace("_", ",", $_COOKIE['_facultys']);
				$cookiedept=str_replace("_", ",", $_COOKIE['_departments']);
				$cookieprog=str_replace("_", ",", $_COOKIE['_programmes']);
				$cookielevel=str_replace("_", ",", $_COOKIE['_studentlevel']);
				$cookiesession=str_replace("_", ",", $_COOKIE['_session']);
				$cookiesemester=str_replace("_", ",", $_COOKIE['_semester']);
				//$cookiecoursecode=str_replace("_", ",", $_COOKIE['_coursecode']); coursecode ='{$cookiecoursecode}' and 

				$query = "SELECT min(b.regNumber) as firstregnumber, max(b.regNumber) as lastregnumber, min(a.qualificationcode) as qualifications FROM regularstudents AS a JOIN registration AS b ON a.regNumber=b.regNumber and b.sessions='{$cookiesession}'  and b.semester='{$cookiesemester}' and a.facultycode='{$cookiefacult}' and a.departmentcode='{$cookiedept}' and a.programmecode='{$cookieprog}' and b.studentlevel='{$cookielevel}' and a.lockrec!='Yes' order by a.regNumber"; 
				$result = mysqli_query($connection, $query);
				extract (mysqli_fetch_array($result, MYSQLI_NUM));

				$tmp_examresultstable="tmp_".date("Y_m_d_H_i_s")."_examresultstable";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_examresultstable}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_examresultstable="tmp_".date("Y_m_d_H_i_s")."_examresultstable";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_examresultstable." select * from examresultstable limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_examresultstable." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_examresultstable." SELECT * FROM examresultstable where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
				mysqli_query($connection, $query);

				$tmp_finalresultstable="tmp_".date("Y_m_d_H_i_s")."_finalresultstable";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_finalresultstable}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_finalresultstable="tmp_".date("Y_m_d_H_i_s")."_finalresultstable";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_finalresultstable." select * from finalresultstable limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_finalresultstable." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_finalresultstable." SELECT * FROM finalresultstable where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
				mysqli_query($connection, $query);

				$tmp_coursestable="tmp_".date("Y_m_d_H_i_s")."_coursestable";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_coursestable}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_coursestable="tmp_".date("Y_m_d_H_i_s")."_coursestable";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_coursestable." select * from coursestable limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_coursestable." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_coursestable." SELECT * FROM coursestable where facultycode ='{$cookiefacult}' and departmentcode ='{$cookiedept}' and programmecode ='{$cookieprog}' and studentlevel ='{$cookielevel}' and sessiondescription ='{$cookiesession}' and semesterdescription ='{$cookiesemester}' ";
				mysqli_query($connection, $query);

				$tmp_regularstudents="tmp_".date("Y_m_d_H_i_s")."_regularstudents";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_regularstudents}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_regularstudents="tmp_".date("Y_m_d_H_i_s")."_regularstudents";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_regularstudents." select * from regularstudents limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_regularstudents." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_regularstudents." SELECT * FROM regularstudents where regNumber>='{$firstregnumber}' and regNumber<='{$lastregnumber}' group by regNumber";
				mysqli_query($connection, $query);

				$tmp_unitstable="tmp_".date("Y_m_d_H_i_s")."_unitstable";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_unitstable}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_unitstable="tmp_".date("Y_m_d_H_i_s")."_unitstable";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_unitstable." select * from unitstable limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_unitstable." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_unitstable." SELECT * FROM unitstable where facultycode ='{$cookiefacult}' and departmentcode ='{$cookiedept}' and programmecode ='{$cookieprog}' and studentlevel ='{$cookielevel}' and sessiondescription ='{$cookiesession}' and semesterdescription ='{$cookiesemester}' ";
				mysqli_query($connection, $query);

				$tmp_registration="tmp_".date("Y_m_d_H_i_s")."_registration";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_registration}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_registration="tmp_".date("Y_m_d_H_i_s")."_registration";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_registration." select * from registration limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_registration." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_registration." SELECT * FROM registration where regNumber>='{$firstregnumber}' and regNumber<='{$lastregnumber}' ";
				mysqli_query($connection, $query);

				$tmp_retakecourses="tmp_".date("Y_m_d_H_i_s")."_retakecourses";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_retakecourses}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_retakecourses="tmp_".date("Y_m_d_H_i_s")."_retakecourses";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_retakecourses." select * from retakecourses limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_retakecourses." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_retakecourses." SELECT * FROM retakecourses where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
				mysqli_query($connection, $query);

				$tmp_gradestable="tmp_".date("Y_m_d_H_i_s")."_gradestable";
				while (true){
					$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name ='{$tmp_gradestable}'  ";
					$result = mysqli_query($connection, $query);
					if(mysql_num_rows($result) > 0){
						$tmp_gradestable="tmp_".date("Y_m_d_H_i_s")."_gradestable";
					}else{
						break;
					}
				}

				$query = "create table ".$tmp_gradestable." select * from gradestable limit 0";
				mysqli_query($connection, $query);

				$query = "alter table ".$tmp_gradestable." MODIFY COLUMN `serialno` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT, add PRIMARY KEY (`serialno`)";
				mysqli_query($connection, $query);

				$query = "insert into ".$tmp_gradestable." SELECT * FROM gradestable where sessions='{$cookiesession}' and qualification='{$qualifications}' ";
				mysqli_query($connection, $query);

				for ($sheets = 0; isset($data->sheets[$sheets]['numRows']) && $process; $sheets++){
					
					$skip=trim($data->sheets[$sheets]['cells'][1][4])."";
					if(strtoupper($skip)=="SKIP") continue;
					for ($row_s = 1; $row_s <= $data->sheets[$sheets]['numRows']; $row_s++) {
						$quit=false;
						$save=true;
						$thereport="";
						for ($col_s = 1; $col_s <= $data->sheets[$sheets]['numCols']; $col_s++) {
//$resp.= "   sheet  $sheets               row  $row_s        col  $col_s               ";
							if($col_s>=8) break;

							if($row_s==1 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM facultiestable where facultydescription ='{$cellvalue}'";
								$faculty = checkCode($query);
								if($faculty=="notinsetup"){
									$resp = $faculty.$cellvalue."notinsetupSchool";
									$quit=true;
									break;
								}
								$cookiefacult=str_replace("_", ",", $_COOKIE['_facultys']);
								if($cellvalue!=$cookiefacult){
									$resp = "School Code in Excel File ".$cellvalue." does not match School Code Selected ".$cookiefacult;
									$quit=true;
									break;
								}
								$faculty=$cellvalue;
							}
							if($row_s==2 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM departmentstable where departmentdescription ='{$cellvalue}'";
								$department = checkCode($query);
								if($department=="notinsetup"){
									$resp = $department.$cellvalue."notinsetupDepartment";
									$quit=true;
									break;
								}

								$cookiedept=str_replace("_", ",", $_COOKIE['_departments']);
								if($cellvalue!=$cookiedept){
									$resp = "Department Code in Excel File ".$cellvalue." does not match Department Code Selected ".$cookiedept;
									$quit=true;
									break;
								}
								$department=$cellvalue;
							}
							if($row_s==3 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM programmestable where programmedescription ='{$cellvalue}'";
								$programme = checkCode($query);
								if($programme=="notinsetup"){
									$resp = $programme.$cellvalue."notinsetupProgramme";
									$quit=true;
									break;
								}

								$cookieprog=str_replace("_", ",", $_COOKIE['_programmes']);
								if($cellvalue!=$cookieprog){
									$resp = "Programme Code in Excel File ".$cellvalue." does not match Programme Code Selected File ".$cookieprog;
									$quit=true;
									break;
								}
								$programme=$cellvalue;
							}
							if($row_s==4 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM studentslevels where leveldescription ='{$cellvalue}'";
								$level = checkCode($query);
								if($level=="notinsetup"){
									$resp = $level.$cellvalue."notinsetupStudent_Level";
									$quit=true;
									break;
								}

								$cookielevel=str_replace("_", ",", $_COOKIE['_studentlevel']);
								if($cellvalue!=$cookielevel){
									$resp = "Student_Level in Excel File ".$cellvalue." does not match Student_Level Selected ".$_COOKIE['_studentlevel'];
									$quit=true;
									break;
								}
								$level=$cellvalue;
							}
							if($row_s==5 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$sesionss = trim($data->sheets[$sheets]['cells'][6][2])."";
								$semesters = trim($data->sheets[$sheets]['cells'][7][2])."";
								$query = "SELECT * FROM ".$tmp_coursestable." where coursecode ='{$cellvalue}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' and sessiondescription ='{$sesionss}' and semesterdescription ='{$semesters}' ";
								$coursecode = checkCode($query);
								if($coursecode=="notinsetup"){
									$resp = $coursecode.$cellvalue."notinsetupCourse_Code";
									$quit=true;
									break;
								}
								//if($cellvalue!=$_COOKIE['_coursecode']){
								//	$resp = "Course_Code in Excel File - ".$cellvalue." does not match Co//urse_Code Selected - ".$_COOKIE['_coursecode'];
								//	break;
								//}
								$coursecode=$cellvalue;
							}
							if($row_s==6 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM sessionstable where sessiondescription ='{$cellvalue}'";
								$sesionss = checkCode($query);
								if($sesionss=="notinsetup"){
									$resp = str_replace("/"," ",$sesionss).str_replace("/"," ",$cellvalue)."notinsetupSessions";
									$quit=true;
									break;
								}

								$cookiesession=str_replace("_", ",", $_COOKIE['_session']);
								if($cellvalue!=$cookiesession){
									$resp = "Session Code in Excel File ".str_replace("/"," ",$cellvalue)." does not match Session Code Selected  ".str_replace("/"," ",$_COOKIE['_session']);
									$quit=true;
									break;
								}
								$sesionss=$cellvalue;
							}
							if($row_s==7 && $col_s==2){
								$cellvalue = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								$query = "SELECT * FROM sessionstable where semesterdescription ='{$cellvalue}'";
								$semesters = checkCode($query);
								if($semesters=="notinsetup"){
									$resp = $semesters.$cellvalue."notinsetupSemester";
									$quit=true;
									break;
								}

								$cookiesemester=str_replace("_", ",", $_COOKIE['_semester']);
								if($cellvalue!=$cookiesemester){
									$resp = "Semester Code in Excel File ".$cellvalue." does not match Semester Code Selected ".$_COOKIE['_semester'];
									$quit=true;
									break;
								}
								$semesters=$cellvalue;
								$query = "select * FROM coursestable where serialno<>0 ";  
								if($faculty != "")
									$query .= " and facultycode='{$faculty}' "; 
								if($department != "")
									$query .= " and departmentcode='{$department}' "; 
								if($programme != "")
									$query .= " and programmecode='{$programme}' ";
								if($level != "")
									$query .= " and studentlevel='{$level}' "; 
								if($sesionss != "")
									$query .= " and sessiondescription='{$sesionss}' "; 
								if($semesters != "")
									$query .= " and semesterdescription='{$semesters}' "; 
								if($coursecode != "")
									$query .= " and coursecode='{$coursecode}' "; 
								$query .= " and recordlock='1' "; 
								$result = mysqli_query($connection, $query);
								if(mysql_num_rows($result) > 0){
									$resp = "uploadlocked for $coursecodes in $sesionss $semesters semester";
									$quit=true;
									break;
								}
							}							
							
							if($row_s==8){
								$query = "SELECT a.*, b.* FROM ".$tmp_regularstudents." a, ".$tmp_registration." b where a.facultycode='{$faculty}' and a.departmentcode='{$department}' and a.programmecode='{$programme}' and a.regNumber=b.regNumber and b.studentlevel='{$level}' and b.sessions='{$sesionss}' and b.semester='{$semesters}'";
								$result = mysqli_query($connection, $query);
								if(mysql_num_rows($result) == 0){
									$resp = "studentnotsetup{$faculty}studentnotsetup{$department}studentnotsetup{$programme}studentnotsetup{$level}studentnotsetup{$sesionss}studentnotsetup{$semesters}";
									$quit=true;
									break;
								}
							}

							if($row_s>9){
								if($col_s<4) continue;
								if($col_s==4) {
									$snum = trim($data->sheets[$sheets]['cells'][$row_s][1])."";
									$marks = trim($data->sheets[$sheets]['cells'][$row_s][7])."";
									$matricno = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
									$lastname = trim($data->sheets[$sheets]['cells'][$row_s][$col_s-2])."";
									$othernames = trim($data->sheets[$sheets]['cells'][$row_s][$col_s-1])."";
									if(($matricno==null || $matricno=="") && ($snum==null || $snum=="") && ($marks==null || $marks=="")){
										$quit=true;
										break;
									}
									if($matricno==""){
										$response = "Invalid Matric No (Blank Matric No) for ".$lastname." ".$othernames." in excel file"; 
										$thereport="Not Successful~".$response;
										$save=false;
									}
									$currentrecordprocessings=str_replace(" ","spc", ($sheets+1)."_-_".($row_s-9)."_-_".$coursecode."_-_".$matricno."_-_".$lastname."coma ".$othernames);
									$query = "update currentrecord set currentrecordprocessing='{$currentrecordprocessings}' where currentuser ='{$currentusers}'";
									mysqli_query($connection, $query);


									$query = "SELECT * FROM ".$tmp_regularstudents." where regNumber='{$matricno}' and facultycode='{$faculty}' and departmentcode='{$department}' and programmecode='{$programme}'";
									$result = mysqli_query($connection, $query);
									if(mysql_num_rows($result) == 0 && $matricno>""){
										//setcookie("azeez", $query, false);
										$response = "Invalid Matric No (Matric No does not exist in database) for ".$lastname." ".$othernames." in excel file of School:  ".$faculty.", Department:  ".$department.", Programme: ".$programme; 
										$thereport="Not Successful~".$response;
										$save=false;
									}
								}

								if($col_s==5) $ca = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								if($col_s==6) $exam = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
								if($col_s==7 && $save){
									$matricno = trim($data->sheets[$sheets]['cells'][$row_s][4])."";
									$lastname = trim($data->sheets[$sheets]['cells'][$row_s][2])."";
									$othernames = trim($data->sheets[$sheets]['cells'][$row_s][3])."";
									$maximumunits=0;
									$query = "SELECT distinct maximumunit FROM ".$tmp_unitstable."  where facultycode='{$faculty}' and departmentcode='{$department}' and programmecode='{$programme}' and studentlevel='{$level}' and sessiondescription='{$sesionss}' and semesterdescription='{$semesters}' "; 
									$result = mysqli_query($connection, $query);
									if(mysqli_num_rows($result) > 0){
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										$maximumunits=$row[0];
									}
	//$resp=$query;
									$totalunits=0;
									$query = "SELECT sum(courseunit) FROM ".$tmp_coursestable." where facultycode='{$faculty}' and departmentcode='{$department}' and programmecode='{$programme}' and studentlevel='{$level}' and sessiondescription='{$sesionss}' and semesterdescription='{$semesters}' and coursecode!='{$coursecode}'  and coursecode in (SELECT b.coursecode FROM  ".$tmp_finalresultstable." b where b.facultycode='{$faculty}' and b.departmentcode='{$department}' and b.programmecode='{$programme}' and b.studentlevel='{$level}' and b.sessiondescription='{$sesionss}' and b.semester='{$semesters}' and b.registrationnumber='{$matricno}') "; 
									$result = mysqli_query($connection, $query);
									if(mysql_num_rows($result) > 0){
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										$totalunits=$row[0];
									}
	//$resp.="  ".$query;

									$coursesunit=0;
									$query = "SELECT courseunit FROM ".$tmp_coursestable."  where facultycode='{$faculty}' and departmentcode='{$department}' and programmecode='{$programme}' and studentlevel='{$level}' and sessiondescription='{$sesionss}' and semesterdescription='{$semesters}' and coursecode='{$coursecode}' "; 
									$result = mysqli_query($connection, $query);
									if(mysql_num_rows($result) > 0){
										$row = mysqli_fetch_array($result, MYSQLI_NUM);
										extract ($row);
										$coursesunit=$row[0];
									}
	//$resp.="  ".$query;

									if(($coursesunit+$totalunits) > $maximumunits){
										$response = $matricno." ".$lastname." ".$othernames." Exceeded maximum unit of (".$maximumunits." units) in ".$semesters." semester of ".$sesionss." session"; 
										$thereport="Not Successful~".$response;
										$save=false;
									}
	//break;
									$query = "SELECT * FROM ".$tmp_regularstudents." where regNumber='{$matricno}' and facultycode='{$faculty}' and departmentcode='{$department}' and programmecode='{$programme}'";
									$result = mysqli_query($connection, $query);
									if(mysql_num_rows($result) == 1){
										$query = "SELECT * FROM ".$tmp_registration." where sessions ='{$sesionss}' and semester ='{$semesters}' and regNumber ='{$matricno}' and registered='Yes' ";
										$result = mysqli_query($connection, $query);
										if(mysql_num_rows($result) == 0){
											$response = $matricno." ".$lastname." ".$othernames." Did not register for ".$semesters." semester of ".$sesionss." session";
											$thereport="Not Successful~".$response;
											$save=false;
										}
									}

									$marksobtained = trim($data->sheets[$sheets]['cells'][$row_s][$col_s])."";
									if(($marksobtained==null || $marksobtained=="") && strlen($exam)==0){
										$thereport = $matricno."~$row_s $col_s    ".$lastname."~".$othernames."~".$coursecode."~$row_s $col_s    DID NOT SIT FOR EXAM~";
										if(!str_in_str($thereport,"~~~")){
											$query = "update currentrecord set report=concat(report,'{$thereport}','_~_') where currentuser ='{$currentusers}'";
											mysqli_query($connection, $query);
										}
										$thereport='';
										continue;
									}
									//$coursecode = trim($data->sheets[$sheets]['cells'][9][$col_s])."";
									$cstatus="";
									if(substr(trim($marksobtained),0,1)=="S" || substr(trim($marksobtained),0,3)=="ABS" || substr(trim($marksobtained),0,3)=="DNR" || substr(trim($marksobtained),0,1)=="I"){
										if(substr(trim($marksobtained),0,1)=="S") $cstatus="Sick";
										if(substr(trim($marksobtained),0,3)=="ABS") $cstatus="Absent";
										if(substr(trim($marksobtained),0,3)=="DNR") $cstatus="Did Not Register";
										if(substr(trim($marksobtained),0,1)=="I") $cstatus="Incomplete";

									//if(substr(trim($exam),0,1)=="S" || substr(trim($exam),0,3)=="ABS" || substr(trim($exam),0,3)=="DNR" || substr(trim($exam),0,1)=="I"){
									//	if(substr(trim($exam),0,1)=="S") $cstatus="Sick";
									//	if(substr(trim($exam),0,3)=="ABS") $cstatus="Absent";
									//	if(substr(trim($exam),0,3)=="DNR") $cstatus="Did Not Register";
									//	if(substr(trim($exam),0,1)=="I") $cstatus="Incomplete";

										$query = "SELECT * FROM ".$tmp_retakecourses." where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}'";
										$result = mysqli_query($connection, $query);
										if(mysql_num_rows($result) == 0){
											$querySNo="select max(serialno)+1 as nextserialno from ".$tmp_retakecourses;
											$resultSNo = mysqli_query($connection, $querySNo);
											extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));

											if($nextserialno<=1){
												$querySNo="select max(serialno)+1 as nextserialno from retakecourses ";
												$resultSNo = mysqli_query($connection, $querySNo);
												extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
											}

											$query = "INSERT INTO ".$tmp_retakecourses." (serialno, sessiondescription, semester, coursecode, registrationnumber, coursestatus, studentlevel, programmecode, facultycode, departmentcode) VALUES ('{$nextserialno}', '{$sesionss}', '{$semesters}', '{$coursecode}', '{$matricno}', '{$cstatus}', '{$level}', '{$programme}', '{$faculty}', '{$department}')";
										}else{
											$query = "UPDATE ".$tmp_retakecourses." set coursestatus='{$cstatus}' where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
										}
										if($save) mysqli_query($connection, $query);
										//and groupsession ='{$parameters[13]}'
										if($save){
											$query = "SELECT * FROM  ".$tmp_examresultstable."  where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
											$result = mysqli_query($connection, $query);
											if(mysql_num_rows($result) == 0 && $matricno>""){
												$querySNo="select max(serialno)+1 as nextserialno from ".$tmp_examresultstable;
												$resultSNo = mysqli_query($connection, $querySNo);
												extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));

												if($nextserialno<=1){
													$querySNo="select max(serialno)+1 as nextserialno from examresultstable ";
													$resultSNo = mysqli_query($connection, $querySNo);
													extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
												}

												$query = "INSERT INTO ".$tmp_examresultstable." (serialno, sessiondescription, semester, coursecode, registrationnumber, marksdescription, marksobtainable, percentage, studentlevel, programmecode, facultycode, departmentcode, currentuser) VALUES ('{$nextserialno}', '{$sesionss}', '{$semesters}', '{$coursecode}', '{$matricno}', '{$marksdescription}', '{$marksobtainable}', '{$percentage}', '{$level}', '{$programme}', '{$faculty}', '{$department}', '{$currentusers}')";
												$result=mysqli_query($connection, $query);

												if(!$result){
													$query2=str_replace("'","`",$query);
													$response = "Insert Failed for ".$matricnos."~".$query2;
													$thereport="Not Successful~".$response;
												}else{
													$thereport="Insert Successful~";
													//$query = "INSERT INTO  ".$tmp_examresultstable."  (sessiondescription, semester, coursecode, registrationnumber, marksdescription, marksobtainable, percentage, studentlevel, programmecode, facultycode, departmentcode, currentuser) VALUES ('{$sesionss}', '{$semesters}', '{$coursecode}', '{$matricno}', '{$marksdescription}', '{$marksobtainable}', '{$percentage}', '{$level}', '{$programme}', '{$faculty}', '{$department}', '{$currentusers}')";
													//$result=mysqli_query($connection, $query);
												}
											}else if($matricno>""){
												/////$query = "UPDATE examresultstable set marksobtained=NULL, currentuser='{$currentusers}'  where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
												/////$result = mysqli_query($connection, $query);

												if(!$result){
													$query2=str_replace("'","`",$query);
													$response = "Update Failed for ".$matricnos."~".$query2;
													$thereport="Not Successful~".$response;
												}else{
													$thereport="Update Successful~";
													$query = "UPDATE  ".$tmp_examresultstable."  set marksobtained=NULL, currentuser='{$currentusers}'  where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
													$result = mysqli_query($connection, $query);
												}
											}
											/*$query = "Delete FROM ".$tmp_examresultstable." where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}'  ";
											mysqli_query($connection, $query);

											$query = "insert into ".$tmp_examresultstable." SELECT * FROM examresultstable where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
											mysqli_query($connection, $query);*/

											if($save) $resp = updateFinalMarks($sesionss, $semesters, $coursecode, $matricno, $level, $programme, $cstatus, $faculty, $department, $ca, $exam);
										}
									}else{
										if(IsNaN($marksobtained)){
											$response = "The mark [".$marksobtained."] in the Excel must be numeric for ".$matricno." ".$lastname." ".$othernames;
											$thereport="Not Successful~".$response;
											$save=false;
										}
										if(!IsNaN($marksobtained) && $marksobtained>"100"){
											$response = "The mark [".$marksobtained."] in the Excel must be less than or equal to 100 for ".$matricno." ".$lastname." ".$othernames;
											$thereport="Not Successful~".$response;
											$save=false;
										}
										$marksobtained = doubleval($marksobtained);
										if($marksobtained==38 || $marksobtained==39) $marksobtained=40;
										$query = "SELECT * FROM  ".$tmp_examresultstable."  where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
										if($save){
											$result = mysqli_query($connection, $query);
											if(mysql_num_rows($result) == 0 && $matricno>""){
												$querySNo="select max(serialno)+1 as nextserialno from ".$tmp_examresultstable;
												$resultSNo = mysqli_query($connection, $querySNo);
												extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));

												if($nextserialno<=1){
													$querySNo="select max(serialno)+1 as nextserialno from examresultstable ";
													$resultSNo = mysqli_query($connection, $querySNo);
													extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
												}

												$query = "INSERT INTO ".$tmp_examresultstable." (serialno, sessiondescription, semester, coursecode, registrationnumber, marksdescription, marksobtained, marksobtainable, percentage, studentlevel, programmecode, facultycode, departmentcode, currentuser) VALUES ('{$nextserialno}', '{$sesionss}', '{$semesters}', '{$coursecode}', '{$matricno}', '{$marksdescription}', '{$marksobtained}', '{$marksobtainable}', '{$percentage}', '{$level}', '{$programme}', '{$faculty}', '{$department}','{$currentusers}')";
												$result = mysqli_query($connection, $query);

												if(!$result){
													$query2=str_replace("'","`",$query);
													$response = "Insert Failed for ".$matricnos."~".$query2;
													$thereport="Not Successful~".$response;
												}else{
													$thereport="Insert Successful~";
													//$query = "INSERT INTO  ".$tmp_examresultstable."  (sessiondescription, semester, coursecode, registrationnumber, marksdescription, marksobtained, marksobtainable, percentage, studentlevel, programmecode, facultycode, departmentcode, currentuser) VALUES ('{$sesionss}', '{$semesters}', '{$coursecode}', '{$matricno}', '{$marksdescription}', '{$marksobtained}', '{$marksobtainable}', '{$percentage}', '{$level}', '{$programme}', '{$faculty}', '{$department}','{$currentusers}')";
													//$result = mysqli_query($connection, $query);
												}
											}else if($matricno>""){
												/////$query = "UPDATE examresultstable set marksobtained='{$marksobtained}', currentuser='{$currentusers}' where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
												/////$result = mysqli_query($connection, $query);

												if(!$result){
													$query2=str_replace("'","`",$query);
													$response = "Update Failed for ".$matricnos."~".$query2;
													$thereport="Not Successful~".$response;
												}else{
													$thereport="Update Successful~";
													$query = "UPDATE  ".$tmp_examresultstable."  set marksobtained='{$marksobtained}', currentuser='{$currentusers}' where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
													$result = mysqli_query($connection, $query);
												}
											}
											/*$query = "Delete FROM ".$tmp_examresultstable." where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}'  ";
											mysqli_query($connection, $query);

											$query = "insert into ".$tmp_examresultstable." SELECT * FROM examresultstable where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
											mysqli_query($connection, $query);*/

										}
									}
									
									if($matricno>""){
										if($save) {
											if(substr($thereport,0,17)=="Update Successful"){
												$query = "Select serialno from ".$tmp_examresultstable." where sessiondescription ='{$sesionss}' and semester ='{$semesters}' and coursecode ='{$coursecode}' and registrationnumber ='{$matricno}' and marksdescription ='{$marksdescription}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
												$result = mysqli_query($connection, $query);
												extract (mysqli_fetch_array($result, MYSQLI_NUM));

												/*$query = "Delete from examresultstable where serialno ='{$serialno}' ";
												mysqli_query($connection, $query);

												$query = "insert into examresultstable SELECT * FROM ".$tmp_examresultstable." where serialno ='{$serialno}' ";
												mysqli_query($connection, $query);*/
											}

											$resp = updateFinalMarks($sesionss, $semesters, $coursecode, $matricno, $level, $programme, $cstatus, $faculty, $department, $ca, $exam);
											if(str_in_str($resp,"already passed")) {
												$response = $resp;
												$thereport="Not Successful~".$response;
												$save=false;
											}
											$query = "DELETE FROM ".$tmp_retakecourses." where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$coursecode}' and registrationnumber='{$matricno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' and coursestatus='' "; //
											if($save) mysqli_query($connection, $query);
										}
									}
								}
							}
							if($quit) break;
						}
						if($quit) break;
						$oldthereport=$thereport;
						$thereport = $matricno."~$row_s $col_s    ".$lastname."~".$othernames."~".$coursecode."~".$thereport;
						if(!str_in_str($thereport,"~~~") && $row_s>9 && $oldthereport!=''){
							$query = "update currentrecord set report=concat(report,'{$thereport}','_~_') where currentuser ='{$currentusers}'";
							mysqli_query($connection, $query);
							$thereport='';
							$oldthereport='';
						}		
						if($resp != "successful") {
							$results = 2;
							break;
						}
					}

					//where B.sessiondescription='{$sesionss}' and B.semester='{$semesters}' and B.coursecode='{$coursecode}' 
					$query = "insert into examresultstable SELECT * FROM ".$tmp_examresultstable." B ON DUPLICATE KEY UPDATE sessiondescription=B.sessiondescription, semester=B.semester, coursecode=B.coursecode, registrationnumber=B.registrationnumber, marksdescription=B.marksdescription, marksobtained=B.marksobtained, marksobtainable=B.marksobtainable, percentage=B.percentage, studentlevel=B.studentlevel, programmecode=B.programmecode, facultycode=B.facultycode, departmentcode=B.departmentcode, currentuser=B.currentuser ";
					mysqli_query($connection, $query);

					//where B.sessiondescription='{$sesionss}' and B.semester='{$semesters}' and B.coursecode='{$coursecode}' 
					$query = "insert into finalresultstable SELECT * FROM ".$tmp_finalresultstable." B ON DUPLICATE KEY UPDATE sessiondescription=B.sessiondescription, semester=B.semester, coursecode=B.coursecode, registrationnumber=B.registrationnumber, studentlevel=B.studentlevel, programmecode=B.programmecode, facultycode=B.facultycode, departmentcode=B.departmentcode, marksobtained=B.marksobtained, gradecode=B.gradecode, gradeunit=B.gradeunit, coursestatus=B.coursestatus, gradepoint=B.gradepoint, cascore=B.cascore, examscore=B.examscore, currentuser=B.currentuser ";
					mysqli_query($connection, $query);

					//where B.sessiondescription='{$sesionss}' and B.semester='{$semesters}' and B.coursecode='{$coursecode}' 
					$query = "insert into retakecourses SELECT * FROM ".$tmp_retakecourses." B ON DUPLICATE KEY UPDATE sessiondescription=B.sessiondescription, semester=B.semester, coursecode=B.coursecode, registrationnumber=B.registrationnumber, studentlevel=B.studentlevel, programmecode=B.programmecode, facultycode=B.facultycode, departmentcode=B.departmentcode, coursestatus=B.coursestatus ";
					mysqli_query($connection, $query);

					//if($quit) break;
					$usernames = $_COOKIE['currentuser'];
					$activitydescriptions = $usernames." uploaded examination marks for Course Code: ".$coursecode.", Department: ".$department.", Program: ".$programme.", Level: ".$level.", Session: ".$sesionss.", Semester: ".$semesters;
					$activitydates = date("Y-m-d");
					$activitytimes = date("H:i:s");
					$query = "INSERT INTO activities (username, descriptions, activityDate, activityTime) VALUES ('{$usernames}', '{$activitydescriptions}', '{$activitydates}', '{$activitytimes}')";
					mysqli_query($connection, $query);
				}

			}else{
				$results = 0;
				//$resp = "file not uploaded";
			}
		}
		sleep(1);
		$resp = str_replace(" ", "_", $resp);
		setcookie("resp", $resp, false);

		$query = "DROP TABLE IF EXISTS ".$tmp_examresultstable;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_examresultstable%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_finalresultstable;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_finalresultstable%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_coursestable;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_coursestable%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_regularstudents;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_regularstudents%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_unitstable;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_unitstable%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_registration;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_registration%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

		$query = "DROP TABLE IF EXISTS ".$tmp_gradestable;
		mysqli_query($connection, $query);

		$query = "SELECT * FROM information_schema.tables WHERE table_schema = 'laspotechdb' AND table_name LIKE '%tmp_%'  AND table_name LIKE '%_gradestable%'  ";
		$result = mysqli_query($connection, $query);
		if(mysql_num_rows($result) > 0){
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if(substr($row[2],4,10)<date("Y_m_d")){
					$queryDrop = "DROP TABLE IF EXISTS ".$row[2];
					mysqli_query($connection, $queryDrop);
				}
			}
		}

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

	//function printHeader($facultycode, $departmentcode, $programmecode, $studentlevel, $sessions, $semester){
	//}

	function checkCode($query){
		include("data.php"); 
		$result = mysqli_query($connection, $query);
		$resp="";
		if(mysql_num_rows($result)==0) $resp .= "notinsetup";
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

	function updateFinalMarks($sesionss, $semesters, $course, $regno, $level, $programme, $cstatus, $faculty, $department, $ca, $exam){
		include("data.php");
		$currentusers = $_COOKIE['currentuser'];
		$tmp_examresultstable = $GLOBALS['tmp_examresultstable'];
		$tmp_finalresultstable = $GLOBALS['tmp_finalresultstable'];
		$tmp_coursestable = $GLOBALS['tmp_coursestable'];
		$tmp_regularstudents = $GLOBALS['tmp_regularstudents'];
		$tmp_gradestable = $GLOBALS['tmp_gradestable'];
		$firstregnumber = $GLOBALS['firstregnumber'];
		$lastregnumber = $GLOBALS['lastregnumber'];
//if($regno=="086141036"){
//	return "invalidmark    ".$ca."    ".$exam; 
//}

		/*$query = "Delete FROM ".$tmp_examresultstable." where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}'  ";
		mysqli_query($connection, $query);

		$query = "insert into ".$tmp_examresultstable." SELECT * FROM examresultstable where registrationnumber>='{$firstregnumber}' and registrationnumber<='{$lastregnumber}' ";
		mysqli_query($connection, $query);*/

		$query = "SELECT qualificationcode FROM ".$tmp_regularstudents." where regNumber='{$regno}'";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			extract ($row);
		}

		$query = "SELECT courseunit, minimumscore FROM ".$tmp_coursestable." where sessiondescription='{$sesionss}' and semesterdescription='{$semesters}' and coursecode='{$course}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			extract ($row);
		}

		$query = "SELECT * FROM  ".$tmp_examresultstable."  where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' order by sessiondescription, semester, coursecode, registrationnumber";
		$result = mysqli_query($connection, $query);

		if(mysql_num_rows($result) > 0){
			$obtained = 0.0;
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				extract ($row);
				if($marksobtained!=null) $obtained += (doubleval($marksobtained) * doubleval($percentage))/doubleval($marksobtainable);
			}
			//$obtained = number_format($obtained,2);

			if($obtained>100){
				return"invalidmark".$obtained."_For ".$regno; 
			}

			$query = "select * from ".$tmp_gradestable." where {$obtained}>=lowerrange and {$obtained}<=upperrange and sessions='{$sesionss}' and qualification='{$qualificationcode}'";
			$result = mysqli_query($connection, $query);
			$gcode="";
			$gunit="";
			if(mysql_num_rows($result) > 0){
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
					extract ($row);
					$gcode=$row[1];
					$gunit=$row[4];
				}
			}else{
				return"gradenotsetup".$sesionss."_Session ".$regno; 
			}
			if($gunit=="") $gunit="0";
			$gradepoints=$gunit * $courseunit;
			$query = "SELECT * FROM  ".$tmp_finalresultstable."  where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
			$result = mysqli_query($connection, $query);
			if($cstatus=="Sick") $gcode="S";
			if($cstatus=="Did Not Register") $gcode="DNR";
			if($cstatus=="Incomplete") $gcode="I";
			if(mysql_num_rows($result) > 0){
				if($cstatus=="Sick" || $cstatus=="Did Not Register" || $cstatus=="Incomplete"){ 
					/////$query = "update  finalresultstable  set gradecode='{$gcode}', gradeunit=null, coursestatus='{$cstatus}', gradepoint=null, cascore=null, examscore=null, markobtained=null, currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					/////mysqli_query($connection, $query);

					$query = "update   ".$tmp_finalresultstable."   set gradecode='{$gcode}', gradeunit=null, coursestatus='{$cstatus}', gradepoint=null, cascore=null, examscore=null, markobtained=null, currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					mysqli_query($connection, $query);
				}else{
					/////$query = "update  finalresultstable  set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					/////mysqli_query($connection, $query);

					$query = "update   ".$tmp_finalresultstable."   set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					mysqli_query($connection, $query);
					if($obtained>0){
						/////$query = "update  finalresultstable  set marksobtained='{$obtained}', gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						/////mysqli_query($connection, $query);

						$query = "update   ".$tmp_finalresultstable."   set marksobtained='{$obtained}', gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						mysqli_query($connection, $query);
					}
				}
				if($ca>0){
					/////$query = "update  finalresultstable  set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', cascore='{$ca}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					/////mysqli_query($connection, $query);

					$query = "update   ".$tmp_finalresultstable."   set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}', cascore='{$ca}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					mysqli_query($connection, $query);
				}
				if($exam>0){
					/////$query = "update  finalresultstable  set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}',examscore='{$exam}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					/////mysqli_query($connection, $query);

					$query = "update   ".$tmp_finalresultstable."   set gradecode='{$gcode}', gradeunit='{$gunit}', coursestatus='{$cstatus}', gradepoint='{$gradepoints}',examscore='{$exam}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
					mysqli_query($connection, $query);
				}
			}else{
				$query = "SELECT * FROM  ".$tmp_finalresultstable."  where (sessiondescription<>'{$sesionss}' || semester<>'{$semesters}' || studentlevel<>'{$level}') and  coursecode ='{$coursecode}' and registrationnumber ='{$regno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and marksobtained>='{$minimumscore}'";
				$result = mysqli_query($connection, $query);
				//if(mysql_num_rows($result) == 0){
				if(mysql_num_rows($result)==0 || $cstatus=="Sick" || $cstatus=="Did Not Register" || $cstatus=="Incomplete"){
					if($cstatus=="Sick" || $cstatus=="Did Not Register" || $cstatus=="Incomplete"){ 
						//$query = "insert into  finalresultstable  (sessiondescription, semester, coursecode, registrationnumber, gradecode, gradeunit, studentlevel, programmecode, coursestatus, facultycode, departmentcode, gradepoint, currentuser ) values ('{$sesionss}', '{$semesters}', '{$course}', '{$regno}', '{$gcode}', null, '{$level}', '{$programme}', '{$cstatus}', '{$faculty}', '{$department}', null,'{$currentusers}')";
						//mysqli_query($connection, $query);

						$querySNo="select max(serialno)+1 as nextserialno from ".$tmp_finalresultstable;
						$resultSNo = mysqli_query($connection, $querySNo);
						extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
						$nextserialno = $row[0];
						
						if($nextserialno<=1){
							$querySNo="select max(serialno)+1 as nextserialno from finalresultstable ";
							$resultSNo = mysqli_query($connection, $querySNo);
							extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
							$nextserialno = $row[0];
						}

						$query = "insert into   ".$tmp_finalresultstable."   (serialno, sessiondescription, semester, coursecode, registrationnumber, gradecode, gradeunit, studentlevel, programmecode, coursestatus, facultycode, departmentcode, gradepoint, currentuser ) values ('{$nextserialno}', '{$sesionss}', '{$semesters}', '{$course}', '{$regno}', '{$gcode}', null, '{$level}', '{$programme}', '{$cstatus}', '{$faculty}', '{$department}', null,'{$currentusers}')";
						mysqli_query($connection, $query);
					}else{
						//$query = "insert into  finalresultstable  (sessiondescription, semester, coursecode, registrationnumber, gradecode, gradeunit, studentlevel, programmecode, coursestatus, facultycode, departmentcode, gradepoint, currentuser ) values ('{$sesionss}', '{$semesters}', '{$course}', '{$regno}', '{$gcode}', '{$gunit}', '{$level}', '{$programme}', '{$cstatus}', '{$faculty}', '{$department}', '{$gradepoints}', '{$currentusers}')";
						//mysqli_query($connection, $query);

						$querySNo="select max(serialno)+1 as nextserialno from ".$tmp_finalresultstable;
						$resultSNo = mysqli_query($connection, $querySNo);
						extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
						$nextserialno = $row[0];

						if($nextserialno<=1){
							$querySNo="select max(serialno)+1 as nextserialno from finalresultstable ";
							$resultSNo = mysqli_query($connection, $querySNo);
							extract (mysqli_fetch_array($resultSNo, MYSQLI_NUM));
							$nextserialno = $row[0];
						}

						$query = "insert into   ".$tmp_finalresultstable."   (serialno, sessiondescription, semester, coursecode, registrationnumber, gradecode, gradeunit, studentlevel, programmecode, coursestatus, facultycode, departmentcode, gradepoint, currentuser ) values ('{$nextserialno}', '{$sesionss}', '{$semesters}', '{$course}', '{$regno}', '{$gcode}', '{$gunit}', '{$level}', '{$programme}', '{$cstatus}', '{$faculty}', '{$department}', '{$gradepoints}', '{$currentusers}')";
						mysqli_query($connection, $query);
					}
					if($obtained>0){
						/////$query = "update  finalresultstable  set marksobtained='{$obtained}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						/////mysqli_query($connection, $query);

						$query = "update   ".$tmp_finalresultstable."   set marksobtained='{$obtained}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						mysqli_query($connection, $query);
					}
					if($ca>0){
						/////$query = "update  finalresultstable  set cascore='{$ca}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						/////mysqli_query($connection, $query);

						$query = "update   ".$tmp_finalresultstable."   set cascore='{$ca}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						mysqli_query($connection, $query);
					}
					if($exam>0){
						/////$query = "update  finalresultstable  set examscore='{$exam}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						/////mysqli_query($connection, $query);

						$query = "update   ".$tmp_finalresultstable."   set examscore='{$exam}', currentuser='{$currentusers}' where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and studentlevel='{$level}' and programmecode='{$programme}' and facultycode ='{$faculty}' and departmentcode ='{$department}' ";
						mysqli_query($connection, $query);
					}
				}else{
					while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
						extract ($row);
					}
					$query = "DELETE FROM examresultstable where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
					mysqli_query($connection, $query);

					$query = "DELETE FROM  ".$tmp_examresultstable."  where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
					mysqli_query($connection, $query);

					//return $regno." in the Excel already passed ".$coursecode." in ".str_replace("/"," ",$sessiondescription)." ".$semester." Semester"; 
				}
			}
			$query = "Select serialno from ".$tmp_finalresultstable." where sessiondescription='{$sesionss}' and semester='{$semesters}' and coursecode='{$course}' and registrationnumber='{$regno}' and facultycode ='{$faculty}' and departmentcode ='{$department}' and programmecode ='{$programme}' and studentlevel ='{$level}' ";
			$result = mysqli_query($connection, $query);
			extract (mysqli_fetch_array($result, MYSQLI_NUM));

			/*$query = "Delete from finalresultstable where serialno ='{$serialno}' ";
			mysqli_query($connection, $query);

			$query = "insert into finalresultstable SELECT * FROM ".$tmp_finalresultstable." where serialno ='{$serialno}' ";
			mysqli_query($connection, $query);*/
		}
		return true;
	}
	mysqli_close($connection);
?>

<script language="javascript" type="text/javascript">
	window.top.window.stopUpload(<?php echo $results; ?>);
	window.top.window.stopUpload2(<?php echo $results; ?>);
	window.top.window.stopUpload3(<?php echo $results; ?>);
</script>
