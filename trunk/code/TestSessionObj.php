<?php

class TestSessionObj extends DataObject {
	static $db = array(
		'Tester' => 'Varchar(20)',
		'OverallNote' => 'Text'
	);
	static $has_one = array(
		"TestPlan" => "TestPlan",
		'Author' => 'Member', 
	);
	static $has_many = array(
		"Results" => "StepResult",
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
		return DataObject::get("StepResult", "TestSessionID = $this->ID AND (Outcome = 'fail' OR Outcome = 'pass' OR Outcome = 'skip') ");
	}
	
	function getNumPasses() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'pass'")->value();
	}
	function getNumFailures() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'fail'")->value();
	}
	
	function getNumSkips() {
		return DB::query("SELECT COUNT(*) FROM StepResult WHERE TestSessionID = $this->ID AND Outcome = 'skip'")->value();
	}
	
	function Link() {
		return 'testplan/reportdetail/' . $this->TestPlanID . '/' . $this->ID;
	}
}

?>