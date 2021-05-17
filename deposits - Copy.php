<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
-->

<!--@page contentType="text/html" pageEncoding="UTF-8"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
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
        <script type='text/javascript' src='js/setup.js'></script>
        <script type='text/javascript' src='js/toword.js'></script>
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
                $(document).ready(function(){
                    $("#infolist").accordion({
                        autoHeight: false
                    });
                });

                $("#dialog").dialog("destroy");

                $("#transactioninfo").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Deposit Transactions Details - Items with RED colour labels are mandatory',
                    height: 650,
                    width: 1300,
                    modal: false,
                    buttons: {
                        Save: function() {
                            updateTransaction("addRecord", "transactions","deposit","contribution");
                        },
                        Update: function() {
                            updateTransaction("updateRecord", "transactions","deposit","contribution");
                        },
                        New: function() {
							//document.getElementById('listdeposits').innerHTML = "";
							createCookie('savetype', 'add', false);
                            resetForm("deposits");
							getRecordlist("cardno",  'customers','recordlist');
							document.getElementById("cardno").focus();
                        },
                        Close: function() {
                            $('#transactioninfo').dialog('close');
							window.location="home.php?pgid=0";
                        }
                    }
                });

                $("#transactionlist").dialog({
                    autoOpen: true,
                    position:'center',
                    title: 'Deposit Transactions List',
                    height: 500,
                    width: 1100,
                    modal: false,
                    buttons: {
                        Post: function() {
                            postTransaction();
                        },
                        Close: function() {
                            $('#transactionlist').dialog('close');
                            $('#transactioninfo').dialog('open');
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 600,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showAlert').dialog('close');
							$('#showError').dialog('close');
                        }
                    }
                });

                $("#showAlert").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 280,
                    width: 350,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Error Message',
                    height: 300,
                    width: 500,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showRecord').dialog('close');
							$('#showAlert').dialog('close');
							$('#showError').dialog('close');
                        }
                    }
                });

            });

			function loadImage(imageID){
				document.getElementById('f1_uploaded_file').innerHTML = "<img src='photo/"+imageID+"'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
			}

			function listAll(){
				createCookie('sexvalue', '', false);
				resetForm("deposits");
				loadImage("silhouette.jpg");
				getRecords("transactions");
			}

			function checkKeyPressed(e, id){ //e is event object passed from function invocation
				var characterCode; //literal character code will be stored in this variable

				if(e && e.which){ //if which property of event object is supported (NN4)
					e = e;
					characterCode = e.which //character code is contained in NN4's which property
				} else {
					e = event;
					characterCode = e.keyCode; //character code is contained in IE's keyCode property
				}
				
				/*if(characterCode==121){
					if(id=="transdate2"){
						document.getElementById("transdate2").value="";
						getDate('transdate2');
					}
				}*/

				if(characterCode==37){ // if arrow up
alert('37');
				}
				if(characterCode==38){ // if arrow up
alert('38');
				}
				if(characterCode==39){ // if arrow down
alert('39');
				}
				if(characterCode==40){ // if arrow down
alert('40');
				}
				if(characterCode==13){ // if enter
alert('13');
				}

		/*cardnoid amountid
		updateid="updateid"+k;
		deleteid="deleteid"+k;
		saveid="saveid"+k;
		clearid="clearid"+k;*/

				/*if(id=="cardno"){
					populateCode(document.getElementById("cardno").value);
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("transdate").focus();
					}
					if(characterCode==37 || characterCode==38){
					//	document.getElementById("cardno").focus();
					}
				}else if(id=="transdate"){
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("amount").focus();
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("cardno").focus();
					}
					if(characterCode==121){
						document.getElementById("transdate").value="";
						getDate('transdate');
					}
				}else if(id=="amount"){
					if(characterCode==13 || characterCode==39 || characterCode==40){
						if(document.getElementById("commission").disabled==false){
							document.getElementById("commission").focus();
						}else{
							document.getElementById("narration").focus();
						}
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("transdate").focus();
					}
				}else if(id=="commission"){
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("narration").focus();
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("amount").focus();
					}
				}else if(id=="narration"){
					if(characterCode==13){
						if(readCookie('savetype')=='add'){
							updateTransaction("addRecord", "transactions","deposit","contribution");
						}
						if(readCookie('savetype')=='update'){
							updateTransaction("updateRecord", "transactions","deposit","contribution");
						}
					}
					if(characterCode==39 || characterCode==40){
						//document.getElementById("othernames").focus();
						//updateCustomer("addRecord", "customers");
					}
					if(characterCode==37 || characterCode==38){
						if(document.getElementById("commission").disabled==false){
							document.getElementById("commission").focus();
						}else{
							document.getElementById("amount").focus();
						}
					}
				}*/
			}
			createCookie('currentform', 'deposit', false);
        </script>
    </head>
    <body>
		<div id="showError"></div>
		<div id="showAlert"></div>
		<div id="showPrompt"></div>
		<div id="showRecord"></div>
		<div id="transactionlist">
			<div style='border:5px solid #ddd;color:#000000;'>
				<table style='font-size:14px;'>
					<tr>
						<td align='right' style="color: red" width='10%'><b>Line&nbsp;No:&nbsp;</b></td>
						<td width='20%'>
							<input type="text" id="lineno" style="display:inline" size="10"  onkeydown="checkKeyPressed(event,this.id);" />
						</td>
						<td align='right' style="color: red" width='10%'><b>Transaction&nbsp;Date:&nbsp;</b></td>
						<td width='20%'>
							<input type='text' id='transdate2' name='transdate2' size='11' onclick="this.value=''; clearLists('recordlist2'); getDate('transdate2')" style="display:inline; " onkeydown="checkKeyPressed(event,this.id);" />
						</td>
						<td align='right' style="color: red" width='10%'><b>User&nbsp;Name:&nbsp;</b></td>
						<td width='30%'>
							<input type="text" id="username" style="display:inline" size="10" onkeyup="getRecordlist(this.id, 'users', 'recordlist2'); " onclick="this.value=''; getRecordlist(this.id, 'users', 'recordlist2');" onkeydown="checkKeyPressed(event,this.id);" />
							&nbsp;&nbsp;
							<input type="button" style="display:inline" value=" List Transactions " onclick="populateTransaction()" />
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<div id="translist" style="width:1000px; height:290px; overflow:auto;"></div>
						</td>
					</tr>
				</table>
			</div>
			<div id='recordlist2' style="overflow:auto;"></div>
			<div id="mypic2"></div>
		</div>
        <!--table width="100%">
            <tr>
                <td>
					<div id="transactioninfo">
						<div style='border:5px solid #ddd;color:#000000;'>
							<table style='font-size:14px;'>
								<tr>
									<td align='right' style="color: red"><b>Card No:&nbsp;</b></td>
									<td>
										<input type="text" id="cardno" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'customers', 'recordlist'); " onclick="this.value=''; document.getElementById('names').value=''; loadImage('silhouette.jpg'); getRecordlist(this.id, 'customers','recordlist');" onblur="this.value=capitalize(this.value); " onkeydown="checkKeyPressed(event,this.id);" />
										<input style="display:inline" type='text' id='names' disabled="true" size='45' />&nbsp;&nbsp;
									</td>
									<td rowspan="9">
										<div id="f1_uploaded_file"><img src="photo/silhouette.jpg" border="1" width="100" height="80" title="Picture" alt="Applicant's Passport"/><br/></div>
									</td>
								</tr>
								<tr>
									<td align='right'><b style=" color: red">Deposit Date:&nbsp;</b></td>
									<td>
										<input type='text' id='transdate' name='transdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('transdate')" style="display:inline; " onkeydown="checkKeyPressed(event,this.id);" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="display:inline; color: red">Commission</b>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style="color: red"><b>Deposit Amount:&nbsp;</b></td>
									<td>
										<input type="text" id="amount" size="15" style="display:inline; text-align:right;" onclick="this.value=''; clearLists('recordlist');" onblur="this.value=numberFormat(this.value); getNarration();" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
										<input type="text" id="commission" disabled="true" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value); getDescription();" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style=" color: red"><b>Narations:&nbsp;</b><BR><BR><BR><BR><BR></td>
									<td>
										<textarea id="narration" rows="5" cols="67" onfocus="this.selected()" onblur="this.value=capAdd(this.value)" onkeydown="checkKeyPressed(event,this.id);" onclick="this.value=''; clearLists('recordlist');" ></textarea>
									</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div><BR>
						<div id='recordlist' style="overflow:auto;"></div>
						<INPUT TYPE="hidden" id="description">
						<INPUT TYPE="hidden" id="transtype">
						<INPUT TYPE="hidden" id="fromdate">
						<INPUT TYPE="hidden" id="todate">
						<input type="hidden" id="lockwitdrawal">
						<div style='border:5px solid #ddd;color:#000000;'>
							<div id="searchdiv">
								<b>Transaction Date:&nbsp;</b><input type='text' id='today' name='today' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('today')" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" style="display:inline" value=" Post Deposits " onclick="showTransForm()" />
								<!--b>Search:&nbsp;</b>
								<input type="text" style="display:inline" id="search" name="search" size="30" />
								<input type="button" style="display:inline" value=" Search " onclick="searchCustomer('search')" />&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" style="display:inline" value=" List All " onclick="listAll()" /><BR-->
							<!--/div>
							<div id="listdeposits" style="overflow:auto; height: 270px"></div>
						</div>
						<div id="mypic"></div>
						</div>
					</div>
                </td>
            </tr>
        </table-->
    </body>
</html>
<script>
	//showTransForm();
	function showTransForm(){
		$('#transactioninfo').dialog('close');
		$('#transactionlist').dialog('open');
		var currentuser = readCookie('currentuser');
		document.getElementById('username').value = currentuser;
		if(currentuser=="Admin"){
			document.getElementById('username').disabled = false;
		}
	}
	document.getElementById('lineno').focus();

	/*createCookie('transtypes', 'deposits', false);
	loadImage("silhouette.jpg");
	
	function DaysInMonth(iMonth, iYear) { 
		return 32 - new Date(iYear, iMonth, 32).getDate();
	}

	var d = new Date();
	var mon = (d.getMonth()+1)+"";
	mon = ((mon.length < 2) ? "0" : "") + mon;

	var lastdate = DaysInMonth(d.getMonth(),d.getFullYear());

	var fromdate = "01/"+mon+"/"+d.getFullYear();
	var todate = lastdate+"/"+mon+"/"+d.getFullYear();

	document.getElementById("fromdate").value=fromdate;
	document.getElementById("todate").value=todate;

	createCookie('date1', d.getFullYear()+"-"+mon+"-01", false);
	createCookie('date2', d.getFullYear()+"-"+mon+"-"+lastdate, false);

	var day = d.getDate()+"";
	var today = ((day.trim().length < 2) ? "0" : "") + d.getDate()+"/"+mon+"/"+d.getFullYear();
	document.getElementById("transdate").value=today;
	document.getElementById("today").value=today;
	createCookie('today', d.getFullYear()+"-"+mon+"-"+d.getDate(), false);

	getRecords("deposits");
	createCookie('savetype', 'add', false);
	//getRecordlist("cardno",  'customers','recordlist');
	//document.getElementById("cardno").focus();*/
</script>
