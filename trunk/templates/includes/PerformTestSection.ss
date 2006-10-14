<h2>$Title</h2>

<% if Steps %>
<ul class="steps">
<% control Steps %>
<li>
	<div class="passfail">
		<label class="pass"><input type="radio" value="pass" name="Outcome[$ID]" />pass</label>
		<label class="fail"><input type="radio" value="fail" name="Outcome[$ID]" />fail</label>
	</div>

	<span>$Step.XML</span>

	
	<div class="failReason" style="display: none">
		<textarea name="FailReason[$ID]"></textarea> 	
		$SeverityDropdown
	</div>
	
</li>
<% end_control %>
</ul>
<% end_if %>

<% control Children %>
	$PerformTestSection
<% end_control %>
