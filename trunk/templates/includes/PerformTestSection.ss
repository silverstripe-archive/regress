<h2>$Title</h2>

$Content

<% if Steps %>
<ul class="steps">
<% control Steps %>
<li>
	<div class="passfail">
		<label class="pass"><input type="radio" value="pass" name="Outcome[$ID]" />pass</label>
		<label class="fail"><input type="radio" value="fail" name="Outcome[$ID]" />fail</label>
	</div>

	<span>$Step.XML
	
		<% if KnownIssues %>
		<div class="knownIssues">
			<h4>Known issues:</h4>
			<ul>
			<% control KnownIssues %>
			<li>$FailReason.XML (<a href="$TestSession.Link">see session results</a>)</li>
			<% end_control %>
			</ul>
		</div>
		<% end_if %>
	</span>

	
	<div class="failReason" style="display: none">
		<textarea name="FailReason[$ID]"></textarea> 	
	</div>
	
</li>
<% end_control %>
</ul>
<% end_if %>

<% control Children %>
	$PerformTestSection
<% end_control %>
