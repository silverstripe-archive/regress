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
		"ResolutionDate" => "Datetime",
	);
	
	static $has_one = array(
		"TestSession" => "TestSessionObj",
		"TestPlan"    => "TestPlan",
		"TestStep"    => "TestStep",
	);
	
	function ResolveActionLink() {
		return "StepResult_Controller/resolve/$this->ID";
	}
	
	function UnresolveActionLink() {
		return "StepResult_Controller/unresolve/$this->ID";
	}
	
	
	function NoteMarkdown() {
		return Markdown($this->Note);
	}	
}

/**
 * Controller Class for Step Result {@link StepResult}.
 *
 */
class StepResult_Controller extends Controller {
	
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
	function resolve() {
		$sr = $this->StepResult();
		$sr->ResolutionDate = date('Y-m-d h:i:s');
		$sr->write();
		Director::redirectBack();
	}
	
	/**
	 * Unmarks the step-result as resolved.
	 *
	 * Set the flag of the step-result to 'unresolved'.
	 */
	function unresolve() {
		$sr = $this->StepResult();
		$sr->ResolutionDate = null;
		$sr->write();
		Director::redirectBack();
	}
}

?>