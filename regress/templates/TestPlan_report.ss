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
	<% if TestPlan %>	
	<% control TestPlan %>
	<h1 class="pageTitle">Test Plan: $Title</h1>
	<% if Content %>
		<div class="box">
			$Content
		</div>
	<% end_if %>
	<% if TestVersion %>
		<div class="label">
			<label>Test Plan Version</label>
		</div>
		<div>
		$TestVersion (stored as '$TestStatus')
		<br/>
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