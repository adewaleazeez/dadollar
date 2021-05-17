<?php
include "config.php";
// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: login.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: login.php');
}
?>
<!doctype html>
<html>
    <head>
		<title>SQLIA-API</title>
		<link rel="stylesheet" href="style.css" />
	</head>
    <body>
		<div class="container">
			<form method="post" action="api.php">
				<div id="div_login">
					<h1>Query testing page</h1>
					<div>
						<textarea type="text" class="textbox" id="txt_query" name="txt_query" rows="5" 
						placeholder="Type sample query string here......" 
						onfocus="document.getElementById('msg_div').innerHTML=''" 
						onblur="createCookie('retval',this.value)"></textarea>
					</div>
					<div>
						<input type="submit" value="Submit" name="but_submit" id="but_submit" />
						<input type="button" value="Logout" name="but_logout" onclick="window.location.assign('login.php?arg=1')">
					</div>
					<div id="msg_div"></div>
				</div>
			</form>
		</div>
    </body>
</html>
<script>
var retval=window.location.href;
retval = retval.replace(/%20/g,' ').replace(/%/g,'%25').replace(/\%22/g,'"');
retval = retval.split("~_~");
if(retval[1] !== undefined){
	document.getElementById("msg_div").innerHTML = decodeURIComponent(retval[1]);
}
if(getCookie('retval')){
	document.getElementById("txt_query").innerHTML = getCookie('retval'); //decodeURIComponent(retval[2]);
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0) == ' ') {
	  c = c.substring(1);
	}
	if (c.indexOf(name) == 0) {
	  return c.substring(name.length, c.length);
	}
  }
  return "";
}

function createCookie(cookieName,cookieValue,daysToExpire){
	var date = new Date();
	date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
	document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
}
</script>
