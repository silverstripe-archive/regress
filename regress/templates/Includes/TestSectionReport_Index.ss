<li>$Title</li>
<% if Children %>
<div class='index_subsection'>
	<ul>
	<% control Children %>
		<li>$Title</li>
	
		<% if Children %>
		<div class='index_subsection'>
			<ul>
			<% control Children %>
				<li>$Title</li>
			<% end_control %>
			</ul>		
		</div>
		<% end_if %>
	<% end_control %>
	</ul>
</div>
<% end_if %>