<?php
/**
 * @package regress
 * @subpackage code
 */

require_once('../markdown/thirdparty/Markdown/markdown.php');

/**
 * Scenario
 */
class TestStep extends DataObject {
	static $db = array(
		"Step" => "MarkdownText",
		'Sort' => 'Int', 
	);
	
	static $has_one = array(
		"Parent" => "TestSection",
	);
	
	/**
	 * Human-readable singular name
	 * @var string
	 */
	public static $singular_name = 'Scenario';

	/**
	 * Human-readable plural name
	 * @var string
	 */
	public static $plural_name = 'Scenarios';
	
	/**
	 * Returns the test session object of a given ID. The ID is passed in as a
	 * HTTP parameter.
	 * 
	 * @return TestSessionObj|Null Instance of the session object.
	 */
	function SessionStepResult() {
		$obj = null;

		$OtherID = Controller::curr()->urlParams['OtherID'];
		if($OtherID) {
			if(is_numeric($OtherID)) {
				$dataobjectset = DataObject::get("StepResult", "TestStepID = $this->ID AND TestSessionID = $OtherID");
				if ($dataobjectset != null) {
					$obj = $dataobjectset->First();
				}
			}
		}
		return $obj;
	}
	
	function IsOutcomePass() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'pass';
	}

	function IsOutcomeSkip() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'skip';
	}

	function IsOutcomeFail() {
		$obj = $this->SessionStepResult();		
		if ($obj) return $obj->Outcome == 'fail';
	}
	
	
	/**
	 * Return the text stored in Step formatted as HTML, using Markdown for the
	 * formatting.
	 *
	 * @return string
	 */
	function StepMarkdown() {
		return Markdown($this->Step);
	}
	
	function KnownIssues() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'fail' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function PassNotes() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'pass' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function SkipNotes() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'skip' AND Note <> '' AND ResolutionDate IS NULL");
		}
	}
	
	function StepNote() {
		return ( $this->KnownIssues() || $this->PassNotes() || $this->SkipNotes() );
	}
}

?>