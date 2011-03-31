<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<% include Favicon %>
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
		<h1>Test Report: '<% if TestPlan %>$TestPlan.Title - <% end_if %> $Title' (feature)</h1>
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
				$ID &nbsp;
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
		<div class='label boxed'>
			Test Plan Version:
		</div>
		<div class="content">
			<div class="box">
				$TestPlanVersion &nbsp;
			</div>
		</div>		
	</div>

	<div>
		<div class='label boxed'>
			Passes:
		</div>
		<div class="content">
			<div class="box">
				$NumPasses  &nbsp;
			</div>
		</div>
		<div class='label boxed'>
			Failures:
		</div>
		<div class="content">
			<div class="box">
				$NumFailures  &nbsp;
			</div>
		</div>
		<div class='label boxed'>
			Skip:
		</div>
		<div class="content">
			<div class="box">
				$NumSkips  &nbsp;
			</div>
		</div>
	</div>
	<hr />

	<div>
		<!-- Tester -->
		<div class='label boxed'>
			Tester:
		</div>
		<div class="content">
			<div class="box">
				$Tester &nbsp;
			</div>
		</div>
		
		<!-- Note -->
		<div class='label boxed'>
	   		Note:
		</div>
		<div>
			<div class="content">
				<div class="box">
					$OverallNoteMarkdown  &nbsp;
				</div>
			</div>
		</div>
		
		<!-- Code revision-->
		<div class='label boxed'>
	   		Code Revision:
		</div>
		<div>
			<div class="content">
				<div class="box">
					$CodeRevision  &nbsp;
				</div>
			</div>
		</div>
		
		<!-- Browser -->
		<div class='label boxed'>
	   		Browser:
		</div>
		<div>
			<div class="content">
				<div class="box">
					$Browser &nbsp;
				</div>
			</div>
		</div>

		<!-- Base URL -->
		<div class='label boxed'>
	   		Base URL:
		</div>
		<div>
			<div class="content">
				<div class="box">
					$BaseURL &nbsp;
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
			
			<li class="stepdetail status $Outcome <% if ResolutionDate %>resolved<% end_if %>" id='step_$ID'>
				
			
				<div>

					<% control TestStep %>
					<div class='label'>
						<p>Feature:</p>
					</div>
					
					<div class="content">
						<p>$Parent.FeatureID (<a href='feature/perform/$Parent.ID#teststep_$ID' target='_performnew'>Perform feature</a>)</p>
					</div>
					<div class='label'>
						<p>Scenario:</p>
					</div>
					<div class="content">
						<p>$StepMarkdown</p>
					</div>
					<% end_control %>
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
				
				<% if IsFail %>
				<div>
					<div class='label'>
						<p>Severity:</p>
					</div>
					<div class="content">
						<% if IsTopSeverityRating %>
						<strong><p class="state">$SeverityNice</p></strong>
						<% else %>
						<p class="state">$SeverityNice &nbsp;</p>
						<% end_if %>
					</div>
				</div>
				<% end_if %>
								
				<% if Attachments %>
					<div class='label'>
						<p>Attachments:</p>
					</div>
					<div class="content">
						<ul class="reportAttachmentList">
						<% control Attachments %>
							<li id="file-{$ID}"><a href="$URL" target="new">$Name</a></li>
						<% end_control %>
						</ul>
					</div>
				<% end_if %>
				
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
							<hr />
							<% if IsFail %>
							<div class="failseverity">
								<label class="severity1"><input type="radio" value="Severity1" title="major impact, testing cannot continue" name="resolution_$ID" <% if IsSeverity1 %>checked="true"<% end_if %> /><strong>Critical</strong></label>
								<label class="severity2"><input type="radio" value="Severity2" title="major impact, potentially a workaround existis, testing can continue" name="resolution_$ID" <% if IsSeverity2 %>checked="true"<% end_if %> /><strong>High</strong></label>
								<label class="severity3"><input type="radio" value="Severity3" title="medium impact, e.g., usability problem" name="resolution_$ID" <% if IsSeverity3 %>checked="true"<% end_if %> />Medium</label>
								<label class="severity4"><input type="radio" value="Severity4" title="minor or no impact, e.g., cosmetic error" name="resolution_$ID" <% if IsSeverity4 %>checked="true"<% end_if %> />Low</label>
							</div>							
							<% end_if %>
							
							<% if ResolutionDate %>
							<% if canUnresolve %>
							<textarea id="textarea_$ID" name="resolution_$ID" cols="115" rows="5" class='resolutionNote'></textarea>
							<br />
							<div class="resolutionAction">
								<div class="resolutionButton">
									<a id="resolution_$ID"  href="$CommentLink" class="commentAction">
										Comment
									</a>
																				
									<a id="resolution_$ID"  href="$UnresolveActionLink" class="unresolveAction">
										Mark as not resolved
									</a>
								</div>
							</div>
							<% end_if %>
							<% else %>							
							<% if canResolve %>
							<textarea id="textarea_$ID" name="resolution_$ID" cols="115" rows="5" class='resolutionNote'></textarea>
							<br />
							<div class="resolutionAction">
								<div class="resolutionButton">
									<a id="resolution_$ID"  href="$CommentLink" class="commentAction">Comment</a>
									<a id="resolution_$ID" href="$ResolveActionLink" class='resolveAction'>Mark as resolved</a>
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
