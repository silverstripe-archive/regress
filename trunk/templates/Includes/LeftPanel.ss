<div class="leftPanel">
	<div class="actions">

	<img src='regress/images/ss-logo.gif' />

		<% if TestRootObject %>
		<% control TestRootObject %>
		$SessionForm
		<% end_control %>
		<% end_if %>
		
		<br />
		<div class="helptext">
			If you close your browser after saving, you can return to the current 
			session via the Drafts tab in the CMS.		
		</div>
		
		<p class="utilityLinks">
			<a class="editModeLink" href="#">Change to Edit Mode</a>
		</p>
		<p class="utilityLinks">
			<a href="Security/logout">Logout</a>
		</p>
		
	</div>	
	<div id="statusmessage">		
	</div>
</div>
