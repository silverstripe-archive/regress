<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% base_tag %>
	<% control TestSection %>
	<title>Perform a Test: '<% if GetTestPlan %>$GetTestPlan.Title - <% end_if %> $Title' (feature test)</title>
	<% end_control %>
	<link rel="stylesheet" type="text/css" href="regress/css/TestPlan.css" />
</head>
<!-- HTML-BODY -->
<body>
	
<div class="leftPanel">
	<div class="actions">
		<input type="button" name='action_doSaveSession' value="Save current session" />
		<br />
		<div class="helptext">
		If you close your browser after saving, you can return to the current session via the Drafts tab in the CMS.		
		<hr />
		</div>
	</div>	
	<div id="statusmessage">		
	</div>
</div>

<div class="rightPanel">
	<% control TestSection %>
	<h1>
		<% if GetTestPlan %>
			$GetTestPlan.Title - 
		<% else %>
		<% end_if %> 
		$Title test
	</h1>
	
	<% include PerformTestForm %>
	<% end_control %>
</div>



</body>
</html>