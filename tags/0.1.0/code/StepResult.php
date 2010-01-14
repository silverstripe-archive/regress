<?php

class StepResult extends DataObject {
	static $db = array(
		"Outcome" => "Enum('pass,fail,skip,','')",
		"Note" => "Text",
		"ResolutionDate" => "Datetime",
	);
	static $has_one = array(
		"TestSession" => "TestSessionObj",
		"TestPlan" => "TestPlan",
		"TestStep" => "TestStep",
	);
	
	function ResolveActionLink() {
		return "StepResult_Controller/resolve/$this->ID";
	}
	function UnresolveActionLink() {
		return "StepResult_Controller/unresolve/$this->ID";
	}
}

class StepResult_Controller extends Controller {
	function StepResult() {
		return DataObject::get_by_id("StepResult", $this->urlParams['ID']);
	}
	
	function resolve() {
		$sr = $this->StepResult();
		$sr->ResolutionDate = date('Y-m-d h:i:s');
		$sr->write();
		Director::redirectBack();
	}
	function unresolve() {
		$sr = $this->StepResult();
		$sr->ResolutionDate = null;
		$sr->write();
		Director::redirectBack();
	}
	
}

?>