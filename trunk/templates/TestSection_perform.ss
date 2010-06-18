<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% include Favicon %>
	<% base_tag %>
	<% control TestSection %>
	<title>Perform a Test: '<% if TestPlan %>$TestPlan.Title - <% end_if %> $Title' (feature test)</title>
	<% end_control %>
</head>
<!-- HTML-BODY -->
<body class="typography">

<% include LeftPanel %>

<div class="rightPanel">
	<% if TestSection %>
	<% control TestSection %>
	<h1 class="pageTitle"><% if TestPlan %>$TestPlan.Title - <% end_if %> $Title test</h1>	
	<% include TestSessionForm %>
	<% end_control %>
	<% else %>
  		Error: Invalid test parameter. <br/>
	<% end_if %>
</div>



</body>
</html>