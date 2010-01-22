<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% base_tag %>
	<title>Perform a Test</title>
	<link rel="stylesheet" type="text/css" href="regress/css/TestPlan.css" />
</head>
<!-- HTML-BODY -->
<body>
	
<div class="leftPanel">
	<h2>Options</h2>
	<div class="actions">
		<input type="button" name='action_doSaveSession' value="Save current session" />		
	</div>	
	<div id="statusmessage">
		
	</div>
</div>

<div class="rightPanel">
	<h1>Perform a Test on a Feature/Story Card</h1>
	<% control TestSection %>

	<% include PerformTestForm %>

	<% end_control %>
</div>
</body>
</html>