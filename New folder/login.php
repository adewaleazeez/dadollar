<!DOCTYPE html>
<html>
	<head>
		<title>SQLIA-API</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="container">
			<form method="post" action="backend.php">
				<div id="div_login">
					<h1>Login</h1>
					<div>
						<input type="text" class="textbox" id="txt_uname" name="txt_uname" required placeholder="Username" 
						onfocus="document.getElementById('msg_div').innerHTML=''" 
						onblur="createCookie('retval1',this.value)" />
					</div>
					<div>
						<input type="password" class="textbox" id="txt_uname" name="txt_pwd" required placeholder="Password" onfocus="document.getElementById('msg_div').innerHTML=''"/>
					</div>
					<div>
						<input type="submit" value="Submit" name="but_submit" id="but_submit" />
					</div>
					<div id="msg_div"></div
				</div>
			</form>
		</div>
	</body>
</html>
<script>

	var retval=window.location.href;
	retval = retval.replace(/%20/g,' ').replace(/%/g,'%25');
	retval = retval.split("~_~");
	if(retval[1] !== undefined){
		document.getElementById("msg_div").innerHTML = decodeURIComponent(retval[1]);
	}
	if(getCookie('retval1')){
		document.getElementById("txt_uname").value = getCookie('retval1'); //decodeURIComponent(retval[2]);
	}

	if(retval[1] === undefined){
		var return_val = getCookie("ret_val");
		if(return_val !== "null"){
			document.getElementById("msg_div").innerHTML = return_val.replace(/[^a-zA-Z0-9]/g,' ');
			var arg = window.location.href;
			arg = arg.split("=");
			if(arg[1] == "1"){
				document.getElementById("txt_uname").focus();
			}
		}
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
