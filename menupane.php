<link rel="stylesheet" href="css/menu.css" />
	
<script type="text/javascript" src="js/chili-1.7.pack.js"></script>
<script type="text/javascript" src="js/jquery.easing.js"></script>
<script type="text/javascript" src="js/jquery.dimensions.js"></script>
<script type="text/javascript" src="js/jquery.accordion.js"></script>
<script type='text/javascript' src='js/setup.js'></script>
<script type="text/javascript" src="js/utilities.js"></script>
<script type="text/javascript">
	jQuery().ready(function(){
  		jQuery('#navigation').accordion({ 
		    active: false, 
			header: '.head', 
			navigation: true, 
			event: 'click', 
			fillSpace: true, 
			animated: 'bounceslide' 
		});
	});
	
</script>

<ul id="navigation">
	<li>
		<a class="head" href="#">&nbsp;&nbsp;Setup</a>
		<ul>
			<li><a href="javascript: checkAccess('customersetup.php', 'Customers Setup');">&nbsp;&nbsp;&nbsp;&nbsp;- Customers&nbsp;Setup</a></li>
			<li><a href="#">&nbsp;</a></li>
		</ul> 
	</li>
	<li>
		<a class="head" href="#">&nbsp;&nbsp;Transactions Posting</a>
		<ul>
			<li><a href="javascript: checkAccess('deposits.php', 'Deposits Posting');">&nbsp;&nbsp;&nbsp;&nbsp;- Deposits&nbsp;Posting</a></li>
			<li><a href="javascript: checkAccess('commissions.php', 'Commissions Posting');">&nbsp;&nbsp;&nbsp;&nbsp;- Commission&nbsp;Posting</a></li>
			<li><a href="javascript: checkAccess('withdrawals.php', 'Withdrawals Posting');">&nbsp;&nbsp;&nbsp;&nbsp;- Withdrawal&nbsp;Posting</a></li>
			<!--li><a href="javascript: checkAccess('loans.php', 'Loans Posting');">&nbsp;&nbsp;&nbsp;&nbsp;- Loans&nbsp;Posting</a></li-->
			<li><a href="javascript: checkAccess('archive.php', 'Archive Records');">&nbsp;&nbsp;&nbsp;&nbsp;- Archive Records</a></li>
			<li><a href="javascript: checkAccess('lockrecords.php', 'Lock Records');">&nbsp;&nbsp;&nbsp;&nbsp;- Lock Records</a></li>
			<li><a href="#">&nbsp;</a></li>
		</ul> 
	</li>
	<li>
		<a class="head" href="#">&nbsp;&nbsp;Users Management</a>
		<ul>
			<li>
				<a href="javascript: checkAccess('manageusers.php', 'Manage Users');">&nbsp;&nbsp;&nbsp;&nbsp;- Manage Users</a>
			</li>
			<li>
				<a href="javascript: checkAccess('accesscontrol.php', 'Users Access Control');">&nbsp;&nbsp;&nbsp;&nbsp;- Users Access Control</a>
			</li>
			<li>
				<a href="javascript: checkAccess('changepassword.php', 'Change Users Password');">&nbsp;&nbsp;&nbsp;&nbsp;- Change Users Password</a>
			</li>
			<li>
				<a href="#">&nbsp;</a>
			</li>
		</ul> 
	</li>
	<li>
		<a class="head" href="#">&nbsp;&nbsp;Reports</a>
		<ul>
			<li><a href="javascript: checkAccess('balances.php', 'Customer Balances');">&nbsp;&nbsp;&nbsp;&nbsp;- Customer&nbsp;Balances</a></li>
			<li><a href="javascript: checkAccess('statements.php', 'Customers Statement');">&nbsp;&nbsp;&nbsp;&nbsp;- Customers&nbsp;Statement</a></li>
			<li><a href="javascript: checkAccess('translisting.php', 'Transactions Listing');">&nbsp;&nbsp;&nbsp;&nbsp;- Transactions&nbsp;Listing</a></li>
			<li><a href="javascript: checkAccess('dailysummary.php', 'Summary by Line No');">&nbsp;&nbsp;&nbsp;&nbsp;- Summary&nbsp;by&nbsp;Line&nbsp;No</a></li>
			<li><a href="javascript: checkAccess('sendsms.php', 'Send SMS');">&nbsp;&nbsp;&nbsp;&nbsp;- Send&nbsp;SMS</a></li>
			<li><a href="javascript: checkAccess('msgsms.php', 'Message SMS');">&nbsp;&nbsp;&nbsp;&nbsp;- SMS&nbsp;Message</a></li>
			<!--li><a href="javascript: checkAccess('loanslisting.php', 'Loans Report');">&nbsp;&nbsp;&nbsp;&nbsp;- Loans&nbsp;Report</a></li-->
			<li><a href="#">&nbsp;</a></li>
		</ul>
	</li>
	<li>
		<a class="head" href="#">&nbsp;&nbsp;Logout</a>
		<ul>
			<li><a href="javascript:logoutUser()" title="Logout">&nbsp;&nbsp;&nbsp;&nbsp;- Logout</a></li>
			<li><a href="#">&nbsp;</a></li>
		</ul> 
	</li>
</ul>
