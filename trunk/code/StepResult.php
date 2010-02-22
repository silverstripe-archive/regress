<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 * A StepResult is the instance of a test-result for a scenario (test-step).
 *
 * Each step-result is related to one scenario (test-step) and stores the 
 * information about the test results for the particular scenario. A scenario
 * can pass it's test, fail or can be skipped by the test person.
 */ 
class StepResult extends DataObject {
	
	static $db = array(
		"Outcome"        => "Enum('pass,fail,skip,','')",
		"Note"           => "Text",
		"ResolutionDate" => "Datetime"
	);
	
	static $has_one = array(
		"TestSession" => "TestSessionObj",
		"TestPlan"    => "TestPlan",
		"TestStep"    => "TestStep",
	);
	
	static $has_many = array(
		"StepResultNotes" => "StepResultNote"
	);
	
	
	function canResolve() {
		return Permission::check("STEPRESULT_RESOLVE");		
	}

	function canUnresolve() {
		return Permission::check("STEPRESULT_UNRESOLVE");		
	}
	
	function ResolveActionLink() {
		return "StepResult_Controller/resolve/$this->ID";
	}
	
	function UnresolveActionLink() {
		return "StepResult_Controller/unresolve/$this->ID";
	}
	
	function NoteMarkdown() {
		return MarkdownText::render($this->Note);
	}	
}

/**
 * Controller Class for Step Result {@link StepResult}.
 *
 */
class StepResult_Controller extends Controller implements PermissionProvider {

	static $allowed_actions = array(
		'resolve',
		'unresolve'
	);
	
	function providePermissions() {
	    return array(
	      "STEPRESULT_RESOLVE"   => "Can resolve marked-issues in session-report",
	      "STEPRESULT_UNRESOLVE" => "Can un-resolve marked-issues in session-report",
	    );
  	}
  
		
	/**
	 * Returns a requested Step-Result
	 *
	 * Returns the step-result for a given id. The ID needs to be passed 
	 * through as an URL-parameter.
	 *
	 * @return StepResult|null
	 */
	function StepResult() {
		return DataObject::get_by_id("StepResult", $this->urlParams['ID']);
	}

	/**
	 * Marks the step-result as resolved.
	 *
	 * Set the flag of the step-result to 'resolved'.
	 */
	function resolve($fields) {
		
		if (!Member::currentUser()) {
			Security::permissionFailure();
			Director::redirectBack();
			return;
		}
		
		// check if current user has the permission to 'resolve' the result.
		if(!Permission::check("STEPRESULT_RESOLVE")) {
			Security::permissionFailure();	
			Director::redirectBack();
			return;
		}
		
		$params = $fields->getVars();

		$ResolutionNote = 'No comments';
		if (isset($params['resolutionnote']) && $params['resolutionnote'] != '') {
			$ResolutionNote = $params['resolutionnote'];
		}
		
		$sr = $this->StepResult();
		$sr->ResolutionDate = date('Y-m-d h:i:s');
		$sr->write();

		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Resolved";
		$StepResultNote->Note = $ResolutionNote;
		$StepResultNote->Date = $sr->ResolutionDate;
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();
		
		Director::redirectBack();
	}
	
	/**
	 * Unmarks the step-result as resolved.
	 *
	 * Set the flag of the step-result to 'unresolved'.
	 */
	function unresolve($fields) {

		if (!Member::currentUser()) {
			return Security::permissionFailure();
		}
		
		// check if current user has the permission to 'unresolve' the result.
		if(!Permission::check("STEPRESULT_UNRESOLVE")) {
			Security::permissionFailure();
			Director::redirectBack();
			return;
		}

		$params = $fields->getVars();

		$ResolutionNote = 'No comments';
		if (isset($params['resolutionnote']) && $params['resolutionnote'] != '') {
			$ResolutionNote = $params['resolutionnote'];
		}

		$sr = $this->StepResult();

		$sr->ResolutionDate = null;

		$StepResultNote = new StepResultNote();
		$StepResultNote->Status = "Unresolved";
		$StepResultNote->Note = $ResolutionNote;
		$StepResultNote->Date  = date('Y-m-d h:i:s');
		$StepResultNote->StepResultID = $sr->ID;
		$StepResultNote->write();

		$sr->write();
		Director::redirectBack();
	}
}

?>