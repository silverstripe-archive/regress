<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<% base_tag %>
<title>Perform a Test</title>
<link rel="stylesheet" type="text/css" href="regress/css/TestPlan.css" />
</head>
<body>
<h1>Peform a test</h1>

<% control TestPlan %>
<h1>Test Results for '$Title'</h1>
<% end_control %>

<% control TestSession %>
<p>Test Session #: $ID<br />
Tested on: $Created.Nice</p>

<p>Passes: $NumPasses<br />
Failures: $NumFailures</p>

<% if Failures %>
	<h2>Failures</h2>
	<ul id="FailureDetail">
	<% control Failures %>
	<li <% if ResolutionDate %>class="resolved"<% end_if %>>
		<b>$TestStep.Step.XML</b><br />
		$FailReason.XML

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


<% end_control %>

</body>
</html>
