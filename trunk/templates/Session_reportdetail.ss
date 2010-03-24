<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<% base_tag %>
<% control TestSessionObj %>
	<!-- session was a test-plan -->
	<% if TestPlan %>
	<% control TestPlan %>
	<title>Test Report: $Title (Test Plan)</title>
	<% end_control %>
	<% end_if %>

	<!-- session was a feature -->
	<% if TestSection %>
	<% control TestSection %>	
	<title>Test Report: <% if TestPlan %> $TestPlan.Title - <% end_if %> $Title (feature test)</title>
	<% end_control %>
	<% end_if %>

<% end_control %>

</head>
<body class="typography">

<% if TestSessionObj %>
<% control TestSessionObj %>
	<!-- session was a test-plan -->
	<% if TestPlan %>
	<% control TestPlan %>
		<h1 class="pageTitle">Test Report: '$Title' (Test Plan)</h1>
	<% end_control %>
	<% end_if %>

	<!-- session was a feature -->
	<% if TestSection %>
	<% control TestSection %>
		<h1>Test Report: '<% if TestPlan %> $TestPlan.Title - <% end_if %> $Title' (feature)</h1>
	<% end_control %>
	<% end_if %>

<% end_control %>
<% end_if %>

<div class="reportheader">
	<% if TestSessionObj %>
	<% control TestSessionObj %>
	<div>
		<div class='label boxed'>
			Session#:
		</div>
		<div class="content">
			<div class="box">
				$ID
			</div>
		</div>
		<div class='label boxed'>
			Tested on:
		</div>
		<div class="content">
			<div class="box">
				$Created.Nice by $Author.Title
			</div>
		</div>
	</div>

	<div>
		<div class='label boxed'>
			Passes:
		</div>
		<div class="content">
			<div class="box">
				$NumPasses
			</div>
		</div>
		<div class='label boxed'>
			Failures:
		</div>
		<div class="content">
			<div class="box">
				$NumFailures
			</div>
		</div>
		<div class='label boxed'>
			Skip:
		</div>
		<div class="content">
			<div class="box">
				$NumSkips
			</div>
		</div>
	</div>
	<hr />

	<div>
		<div class='label boxed'>
			Tester:
		</div>
		<div class="content">
			<div class="box">
				$Tester &nbsp;
			</div>
		</div>
		<div class='label boxed'>
	   		Note:
		</div>
		<div>
		<div class="content">
			<div class="box">
				$OverallNoteMarkdown
			</div>
		</div>
		</div>
	</div>
	<% end_control %>

	<% else %>
		<p>Showing unresolved notes from all test sessions...</p>
	<% end_if %>
</div>

<!-- SHOW RESULTS OF THE TEST -->
<div class="reportnotes">
	<form id="report" action="session" method="post" name="report">
		<!-- NOTES -->
		<% if Notes %>
		
		<h2>Notes</h2>
		<ul id="NoteDetail">

			<% control Notes %>
			<li class="stepdetail status $Outcome <% if ResolutionDate %>resolved<% end_if %>">
				<div>
					<div class='label'>
						<p>Scenario:</p>
					</div>
					<div class="content">
						<% control TestStep %>
							<p>$StepMarkdown</p>
						<% end_control %>
					</div>
				</div>
		
				<div>
					<div class='label'>
						<p>Note:</p>
					</div>
					<div class="content">
						<p>
						<% if Note %>
							$NoteMarkdown
						<% else %>
						  Tester has not entered any further information/comments.
						<% end_if %>
						</p>
					</div>
				</div>
				<div>
					<div class='label'>
						<p>Status:</p>
					</div>
					<div class="content">
						<p class="state">$Outcome</p>
					</div>
				</div>
				<div>
					<hr />
					<div class='resolutionComments'>
						<div class='label'>
							<p>Comments:</p>
						</div>						
						<div class="content">
							<div>
								<p>
									<% if StepResultNotes %>
									<ul>
									<% control StepResultNotes %>
										<li>
											$Status on $Date.Date
											<br />
											$NoteMarkdown
										</li>									
									<% end_control %>
									</ul>
									<% else %> 
									<i>No comments available.</i>
									<% end_if %>
								</p>
							</div>
						</div>
						<div class="content actions">
							<% if ResolutionDate %>
								<% if canUnresolve %>
								<textarea id="textarea_$ID" name="resolution_$ID" cols="120" rows="5" class='resolutionNote'></textarea>
								<br />
								<div class="resolutionAction">
									<div class="resolutionButton">
										<img src="cms/images/alert-bad.gif">
										<a id="resolution_$ID"  href="$UnresolveActionLink" class="unresolveAction">
											Mark as not resolved
										</a>
									</div>
								</div>
								<% end_if %>
							<% else %>							
								<% if canResolve %>
								<textarea id="textarea_$ID" name="resolution_$ID" cols="120" rows="5" class='resolutionNote'></textarea>
								<br />
								<div class="resolutionAction">
									<div class="resolutionButton">
										<img src="cms/images/alert-good.gif">
										<a id="resolution_$ID" href="$ResolveActionLink" class='resolveAction'>
											Mark as resolved
										</a>
									</div>
								</div>
								<% end_if %>
							<% end_if %>
						</div>
					</div>
				</div>
			</li>
		<% end_control %>
		</ul>
		<% end_if %>
	</form>
</div>

</body>
</html>
