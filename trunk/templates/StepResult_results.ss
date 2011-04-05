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
	
	<% if ListResults(submitted) %>
		<h1>Results for '$TestPlan.Title'</h1>
		<div class="resultsListHeader">
			<span>Session #</span>
			<span>Date</span>
			<span># Passes</span>
			<span># Failures</span>
			<span># Skips</span>
			<span>Tester</span>
			<span class="options"></span>
		</div>
		<% control ListResults(submitted) %>
			<div class="resultsList $evenodd">
				<span>$ID</span>
				<span>$Created.Nice</span>
				<span>$NumPasses</span>
				<span>$NumFailures</span>
				<span>$NumSkips</span>
				<span>$Author.Name</span>
				<span class="options"><a href="session/reportdetail/$TestPlanID/$ID"><img src="cms/images/show.png" alt="details" /></a></span>
			</div>
		<% end_control %>
	<% end_if %>
	
	<% if TestPlan %>
		<% control TestPlan %>
			<% control Children %>
				<% if ListResults %>
					<h2>$Title</h2>
					<div class="resultsListHeader">
						<span>Session #</span>
						<span>Date</span>
						<span># Passes</span>
						<span># Failures</span>
						<span># Skips</span>
						<span>Tester</span>
						<span class="options"></span>
					</div>
					<% control ListResults %>
						<div class="resultsList $evenodd">
							<span>$ID</span>
							<span>$Created.Nice</span>
							<span>$NumPasses</span>
							<span>$NumFailures</span>
							<span>$NumSkips</span>
							<span>$Author.Name</span>
							<span class="options"><a href="session/reportdetail/$TestSectionID/$ID"><img src="cms/images/show.png" alt="details" /></a></span>
						</div>
					<% end_control %>
				<% end_if %>
			<% end_control %>
		<% end_control %>
	<% end_if %>


</div>

</body>
</html>