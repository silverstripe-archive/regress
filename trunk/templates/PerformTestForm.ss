
<!-- The scope can be a test-plan or a test-section -->
<form action="session/saveperformance/$ID" method="post" name="session">
	<input type="hidden" name="ParentID" value="$ID" />
	<input type="hidden" name="SessionType" value="$ClassName" />
	
	<% control TestSessionObj %>
	<input type="hidden"  id="TestSessionObjID" name="TestSessionObjID" value="$ID" />
	<% end_control %>
	
	<!-- HEADER -->
	<div class="header">
		<!-- Title block-->
		<div class="title">
			<h2>Feature: $Title</h2>
			<div>
				$Content.Markdown
			</div>
		</div>
		
		<!-- Preparation block-->
		<div class='preparation'>
			<h3>Test Preparation</h3>
			<div>
				$PreparationMarkdown
			</div>
		</div>
		
		<div class="extra">
			<p class="extra">
			<% control TestSessionObj %>
			<div>
				<div class="label">
					<label>Tester</label>
				</div>
				<div>
					<input type="text" name="Tester" size="30" value="$Tester"/><br/>
				</div>

				<div class="label">
					<label>Note</label>
				</div>
				<div style="">
					<textarea name="OverallNote" cols="50" rows="10">$OverallNote</textarea>
				</div>	
				</div>
			<% end_control %>
			</p>
		</div>
	</div>
	
	<div class="features">
		<!-- Show all steps of the currenct testplan -->
		<% if PerformTestSection %>
			$PerformTestSection
		<% else %>
			No senarios found for current $classname
		<% end_if %>

		<!-- Show all steps of the children -->
		<% control Children %>
		$PerformTestSection
		<% end_control %>
	</div>
	
	<div class="actions">
		<input type="submit" value="Submit test results" />
	</div>
</form>