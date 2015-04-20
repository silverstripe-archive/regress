<html>
<head>
	<% include Favicon %>
	<% base_tag %>
</head>
<body class="Regress typography">

<h1>Test Sessions Waterfall</h1>

$FilterForm

<table class="testSessions">
	<thead>
		<th></th>
		<% control TestPlans %>
		<th>$Title</th>
		<% end_control %>
	</thead>
	<tbody>
	<% control TestPlansByInterval %>
		<tr>
			<td>
				$Interval.Title
			</td>
			<% control TestPlansForInterval %>
				<td>
				<% control TestSessionsByInterval %>
				<div class="testSession">
					<h3>
						<% if Title %>
						<a href="$Link">$Title</a>
						<br>
						<% end_if %>
						<a href="$Link">(#$ID, $Created.Date)</a>
					</h3>
					<div class="results">
						$ProgressHTML
					</div>
					<div class="author">
						<% if Author %>
							$Author.Title
						<% else %>
							(No author)
						<% end_if %>
					</div>
				</div>
				<% end_control %>
				</td>
			<% end_control %>
		</tr>
	<% end_control %>
	</tbody>
</table>

</body>
</html>