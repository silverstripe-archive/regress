<?php

class TestPlan extends Page {
	static $allowed_children = array("TestSection");
	static $default_child = "TestSection";
	
	static $has_many = array(
		"Sessions" => "TestSession",
	);
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$sessionReport = new TableListField("Sessions", "TestSession",
			array("ID" => "Session #", "Created" => "Date", "NumPasses" => "# Passes", "NumFailures" => "# Failures"), "TestPlanID = '$this->ID'", "Created DESC"
		);
		$sessionReport->setClick_PopupLoad("testplan/reportdetail/$this->ID/");
		
		$fields->addFieldToTab("Root.Results", $sessionReport);
		return $fields;
	}

	function getAllCMSActions() {
		return new FieldSet(
			new FormAction("callPageMethod", "Perform test", null, "cms_performTest"),
			new FormAction("save", "Save changes")
		);
	}

	function cms_performTest() {
		return <<<JS
			var w = window.open(baseHref() + "testplan/perform/$this->ID/" , "performtest");
			w.focus();
JS;
	}	
}

class TestPlan_Controller extends Controller {
	function init() {
		HTTP::set_cache_age(0);
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
			$result->TestPlanID = $this->urlParams[ID];
			$result->TestSessionID = $session->ID;
			$result->Outcome = $outcome;
			$result->FailReason = $_REQUEST[FailReason][$stepID];
			$result->write();
		}
		
		Director::redirect("testplan/reportdetail/$_REQUEST[TestPlanID]/$session->ID");
	}
	
}

?>