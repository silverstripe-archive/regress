<?php
/**
 * @package regress
 * @subpackage code
 */

/**
 *
 */
class TestSessionObj extends DataObject {
	
	static $db = array(
		'Tester'      => 'Varchar(20)',
		'OverallNote' => 'Text',
		"Status"      => "Enum(array('new','draft','submitted','archived'),'new')",
	);
	
	static $has_one = array(
		"TestPlan"    => "TestPlan",
		"TestSection" => "TestSection",
		'Author'      => 'Member', 
	);
	
	static $has_many = array(
		"Results"     => "StepResult",
	);

	static $defaults = array(
	    'Status' => 'new',
	 );
	
	function onBeforeWrite() {
	   $this->AuthorID = Member::currentUserID();
	   parent::onBeforeWrite();
	}
	
	function Passes() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND Outcome = 'pass'");
	}

	function Failures() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND Outcome = 'fail'");
	}
	
	function Notes() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND (Outcome = 'fail' OR (Outcome IN ('pass','skip') AND Note != '' AND Note IS NOT NULL))");
	}
	
	/**
	 * Return number (as string) of passed scenarios for the feature.
	 *
	 * @return String value of the first item of the {@link SS_Query} object
	 */
	function getNumPasses() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'pass'")->value();
	}

	/**
	 * Return number (as string) of failed scenarios for the feature.
	 *
	 * @return String value of the first item of the {@link SS_Query} object
	 */
	function getNumFailures() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'fail'")->value();
	}
	
	/**
	 * Return number (as string) of skipped scenarios for the feature.
	 *
	 * @return String value of the first item of the {@link SS_Query} object
	 */
	function getNumSkips() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'skip'")->value();
	}
	
	function isEditable() {
		return ($this->Status == 'new' || $this->Status == 'draft');
	}
	
	/**
	 * Returns the parent ID for this session. It can be a test-section or a 
	 * test-plan ID.
	 */
	function getTestReferenceID() {
		$id = '';
		if ($this->TestPlanID) {
			$id = $this->TestPlanID;
		} else
		if ($this->TestSectionID) {
			$id = $this->TestSectionID;
		} 
		return $id;
	}
	
	/**
	 * Return link to the report for this feature.
	 *
	 * @return String relative link to the test-report.
	 */
	function Link() {
		$id = $this->getTestReferenceID();
		return 'session/reportdetail/' . $id . '/' . $this->ID;
	}
}

?>
