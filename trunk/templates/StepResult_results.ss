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
	
	<% if ListResults %>
		<h1>Results for '$TestTitle'</h1>
		<div class="resultsListHeader">
			<span>Session #</span>
			<span>Date</span>
			<span># Passes</span>
			<span># Failures</span>
			<span># Skips</span>
			<span>Author</span>
		</div>
		<% control ListResults %>
			<div class="resultsList $evenodd">
				<span>$ID</span>
				<span>$Created.Nice</span>
				<span>$NumPasses</span>
				<span>$NumFailures</span>
				<span>$NumSkips</span>
				<span>$Author.Name</span>
			</div>
		<% end_control %>
	<% else %>
		<p>We are sorry, but you don't have permissions to access this page.</p><a href="javascript:history.back(-1);">go back</a>
	<% end_if %>

</div>

</body>
</html>