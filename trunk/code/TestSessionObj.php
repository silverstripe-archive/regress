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
		'BaseURL' => 'Text',
		'Browser' => 'Text',
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
	
	function getTitle() {
		$section = $this->TestSection();
		$plan = $this->TestPlan();
		// At the moment, sessions need to have either a section or a plan
		if($section) {
			return $section->Title;
		} else if($plan) {
			return $plan->Title;
		} else {
			return '';
		}
	}
	
	function Passes() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND Outcome = 'pass'");
	}

	function Failures() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND Outcome = 'fail'");
	}
	
	function Skips() {
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND Outcome = 'skip'");
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
	
	function OverallNoteMarkdown() {
		$value = "&nbsp;";
		
		if ($this->OverallNote) {
			$value = MarkdownText::render($this->OverallNote);
		}
		return $value;
	}
	
	/**
	 * A bit too much presentation in the model, but its just too handy
	 * to have around for TableListFields etc.
	 * 
	 * The pixel widths aren't completely accurate, as we need to reserve
	 * a certain minimum space for each <span> to fit the numbers in.
	 * 
	 * @todo This isn't fully accurate on the "untested" counts, as steps might not have
	 * existed at the time a session was executed.
	 * 
	 * @return string HTML
	 */
	function getProgressHTML() {
		$html = '';
		$section = $this->TestSection();
		$plan = $this->TestPlan();
		
		// Roughly fitting two digits at 10px font size
		$minWidth = 12; // px
		// Add four possible <span> categories (they need to be the same width even if the spans are not addded themselves)
		$baseWidth = 30 + $minWidth*4; // px
		
		$numPasses = $this->getNumPasses();
		$numFailures = $this->getNumFailures();
		$numSkips = $this->getNumSkips();
		$numTested = $numPasses + $numFailures + $numSkips;
		$numTotal = ($section->exists()) ? $section->getAllTestSteps()->Count() : $plan->getAllTestSteps()->Count();
		$numUntested = $numTotal - $numTested;
		
		// Counteract width differences by missing out zero <spans> further down
		if(!$numFailures) $baseWidth += $minWidth;
		if(!$numSkips) $baseWidth += $minWidth;
		if(!($numTested < $numTotal)) $baseWidth += $minWidth;
		
		$widthPasses = $minWidth + $baseWidth * ($numPasses/$numTotal);
		$html .= "<span class=\"resultBlock pass\" title=\"Passed\" style=\"width: {$widthPasses}px\">$numPasses</span>";
		
		if($numFailures) {
			$widthFailures = $minWidth + $baseWidth * ($numFailures/$numTotal);
			$html .= "<span class=\"resultBlock fail\" title=\"Failed\" style=\"width: {$widthFailures}px\">$numFailures</span>";
		}
		
		if($numSkips) {
			$widthSkips = $minWidth + $baseWidth * ($numSkips/$numTotal);
			$html .= "<span class=\"resultBlock skip\" title=\"Skipped\" style=\"width: {$widthSkips}px\">$numSkips</span>";
		}
		
		if($numTested < $numTotal) {
			$widthUntested = $minWidth + $baseWidth * ($numUntested/$numTotal);
			$html .= "<span class=\"resultBlock untested\" title=\"Untested\" style=\"width: {$widthUntested}px\">$numUntested</span>";
		}

		return $html;
	}
}

?>
