<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<% base_tag %>
<title>Perform a Test</title>
<link rel="stylesheet" type="text/css" href="regress/css/TestPlan.css" />
</head>
<body>
<h1>Test-Report</h1>

<% if TestSessionObj %>
<% control TestSessionObj %>
	<!-- session was a test-plan -->
	<% if TestPlan %>
	<% control TestPlan %>
		<h2>Test Results for Test-Plan '$Title'</h2>
	<% end_control %>
	<% end_if %>

	<!-- session was a feature -->
	<% if TestSection %>
		test-section
	<% control TestSection %>
		<h2>Test Results for Feature '$Title'</h2>
	<% end_control %>
	<% end_if %>

<% end_control %>
<% end_if %>

<div class="reportheader">
	<% if TestSessionObj %>
	<% control TestSessionObj %>
	<p>
		Test Session #: $ID<br />
		Tested on: $Created.Nice by $Author.Title
	</p>

	<p>
		Passes: $NumPasses<br />
		Failures: $NumFailures<br />
		Skip: $NumSkips
	</p>

	<p>
		Tester: $Tester<br />
	   	Note: $OverallNote
	</p>
	<% end_control %>

	<% else %>
		<p>Showing unresolved notes from all test sessions...</p>
	<% end_if %>
</div>

<div class="reportnotes">
	<!-- NOTES -->
	<% if Notes %>
		<h2>Notes</h2>
		<ul id="NoteDetail">
		<% control Notes %>
		<li class="status $Outcome <% if ResolutionDate %>resolved<% end_if %>">
			<div class="scenario">
				<div class='label'>Scenario:</div>
				<div class="content">
				<% control TestStep %>
					<p>$StepMarkdown</p>
				<% end_control %>
				</div>
			</div>
		
			<div>
				<div class='label'>Note:</div>
				<div class="content">
				<p>
				<% if Note %>
					$Note.XML
				<% else %>
				  Tester has not entered any further information/comments.
				<% end_if %>
				</p>
			</div>
			</div>
			<div class="outcome">
				<div class='label'>Status:</div>
				<div class="content">
				<p class="state">$Outcome</p>
				<% if ResolutionDate %>
					<br /> 
						<i class="resolutionInfo">Resolved on $ResolutionDate.Date
						<a href="$UnresolveActionLink">mark as not resolved</a></i>
				<% else %>
					<i class="resolutionInfo"><a href="$ResolveActionLink">mark as resolved</a></i>
				<% end_if %>
				</div>
			</div>
		</li>
		<% end_control %>
		</ul>
	<% end_if %>
</div>

</body>
</html>
