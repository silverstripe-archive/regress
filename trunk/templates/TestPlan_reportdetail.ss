<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<% base_tag %>
<title>Perform a Test</title>
</head>
<body>
<h1>Peform a test</h1>

<% control TestPlan %>
<h1>Test Results for '$Title'</h1>
<% end_control %>

<% if TestSessionObj %>
<% control TestSessionObj %>
<p>Test Session #: $ID<br />
Tested on: $Created.Nice by $Author.Title</p>

<p>Passes: $NumPasses<br />
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

<% if Notes %>
	<h2>Notes</h2>
	<ul id="NoteDetail">
	<% control Notes %>
	<li class="status $Outcome <% if ResolutionDate %>resolved<% end_if %>">
		<b>$TestStep.Step.XML</b><br />
		$Note.XML
		<p class="state">Status: $Outcome</p>
		<% if ResolutionDate %>
			<br /> 
				<i class="resolutionInfo">Resolved on $ResolutionDate.Date
				<a href="$UnresolveActionLink">mark as not resolved</a></i>
		<% else %>
			<i class="resolutionInfo"><a href="$ResolveActionLink">mark as resolved</a></i>
		<% end_if %>
	</li>
	<% end_control %>
	</ul>
<% end_if %>

</body>
</html>
