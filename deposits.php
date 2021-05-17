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

                $("#transactionlist").dialog({
                    autoOpen: true,
                    position:'center',
                    title: 'Deposit Transactions List',
                    height: 500,
                    width: 1200,
                    modal: false,
                    buttons: {
                        Post: function() {
                            postTransaction();
                        },
                        /*Delete: function() {
							if(confirm("Sure to delete ?")){
								deleteTransaction();
							}
                        },*/
                        Close: function() {
                            $('#transactionlist').dialog('close');
							window.location="home.php?pgid=0";
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
							$('#showAlert').dialog('close');
							$('#showError').dialog('close');
                        }
                    }
                });

            });

			function loadImage(imageID){
				document.getElementById('f1_uploaded_file').innerHTML = "<img src='photo/"+imageID+"'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
			}

			function checkKeyPressed(e, id){ //e is event object passed from function invocation
				clearLists('recordlist');
				var characterCode; //literal character code will be stored in this variable

				if(e && e.which){ //if which property of event object is supported (NN4)
					e = e;
					characterCode = e.which //character code is contained in NN4's which property
				} else {
					e = event;
					characterCode = e.keyCode; //character code is contained in IE's keyCode property
				}
				//if(characterCode==8 || characterCode==27){ // if backspace or Esc
				if(characterCode==27){ // if backspace or Esc
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				/*if(characterCode==121){
					if(id=="transdate"){
						document.getElementById("transdate").value="";
						getDate('transdate');
					}
				}*/

				if(id=="lineno"){
					if(characterCode==37 || characterCode==38){
						document.getElementById('lineno').focus();
					}
					if(characterCode==39 || characterCode==40 || characterCode==13){
						document.getElementById('transdate').focus();
					}					
				}

				if(id=="transdate"){
					if(characterCode==37 || characterCode==38){
						document.getElementById('lineno').focus();
					}
					if(characterCode==39 || characterCode==40 || characterCode==13){
						document.getElementById('username').focus();
					}					
				}

				if(id=="username"){
					if(characterCode==37 || characterCode==38){
						document.getElementById('transdate').focus();
					}
					if(characterCode==39 || characterCode==40 || characterCode==13){
						document.getElementById('listtrans').focus();
					}					
				}

				if(id=="listtrans"){
					if(characterCode==37 || characterCode==38){
						document.getElementById('lineno').focus();
					}
					if(characterCode==39 || characterCode==40){
						document.getElementById('lineno').focus();
					}					
					if(characterCode==13 || characterCode==9){ // if enter ot tab
						populateTransaction()
					}					
				}

				var old_id=id;
				id = id.split('id');
				var idcode = id[0]+'id';

				if((idcode=="saveid" || idcode=="updateid" || idcode=="clearid" || idcode=="deleteid") && characterCode==8){ // if backspace
					e.preventDefault();
					e.stopPropagation();
					return false;
				}

				var idno = parseInt(id[1]);
				if(characterCode==38){ // if arrow up
					if(idno > 1) --idno;
				}
				if(characterCode==40){ // if arrow down
					if(idno < globalK) ++idno;
				}
				if(characterCode==13 || characterCode==9){ // if enter ot tab
					if(idcode=="cardnoid"){ 
						idcode = "amountid";
					}else if(idcode=="amountid"){
						if(document.getElementById(old_id).value.trim().length==0){
							document.getElementById(old_id).focus();
							return true;
						}
						if(isNaN(document.getElementById(old_id).value.replace(/,/g, '')+"")){
							alert('The value you typed for Amount in not a number!!!');
							document.getElementById(old_id).value='';
							document.getElementById(old_id).focus();
							return true;
						}
						if(globalK==idno){
							idcode = "saveid";
						}else{
							idcode = "updateid";
						}
					}else if(idcode=="updateid"){
						document.getElementById(id).click();
					}else if(idcode=="deleteid"){
						document.getElementById(id).click();
					}else if(idcode=="saveid"){
						document.getElementById(id).click();
					}else if(idcode=="clearid"){
						document.getElementById(id).click();
					}
				}
				id = idcode.trim()+idno;
				if(old_id.substr(0,6)=="cardno" && characterCode==13) {
					if(document.getElementById(old_id).value!=null && document.getElementById(old_id).value!=""){
						if(!document.getElementById(old_id).value.trim().match(document.getElementById("lineno").value.trim()) && document.getElementById("lineno").value.trim()!='100'){
							alert("Card No: "+document.getElementById(old_id).value+" does not belong to Line: "+document.getElementById("lineno").value);
							document.getElementById(old_id).value="";
							document.getElementById(old_id).focus();
							return true;
						}
						populateCardno(document.getElementById(old_id));
						var id2 = old_id.split('id');
						setTimeout(function () {
							if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
								populateCardno(document.getElementById(old_id));
							}
						}, 1000);
						
						setTimeout(function () {
							if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
								populateCardno(document.getElementById(old_id));
							}
						}, 1500);
						
						if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
							setTimeout(function () {
								if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
									alert("Click ok to continue......");
									//alert("Card No: "+document.getElementById(old_id).value+" does not exist!!! ");
									//document.getElementById("amountid"+id2[1]).value="";
//									document.getElementById("balance_aid"+id2[1]).value="";
									//document.getElementById(old_id).value="";
									//document.getElementById(old_id).focus();
									//return true;
								}
							}, 2000);
						}else{
							clearLists('recordlist');
						}
					}else{
						document.getElementById(old_id).focus();
						clearLists('recordlist');
						return true;
					}
					clearLists('recordlist');
				}
				clearLists('recordlist');
				document.getElementById(id).focus();
				//setTimeout(function () { document.getElementById(id).focus(); }, 1000);
			}
			createCookie('currentform', 'deposit', false);
        </script>
    </head>
    <body>
		<div id="showError"></div>
		<div id="showAlert"></div>
		<div id="showPrompt"></div>
		<div id="transactionlist">
			<div style='border:5px solid #ddd;color:#000000;'>
				<table style='font-size:14px;'>
					<tr>
						<td align='right' style="color: red"><b>Line&nbsp;No:&nbsp;</b></td>
						<td>
							<input type="text" id="lineno" size="10" onclick="this.value=''; clearLists('recordlist');" onkeydown="checkKeyPressed(event,this.id);" />
						</td>
						<td align='right' style="color: red"><b>Transaction&nbsp;Date:&nbsp;</b></td>
						<td>
							<input type='text' id='transdate' name='transdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('transdate')" style="display:inline; " onkeydown="checkKeyPressed(event,this.id);" />
						</td>
						<td align='right' style="color: red"><b>User&nbsp;Name:&nbsp;</b></td>
						<td>
							<input type="text" id="username" disabled style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'users', 'recordlist'); " onclick="this.value=''; getRecordlist(this.id, 'users', 'recordlist');" onkeydown="checkKeyPressed(event,this.id);" />
							&nbsp;&nbsp;
							<input type="button" style="display:inline" value=" List Transactions " id="listtrans" onclick="populateTransaction();" onkeydown="checkKeyPressed(event,this.id);" />
						</td>
					</tr>
						<div id='opentrans'></div>
					<tr>
						<td colspan="6">
							<div id="translist" style="width:1100px; height:310px; overflow:auto;"></div>
						</td>
					</tr>
				</table>
			</div>
			<div id='recordlist' style="overflow:auto;"></div>
			<div id="mypic"></div>
			<div id="transtotal">
				<table style='font-size:14px;'>
					<tr>
						<td width='5.2%'>&nbsp;</td>
						<td width='10%'>
							<b>Total Deposit:</b>&nbsp;<input type="text" id="totalamount" size="10" disabled style='text-align:right;' />
						</td>
					</tr>
				</table>
			</div>
		</div>
    </body>
</html>
<script type="text/javascript">
	var d = new Date();

	var day = d.getDate()+"";
	day = ((day.length < 2) ? "0" : "") + day;
	var mon = (d.getMonth()+1)+"";

	mon = ((mon.length < 2) ? "0" : "") + mon;
	
	var today = day+"/"+mon+"/"+d.getFullYear();
	
	document.getElementById("transdate").value=today;
	
	showTransForm();
	function showTransForm(){
		var currentuser = readCookie('currentuser');
		document.getElementById('username').value = currentuser;
		if(currentuser=="Admin"){
			document.getElementById('username').disabled = false;
		}
		
	}
	setTimeout(function () { document.getElementById("lineno").focus(); }, 1000);
</script>