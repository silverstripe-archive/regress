<div class="feature">
	<div class="header">
		<h3>Feature: $Title</h3>
		<div class="content">
			$Content.Markdown
		</div>
	</div>
	
	<% if Steps %>
	<div class="scenarios">
	
		<ul class="steps">
			<% control Steps %>
			<li class="scenario">
			
				<div class="passfail">
					<label class="pass"><input type="radio" value="pass" name="Outcome[$ID]" <% if IsOutcomePass %>checked="true"<% end_if %> />pass</label>
					<label class="fail"><input type="radio" value="fail" name="Outcome[$ID]" <% if IsOutcomeFail %>checked="true"<% end_if %> />fail</label>
					<label class="skip"><input type="radio" value="skip" name="Outcome[$ID]" <% if IsOutcomeSkip %>checked="true"<% end_if %> />skip</label>
				</div>

				<div class="content">
					$StepMarkdown
					
					<% if StepNote %>

					<div class="stepNote" style="display:;">
						<% if KnownIssues %>
						<div class="knownIssues">
							<h4>Fail Notes:</h4>
							<ul>
							<% control KnownIssues %>
							<li>$Note.XML 
								<% if TestSession.Link %>
								(<a href="$TestSession.Link">see session results</a>)
								<% else %>
								(Session information not available.)
								<% end_if %>
							</li>
							<% end_control %>
							</ul>
						</div>
						<% end_if %>

						<% if PassNotes %>
						<div class="passNotes">
							<h4>Pass Notes:</h4>
							<ul> 
							<% control PassNotes %>
							$ID $ClassName
							<li>$Note.XML 
								<% if TestSession.Link %>
								(<a href="$TestSession.Link">see session results</a>)
								<% else %>
								(Session information not available.)
								<% end_if %>
							</li>
							<% end_control %>
							</ul>
						</div>
						<% end_if %>

						<% if SkipNotes %>
						<div class="skipNotes">
							<h4>Skip Notes:</h4>
							<ul>
							<% control SkipNotes %>
							<li>$Note.XML
								<% if TestSession.Link %>
								(<a href="$TestSession.Link">see session results</a>)
								<% else %>
								(Session information not available.)
								<% end_if %>
							</li>
							<% end_control %>
							</ul>
						</div>
						<% end_if %>
					</div>
					<% end_if %>
				</div>
			
				<div class="note">
					<label>Comments:<textarea name="Note[$ID]">$SessionStepResult.Note</textarea> </label>
				</div>

			</li>
		<% end_control %>
		</ul>
	</div>
	<% end_if %>

	<% control Children %>
		$PerformTestSection
	<% end_control %>
</div>