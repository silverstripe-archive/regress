<div class="feature">
	<div class="header">
		<div class='featureTitle'>
			<h3 id="feature{$ID}" class="anchor">Feature: $Title</h3>
		</div>

		<% if Content %>
			<div class="description">
				$Content
			</div>
		<% end_if %>
		
		<!-- FeatureID block-->
		<% if FeatureID %>
		<div class='description'>
			<h3>Feature ID</h3>
			<div>
				$FeatureID
			</div>
		</div>
		<% end_if %>
		
		<!-- Preparation block-->
		<% if Preparation %>
		<div class='description'>
			<h3>Test Preparation</h3>
			<div>
				$Preparation.Raw
			</div>
		</div>
		<% end_if %>

		<!-- TestData block-->
		<% if TestData %>
		<div class='description'>
			<h3>Test Data</h3>
			<div>
				$TestData.Raw
			</div>
		</div>
		<% end_if %>

		<!-- Notes block-->
		<% if Notes %>
		<div class='description'>
			<h3>Test Notes</h3>
			<div>
				$Notes.Raw
			</div>
		</div>
		<% end_if %>

	</div>


	<div class="scenarios">
		<% if Steps %>	
		<ul class="steps">
			<% control Steps %>
				<% include TestStep %>
			<% end_control %>
		</ul>
		<% end_if %>
	</div>

	<% control Children %>
		$Render
	<% end_control %>
</div>