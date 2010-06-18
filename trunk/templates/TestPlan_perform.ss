<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% include Favicon %>
	<% base_tag %>
	<% control TestPlan %>
	<title>Perform a Test: '$Title' (Test Plan)</title>
	<% end_control %>
</head>
<!-- HTML-BODY -->
<body class="typography">

<% include LeftPanel %>

<div class="rightPanel">
	<% if TestPlan %>
	
	<% control TestPlan %>
	<h1 class="pageTitle">$Title Test Plan</h1>
	<% if Content %>
		<div class="box">
			$Content
		</div>
	<% end_if %>
	<% include TestSessionForm %>
	<% end_control %>
	
	<% else %>
	  Error: Invalid test parameter. <br/>
	<% end_if %>
</div>

</body>
</html>