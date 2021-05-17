<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
--> 
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
        <script type='text/javascript' src='js/setup.js'></script>
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

                $("#archive").dialog({
                    autoOpen: true,
                    position:[280,70],
                    title: 'Archive Records',
                    height: 420,
                    width: 910,
                    modal: false,
                    buttons: {
                        Archive: function() {
							var archivedate = document.getElementById('startdate').value;
							if(archivedate===""){
								var error = "<br><br><br><b>Archive Date must not beblank......</b>";
								document.getElementById("showError").innerHTML = error;
								$('#showError').dialog('open');
								return true;
							}
							
							archivedate = archivedate.substr(6, 4) + '-' + archivedate.substr(3, 2) + '-' + archivedate.substr(0, 2);
							var url = "/dadollar/userbackend.php?option=archive&archivedate="+archivedate;
							
							var msg = "<br><br><br><b>Please wait............<br><br>Your records are being archived</b>";
							document.getElementById("showAlerts").innerHTML = msg;
							$('#showAlerts').dialog('open');
							
							AjaxFunctionSetup(url);
                        },
						Close: function() {
                            $('#archive').dialog('close');
							window.location="home.php?pgid=0";
                        }
                    }
                });

				$("#showPrompt").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
                        }
                    }
                });

				$("#showAlerts").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 200,
                    width: 400,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Error Message',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showError').dialog('close');
                        }
                    }
                });

                $("#showRecord").dialog({
                    autoOpen: false,
                    position:[1100,70],
                    title: 'Alert!!!',
                    height: 500,
                    width: 400,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showRecord').dialog('close');
							$('#showAlerts').dialog('close');
							$('#showError').dialog('close');
                        }
                    }
                });

            });

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

				var old_id=id;
				id = id.split('id');
				var idcode = id[0]+'id';
				var idno = parseInt(id[1]);
				id = idcode.trim()+idno;

				if(old_id.substr(0,6)=="cardno" && characterCode==13) {
					if(document.getElementById(old_id).value!=null && document.getElementById(old_id).value!=""){
						populateCardno(document.getElementById(old_id));
						var id2 = old_id.split('id');
						if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
							setTimeout(function () {
								populateCardno(document.getElementById(old_id));
							}, 1000);
						}
						if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
							setTimeout(function () {
								populateCardno(document.getElementById(old_id));
							}, 1500);
						}
						if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
							setTimeout(function () {
								if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
									alert("Card No: "+document.getElementById(old_id).value+" does not exist!!! ");
									document.getElementById("nameid"+id2[1]).value="";
									document.getElementById(old_id).value="";
									document.getElementById(old_id).focus();
									return true;
								}
							}, 2000);
						}
						document.getElementById("startdate").focus(); 
						clearLists('recordlist');
						//populateCardno(document.getElementById(old_id));
						//setTimeout(function () { document.getElementById("startdate").focus(); clearLists('recordlist');}, 1000);
					}else{
						document.getElementById(old_id).focus();
						return true;
					}
				}
				
			}
			createCookie('currentform', 'statement', false);

        </script>
    </head>
    <body>
        <table width="100%">
            <div id="showError"></div>
            <div id="showAlerts"></div>
            <div id="showPrompt"></div>
            <div id="showRecord"></div>
            <tr>
                <td>
					<div id="archive">
						<table width='100%' style='font-size:14px;'>
							
							<tr>
								<td align='right'><b>Archive&nbsp;Date:&nbsp;</b></td>
								<td>
									<input type='text' id='startdate' name='startdate' size='11' onclick="this.value='';  getDate('startdate')" />
								</td>
							</tr>
							
						</table>
						<div id='recordlist'></div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script>
	//document.getElementById("cardnoid1").focus();
	setTimeout(function () { document.getElementById("cardnoid1").focus(); }, 500);
</script>
