<!-- The scope can be a test-plan or a test-section -->
<form action="session/saveperformance/$ID" method="post" name="session" enctype="multipart/form-data">
	<input type="hidden" name="ParentID" value="$ID" />
	<input type="hidden" name="SessionType" value="$ClassName" />
	
	<% control TestSessionObj %>
	<input type="hidden"  id="TestSessionObjID" name="TestSessionObjID" value="$ID" />
	<% end_control %>
	
	<!-- HEADER -->
	<div class="header">
		
		<!-- extra info block-->
		<div class="extra">
			<p class="extra">
			<% control TestSessionObj %>
			<div>
				<div class="label">
					<label>Tester</label>
				</div>
				<div>
				<% if Tester %>
					<input type="text" name="Tester" size="30" value="$Tester"/><br/>
				<% else %>
					<input type="text" name="Tester" size="30" value="$CurrentMember.Name"/><br/>
				<% end_if %>
				</div>

				<div class="label">
					<label>Note</label>
				</div>
				<div>
					<textarea name="OverallNote" cols="50" rows="10">$OverallNote.Raw</textarea>
				</div>	
				</div>
			<% end_control %>
			</p>
		</div>
	</div>
	
	<div class="features">
		<% if ClassName = TestSection %>
			<% include TestSection %>
		<% end_if %>

		<% if ClassName = TestPlan %>
			<% if TestSections %>
				<% control TestSections %>
					<% include TestSection %>
				<% end_control %>
			<% end_if %>
		<% end_if %>
	</div>
	
	<div class="actions">
		<input type="submit" class="action" name="action_doSaveSession" value="Save as draft" />
		<input type="submit" class="action" value="Submit test" />
	</div>
</form>