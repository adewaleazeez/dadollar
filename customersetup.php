<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
-->

<!--@page contentType="text/html" pageEncoding="UTF-8"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Da-Dollar Global Resources Limited</title>
		<!-- DEMO styles - specific to this page -->
		<link rel="stylesheet" type="text/css" href="css/complex.css" />
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
		<!--[if lte IE 7]>
			<style type="text/css"> body { font-size: 85%; } </style>
		<![endif]-->

        <script type="text/javascript" src="js/calendar.js"></script>
		<script type="text/javascript" src="js/jquery-ui-latest.js"></script>
		<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
		<script type="text/javascript" src="js/complex.js"></script>
        <script type="text/javascript" src="js/setup.js"></script>
		<script type="text/javascript" src="js/utilities.js"></script>

		<script language="javascript" src="js/jquery.marquee.js"></script>

		<link href="css/mycss.css" rel="stylesheet" type="text/css"/>
		<link href="css/westmart.css" rel="stylesheet" type="text/css"/>

        <script type="text/javascript">
            checkLogin();
        </script>
        <script type="text/javascript">
            $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $("#dialog").dialog("destroy");

                $("#customerinfo").dialog({
                    autoOpen: true,
                    position:"center",
                    title: "Customers Details - Items with RED colour labels are mandatory",
                    height: 600,
                    width: 1300,
                    modal: false,
                    buttons: {
                        Save: function() {
                            updateCustomer("addRecord", "customers");
                        },
                        Update: function() {
                            updateCustomer("updateRecord", "customers");
                        },
                        Delete: function() {
                            updateCustomer("deleteRecord", "customers");
                        },
                        New: function() {
							createCookie("savetype", "add", false);
                            resetForm("customers");
                        },
                        Close: function() {
                            $("#customerinfo").dialog("close");
							window.location="home.php?pgid=0";
                        }
                    }
                });

                $("#myupload").dialog({
                    autoOpen: false,
                    position:"center",
                    title: "Upload Customers` Records!!!",
                    height: 300,
                    width: 300,
                    modal: true
                });

                $("#updatecardno").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Update Card No!!!',
                    height: 200,
                    width: 650,
                    modal: true,
                    buttons: {
                        Update: function() {
                            updateCardNo();
                        },
                        Close: function() {
                            $('#updatecardno').dialog('close');
                            $('#customerinfo').dialog('open');
                            getRecords('customers');
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position:"center",
                    title: "Alert!!!",
                    height: 300,
                    width: 600,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $("#showPrompt").dialog("close");
							$("#showAlert").dialog("close");
							$("#showError").dialog("close");
                        }
                    }
                });

                $("#showRecord").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showRecord').dialog('close');
							$('#showAlert').dialog('close');
							$('#showError').dialog('close');
                        },
                    }
                });

                $("#showAlert").dialog({
                    autoOpen: false,
                    position:"center",
                    title: "Alert!!!",
                    height: 280,
                    width: 350,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position:"center",
                    title: "Error Message",
                    height: 300,
                    width: 500,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $("#showPrompt").dialog("close");
							$("#showRecord").dialog("close");
							$("#showAlert").dialog("close");
							$("#showError").dialog("close");
                        }
                    }
                });

            });

            function browseFiles(){
                var txtFile = document.getElementById("txtFile");
                txtFile.click();
            }

			function submitForm(){
                var submitButton = document.getElementById("submitButton");
                submitButton.click();
			}

			function startUpload(){
				var filename = document.getElementById("txtFile").value;
				var filenames = filename.split("\\");
				var theImage = filenames[filenames.length-1];
				createCookie("theImage",theImage,false);
				document.getElementById("f1_upload_process").style.visibility = "visible";
				document.getElementById("f1_upload_form").style.visibility = "hidden";
				document.getElementById("f1_upload_button").style.visibility = "hidden";
				document.getElementById("f1_uploaded_file").style.visibility = "hidden";
			}

			function stopUpload(success){
				if(readCookie("filetype")!="pic") return true;
				var result = "";
				if(success == 1){
					result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
				}else {
					createCookie("theImage",null,false);
					result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
					alert("There was an error during file upload!");
				}
				document.getElementById("f1_upload_process").style.visibility = "hidden";
				var theImage = readCookie("theImage");
				document.getElementById("f1_uploaded_file").innerHTML = "<img src='photo/"+theImage+"'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
				document.getElementById('f1_upload_form').style.visibility = 'visible';      
				document.getElementById('f1_upload_button').style.visibility = 'visible';      
				document.getElementById('f1_uploaded_file').style.visibility = 'visible';      
				return true;   
			}

			function loadImage(imageID){
				document.getElementById('f1_uploaded_file').innerHTML = "<img src='photo/"+imageID+"'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
			}

			createCookie("theImage", 'silhouette.jpg', false);

			function checkKeyPressed(e, id){ //e is event object passed from function invocation
				var characterCode; //literal character code will be stored in this variable
				if(e && e.which){ //if which property of event object is supported (NN4)
					e = e;
					characterCode = e.which //character code is contained in NN4's which property
				} else {
					e = event;
					characterCode = e.keyCode; //character code is contained in IE's keyCode property
				}

				if(id=="cardno"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("lastname").focus();
					}
					if(characterCode==38){
					//	document.getElementById("cardno").focus();
					}
				}else if(id=="lastname"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("othernames").focus();
					}
					if(characterCode==38){
						document.getElementById("cardno").focus();
					}
				}else if(id=="othernames"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("sex").focus();
					}
					if(characterCode==38){
						document.getElementById("lastname").focus();
					}
				}else if(id=="sex"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("telephone").focus();
					}
					if(characterCode==38){
						document.getElementById("othernames").focus();
					}
				}else if(id=="telephone"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("openingbalance").focus();
					}
					if(characterCode==38){
						document.getElementById("sex").focus();
					}
				}else if(id=="openingbalance"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("address").focus();
					}
					if(characterCode==38){
						document.getElementById("telephone").focus();
					}
				}else if(id=="address"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("datedisbursed").focus();
					}
					if(characterCode==38){
						document.getElementById("openingbalance").focus();
					}
				}else if(id=="datedisbursed"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("loanamount").focus();
					}
					if(characterCode==38){
						document.getElementById("address").focus();
					}
				}else if(id=="loanamount"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("loaninterest").focus();
					}
					if(characterCode==38){
						document.getElementById("datedisbursed").focus();
					}
				}else if(id=="loaninterest"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("loanstartdate").focus();
					}
					if(characterCode==38){
						document.getElementById("loanamount").focus();
					}
				}else if(id=="loanstartdate"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("loanenddate").focus();
					}
					if(characterCode==38){
						document.getElementById("loaninterest").focus();
					}
				}else if(id=="loanenddate"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("repayoption").focus();
					}
					if(characterCode==38){
						document.getElementById("loanstartdate").focus();
					}
				}else if(id=="repayoption"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("amountperrepay").focus();
					}
					if(characterCode==38){
						document.getElementById("loanenddate").focus();
					}
				}else if(id=="amountperrepay"){
					if(characterCode==13){
						if(readCookie('savetype')=='add'){
							updateCustomer("addRecord", "customers");
						}
						if(readCookie('savetype')=='update'){
							updateCustomer("updateRecord", "customers");
						}
					}
					if(characterCode==39 || characterCode==40){
						//document.getElementById("othernames").focus();
						//updateCustomer("addRecord", "customers");
					}
					if(characterCode==38){
						document.getElementById("repayoption").focus();
					}
				}else if(id=="search"){
					if(characterCode==13){
						searchCustomer2('search','customers2');
					}
				}
			}

            function importRecords(){
				alert("Please ensure that:\n\n\n 1. Only Excel file types must be selected.\n 2. Only Excel files with .xls extension i.e. Excel 97 - Excel 2003 versions are acceptable.\n\n\n\n\nPlease click ok to continue");
				document.getElementById("myupload").style.visibility = "visible";
				document.getElementById("txtFile3").style.visibility = "visible";
				$("#myupload").dialog("open");
            }

			function startUpload3(){
				$("#myupload").dialog("close");
				//document.getElementById("f1_upload_process2").style.visibility = "visible";
                document.getElementById("showRecord").innerHTML = "<b>Upload in Progress!!!</b><br><br>Your file upload is in progress.........";
                $("#showRecord").dialog("open");
					document.getElementById("myupload").submit();
			}

			function stopUpload3(results){
				$("#showRecord").dialog("close");
				$("#showPrompt").dialog("close");
				//document.getElementById("f1_upload_process2").style.visibility = "hidden";
				if(readCookie("filetype")!="excel") return true;
				var resp = readCookie("resp").replace(/%2F/g, "/");
				resp = resp.replace(/_/g, " ");
				resp = resp.replace(/%2C/g, ",");
				if(readCookie("resp").match("blankfile")){
					document.getElementById("showError").innerHTML = "<b>Blank File!!!</b><br><br>You did not select any file.";
					$("#showError").dialog("open");
				}else if(readCookie("resp").match("wrongformat")){
					resp = resp.replace(/_/g, " ");
					var break_resp = resp.split("wrongformat");
					document.getElementById("showError").innerHTML = "<b>Wrong File Format Selected!!!</b><br><br>You have selected a wrong file:  <br><br>[ "+break_resp[1]+" ]<br><br>Only Excel files with .xls extension i.e. Excel 97 - Excel 2003 versions are allowed for upload.";
					$("#showError").dialog("open");
				}else if(readCookie("resp").match("wrongexcel")){
					resp = resp.replace(/_/g, " ");
					var break_resp = resp.split("wrongexcel");
					document.getElementById("showError").innerHTML = "<b>Wrong Excel File Selected!!!</b><br><br>You have selected a wrong excel file:  <br><br>[ "+break_resp[1]+" ]";
					$("#showError").dialog("open");
				}else if(readCookie("resp").match("Excel")){
					document.getElementById("showError").innerHTML = "<b>Excel Value Does Not Match Selected Value!!!</b><br><br>"+resp;
					$("#showError").dialog("open");
				}else if(readCookie("resp").match("invalidmatric")){
					var break_resp = resp.split("invalidmaric");
					document.getElementById("showError").innerHTML = "<b>Alert!!!</b><br><br>Invalid Matric No "+break_resp[1];
					$("#showError").dialog("open");
				}else{
					var response = "<b>Invalid File!!!</b><br><br>Only Excel files with .xls extension i.e. Excel 97 - Excel 2003 versions are allowed for upload.";
					if(results==1 && resp.match("successful") && !resp.match("Not Successful")){
						response = "<b>Upload Successful!!!</b><br><br>Your Customers records upload is successful."; //+readCookie("resp1")+" |  "+readCookie("resp2");
						document.getElementById("showPrompt").innerHTML = response;
						$("#showPrompt").dialog("open");
						getRecords("customers");
					}else if(resp.match("Matric Numbers are already used")){
						response = "<b>Matric Numbers Already Used!!!</b><br><br><br>"+resp+"<br><br>Please open the report below to see the details.";
						document.getElementById("showError").innerHTML = response;
						$("#showError").dialog("open");
					}else{
						document.getElementById("showError").innerHTML = response;
						$("#showError").dialog("open");
					}
				}
			}


        </script>
    </head>
    <body>
		<div id="showError"></div>
		<div id="showAlert"></div>
		<div id="showPrompt"></div>
		<div id="showRecord"></div>
		<form id="myupload" action="uploadcustomers.php?ftype=excel" method="post" enctype="multipart/form-data" target="upload_target3" onsubmit="startUpload3();" style="visibility: hidden" >
			<div id="myform">
				<div id="selectedfile"><b>Please select a file and click Upload button below:</b></div><BR><BR>
				<input type="file" name="txtFile3" id="txtFile3" style="visibility: hidden" /><BR><BR>
				<input type="submit" id="submitButton3" name="submitButton3" value=" Upload " style="display:block; margin-left: 15px; width: 105px; padding: 5px 5px; text-align:center; background:#880000; border-bottom:1px solid #ddd;color:#fff; cursor: pointer; border:1px solid #000033;   height: 27px; width: 82px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;"/>
			</div>
			<iframe id="upload_target3" name="upload_target3" style="width:0;height:0;border:0px solid #fff;"></iframe>
		</form>
		<div id="updatecardno">
			<table width="100%">
				<tr>
					<td align='right'><b>Old&nbsp;Card&nbsp;No:</b></td>
					<td>
						<input type="text" id="oldcardNumber" name="oldcardNumber" size="20" onblur="this.value=capitalize(this.value)" disabled="true" readonly />
					</td>
					<td align='right'><b>New&nbsp;Card&nbsp;No:</b></td>
					<td>
						<input type="text" id="newcardNumber" name="newcardNumber" size="20" onblur="this.value=capitalize(this.value)" />
					</td>
				</tr>
			</table>
		</div>
        <table width="100%">
            <tr>
                <td>
					<div id="customerinfo">
						<div id="f1_upload_process" style="z-index:100; visibility:hidden; position:absolute; text-align:center; width:400px; top:100px; left:400px">Loading...<br/><img src="imageloader.gif" /><br/></div>
						<div>
							<table>
								<tr>
									<td width="40%">
										<div style="border:5px solid #ddd;color:#000000;">
											<table style="font-size:14px;">
												<tr>
													<td align="center" colspan="2" style="color: red; font-size:15px;"><b>Personal&nbsp;Details</b></td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Card&nbsp;No:&nbsp;</b></td>
													<td>
														<input type="text" id="cardno" size="20" onblur="this.value=capitalize(this.value)" onkeydown="checkKeyPressed(event,this.id);" />
														<INPUT type="button" style="display:inline" id="updatecardnumber" value=" Update Card No " onclick="checkUpdCard();" />
													</td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Line&nbsp;No:</b></td>
													<td>
														<input type="text" id="lineno" size="20" onblur="this.value=capitalize(this.value)" />
													</td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Card&nbsp;Serial&nbsp;No:</b></td>
													<td>
														<input type="text" id="cardserial" size="20" onblur="this.value=capitalize(this.value)" />
													</td>
												</tr>
												<tr>
													<td align="right"><b style=" color: red">Last&nbsp;Name:&nbsp;</b></td>
													<td>
														<input type="text" id="lastname" size="20" onblur="this.value=capitalize(this.value)" onkeydown="checkKeyPressed(event,this.id);" />&nbsp;&nbsp;&nbsp;<b>Lock&nbsp;Withdrawal&nbsp;<input type="checkbox" id="lockwitdrawal" onclick="lockWithdrawal(this.id)"></b>
													</td>
												</tr>
												<tr>
													<td align="right" style=" color: red"><b>Other&nbsp;Names:&nbsp;</b></td>
													<td>
														<input type="text" id="othernames" size="50" onblur="this.value=capAdd(this.value)" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Sex:&nbsp;</b></td>
													<td>
														<select id="sex" onkeydown="checkKeyPressed(event,this.id);" >
															<option></option>
															<option>Male</option>
															<option>Female</option>
														</select>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<div style="display:inline; color: red"><b>Opening&nbsp;Balance</div>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<div style="display:inline; color: red"><b>Commission</div>
													</td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Phone&nbsp;No:&nbsp;</b></td>
													<td>
														<input type="text" id="telephone" size="15" onkeydown="checkKeyPressed(event,this.id);" />
														&nbsp;&nbsp;&nbsp;
														<input type="text" style="display:inline; text-align:right;" id="openingbalance" size="15" onkeydown="checkKeyPressed(event,this.id);" onblur="this.value=numberFormat(this.value);" />
														<input type="text" style="display:inline; text-align:right;" id="commission" size="15" onkeydown="checkKeyPressed(event,this.id);" onblur="this.value=numberFormat(this.value);" />
														<input type="hidden" id="recordlock">
													</td>
												</tr>
												<tr>
													<td align="right" style="color: red"><b>Address:</b>&nbsp;<BR><BR><BR><BR><BR></td>
													<td>
														<textarea id="address" name="address" rows="5" cols="60" onblur="this.value=capAdd(this.value).trim()" onkeydown="checkKeyPressed(event,this.id);" ></textarea>
													</td>
												</tr>
											</table>
										</div>
									</td>
									<td width="20%">
										<div style="border:5px solid #ddd;color:#000000;">
											<table>
												<tr>
													<td>
														<div id="f1_uploaded_file" style="margin-left: 15px;"><img src="photo/silhouette.jpg" border="1" width="100" height="80" title="Picture" alt="Applicant's Passport" /><br/></div>

														<div id="f1_upload_button" onclick="javascript:browseFiles()" style="display:block; margin-left: 45px; width: 105px; padding: 5px 5px; text-align:center; background:#880000; border-bottom:1px solid #ddd;color:#fff; cursor: pointer; border:1px solid #000033;   height: 15px; width: 70px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;">Upload</div>
													</td>
												</tr>
											</table>
										</div>
									</td>
									<!--td width="35%">
										<div style="border:5px solid #ddd;color:#000000;">
											<table style="font-size:14px;">
												<tr>
													<td align="center" colspan="2" style="color: red; font-size:15px;"><b>Loan&nbsp;Details</b></td>
												</tr>
												<tr>
													<td align="right"><b>Date&nbsp;Disbursed:&nbsp;</b></td>
													<td>
														<input type="text" id="datedisbursed" name="datedisbursed" size="11" onclick="this.value='';  getDate('datedisbursed')" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right"><b>Loan Amount:&nbsp;</b></td>
													<td>
														<input type="text" id="loanamount" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value);" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right"><b>Loan&nbsp;Interest:&nbsp;</b></td>
													<td>
														<input type="text" id="loaninterest" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value);" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right"><b>Loan&nbsp;Start&nbsp;Date:&nbsp;</b></td>
													<td>
														<input type="text" id="loanstartdate" name="loanstartdate" size="11" onclick="this.value=''; getDate('loanstartdate')" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right"><b>Loan&nbsp;Date:&nbsp;</b></td>
													<td>
														<input type="text" id="loanenddate" name="loanenddate" size="11" onclick="this.value=''; getDate('loanenddate')" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
												<tr>
													<td align="right"><b>Repay&nbsp;Option:&nbsp;</b></td>
													<td>
														<select id="repayoption" onkeydown="checkKeyPressed(event,this.id);" >
															<option></option>
															<option>Daily</option>
															<option>Weekly</option>
															<option>Monthly</option>
														</select>
													</td>
												</tr>
												<tr>
													<td align="right"><b>Amount&nbsp;Per&nbsp;Repay:&nbsp;</b></td>
													<td>
														<input type="text" id="amountperrepay" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value);" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
													</td>
												</tr>
											</table>
										</div>
									</td-->
									<input type="hidden" id="datedisbursed" />
									<input type="hidden" id="loanamount" />
									<input type="hidden" id="loaninterest" />
									<input type="hidden" id="loanstartdate" />
									<input type="hidden" id="loanenddate" />
									<input type="hidden" id="repayoption" />
									<input type="hidden" id="amountperrepay" />
									<td width="5%">
										<form action="uploadfile.php?ftype=pic" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
										
											<div id="f1_upload_form" style="font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #666666; height:100px;" align="center"><br/>
												
												<div style="visibility: hidden">
													<input type="file" name="txtFile" id="txtFile" onchange="javascript:submitForm();" />
													<INPUT TYPE="submit" id="submitButton" value="Submit" name="submitButton" />
												</div>
												<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;"></iframe>
											</div>
										</form>
									</td>
								</tr>
							</table>
						</div>
						<!--div id="customerlist" background-color: #006699; border: 1px solid #006699; -->
						<div style="border:5px solid #ddd;color:#000000;">
							<div id="searchdiv">
								<b>Search:&nbsp;</b>
								<input type="text" style="display:inline" id="search" name="search" size="30" onkeydown="checkKeyPressed(event,this.id);" />
								<input type="button" style="display:inline" value=" Search " onclick="searchCustomer2('search','customers2')" />&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" style="display:inline" value=" List All " onclick="listAll(0)" />&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" style="display:inline" id="import" onclick="importRecords();" value=" Import Records From Excel " />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Line No:&nbsp;
								 <input type="text" id="linenoid" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'lineno', 'recordlist');" 
								 onclick="this.value = ''; getRecordlist(this.id, 'lineno', 'recordlist');" />&nbsp;&nbsp;
								<input type="button" style="display:inline" value=" List Customers " id="listtrans" onclick="searchCustomer2(document.getElementById('linenoid').id,'customers5');" />
								<!--input type="button" style="display:inline" id="import" onclick="exportExcel();" value=" Export Records To Excel " /  onkeydown="checkKeyPressed(event, this.id);"  onkeydown="checkKeyPressed(event, this.id);" --><BR>
							</div>
							<div id='recordlist'></div>
							<div id="listcustomers" style="overflow:auto; height:150px; max-width:1400px; margin-left: 5px;"></div>
						</div>
						<div id="mypic"></div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script>
	createCookie("transtypes", "customers", false);
	createCookie("sexvalue", "", false);
	createCookie("names", null, false);
	//getRecords("customers");
	loadImage("silhouette.jpg");
	function listAll(arg){
		createCookie("sexvalue", "", false);
		document.getElementById("search").value="";
		resetForm("customers");
		loadImage("silhouette.jpg");
		getRecords("customers");
	}
	document.getElementById("cardno").focus();
	createCookie("savetype", "add", false);

</script>
