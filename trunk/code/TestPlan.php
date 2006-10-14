<?php

class TestPlan extends Page {
	static $allowed_children = array("TestSection");
	static $default_child = "TestSection";
	
}

class TestPlan_Controller extends Controller {
	function init() {
		parent::init();
		Requirements::javascript("jsparty/behaviour.js");
		Requirements::javascript("jsparty/prototype.js");
		Requirements::javascript("regress/javascript/TestPlan.js");
	}
	
	function TestPlan() {
		return DataObject::get_by_id("TestPlan", $this->urlParams[ID]);
	}
	function TestSession() {
		return DataObject::get_by_id("TestSession", $this->urlParams[OtherID]);
	}
		
	function saveperformance() {
		$session = new TestSession();
		$session->write();
		
		foreach($_REQUEST[Outcome] as $stepID => $outcome) {
			$result = new StepResult();
			$result->TestStepID = $stepID;
			$result->TestPlanID = $_REQUEST[TestPlanID];
			$result->TestSessionID = $session->ID;
			$result->Outcome = $outcome;
			$result->FailReason = $_REQUEST[FailReason][$stepID];
			$result->write();
		}
		
		Director::redirect("testplan/reportdetail/$_REQUEST[TestPlanID]/$session->ID");
	}
	
}

?>