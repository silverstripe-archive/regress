<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% include Favicon %>
	<% base_tag %>
	<% control TestPlan %>
	<title>Test Plan: '$Title'</title>
	<% end_control %>
</head>
<!-- HTML-BODY -->
<body class="typography">

<div class="">
	<% if TestSection %>	
	<% control TestSection %>
	<h1 class="pageTitle">Test Feature: $Title</h1>

	<% if Content %>
		<div class="label">
			<label>Description</label>
		</div>
		<div>
		$Content
		<hr/>
		</div>
	<% end_if %>
	
	<% include TestSessionReportForm %>
	<% end_control %>	
	<% else %>
	Error: Invalid test parameter. <br/>
	<% end_if %>
</div>

</body>
</html>