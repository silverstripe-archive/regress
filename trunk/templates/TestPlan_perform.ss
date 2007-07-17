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
<form action="testplan/saveperformance/$ID" method="post">
<input type="hidden" name="TestPlanID" value="$ID" />
<h1>$Title</h1>

$Content

<% control Children %>
	$PerformTestSection
<% end_control %>

<% end_control %>

<p>
<input type="submit" value="Save test results" />
</p>
</form>

</body>
</html>
