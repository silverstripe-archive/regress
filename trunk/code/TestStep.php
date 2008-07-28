<?php

class TestStep extends DataObject {
	static $db = array(
		"Step" => "Text",
		'Sort' => 'Int', 
	);
	static $has_one = array(
		"Parent" => "TestSection",
	);
	
	function KnownIssues() {
		if(is_numeric($this->ID)) {
			return DataObject::get("StepResult", "TestStepID = $this->ID AND Outcome = 'fail' AND FailReason <> '' AND ResolutionDate IS NULL");
		}
	}
}

?>