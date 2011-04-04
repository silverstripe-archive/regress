<div class="leftPanel">
	<div class="actions">

		<a href="testplan" title="go back to your dashboard" class='noborder'>
			<img src='regress/images/ss-logo.gif' class='noborder' />
		</a>

		<% if TestRootObject %>
		<% control TestRootObject %>
		$SessionForm
		<% end_control %>
		<% end_if %>
		
		<br />
		<% if ShowLeftOptions %>
			<div class="helptext">
				If you close your browser after saving, you can return to the current 
				session via the Drafts tab in the CMS.		
			</div>
			<% if ShowCanEdit %>
				<p class="utilityLinks">
					<a class="editModeLink" href="#">Change to Edit Mode</a>
				</p>
			<% end_if %>
		<% end_if %>
		<p class="utilityLinks">
			<a href="Security/logout">Logout</a>
		</p>
		
	</div>	
	<div id="statusmessage">		
	</div>
</div>
