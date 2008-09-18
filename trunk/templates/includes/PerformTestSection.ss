<h2>$Title</h2>

$Content

<% if Steps %>
<ul class="steps">
<% control Steps %>
<li>
	<div class="passfail">
		<label class="pass"><input type="radio" value="pass" name="Outcome[$ID]" />pass</label>
		<label class="fail"><input type="radio" value="fail" name="Outcome[$ID]" />fail</label>
		<label class="skip"><input type="radio" value="skip" name="Outcome[$ID]" />skip</label>
	</div>

	<span>$Step.XML
		<div class="stepNote">
			<% if KnownIssues %>
			<div class="knownIssues">
				<h4>Fail Notes:</h4>
				<ul>
				<% control KnownIssues %>
				<li>$Note.XML (<a href="$TestSession.Link">see session results</a>)</li>
				<% end_control %>
				</ul>
			</div>
			<% end_if %>
		
			<% if PassNotes %>
			<div class="passNotes">
				<h4>Pass Notes:</h4>
				<ul>
				<% control KnownIssues %>
				<li>$Note.XML (<a href="$TestSession.Link">see session results</a>)</li>
				<% end_control %>
				</ul>
			</div>
			<% end_if %>
		
			<% if SkipNotes %>
			<div class="skipNotes">
				<h4>Skip Notes:</h4>
				<ul>
				<% control KnownIssues %>
				<li>$Note.XML (<a href="$TestSession.Link">see session results</a>)</li>
				<% end_control %>
				</ul>
			</div>
			<% end_if %>
		</div>
	</span>
	
	<div class="note" style="display: none">
		<textarea name="Note[$ID]"></textarea> 	
	</div>
	
</li>
<% end_control %>
</ul>
<% end_if %>

<% control Children %>
	$PerformTestSection
<% end_control %>
