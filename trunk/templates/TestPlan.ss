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

	<h1>Please choose an test below</h1>
	<% control MyTests %>
		<div><h2>$Title</h2> <a href="testplan/perform/$ID">[perform test]</a> | <a href="testplan/report/$ID">[create test report]</a></div>
		<ul>
		<% control Children %>
			<li>$Title <a href="feature/perform/$ID">[perform test]</a> | <a href="feature/report/$ID">[create test report]</a></li>
		<% end_control %>
		</ul>
	<% end_control %>


</div>

</body>
</html>