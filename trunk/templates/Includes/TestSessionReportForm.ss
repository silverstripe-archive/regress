<!-- The scope can be a test-plan or a test-section -->

	<div class="features">
		<h2 class="pageTitle">Outline</h2>
		
		<% if ClassName = TestSection %>
		<div class='index_subsection'>
			<ul>
			<% include TestSectionReport_Index %>
			</ul>
		</div>
		<% end_if %>

		<% if ClassName = TestPlan %>
		<div class='index_subsection'>
			<% if TestSections %>
				<% control TestSections %>
					<% include TestSectionReport_Index %>
				<% end_control %>
			<% end_if %>
		</div>
		<% end_if %>
	</div>	
	
	<!-- Test script -->
	<h2 class="pageTitle">Test Execution Plan</h2>
	<div class="index_scenarios">
		<div class="features">
			<% if ClassName = TestSection %>
				<% include TestSectionReport %>
			<% end_if %>

			<% if ClassName = TestPlan %>
				<% if TestSections %>
					<% control TestSections %>
						<% include TestSectionReport %>
					<% end_control %>
				<% end_if %>
			<% end_if %>
		</div>
	</div>
