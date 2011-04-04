<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<% include Favicon %>
	<% base_tag %>
	<% control TestPlan %>
	<% if Title %>
		<title>Perform a Test: '$Title' (Test Plan)</title>
	<% else %>
		<title>SilverStripe Regress - Dashboard</title>
	<% end_if %>
	<% end_control %>
</head>
<!-- HTML-BODY -->
<body class="typography">

<% include LeftPanel %>

<div class="rightPanel">
	<h1>$DashboardIndroduction.DashboardIndroduction</h1>
	<% control MyTests %>
		<div class="dashboardTestTitle"><h2>$Title</h2> <a href="testplan/perform/$ID">[perform test]</a> <a href="testplan/report/$ID">[view test script]</a> <a href="results/results/$ID/plan">[view results]</a></div>
		<ul>
		<% control Children %>
			<li>$Title <a href="feature/perform/$ID">[perform test]</a> | <a href="feature/report/$ID">[view test script]</a> | <a href="results/results/$ID/section">[view results]</a></li>
				<% control Children %>
					<li class="featureSecondLevel">$Title <a href="feature/perform/$ID">[perform test]</a> | <a href="feature/report/$ID">[view test script]</a> | <a href="results/results/$ID/section">[view results]</a></li>
				<% end_control %>
		<% end_control %>
		</ul>
	<% end_control %>


</div>

</body>
</html>