<div class="leftPanel">
	<div class="actions">

	<img src='regress/images/ss-logo.gif' width='100%'/>

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
		
	</div>	
	<div id="statusmessage">		
	</div>
</div>